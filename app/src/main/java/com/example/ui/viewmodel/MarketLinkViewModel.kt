package com.example.ui.viewmodel

import android.app.Application as AndroidApp
import androidx.lifecycle.AndroidViewModel
import androidx.lifecycle.viewModelScope
import com.example.data.local.MarketLinkDatabase
import com.example.data.model.*
import com.example.data.remote.GeminiService
import com.example.data.repository.MarketLinkRepository
import kotlinx.coroutines.flow.*
import kotlinx.coroutines.launch
import java.text.SimpleDateFormat
import java.util.Date
import java.util.Locale

val defaultUserProfile = UserProfile(
    id = "guest_user",
    fullName = "Guest User",
    phone = "",
    email = "",
    role = UserRole.JOB_SEEKER,
    verificationStatus = VerificationStatus.UNVERIFIED,
    nidaNumber = "",
    location = "Tanzania",
    bio = "Welcome to Linkora. Register or sign in to access opportunities.",
    skills = emptyList(),
    rating = 0.0f,
    totalTransactions = 0,
    completedGigs = 0,
    reputationScore = 0,
    communityVouches = 0,
    badges = emptyList()
)

data class MarketLinkUiState(
    val isLoggedIn: Boolean = false,
    val userProfile: UserProfile = defaultUserProfile,
    val allUsers: List<UserProfile> = emptyList(),
    val isSwahili: Boolean = false,
    val selectedRole: UserRole = UserRole.JOB_SEEKER,
    val listings: List<Listing> = emptyList(),
    val myOrganizationListings: List<Listing> = emptyList(),
    val isViewingPublicDirectory: Boolean = false,
    val searchQuery: String = "",
    val selectedCategory: OpportunityCategory? = null,
    val selectedRegion: String = "All Regions",
    val applications: List<Application> = emptyList(),
    val transactions: List<Transaction> = emptyList(),
    val courses: List<Course> = emptyList(),
    val notifications: List<NotificationItem> = emptyList(),
    val selectedListingDetail: Listing? = null,
    val activeMessages: List<Message> = emptyList(),
    val isAiAnalyzing: Boolean = false,
    val aiMatchExplanation: String? = null,
    val aiMatchScore: Int? = null,
    val aiFraudWarning: String? = null,
    val generatedPitchText: String? = null,
    val ussdSimulatedCode: String? = null
) {
    val isOrganizationWorkspace: Boolean
        get() = userProfile.accountType == "ORGANIZATION" || selectedRole in listOf(UserRole.EMPLOYER, UserRole.SERVICE_CLIENT, UserRole.FARMER_COOP)
}

private data class FilterState(
    val isLoggedIn: Boolean = false,
    val isSwahili: Boolean = false,
    val selectedRole: UserRole = UserRole.JOB_SEEKER,
    val searchQuery: String = "",
    val selectedCategory: OpportunityCategory? = null,
    val selectedRegion: String = "All Regions",
    val selectedListingDetail: Listing? = null,
    val isAiAnalyzing: Boolean = false,
    val isViewingPublicDirectory: Boolean = false
)

private data class RepoData(
    val userProfile: UserProfile?,
    val allUsers: List<UserProfile>,
    val listings: List<Listing>,
    val applications: List<Application>,
    val transactions: List<Transaction>,
    val courses: List<Course>,
    val notifications: List<NotificationItem>
)

class MarketLinkViewModel(application: AndroidApp) : AndroidViewModel(application) {

    private val repository: MarketLinkRepository

    private val _isLoggedIn = MutableStateFlow(false)
    private val _isSwahili = MutableStateFlow(false)
    private val _selectedRole = MutableStateFlow(UserRole.JOB_SEEKER)
    private val _searchQuery = MutableStateFlow("")
    private val _selectedCategory = MutableStateFlow<OpportunityCategory?>(null)
    private val _selectedRegion = MutableStateFlow("All Regions")
    private val _selectedListingDetail = MutableStateFlow<Listing?>(null)
    private val _isAiAnalyzing = MutableStateFlow(false)
    private val _aiMatchExplanation = MutableStateFlow<String?>(null)
    private val _aiMatchScore = MutableStateFlow<Int?>(null)
    private val _aiFraudWarning = MutableStateFlow<String?>(null)
    private val _generatedPitchText = MutableStateFlow<String?>(null)
    private val _ussdCode = MutableStateFlow<String?>(null)
    private val _isViewingPublicDirectory = MutableStateFlow(false)

