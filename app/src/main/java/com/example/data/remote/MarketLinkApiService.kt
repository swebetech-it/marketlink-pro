package com.example.data.remote

import com.example.data.model.*
import retrofit2.Response
import retrofit2.http.*

data class ApiResponse<T>(
    val success: Boolean = true,
    val message: String? = null,
    val data: T? = null
)

data class RegisterRequest(
    val id: String? = null,
    val fullName: String,
    val phone: String,
    val email: String,
    val password: String? = null,
    val role: String,
    val accountType: String = "INDIVIDUAL",
    val organizationName: String? = null,
    val tinNumber: String? = null,
    val industrySector: String? = null,
    val primaryProfession: String? = null,
    val district: String? = null,
    val nidaNumber: String,
    val location: String,
    val bio: String,
    val skills: List<String>
)

data class NidaVerificationRequest(
    val nidaNumber: String
)

data class CreateListingRequest(
    val title: String,
    val category: String,
    val targetProfession: String? = null,
    val jobTypeLevel: String = "NON_PROFESSIONAL",
    val description: String,
    val location: String,
    val district: String? = null,
    val compensation: String,
    val compensationAmountTzs: Long,
    val requiredSkills: List<String>,
    val isEscrowRequired: Boolean,
    val agriCropType: String? = null,
    val equipmentDetails: String? = null
)

data class CreateApplicationRequest(
    val listingId: String,
    val listingTitle: String,
    val pitchMessage: String
)

data class DepositEscrowRequest(
    val listingId: String,
    val listingTitle: String,
    val payeeName: String,
    val amountTzs: Long,
    val paymentMethod: String
)

data class DisputeEscrowRequest(
    val reason: String = "Client or worker dispute raised"
)

data class SendMessageRequest(
    val listingId: String,
    val text: String
)

data class AiMatchRequest(
    val skills: List<String>,
    val location: String,
    val listingTitle: String,
    val requiredSkills: List<String>
)

data class AiMatchResponse(
    val matchScore: Int,
    val explanation: String
)

data class AiFraudCheckRequest(
    val title: String,
    val description: String,
    val compensation: String
)

data class AiFraudCheckResponse(
    val isSuspicious: Boolean,
    val riskReason: String
)

data class AiPitchRequest(
    val jobTitle: String,
    val applicantSkills: List<String>,
    val isSwahili: Boolean
)

data class AiPitchResponse(
    val pitch: String
)

data class UssdSimulateRequest(
    val sessionId: String,
    val phoneNumber: String,
    val text: String
)

data class UssdSimulateResponse(
    val responseType: String, // "CON" or "END"
    val message: String
)

data class LoginRequest(
    val login: String,
    val password: String? = null
)

interface MarketLinkApiService {

    // Auth & Users
    @POST("api/auth/login")
    suspend fun loginUser(@Body request: LoginRequest): Response<ApiResponse<UserProfile>>

    @POST("api/auth/register")
    suspend fun registerUser(@Body request: RegisterRequest): Response<ApiResponse<UserProfile>>

    @GET("api/users/profile")
    suspend fun getCurrentUserProfile(
        @Header("X-User-Id") userId: String? = null
    ): Response<ApiResponse<UserProfile>>

    @GET("api/users")
    suspend fun getAllUsers(): Response<ApiResponse<List<UserProfile>>>

    @PUT("api/users/{id}/verify-nida")
    suspend fun verifyNida(
        @Path("id") userId: String,
        @Body request: NidaVerificationRequest
    ): Response<ApiResponse<UserProfile>>

    @POST("api/users/{id}/vouch")
    suspend fun vouchUser(@Path("id") userId: String): Response<ApiResponse<UserProfile>>

    @PUT("api/users/{id}/role")
    suspend fun updateUserRole(
        @Path("id") userId: String,
        @Body roleMap: Map<String, String>
    ): Response<ApiResponse<UserProfile>>

    @DELETE("api/users/{id}")
    suspend fun deleteUser(@Path("id") userId: String): Response<ApiResponse<Boolean>>

    // Listings
    @GET("api/listings/professions")
    suspend fun getProfessions(): Response<ApiResponse<List<ProfessionItem>>>

    @GET("api/listings")
    suspend fun getListings(
        @Header("X-User-Id") userId: String? = null,
        @Query("category") category: String? = null,
        @Query("region") region: String? = null,
        @Query("profession") profession: String? = null,
        @Query("query") query: String? = null
    ): Response<ApiResponse<List<Listing>>>

    @POST("api/listings")
    suspend fun createListing(@Body request: CreateListingRequest): Response<ApiResponse<Listing>>

    @DELETE("api/listings/{id}")
    suspend fun deleteListing(@Path("id") listingId: String): Response<ApiResponse<Boolean>>

    // Applications
    @GET("api/applications")
    suspend fun getApplications(): Response<ApiResponse<List<Application>>>

    @POST("api/applications")
    suspend fun applyToListing(@Body request: CreateApplicationRequest): Response<ApiResponse<Application>>

    // Escrow & Transactions
    @GET("api/escrow/transactions")
    suspend fun getTransactions(): Response<ApiResponse<List<Transaction>>>

    @POST("api/escrow/deposit")
    suspend fun depositEscrow(@Body request: DepositEscrowRequest): Response<ApiResponse<Transaction>>

    @POST("api/escrow/{id}/release")
    suspend fun releaseEscrow(@Path("id") transactionId: String): Response<ApiResponse<Transaction>>

    @POST("api/escrow/{id}/dispute")
    suspend fun disputeEscrow(
        @Path("id") transactionId: String,
        @Body request: DisputeEscrowRequest = DisputeEscrowRequest()
    ): Response<ApiResponse<Transaction>>

    // Courses & Training Hub
    @GET("api/courses")
    suspend fun getCourses(): Response<ApiResponse<List<Course>>>

    @POST("api/courses/{id}/enroll")
    suspend fun enrollCourse(@Path("id") courseId: String): Response<ApiResponse<Course>>

    // Financial Products
    @GET("api/financial-products")
    suspend fun getFinancialProducts(): Response<ApiResponse<List<FinancialProduct>>>

    // Messaging
    @GET("api/messages/{listingId}")
    suspend fun getMessages(@Path("listingId") listingId: String): Response<ApiResponse<List<Message>>>

    @POST("api/messages")
    suspend fun sendMessage(@Body request: SendMessageRequest): Response<ApiResponse<Message>>

    // Notifications
    @GET("api/notifications")
    suspend fun getNotifications(
        @Header("X-User-Id") userId: String? = null
    ): Response<ApiResponse<List<NotificationItem>>>

    // AI Endpoints
    @POST("api/ai/match")
    suspend fun getAiMatch(@Body request: AiMatchRequest): Response<ApiResponse<AiMatchResponse>>

    @POST("api/ai/fraud-check")
    suspend fun checkAiFraud(@Body request: AiFraudCheckRequest): Response<ApiResponse<AiFraudCheckResponse>>

    @POST("api/ai/generate-pitch")
    suspend fun generateAiPitch(@Body request: AiPitchRequest): Response<ApiResponse<AiPitchResponse>>

    // USSD Gateway Simulator
    @POST("api/ussd/simulate")
    suspend fun simulateUssd(@Body request: UssdSimulateRequest): Response<ApiResponse<UssdSimulateResponse>>
}
