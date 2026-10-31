package com.example.data.repository

import android.util.Log
import com.example.data.local.MarketLinkDatabase
import com.example.data.model.*
import com.example.data.remote.*
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.flow.Flow
import kotlinx.coroutines.withContext
import java.text.SimpleDateFormat
import java.util.Date
import java.util.Locale
import java.util.UUID

class MarketLinkRepository(private val db: MarketLinkDatabase) {

    // Room Single Source of Truth Flows
    val userProfile: Flow<UserProfile?> = db.userProfileDao().getUserProfile("current_user")
    val allUsers: Flow<List<UserProfile>> = db.userProfileDao().getAllUserProfiles()
    val allListings: Flow<List<Listing>> = db.listingDao().getAllListings()
    val allApplications: Flow<List<Application>> = db.applicationDao().getAllApplications()
    val allTransactions: Flow<List<Transaction>> = db.transactionDao().getAllTransactions()
    val allCourses: Flow<List<Course>> = db.courseDao().getAllCourses()
    val allNotifications: Flow<List<NotificationItem>> = db.notificationDao().getAllNotifications()

    fun getMessagesForListing(listingId: String): Flow<List<Message>> = db.messageDao().getMessagesForListing(listingId)

    private var activeUserId: String? = null

    fun setActiveUserId(id: String?) {
        activeUserId = id
    }

    fun getActiveUserId(): String? = activeUserId

    /**
     * Offline-First Background Sync Engine:
     * Pulls live data from the Laravel 11 / MySQL backend and reconciles Room cache.
     */
    suspend fun syncWithBackend(): Boolean = withContext(Dispatchers.IO) {
        try {
            // 1. Sync Listings
            val listingsResponse = ApiClient.apiService.getListings(userId = activeUserId)
            if (listingsResponse.isSuccessful && listingsResponse.body()?.data != null) {
                val remoteListings = listingsResponse.body()!!.data!!
                if (remoteListings.isNotEmpty()) {
                    db.listingDao().clearAllListings()
                    db.listingDao().insertAllListings(remoteListings)
                }
            }

            // 2. Sync Current User Profile
            val profileResponse = ApiClient.apiService.getCurrentUserProfile(activeUserId)
            if (profileResponse.isSuccessful && profileResponse.body()?.data != null) {
                val profile = profileResponse.body()!!.data!!
                db.userProfileDao().insertOrUpdateProfile(profile)
                db.userProfileDao().insertOrUpdateProfile(profile.copy(id = "current_user"))
            }

            // 3. Sync All Users
            val usersResponse = ApiClient.apiService.getAllUsers()
            if (usersResponse.isSuccessful && usersResponse.body()?.data != null) {
                val users = usersResponse.body()!!.data!!
                if (users.isNotEmpty()) {
                    db.userProfileDao().clearRemoteUsers()
                    users.forEach { db.userProfileDao().insertOrUpdateProfile(it) }
                }
            }

            // 4. Sync Transactions
            val txResponse = ApiClient.apiService.getTransactions()
            if (txResponse.isSuccessful && txResponse.body()?.data != null) {
                val txs = txResponse.body()!!.data!!
                txs.forEach { db.transactionDao().insertTransaction(it) }
            }

            // 5. Sync Courses
            val courseResponse = ApiClient.apiService.getCourses()
            if (courseResponse.isSuccessful && courseResponse.body()?.data != null) {
                val courses = courseResponse.body()!!.data!!
                if (courses.isNotEmpty()) {
                    db.courseDao().clearAllCourses()
                    db.courseDao().insertAllCourses(courses)
                }
            }

            // 6. Sync Financial Products
            val finResponse = ApiClient.apiService.getFinancialProducts()
            if (finResponse.isSuccessful && finResponse.body()?.data != null) {
                val products = finResponse.body()!!.data!!
                if (products.isNotEmpty()) {
                    db.financialProductDao().clearAllProducts()
                    db.financialProductDao().insertAllProducts(products)
                }
            }

            // 7. Sync Notifications targeted to active user
            val notifsResponse = ApiClient.apiService.getNotifications(userId = activeUserId)
            if (notifsResponse.isSuccessful && notifsResponse.body()?.data != null) {
                val notifs = notifsResponse.body()!!.data!!
                notifs.forEach { db.notificationDao().insertNotification(it) }
            }

            Log.d("MarketLinkRepo", "Offline-first sync completed with Laravel API.")
            return@withContext true
        } catch (e: Exception) {
            // In offline or low connectivity environments, gracefully retain Room cached records
            Log.w("MarketLinkRepo", "Backend sync offline: ${e.message}. Using Room local cache.")
            return@withContext false
        }
    }