    init {
        val database = MarketLinkDatabase.getDatabase(application)
        repository = MarketLinkRepository(database)
        viewModelScope.launch {
            repository.seedInitialDataIfEmpty()
        }
    }

private data class FilterPartA(
    val isLoggedIn: Boolean,
    val isSwahili: Boolean,
    val selectedRole: UserRole,
    val searchQuery: String
)

private data class FilterPartB(
    val selectedCategory: OpportunityCategory?,
    val selectedRegion: String,
    val selectedListingDetail: Listing?,
    val isAiAnalyzing: Boolean,
    val isViewingPublicDirectory: Boolean
)

private data class RepoPartA(
    val userProfile: UserProfile?,
    val allUsers: List<UserProfile>,
    val listings: List<Listing>
)

private data class RepoPartB(
    val applications: List<Application>,
    val transactions: List<Transaction>,
    val courses: List<Course>,
    val notifications: List<NotificationItem>
)

    private val filterState: Flow<FilterState> = combine(
        combine(_isLoggedIn, _isSwahili, _selectedRole, _searchQuery) { isLoggedIn, isSwahili, role, query ->
            FilterPartA(isLoggedIn, isSwahili, role, query)
        },
        combine(_selectedCategory, _selectedRegion, _selectedListingDetail, _isAiAnalyzing, _isViewingPublicDirectory) { cat, reg, det, ai, pub ->
            FilterPartB(cat, reg, det, ai, pub)
        }
    ) { a, b ->
        FilterState(
            isLoggedIn = a.isLoggedIn,
            isSwahili = a.isSwahili,
            selectedRole = a.selectedRole,
            searchQuery = a.searchQuery,
            selectedCategory = b.selectedCategory,
            selectedRegion = b.selectedRegion,
            selectedListingDetail = b.selectedListingDetail,
            isAiAnalyzing = b.isAiAnalyzing,
            isViewingPublicDirectory = b.isViewingPublicDirectory
        )
    }

    private val repoData: Flow<RepoData> = combine(
        combine(repository.userProfile, repository.allUsers, repository.allListings) { profile, allUsers, listings ->
            RepoPartA(profile, allUsers, listings)
        },
        combine(repository.allApplications, repository.allTransactions, repository.allCourses, repository.allNotifications) { apps, txs, courses, notifs ->
            RepoPartB(apps, txs, courses, notifs)
        }
    ) { a, b ->
        RepoData(
            userProfile = a.userProfile,
            allUsers = a.allUsers,
            listings = a.listings,
            applications = b.applications,
            transactions = b.transactions,
            courses = b.courses,
            notifications = b.notifications
        )
    }

