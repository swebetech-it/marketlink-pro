package com.example.data.remote

import android.content.Context
import com.squareup.moshi.Moshi
import com.squareup.moshi.kotlin.reflect.KotlinJsonAdapterFactory
import okhttp3.OkHttpClient
import okhttp3.logging.HttpLoggingInterceptor
import retrofit2.Retrofit
import retrofit2.converter.moshi.MoshiConverterFactory
import java.util.concurrent.TimeUnit

object ApiClient {
    // Default Linkora cloud production API gateway (eliminates local IP configuration)
    var baseUrl: String = "https://api.linkora.co.tz/"

    private const val PREFS_NAME = "marketlink_network_prefs"
    private const val KEY_BASE_URL = "backend_base_url"

    private val moshi = Moshi.Builder()
        .add(KotlinJsonAdapterFactory())
        .build()

    private val loggingInterceptor = HttpLoggingInterceptor().apply {
        level = HttpLoggingInterceptor.Level.BODY
    }

    private val okHttpClient = OkHttpClient.Builder()
        .connectTimeout(15, TimeUnit.SECONDS)
        .readTimeout(15, TimeUnit.SECONDS)
        .writeTimeout(15, TimeUnit.SECONDS)
        .addInterceptor(loggingInterceptor)
        .build()

    private var retrofit: Retrofit = buildRetrofit(baseUrl)

    private fun buildRetrofit(url: String): Retrofit {
        return Retrofit.Builder()
            .baseUrl(url)
            .client(okHttpClient)
            .addConverterFactory(MoshiConverterFactory.create(moshi))
            .build()
    }

    val apiService: MarketLinkApiService
        get() = retrofit.create(MarketLinkApiService::class.java)

    fun init(context: Context) {
        val prefs = context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE)
        val saved = prefs.getString(KEY_BASE_URL, null)
        if (!saved.isNullOrBlank()) {
            val sanitized = if (saved.endsWith("/")) saved else "$saved/"
            baseUrl = sanitized
            retrofit = buildRetrofit(baseUrl)
        }
    }

    fun updateBaseUrl(newUrl: String, context: Context? = null) {
        val sanitized = if (newUrl.endsWith("/")) newUrl else "$newUrl/"
        baseUrl = sanitized
        retrofit = buildRetrofit(baseUrl)
        if (context != null) {
            val prefs = context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE)
            prefs.edit().putString(KEY_BASE_URL, sanitized).apply()
        }
    }
}
