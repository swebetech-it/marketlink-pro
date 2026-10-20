package com.example.data.local

import androidx.room.TypeConverter
import com.example.data.model.EscrowStatus
import com.example.data.model.OpportunityCategory
import com.example.data.model.UserRole
import com.example.data.model.VerificationStatus

class Converters {
    @TypeConverter
    fun fromListString(list: List<String>?): String {
        return list?.joinToString("|||") ?: ""
    }

    @TypeConverter
    fun toListString(data: String?): List<String> {
        if (data.isNullOrEmpty()) return emptyList()
        return data.split("|||").filter { it.isNotBlank() }
    }

    @TypeConverter
    fun fromUserRole(role: UserRole?): String = role?.name ?: UserRole.JOB_SEEKER.name

    @TypeConverter
    fun toUserRole(value: String?): UserRole = try {
        UserRole.valueOf(value ?: UserRole.JOB_SEEKER.name)
    } catch (e: Exception) {
        UserRole.JOB_SEEKER
    }

    @TypeConverter
    fun fromVerificationStatus(status: VerificationStatus?): String = status?.name ?: VerificationStatus.UNVERIFIED.name

    @TypeConverter
    fun toVerificationStatus(value: String?): VerificationStatus = try {
        VerificationStatus.valueOf(value ?: VerificationStatus.UNVERIFIED.name)
    } catch (e: Exception) {
        VerificationStatus.UNVERIFIED
    }

    @TypeConverter
    fun fromOpportunityCategory(cat: OpportunityCategory?): String = cat?.name ?: OpportunityCategory.EMPLOYMENT.name

    @TypeConverter
    fun toOpportunityCategory(value: String?): OpportunityCategory = try {
        OpportunityCategory.valueOf(value ?: OpportunityCategory.EMPLOYMENT.name)
    } catch (e: Exception) {
        OpportunityCategory.EMPLOYMENT
    }

    @TypeConverter
    fun fromEscrowStatus(status: EscrowStatus?): String = status?.name ?: EscrowStatus.NONE.name

    @TypeConverter
    fun toEscrowStatus(value: String?): EscrowStatus = try {
        EscrowStatus.valueOf(value ?: EscrowStatus.NONE.name)
    } catch (e: Exception) {
        EscrowStatus.NONE
    }
}