    val uiState: StateFlow<MarketLinkUiState> = combine(
        filterState,
        repoData,
        _aiMatchExplanation,
        _aiMatchScore,
        _aiFraudWarning
    ) { filter, data, matchExp, matchScore, fraudWarning ->
        val user = data.userProfile ?: defaultUserProfile
        val userProfNorm = user.primaryProfession.uppercase()
        val userSkillsNorm = user.skills.joinToString(" ").lowercase()
        val isUserIt = userProfNorm.contains("IT") || userProfNorm.contains("SOFTWARE") ||
                userSkillsNorm.contains("software") || userSkillsNorm.contains("developer") ||
                userSkillsNorm.contains("computer") || userSkillsNorm.contains("network") ||
                userSkillsNorm.contains("kotlin") || userSkillsNorm.contains("laravel")

        val scoredListings = data.listings.map { item ->
            val targetProfNorm = (item.targetProfession ?: "").uppercase()
            val jobText = (item.title + " " + item.description + " " + item.requiredSkills.joinToString(" ")).lowercase()

            val isJobCleaning = targetProfNorm.contains("CLEANING") ||
                    jobText.contains("usafi") || jobText.contains("cleaner") ||
                    jobText.contains("kuosha") || jobText.contains("housekeeping") || jobText.contains("janitor")

            val isJobIt = targetProfNorm.contains("IT") || targetProfNorm.contains("SOFTWARE") ||
                    jobText.contains("software") || jobText.contains("developer") ||
                    jobText.contains("network") || jobText.contains("system") || jobText.contains("database")

            val dynamicScore = when {
                isUserIt && isJobCleaning -> 12
                isUserIt && isJobIt -> 95
                userProfNorm.contains("CLEAN") && isJobCleaning -> 93
                userProfNorm.contains("CLEAN") && isJobIt -> 10
                userProfNorm.isNotBlank() && targetProfNorm.isNotBlank() && userProfNorm == targetProfNorm -> 90
                item.matchScore > 0 -> item.matchScore
                else -> {
                    val matching = user.skills.count { skill ->
                        item.requiredSkills.any { req -> req.contains(skill, ignoreCase = true) || skill.contains(req, ignoreCase = true) }
                    }
                    if (matching > 0) minOf(94, 75 + (matching * 6)) else 45
                }
            }
            item.copy(matchScore = dynamicScore)
        }

        val filteredListings = scoredListings
            .filter { item ->
                val matchesQuery = filter.searchQuery.isBlank() ||
                        item.title.contains(filter.searchQuery, ignoreCase = true) ||
                        item.description.contains(filter.searchQuery, ignoreCase = true) ||
                        item.location.contains(filter.searchQuery, ignoreCase = true) ||
                        item.requiredSkills.any { it.contains(filter.searchQuery, ignoreCase = true) }

                val matchesCat = filter.selectedCategory == null || item.category == filter.selectedCategory
                val matchesRegion = filter.selectedRegion == "All Regions" || item.location.contains(filter.selectedRegion, ignoreCase = true)

                matchesQuery && matchesCat && matchesRegion
            }
            .sortedByDescending { it.matchScore }

        val isOrgUser = user.accountType == "ORGANIZATION" ||
                filter.selectedRole in listOf(UserRole.EMPLOYER, UserRole.SERVICE_CLIENT, UserRole.FARMER_COOP)

        val myOrgListings = scoredListings.filter { item ->
            item.posterId == user.id ||
                    (user.organizationName.isNotBlank() && item.posterName.equals(user.organizationName, ignoreCase = true))
        }

        val activeListingsForFeed = if (isOrgUser && !filter.isViewingPublicDirectory) {
            myOrgListings.filter { item ->
                filter.searchQuery.isBlank() ||
                        item.title.contains(filter.searchQuery, ignoreCase = true) ||
                        item.description.contains(filter.searchQuery, ignoreCase = true) ||
                        item.location.contains(filter.searchQuery, ignoreCase = true)
            }
        } else {
            filteredListings
        }

        MarketLinkUiState(
            isLoggedIn = filter.isLoggedIn,
            userProfile = data.userProfile ?: defaultUserProfile,
            allUsers = data.allUsers,
            isSwahili = filter.isSwahili,
            selectedRole = filter.selectedRole,
            listings = activeListingsForFeed,
            myOrganizationListings = myOrgListings,
            isViewingPublicDirectory = filter.isViewingPublicDirectory,
            searchQuery = filter.searchQuery,
            selectedCategory = filter.selectedCategory,
            selectedRegion = filter.selectedRegion,
            applications = data.applications,
            transactions = data.transactions,
            courses = data.courses,
            notifications = data.notifications,
            selectedListingDetail = filter.selectedListingDetail,
            isAiAnalyzing = filter.isAiAnalyzing,
            aiMatchExplanation = matchExp,
            aiMatchScore = matchScore,
            aiFraudWarning = fraudWarning,
            generatedPitchText = _generatedPitchText.value,
            ussdSimulatedCode = _ussdCode.value
        )
    }.stateIn(
        scope = viewModelScope,
        started = SharingStarted.WhileSubscribed(5000),
        initialValue = MarketLinkUiState()
    )

    fun setLanguage(isSwahili: Boolean) {
        _isSwahili.value = isSwahili
    }

    fun setUserRole(role: UserRole) {
        _selectedRole.value = role
        viewModelScope.launch {
            val current = repository.userProfile.first()
            if (current != null) {
                repository.updateProfile(current.copy(role = role))
            }
        }
    }

