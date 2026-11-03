package com.example.data.local

import android.content.Context
import androidx.room.Database
import androidx.room.Room
import androidx.room.RoomDatabase
import androidx.room.TypeConverters
import androidx.room.migration.Migration
import androidx.sqlite.db.SupportSQLiteDatabase
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
    version = 4,
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

        val MIGRATION_1_2 = object : Migration(1, 2) {
            override fun migrate(db: SupportSQLiteDatabase) {
                // Version 1 to 2 schema baseline
            }
        }

        val MIGRATION_2_3 = object : Migration(2, 3) {
            override fun migrate(db: SupportSQLiteDatabase) {
                try {
                    db.execSQL("ALTER TABLE user_profiles ADD COLUMN tenantId TEXT")
                } catch (_: Exception) {}
                try {
                    db.execSQL("ALTER TABLE user_profiles ADD COLUMN tenantDatabase TEXT")
                } catch (_: Exception) {}
                try {
                    db.execSQL("ALTER TABLE user_profiles ADD COLUMN isolationLevel TEXT DEFAULT 'SHARED'")
                } catch (_: Exception) {}
            }
        }

        val MIGRATION_1_3 = object : Migration(1, 3) {
            override fun migrate(db: SupportSQLiteDatabase) {
                try {
                    db.execSQL("ALTER TABLE user_profiles ADD COLUMN tenantId TEXT")
                } catch (_: Exception) {}
                try {
                    db.execSQL("ALTER TABLE user_profiles ADD COLUMN tenantDatabase TEXT")
                } catch (_: Exception) {}
                try {
                    db.execSQL("ALTER TABLE user_profiles ADD COLUMN isolationLevel TEXT DEFAULT 'SHARED'")
                } catch (_: Exception) {}
            }
        }

        val MIGRATION_3_4 = object : Migration(3, 4) {
            override fun migrate(db: SupportSQLiteDatabase) {
                try {
                    db.execSQL("ALTER TABLE user_profiles ADD COLUMN avatarUrl TEXT NOT NULL DEFAULT ''")
                } catch (_: Exception) {}
                try {
                    db.execSQL("ALTER TABLE user_profiles ADD COLUMN referralCode TEXT NOT NULL DEFAULT ''")
                } catch (_: Exception) {}
                try {
                    db.execSQL("ALTER TABLE user_profiles ADD COLUMN referredBy TEXT NOT NULL DEFAULT ''")
                } catch (_: Exception) {}
                try {
                    db.execSQL("ALTER TABLE user_profiles ADD COLUMN referralCount INTEGER NOT NULL DEFAULT 0")
                } catch (_: Exception) {}
                try {
                    db.execSQL("ALTER TABLE user_profiles ADD COLUMN referralEarningsTzs INTEGER NOT NULL DEFAULT 0")
                } catch (_: Exception) {}
            }
        }

        val MIGRATION_1_4 = object : Migration(1, 4) {
            override fun migrate(db: SupportSQLiteDatabase) {
                MIGRATION_1_3.migrate(db)
                MIGRATION_3_4.migrate(db)
            }
        }

        val MIGRATION_2_4 = object : Migration(2, 4) {
            override fun migrate(db: SupportSQLiteDatabase) {
                MIGRATION_2_3.migrate(db)
                MIGRATION_3_4.migrate(db)
            }
        }

        fun getDatabase(context: Context): MarketLinkDatabase {
            return INSTANCE ?: synchronized(this) {
                val instance = Room.databaseBuilder(
                    context.applicationContext,
                    MarketLinkDatabase::class.java,
                    "marketlink_pro_db"
                )
                .addMigrations(
                    MIGRATION_1_2,
                    MIGRATION_2_3,
                    MIGRATION_1_3,
                    MIGRATION_3_4,
                    MIGRATION_1_4,
                    MIGRATION_2_4
                )
                .fallbackToDestructiveMigration(dropAllTables = true)
                .fallbackToDestructiveMigrationOnDowngrade(dropAllTables = true)
                .build()
                INSTANCE = instance
                instance
            }
        }
    }
}