    suspend fun loginUser(login: String, password: String? = null): Pair<UserProfile?, String?> = withContext(Dispatchers.IO) {
        val clean = login.trim()
        try {
            val response = ApiClient.apiService.loginUser(LoginRequest(login = clean, password = password))
            if (response.isSuccessful && response.body()?.data != null) {
                val remoteProfile = response.body()!!.data!!
                activeUserId = remoteProfile.id
                db.userProfileDao().insertOrUpdateProfile(remoteProfile)
                db.userProfileDao().insertOrUpdateProfile(remoteProfile.copy(id = "current_user"))
                syncWithBackend()
                return@withContext Pair(remoteProfile, null)
            } else {
                val errBody = response.errorBody()?.string()
                Log.w("MarketLinkRepo", "Backend rejected login for '$clean': $errBody")
                return@withContext Pair(null, "User not found or credentials invalid on backend database.")
            }
        } catch (e: Exception) {
            val msg = e.localizedMessage ?: e.message ?: "Connection error"
            Log.e("MarketLinkRepo", "Remote login failed: $msg", e)
            return@withContext Pair(null, "Hitilafu ya kuunganisha mtandao. Tafadhali hakikisha simu yako ina intaneti kisha jaribu tena.")
        }
    }

    suspend fun seedInitialDataIfEmpty() {
        // Production Mode: Zero mock users, zero demo listings.
        // Sync directly with live backend database on launch:
        syncWithBackend()
    }

    suspend fun updateProfile(profile: UserProfile) {
        activeUserId = profile.id
        db.userProfileDao().insertOrUpdateProfile(profile)
        db.userProfileDao().insertOrUpdateProfile(profile.copy(id = "current_user"))
    }

    suspend fun registerNewUser(profile: UserProfile, password: String? = null): Pair<Boolean, String?> = withContext(Dispatchers.IO) {
        try {
            val response = ApiClient.apiService.registerUser(
                RegisterRequest(
                    id = profile.id,
                    fullName = profile.fullName,
                    phone = profile.phone,
                    email = profile.email,
                    password = password,
                    role = profile.role.name,
                    accountType = profile.accountType,
                    organizationName = profile.organizationName,
                    tinNumber = profile.tinNumber,
                    industrySector = profile.industrySector,
                    primaryProfession = profile.primaryProfession,
                    district = profile.district,
                    nidaNumber = profile.nidaNumber,
                    location = profile.location,
                    bio = profile.bio,
                    skills = profile.skills
                )
            )
            if (response.isSuccessful && response.body()?.data != null) {
                val remoteProfile = response.body()!!.data!!
                activeUserId = remoteProfile.id
                db.userProfileDao().insertOrUpdateProfile(remoteProfile)
                db.userProfileDao().insertOrUpdateProfile(remoteProfile.copy(id = "current_user"))
                db.notificationDao().insertNotification(
                    NotificationItem(
                        id = "n_" + System.currentTimeMillis(),
                        title = "Welcome to Linkora!",
                        body = "User account '${remoteProfile.fullName}' successfully registered with NIDA ID ${remoteProfile.nidaNumber}.",
                        channel = "SMS (Africa's Talking)",
                        timestamp = "Just now",
                        isRead = false
                    )
                )
                syncWithBackend()
                return@withContext Pair(true, null)
            } else {
                val errorMsg = response.errorBody()?.string() ?: "HTTP ${response.code()}"
                Log.e("MarketLinkRepo", "Registration failed on backend: $errorMsg")
                return@withContext Pair(false, "Server registration failed: $errorMsg")
            }
        } catch (e: Exception) {
            val msg = e.localizedMessage ?: e.message ?: "Network unreachable"
            Log.e("MarketLinkRepo", "Network error reaching ${ApiClient.baseUrl}: $msg", e)
            return@withContext Pair(false, "Cannot connect to server at ${ApiClient.baseUrl} ($msg). Please configure the server URL.")
        }
    }