    fun setSearchQuery(query: String) {
        _searchQuery.value = query
    }

    fun setCategoryFilter(category: OpportunityCategory?) {
        _selectedCategory.value = category
    }

    fun setRegionFilter(region: String) {
        _selectedRegion.value = region
    }

    fun selectListingDetail(listing: Listing?) {
        _selectedListingDetail.value = listing
        if (listing != null) {
            runAiMatchAnalysis(listing)
            runAiFraudCheck(listing.title, listing.description, listing.compensation)
        }
    }

    fun applyToListing(listingId: String, title: String, pitch: String) {
        viewModelScope.launch {
            val user = repository.userProfile.first() ?: defaultUserProfile
            repository.applyToListing(listingId, title, user.id, user.fullName, pitch)
        }
    }

    fun depositEscrow(listingId: String, title: String, payeeName: String, amount: Long, method: String) {
        viewModelScope.launch {
            val user = repository.userProfile.first() ?: defaultUserProfile
            val payerName = if (user.fullName.isNotBlank()) user.fullName else "Client"
            repository.depositEscrow(listingId, title, payerName, payeeName, amount, method)
        }
    }

    fun releaseEscrow(transactionId: String) {
        viewModelScope.launch {
            repository.releaseEscrow(transactionId)
        }
    }

    fun disputeEscrow(transactionId: String, reason: String = "Client or worker dispute raised") {
        viewModelScope.launch {
            repository.disputeEscrow(transactionId, reason)
        }
    }

    fun requestNidaVerification(nidaNum: String) {
        viewModelScope.launch {
            val current = repository.userProfile.first()
            if (current != null) {
                val updated = current.copy(
                    nidaNumber = nidaNum,
                    verificationStatus = VerificationStatus.VERIFIED,
                    reputationScore = (current.reputationScore + 50).coerceAtMost(1000)
                )
                repository.updateProfile(updated)
            }
        }
    }

    fun vouchForUser() {
        viewModelScope.launch {
            val current = repository.userProfile.first()
            if (current != null) {
                val updated = current.copy(
                    communityVouches = current.communityVouches + 1,
                    reputationScore = (current.reputationScore + 15).coerceAtMost(1000)
                )
                repository.updateProfile(updated)
            }
        }
    }

    fun createNewListing(
        title: String,
        category: OpportunityCategory,
        description: String,
        compensation: String,
        amountTzs: Long,
        location: String,
        skills: List<String>,
        isEscrow: Boolean,
        cropType: String? = null,
        equipDetails: String? = null,
        targetProfession: String? = null,
        jobTypeLevel: String = "NON_PROFESSIONAL",
        district: String? = null
    ) {
        viewModelScope.launch {
            val user = repository.userProfile.first() ?: defaultUserProfile
            val today = SimpleDateFormat("yyyy-MM-dd", Locale.getDefault()).format(Date())
            val posterName = if (user.organizationName.isNotBlank()) user.organizationName else if (user.fullName.isNotBlank()) user.fullName else "Platform Member"
            val newListing = Listing(
                id = "lst_" + System.currentTimeMillis().toString().takeLast(6),
                posterId = user.id,
                posterName = posterName,
                posterVerification = user.verificationStatus,
                posterRating = if (user.rating > 0f) user.rating else 5.0f,
                category = category,
                targetProfession = targetProfession,
                jobTypeLevel = jobTypeLevel,
                title = title,
                description = description,
                location = location,
                district = district,
                compensation = compensation,
                compensationAmountTzs = amountTzs,
                requiredSkills = skills,
                datePosted = today,
                deadline = today,
                isFeatured = false,
                isEscrowRequired = isEscrow,
                matchScore = 0,
                agriCropType = cropType,
                equipmentDetails = equipDetails
            )
            repository.addListing(newListing)
            _isViewingPublicDirectory.value = false
        }
    }

