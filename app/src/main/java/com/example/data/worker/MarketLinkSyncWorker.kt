package com.example.data.worker

import android.content.Context
import android.util.Log
import androidx.work.*
import com.example.data.local.MarketLinkDatabase
import com.example.data.repository.MarketLinkRepository
import java.util.concurrent.TimeUnit

class MarketLinkSyncWorker(
    appContext: Context,
    workerParams: WorkerParameters
) : CoroutineWorker(appContext, workerParams) {

    override suspend fun doWork(): Result {
        Log.d(TAG, "MarketLinkSyncWorker starting background sync with Laravel API...")
        return try {
            val db = MarketLinkDatabase.getDatabase(applicationContext)
            val repository = MarketLinkRepository(db)
            repository.syncWithBackend()
            Log.d(TAG, "MarketLinkSyncWorker successfully synchronized Room database with backend.")
            Result.success()
        } catch (e: Exception) {
            Log.e(TAG, "MarketLinkSyncWorker error during background sync: ${e.message}", e)
            Result.retry()
        }
    }

    companion object {
        private const val TAG = "MarketLinkSyncWorker"
        private const val UNIQUE_WORK_NAME = "MarketLinkPeriodicSyncWork"

        /**
         * Schedules automated background synchronization every 15 minutes whenever network is connected.
         */
        fun schedulePeriodicSync(context: Context) {
            val constraints = Constraints.Builder()
                .setRequiredNetworkType(NetworkType.CONNECTED)
                .build()

            val syncRequest = PeriodicWorkRequestBuilder<MarketLinkSyncWorker>(
                15, TimeUnit.MINUTES,
                5, TimeUnit.MINUTES // Flex interval
            )
                .setConstraints(constraints)
                .setBackoffCriteria(BackoffPolicy.EXPONENTIAL, 1, TimeUnit.MINUTES)
                .build()

            WorkManager.getInstance(context).enqueueUniquePeriodicWork(
                UNIQUE_WORK_NAME,
                ExistingPeriodicWorkPolicy.KEEP,
                syncRequest
            )
            Log.d(TAG, "Periodic background sync work scheduled (15 min interval).")
        }

        /**
         * Enqueues an immediate one-time sync request when app resumes or network restores.
         */
        fun enqueueImmediateSync(context: Context) {
            val constraints = Constraints.Builder()
                .setRequiredNetworkType(NetworkType.CONNECTED)
                .build()

            val oneTimeRequest = OneTimeWorkRequestBuilder<MarketLinkSyncWorker>()
                .setConstraints(constraints)
                .build()

            WorkManager.getInstance(context).enqueue(oneTimeRequest)
            Log.d(TAG, "One-time immediate background sync enqueued.")
        }
    }
}
