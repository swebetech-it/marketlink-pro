package com.example.data.model

import androidx.room.Entity
import androidx.room.PrimaryKey

enum class UserRole(val displayNameEn: String, val displayNameSw: String) {
    JOB_SEEKER("Job Seeker / Worker", "Mtafuta Kazi / Mfanyakazi"),
    FREELANCER("Freelancer / Consultant", "Mtaalamu / Mshauri"),
    EMPLOYER("Employer / Business", "Mwajiri / Biashara"),
    SERVICE_CLIENT("Service Client", "Mteja wa Huduma"),
    FARMER_COOP("Farmer / AMCOS Cooperative", "Mkulima / Chama cha AMCOS"),
    INSTITUTION("Training Institution", "Taasisi ya Mafunzo"),
    FINANCIAL_PARTNER("Financial Partner", "Mshirika wa Kifedha"),
    ADMIN("Platform Administrator", "Msimamizi wa Mfumo")
}

enum class VerificationStatus(val labelEn: String, val labelSw: String) {
    UNVERIFIED("Unverified", "Hajathibitishwa"),
    PENDING("NIDA Verification Pending", "Uhakiki wa NIDA Unasubiri"),
    VERIFIED("NIDA Verified", "Amethibitishwa na NIDA")
}

enum class OpportunityCategory(val labelEn: String, val labelSw: String) {
    EMPLOYMENT("Formal Employment", "Ajira Rasmi"),
    SKILLED_SERVICE("Skilled Service", "Huduma ya Utaalamu"),
    CASUAL_GIG("Casual Labor / Gig", "Kazi ya Siku / Kibarua"),
    BUSINESS_OPP("Business Opportunity", "Fursa ya Biashara"),
    AGRI_LABOR("Agri Seasonal Labor", "Kibarua cha Kilimo"),
    EQUIPMENT_RENTAL("Farm Equipment Rental", "Kukodi Zana za Kilimo")
}

enum class EscrowStatus(val labelEn: String, val labelSw: String) {
    NONE("Direct Payment", "Malipo ya Moja kwa Moja"),
    AWAITING_DEPOSIT("Awaiting Deposit", "Inasubiri Amana"),
    HELD_IN_ESCROW("Funds Held in Escrow", "Pesa Zimeshikiliwa Escrow"),
    RELEASED("Funds Released", "Pesa Zimetolewa"),
    DISPUTED("Under Dispute Review", "Inapitiwa kwa Mgogoro"),
    REFUNDED("Refunded to Client", "Imerudishwa kwa Mteja")
}

@Entity(tableName = "user_profiles")
data class UserProfile(
    @PrimaryKey val id: String,
    val fullName: String,
    val phone: String,
    val email: String,
    val role: UserRole,
    val accountType: String = "INDIVIDUAL", // "INDIVIDUAL", "ORGANIZATION"
    val organizationName: String = "",
    val tinNumber: String = "",
    val industrySector: String = "",
    val primaryProfession: String = "", // e.g. "CLEANING_SANITATION", "SECURITY", "ELECTRICAL"
    val district: String = "",
    val verificationStatus: VerificationStatus,
    val nidaNumber: String = "",
    val location: String, // e.g. "Mbeya City", "Dar es Salaam", "Kilombero, Morogoro"
    val bio: String,
    val skills: List<String>, // e.g. ["Mobile App Dev", "Tractor Driving", "Accounting", "Masonry"]
    val rating: Float = 0.0f,
    val totalTransactions: Int = 0,
    val completedGigs: Int = 0,
    val reputationScore: Int = 0, // out of 1000
    val communityVouches: Int = 0,
    val badges: List<String> = emptyList(),
    val tenantId: String? = null,
    val tenantDatabase: String? = null,
    val isolationLevel: String? = "SHARED"
)

@Entity(tableName = "listings")
data class Listing(
    @PrimaryKey val id: String,
    val posterId: String,
    val posterName: String,
    val posterVerification: VerificationStatus,
    val posterRating: Float = 0.0f,
    val category: OpportunityCategory,
    val targetProfession: String? = null,
    val jobTypeLevel: String = "NON_PROFESSIONAL",
    val title: String,
    val description: String,
    val location: String,
    val district: String? = null,
    val compensation: String,
    val compensationAmountTzs: Long = 0L,
    val requiredSkills: List<String> = emptyList(),
    val datePosted: String,
    val deadline: String,
    val isFeatured: Boolean = false,
    val isEscrowRequired: Boolean = false,
    val matchScore: Int = 0,
    val matchedCandidatesCount: Int = 0,
    val isTopMatch: Boolean = false,
    val agriCropType: String? = null,
    val equipmentDetails: String? = null,
    val isFlaggedSuspicious: Boolean = false,
    val fraudRiskScore: Int = 0
)

data class ProfessionItem(
    val code: String,
    val labelSw: String,
    val labelEn: String,
    val isProfessional: Boolean
)

@Entity(tableName = "applications")
data class Application(
    @PrimaryKey val id: String,
    val listingId: String,
    val listingTitle: String,
    val applicantId: String,
    val applicantName: String,
    val pitchMessage: String,
    val status: String, // "Applied", "Shortlisted", "Accepted", "Completed", "Rejected"
    val appliedDate: String
)

@Entity(tableName = "transactions")
data class Transaction(
    @PrimaryKey val id: String,
    val listingId: String,
    val listingTitle: String,
    val payerName: String,
    val payeeName: String,
    val amountTzs: Long,
    val feeTzs: Long,
    val escrowStatus: EscrowStatus,
    val paymentMethod: String, // "M-Pesa", "Tigo Pesa", "Airtel Money", "CRDB Bank", "NMB Bank"
    val timestamp: String,
    val auditCode: String
)

@Entity(tableName = "ratings")
data class Rating(
    @PrimaryKey val id: String,
    val transactionId: String,
    val raterName: String,
    val rateeName: String,
    val score: Int, // 1 to 5
    val comment: String,
    val date: String
)

@Entity(tableName = "courses")
data class Course(
    @PrimaryKey val id: String,
    val title: String,
    val providerName: String,
    val category: String,
    val duration: String,
    val priceTzs: Long,
    val rating: Float,
    val isEnrolled: Boolean = false,
    val progressPercent: Int = 0,
    val certificateIssued: Boolean = false,
    val skillBadgeName: String
)

@Entity(tableName = "financial_products")
data class FinancialProduct(
    @PrimaryKey val id: String,
    val providerName: String,
    val productName: String,
    val category: String, // "Agri-Input Microloan", "Equipment Lease", "Working Capital"
    val maxAmountTzs: Long,
    val interestRate: String,
    val minReputationScore: Int,
    val description: String
)

@Entity(tableName = "messages")
data class Message(
    @PrimaryKey val id: String,
    val listingId: String,
    val senderName: String,
    val receiverName: String,
    val text: String,
    val timestamp: String,
    val isFromMe: Boolean
)

@Entity(tableName = "notifications")
data class NotificationItem(
    @PrimaryKey val id: String,
    val title: String,
    val body: String,
    val channel: String, // "In-App", "SMS (Africa's Talking)", "WhatsApp", "USSD Alert"
    val timestamp: String,
    val isRead: Boolean = false
)
