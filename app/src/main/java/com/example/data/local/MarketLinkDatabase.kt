package com.example.data.local

import android.content.Context
import androidx.room.Database
import androidx.room.Room
import androidx.room.RoomDatabase
import androidx.room.TypeConverters
import com.example.data.model.UserProfile
import com.example.data.model.Listing
import com.example.data.model.Application
import com.example.data.model.Transaction
import com.example.data.model.Rating
import com.example.data.model.Course
import com.example.data.model.FinancialProduct
import com.example.data.model.Message
import com.example.data.model.NotificationItem

@Database(
    entities = [
        UserProfile::class,
        Listing::class,
        Application::class,
        Transaction::class,
        Rating::class,
        Course::class,
        FinancialProduct::class,
        Message::class,
        NotificationItem::class
    ],
    version = 2,
    exportSchema = false
)
@TypeConverters(Converters::class)
abstract class MarketLinkDatabase : RoomDatabase() {
    abstract fun userProfileDao(): UserProfileDao
    abstract fun listingDao(): ListingDao
    abstract fun applicationDao(): ApplicationDao
    abstract fun transactionDao(): TransactionDao
    abstract fun ratingDao(): RatingDao
    abstract fun financialProductDao(): FinancialProductDao
    abstract fun messageDao(): MessageDao
    abstract fun courseDao(): CourseDao
    abstract fun notificationDao(): NotificationDao

    companion object {
        @Volatile
        private var INSTANCE: MarketLinkDatabase? = null

        fun getDatabase(context: Context): MarketLinkDatabase {
            return INSTANCE ?: synchronized(this) {
                val instance = Room.databaseBuilder(
                    context.applicationContext,
                    MarketLinkDatabase::class.java,
                    "marketlink_pro_db"
                )
                .fallbackToDestructiveMigration()
                .build()
                INSTANCE = instance
                instance
            }
        }
    }
}
