package com.example.service

import android.content.Context
import android.util.Log
import com.example.data.local.MarketLinkDatabase
import com.example.data.model.NotificationItem
import com.example.data.remote.ApiClient
import com.google.firebase.messaging.FirebaseMessagingService
import com.google.firebase.messaging.RemoteMessage
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch

class LinkoraFirebaseMessagingService : FirebaseMessagingService() {

    override fun onNewToken(token: String) {
        super.onNewToken(token)
        Log.d("LinkoraFCM", "New FCM Push Token generated: $token")

        // Persist token in shared preferences
        val prefs = getSharedPreferences("linkora_push_prefs", Context.MODE_PRIVATE)
        prefs.edit().putString("fcm_token", token).apply()

        // If user is logged in, register immediately with backend
        CoroutineScope(Dispatchers.IO).launch {
            try {
                val db = MarketLinkDatabase.getDatabase(applicationContext)
                val user = db.userProfileDao().getUserProfileOnce("current_user")
                if (user != null && user.id != "guest_user") {
                    ApiClient.apiService.registerFcmToken(
                        mapOf(
                            "userId" to user.id,
                            "fcmToken" to token,
                            "deviceType" to "android"
                        )
                    )
                }
            } catch (e: Exception) {
                Log.w("LinkoraFCM", "Failed to register new FCM token: ${e.message}")
            }
        }
    }

    override fun onMessageReceived(remoteMessage: RemoteMessage) {
        super.onMessageReceived(remoteMessage)

        val title = remoteMessage.notification?.title
            ?: remoteMessage.data["title"]
            ?: "Arifa ya Linkora"
        val body = remoteMessage.notification?.body
            ?: remoteMessage.data["body"]
            ?: "Una arifa mpya kwenye akaunti yako."

        Log.d("LinkoraFCM", "Push received: $title - $body")

        // 1. Post Android System Status Bar Notification
        NotificationHelper.showNotification(applicationContext, title, body)

        // 2. Persist directly into local Room database
        CoroutineScope(Dispatchers.IO).launch {
            try {
                val db = MarketLinkDatabase.getDatabase(applicationContext)
                db.notificationDao().insertNotification(
                    NotificationItem(
                        id = "notif_" + System.currentTimeMillis(),
                        title = title,
                        body = body,
                        channel = "Push (FCM)",
                        timestamp = "Just now",
                        isRead = false
                    )
                )
            } catch (e: Exception) {
                Log.e("LinkoraFCM", "Failed to persist push message into local db: ${e.message}")
            }
        }
    }
}