    suspend fun updateUserVerificationStatus(userId: String, status: VerificationStatus) {
        val user = db.userProfileDao().getUserProfileOnce(userId)
        if (user != null) {
            val updated = user.copy(
                verificationStatus = status,
                reputationScore = if (status == VerificationStatus.VERIFIED) (user.reputationScore + 50).coerceAtMost(1000) else user.reputationScore
            )
            db.userProfileDao().insertOrUpdateProfile(updated)

            try {
                ApiClient.apiService.verifyNida(userId, NidaVerificationRequest(nidaNumber = user.nidaNumber))
            } catch (e: Exception) {
                Log.w("MarketLinkRepo", "NIDA verification update queued locally: ${e.message}")
            }
        }
    }

    suspend fun updateUserRole(userId: String, newRole: UserRole) {
        val user = db.userProfileDao().getUserProfileOnce(userId)
        if (user != null) {
            val updated = user.copy(role = newRole)
            db.userProfileDao().insertOrUpdateProfile(updated)

            try {
                ApiClient.apiService.updateUserRole(userId, mapOf("role" to newRole.name))
            } catch (e: Exception) {
                Log.w("MarketLinkRepo", "Role update queued locally: ${e.message}")
            }
        }
    }

    suspend fun deleteUser(userId: String) {
        db.userProfileDao().deleteUserProfile(userId)
        try {
            ApiClient.apiService.deleteUser(userId)
        } catch (e: Exception) {
            Log.w("MarketLinkRepo", "Delete user queued locally: ${e.message}")
        }
    }

    suspend fun deleteListingByAdmin(listingId: String) {
        db.listingDao().deleteListing(listingId)
        try {
            ApiClient.apiService.deleteListing(listingId)
        } catch (e: Exception) {
            Log.w("MarketLinkRepo", "Delete listing queued locally: ${e.message}")
        }
    }

    suspend fun addListing(listing: Listing) {
        db.listingDao().insertListing(listing)
        try {
            ApiClient.apiService.createListing(
                CreateListingRequest(
                    title = listing.title,
                    category = listing.category.name,
                    targetProfession = listing.targetProfession,
                    jobTypeLevel = listing.jobTypeLevel,
                    description = listing.description,
                    location = listing.location,
                    district = listing.district,
                    compensation = listing.compensation,
                    compensationAmountTzs = listing.compensationAmountTzs,
                    requiredSkills = listing.requiredSkills,
                    isEscrowRequired = listing.isEscrowRequired,
                    agriCropType = listing.agriCropType,
                    equipmentDetails = listing.equipmentDetails
                )
            )
        } catch (e: Exception) {
            Log.w("MarketLinkRepo", "New listing cached locally: ${e.message}")
        }
    }

    suspend fun applyToListing(listingId: String, listingTitle: String, applicantId: String, applicantName: String, pitch: String) {
        val today = SimpleDateFormat("yyyy-MM-dd", Locale.getDefault()).format(Date())
        val app = Application(
            id = "app_" + UUID.randomUUID().toString().take(8),
            listingId = listingId,
            listingTitle = listingTitle,
            applicantId = applicantId,
            applicantName = applicantName,
            pitchMessage = pitch,
            status = "Applied (Awaiting Review)",
            appliedDate = today
        )
        db.applicationDao().insertApplication(app)

        db.notificationDao().insertNotification(
            NotificationItem(
                id = "n_" + System.currentTimeMillis(),
                title = "Application Submitted Successfully",
                body = "Your application for '$listingTitle' has been sent.",
                channel = "In-App",
                timestamp = "Just now",
                isRead = false
            )
        )

        try {
            ApiClient.apiService.applyToListing(
                CreateApplicationRequest(
                    listingId = listingId,
                    listingTitle = listingTitle,
                    pitchMessage = pitch
                )
            )
        } catch (e: Exception) {
            Log.w("MarketLinkRepo", "Application queued locally: ${e.message}")
        }
    }