    fun runAiMatchAnalysis(listing: Listing) {
        viewModelScope.launch {
            _isAiAnalyzing.value = true
            val profile = repository.userProfile.first()
            val skills = profile?.skills ?: listOf("General Labor")
            val loc = profile?.location ?: "Mbeya"

            try {
                val remoteResp = com.example.data.remote.ApiClient.apiService.getAiMatch(
                    com.example.data.remote.AiMatchRequest(skills, loc, listing.title, listing.requiredSkills)
                )
                if (remoteResp.isSuccessful && remoteResp.body()?.data != null) {
                    val data = remoteResp.body()!!.data!!
                    _aiMatchScore.value = data.matchScore
                    _aiMatchExplanation.value = data.explanation
                    _isAiAnalyzing.value = false
                    return@launch
                }
            } catch (e: Exception) {
                // Fallback to local GeminiService
            }

            val (score, explanation) = GeminiService.analyzeMatchScore(
                userSkills = skills,
                userLocation = loc,
                listingTitle = listing.title,
                listingReqs = listing.requiredSkills,
                userProfession = profile?.primaryProfession,
                targetProfession = listing.targetProfession,
                isSwahili = _isSwahili.value
            )

            _aiMatchScore.value = score
            _aiMatchExplanation.value = explanation
            _isAiAnalyzing.value = false
        }
    }

    fun runAiFraudCheck(title: String, desc: String, comp: String) {
        viewModelScope.launch {
            try {
                val remoteResp = com.example.data.remote.ApiClient.apiService.checkAiFraud(
                    com.example.data.remote.AiFraudCheckRequest(title, desc, comp)
                )
                if (remoteResp.isSuccessful && remoteResp.body()?.data != null) {
                    val data = remoteResp.body()!!.data!!
                    _aiFraudWarning.value = if (data.isSuspicious) data.riskReason else null
                    return@launch
                }
            } catch (e: Exception) {
                // Fallback to local GeminiService
            }

            val (isSuspicious, reason) = GeminiService.checkFraudRisk(title, desc, comp)
            _aiFraudWarning.value = if (isSuspicious) reason else null
        }
    }

    fun generateAiPitch(jobTitle: String) {
        viewModelScope.launch {
            _isAiAnalyzing.value = true
            val profile = repository.userProfile.first()
            val skills = profile?.skills ?: listOf("Communication", "Field Operations")
            val isSwahili = _isSwahili.value

            try {
                val remoteResp = com.example.data.remote.ApiClient.apiService.generateAiPitch(
                    com.example.data.remote.AiPitchRequest(jobTitle, skills, isSwahili)
                )
                if (remoteResp.isSuccessful && remoteResp.body()?.data != null) {
                    val data = remoteResp.body()!!.data!!
                    _generatedPitchText.value = data.pitch
                    _isAiAnalyzing.value = false
                    return@launch
                }
            } catch (e: Exception) {
                // Fallback to local GeminiService
            }

            val pitch = GeminiService.generateApplicationPitch(jobTitle, skills, isSwahili)
            _generatedPitchText.value = pitch
            _isAiAnalyzing.value = false
        }
    }

    fun simulateUssdDial(code: String) {
        _ussdCode.value = code
        viewModelScope.launch {
            try {
                com.example.data.remote.ApiClient.apiService.simulateUssd(
                    com.example.data.remote.UssdSimulateRequest(
                        sessionId = "ussd_" + System.currentTimeMillis(),
                        phoneNumber = "+255754892104",
                        text = ""
                    )
                )
            } catch (e: Exception) {
                // Local fallback
            }
        }
    }

    fun sendMessage(listingId: String, text: String) {
        viewModelScope.launch {
            val user = repository.userProfile.first() ?: defaultUserProfile
            val senderName = if (user.fullName.isNotBlank()) user.fullName else "Member"
            repository.sendMessage(listingId, senderName, text)
        }
    }

