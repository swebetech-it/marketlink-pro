package com.example.data.local

import androidx.room.Dao
import androidx.room.Insert
import androidx.room.OnConflictStrategy
import androidx.room.Query
import androidx.room.TypeConverters
import com.example.data.model.Application
import com.example.data.model.Course
import com.example.data.model.FinancialProduct
import com.example.data.model.Listing
import com.example.data.model.Message
import com.example.data.model.NotificationItem
import com.example.data.model.OpportunityCategory
import com.example.data.model.Rating
import com.example.data.model.Transaction
import com.example.data.model.UserProfile
import com.example.data.model.EscrowStatus
import kotlinx.coroutines.flow.Flow

@TypeConverters(Converters::class)
@Dao
interface UserProfileDao {
    @Query("SELECT * FROM user_profiles WHERE id = :id LIMIT 1")
    fun getUserProfile(id: String): Flow<UserProfile?>

    @Query("SELECT * FROM user_profiles ORDER BY fullName ASC")
    fun getAllUserProfiles(): Flow<List<UserProfile>>

    @Query("SELECT * FROM user_profiles WHERE id = :id LIMIT 1")
    suspend fun getUserProfileOnce(id: String): UserProfile?

    @Query("SELECT * FROM user_profiles")
    suspend fun getAllUserProfilesOnce(): List<UserProfile>

    @Insert(onConflict = OnConflictStrategy.REPLACE)
    suspend fun insertOrUpdateProfile(profile: UserProfile)

    @Query("DELETE FROM user_profiles WHERE id = :id")
    suspend fun deleteUserProfile(id: String)

    @Query("DELETE FROM user_profiles WHERE id != 'current_user'")
    suspend fun clearRemoteUsers()
}

@TypeConverters(Converters::class)
@Dao
interface ListingDao {
    @Query("SELECT * FROM listings ORDER BY isFeatured DESC, datePosted DESC")
    fun getAllListings(): Flow<List<Listing>>

    @Query("SELECT * FROM listings WHERE category = :category ORDER BY datePosted DESC")
    fun getListingsByCategory(category: OpportunityCategory): Flow<List<Listing>>

    @Query("SELECT * FROM listings WHERE id = :id LIMIT 1")
    suspend fun getListingById(id: String): Listing?

    @Insert(onConflict = OnConflictStrategy.REPLACE)
    suspend fun insertListing(listing: Listing)

    @Insert(onConflict = OnConflictStrategy.REPLACE)
    suspend fun insertAllListings(listings: List<Listing>)

    @Query("DELETE FROM listings WHERE id = :id")
    suspend fun deleteListing(id: String)

    @Query("DELETE FROM listings")
    suspend fun clearAllListings()
}

@TypeConverters(Converters::class)
@Dao
interface ApplicationDao {
    @Query("SELECT * FROM applications ORDER BY appliedDate DESC")
    fun getAllApplications(): Flow<List<Application>>

    @Insert(onConflict = OnConflictStrategy.REPLACE)
    suspend fun insertApplication(application: Application)
}

@TypeConverters(Converters::class)
@Dao
interface TransactionDao {
    @Query("SELECT * FROM transactions ORDER BY timestamp DESC")
    fun getAllTransactions(): Flow<List<Transaction>>

    @Insert(onConflict = OnConflictStrategy.REPLACE)
    suspend fun insertTransaction(transaction: Transaction)

    @Query("UPDATE transactions SET escrowStatus = :escrowStatus WHERE id = :id")
    suspend fun updateEscrowStatus(id: String, escrowStatus: EscrowStatus)
}

@TypeConverters(Converters::class)
@Dao
interface RatingDao {
    @Query("SELECT * FROM ratings ORDER BY date DESC")
    fun getAllRatings(): Flow<List<Rating>>

    @Insert(onConflict = OnConflictStrategy.REPLACE)
    suspend fun insertRating(rating: Rating)
}

@TypeConverters(Converters::class)
@Dao
interface FinancialProductDao {
    @Query("SELECT * FROM financial_products")
    fun getAllFinancialProducts(): Flow<List<FinancialProduct>>

    @Insert(onConflict = OnConflictStrategy.REPLACE)
    suspend fun insertAllProducts(products: List<FinancialProduct>)

    @Query("DELETE FROM financial_products")
    suspend fun clearAllProducts()
}

@TypeConverters(Converters::class)
@Dao
interface MessageDao {
    @Query("SELECT * FROM messages WHERE listingId = :listingId ORDER BY timestamp ASC")
    fun getMessagesForListing(listingId: String): Flow<List<Message>>

    @Insert(onConflict = OnConflictStrategy.REPLACE)
    suspend fun insertMessage(message: Message)
}

@TypeConverters(Converters::class)
@Dao
interface CourseDao {
    @Query("SELECT * FROM courses")
    fun getAllCourses(): Flow<List<Course>>

    @Insert(onConflict = OnConflictStrategy.REPLACE)
    suspend fun insertAllCourses(courses: List<Course>)

    @Query("UPDATE courses SET isEnrolled = :isEnrolled, progressPercent = :progress WHERE id = :id")
    suspend fun updateCourseEnrollment(id: String, isEnrolled: Boolean, progress: Int)

    @Query("DELETE FROM courses")
    suspend fun clearAllCourses()
}

@TypeConverters(Converters::class)
@Dao
interface NotificationDao {
    @Query("SELECT * FROM notifications ORDER BY timestamp DESC")
    fun getAllNotifications(): Flow<List<NotificationItem>>

    @Insert(onConflict = OnConflictStrategy.REPLACE)
    suspend fun insertNotification(notification: NotificationItem)

    @Query("UPDATE notifications SET isRead = 1 WHERE id = :id")
    suspend fun markAsRead(id: String)
}