    suspend fun depositEscrow(listingId: String, title: String, payerName: String, payeeName: String, amount: Long, method: String) {
        val fee = (amount * 0.05).toLong()
        val now = SimpleDateFormat("yyyy-MM-dd HH:mm", Locale.getDefault()).format(Date())
        val tx = Transaction(
            id = "tx_" + UUID.randomUUID().toString().take(6),
            listingId = listingId,
            listingTitle = title,
            payerName = payerName,
            payeeName = payeeName,
            amountTzs = amount,
            feeTzs = fee,
            escrowStatus = EscrowStatus.HELD_IN_ESCROW,
            paymentMethod = "$method Escrow",
            timestamp = now,
            auditCode = "MLP-${method.uppercase().take(4)}-${(10000..99999).random()}-ESC"
        )
        db.transactionDao().insertTransaction(tx)

        db.notificationDao().insertNotification(
            NotificationItem(
                id = "n_" + System.currentTimeMillis(),
                title = "$method Escrow Deposit Complete",
                body = "Locked TZS ${String.format("%,d", amount)} in escrow. Funds will be released upon milestone confirmation.",
                channel = "SMS (Africa's Talking)",
                timestamp = "Just now",
                isRead = false
            )
        )

        try {
            ApiClient.apiService.depositEscrow(
                DepositEscrowRequest(
                    listingId = listingId,
                    listingTitle = title,
                    payeeName = payeeName,
                    amountTzs = amount,
                    paymentMethod = method
                )
            )
        } catch (e: Exception) {
            Log.w("MarketLinkRepo", "Escrow deposit queued locally: ${e.message}")
        }
    }

    suspend fun releaseEscrow(transactionId: String) {
        db.transactionDao().updateEscrowStatus(transactionId, EscrowStatus.RELEASED)

        db.notificationDao().insertNotification(
            NotificationItem(
                id = "n_" + System.currentTimeMillis(),
                title = "Escrow Funds Released",
                body = "Transaction $transactionId funds have been disbursed to worker's mobile wallet.",
                channel = "SMS (Africa's Talking)",
                timestamp = "Just now",
                isRead = false
            )
        )

        try {
            ApiClient.apiService.releaseEscrow(transactionId)
        } catch (e: Exception) {
            Log.w("MarketLinkRepo", "Escrow release queued locally: ${e.message}")
        }
    }

    suspend fun disputeEscrow(transactionId: String, reason: String = "Client or worker dispute raised") {
        db.transactionDao().updateEscrowStatus(transactionId, EscrowStatus.DISPUTED)

        db.notificationDao().insertNotification(
            NotificationItem(
                id = "n_" + System.currentTimeMillis(),
                title = "Mgogoro wa Escrow Umewasilishwa",
                body = "Muamala $transactionId umesitishwa kwa ajili ya upatanishi wa Swebetech. Pesa zimehifadhiwa salama.",
                channel = "SMS (Africa's Talking)",
                timestamp = "Just now",
                isRead = false
            )
        )

        try {
            ApiClient.apiService.disputeEscrow(transactionId, DisputeEscrowRequest(reason))
        } catch (e: Exception) {
            Log.w("MarketLinkRepo", "Escrow dispute queued locally: ${e.message}")
        }
    }

    suspend fun sendMessage(listingId: String, senderName: String, text: String) {
        val now = SimpleDateFormat("hh:mm a", Locale.getDefault()).format(Date())
        val msg = Message(
            id = "msg_" + System.currentTimeMillis(),
            listingId = listingId,
            senderName = senderName,
            receiverName = "Poster/Client",
            text = text,
            timestamp = now,
            isFromMe = true
        )
        db.messageDao().insertMessage(msg)

        try {
            ApiClient.apiService.sendMessage(SendMessageRequest(listingId = listingId, text = text))
        } catch (e: Exception) {
            Log.w("MarketLinkRepo", "Message cached locally: ${e.message}")
        }
    }
}