    fun registerUser(
        fullName: String,
        phone: String,
        email: String,
        role: UserRole,
        nidaNumber: String,
        location: String,
        bio: String,
        skills: List<String>,
        accountType: String = "INDIVIDUAL",
        organizationName: String = "",
        tinNumber: String = "",
        industrySector: String = "",
        primaryProfession: String = "",
        district: String = "",
        password: String? = null,
        onResult: ((Boolean, String?) -> Unit)? = null
    ) {
        viewModelScope.launch {
            try {
                val newUserId = (if (accountType == "ORGANIZATION") "org_" else "usr_") + System.currentTimeMillis().toString().takeLast(6)
                val profile = UserProfile(
                    id = newUserId,
                    fullName = fullName,
                    phone = phone,
                    email = email,
                    role = role,
                    accountType = accountType,
                    organizationName = organizationName,
                    tinNumber = tinNumber,
                    industrySector = industrySector,
                    primaryProfession = primaryProfession,
                    district = district,
                    verificationStatus = if (nidaNumber.isNotBlank() || tinNumber.isNotBlank()) VerificationStatus.VERIFIED else VerificationStatus.PENDING,
                    nidaNumber = nidaNumber,
                    location = location,
                    bio = bio,
                    skills = skills,
                    rating = 5.0f,
                    totalTransactions = 0,
                    completedGigs = 0,
                    reputationScore = if (nidaNumber.isNotBlank() || tinNumber.isNotBlank()) 850 else 700,
                    communityVouches = 0,
                    badges = if (accountType == "ORGANIZATION") listOf("Verified Organization") else if (nidaNumber.isNotBlank()) listOf("NIDA Verified") else emptyList()
                )
                val (success, err) = repository.registerNewUser(profile, password)
                if (success) {
                    _selectedRole.value = role
                    _isLoggedIn.value = true
                    onResult?.invoke(true, null)
                } else {
                    onResult?.invoke(false, err)
                }
            } catch (e: Throwable) {
                android.util.Log.e("MarketLinkVM", "Error in registerUser: ${e.message}", e)
                onResult?.invoke(false, e.localizedMessage ?: "Registration error: ${e.message}")
            }
        }
    }

    fun signInUser(profile: UserProfile) {
        viewModelScope.launch {
            repository.updateProfile(profile)
            _selectedRole.value = profile.role
            _isLoggedIn.value = true
        }
    }

    fun switchActiveProfile(profile: UserProfile) {
        viewModelScope.launch {
            repository.updateProfile(profile)
            _selectedRole.value = profile.role
            _isLoggedIn.value = true
        }
    }

    fun adminVerifyUser(userId: String, status: VerificationStatus) {
        viewModelScope.launch {
            repository.updateUserVerificationStatus(userId, status)
        }
    }

    fun adminUpdateUserRole(userId: String, role: UserRole) {
        viewModelScope.launch {
            repository.updateUserRole(userId, role)
        }
    }

    fun adminDeleteUser(userId: String) {
        viewModelScope.launch {
            repository.deleteUser(userId)
        }
    }

    fun adminDeleteListing(listingId: String) {
        viewModelScope.launch {
            repository.deleteListingByAdmin(listingId)
        }
    }

    fun signInWithCredentials(login: String, password: String? = null, onResult: (Boolean, String?) -> Unit) {
        viewModelScope.launch {
            try {
                val (profile, err) = repository.loginUser(login, password)
                if (profile != null) {
                    _selectedRole.value = profile.role
                    _isLoggedIn.value = true
                    onResult(true, null)
                } else {
                    onResult(false, err ?: "Invalid credentials or user not found on backend database.")
                }
            } catch (e: Throwable) {
                android.util.Log.e("MarketLinkVM", "Error in signInWithCredentials: ${e.message}", e)
                onResult(false, e.localizedMessage ?: "Sign in error: ${e.message}")
            }
        }
    }

    fun triggerManualSync(onComplete: ((Boolean) -> Unit)? = null) {
        viewModelScope.launch {
            val success = repository.syncWithBackend()
            onComplete?.invoke(success)
        }
    }

    fun updateServerBaseUrl(newUrl: String, onComplete: ((Boolean) -> Unit)? = null) {
        com.example.data.remote.ApiClient.updateBaseUrl(newUrl)
        triggerManualSync(onComplete)
    }

    fun setViewingPublicDirectory(viewPublic: Boolean) {
        _isViewingPublicDirectory.value = viewPublic
    }

    fun deleteMyListing(listingId: String) {
        viewModelScope.launch {
            val user = repository.userProfile.first() ?: defaultUserProfile
            repository.deleteListing(listingId, user.id)
        }
    }

    fun logout() {
        viewModelScope.launch {
            repository.updateProfile(defaultUserProfile)
            _selectedRole.value = UserRole.JOB_SEEKER
            _isViewingPublicDirectory.value = false
            _isLoggedIn.value = false
        }
    }
}
