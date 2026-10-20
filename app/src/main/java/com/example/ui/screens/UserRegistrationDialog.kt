package com.example.ui.screens

import androidx.compose.foundation.background
import androidx.compose.foundation.border
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.foundation.verticalScroll
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.*
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.platform.testTag
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import com.example.data.model.UserRole
import com.example.data.model.VerificationStatus
import com.example.ui.components.BrandBlue
import com.example.ui.components.BrandDarkBg
import com.example.ui.components.BrandGreen
import com.example.ui.components.BrandOrange
import com.example.ui.components.MarketLinkLogoMark
import com.example.ui.components.MarketLinkLogoText
import com.example.ui.theme.*

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun UserRegistrationDialog(
    isSwahili: Boolean,
    onDismiss: () -> Unit,
    onRegisterSubmit: (
        fullName: String,
        phone: String,
        email: String,
        role: UserRole,
        nidaNumber: String,
        location: String,
        bio: String,
        skills: List<String>,
        accountType: String,
        organizationName: String,
        tinNumber: String,
        industrySector: String,
        primaryProfession: String,
        district: String,
        password: String?,
        onResult: (Boolean, String?) -> Unit
    ) -> Unit
) {
    var accountType by remember { mutableStateOf("INDIVIDUAL") } // "INDIVIDUAL" or "ORGANIZATION"
    var fullName by remember { mutableStateOf("") }
    var orgName by remember { mutableStateOf("") }
    var tinNumber by remember { mutableStateOf("") }
    var industrySector by remember { mutableStateOf("Agribusiness & Commercial") }
    var primaryProfession by remember { mutableStateOf("CLEANING_SANITATION") }
    var district by remember { mutableStateOf("Mbeya Mjini") }
    var phone by remember { mutableStateOf("+255 ") }
    var email by remember { mutableStateOf("") }
    var selectedRole by remember { mutableStateOf(UserRole.JOB_SEEKER) }
    var roleDropdownExpanded by remember { mutableStateOf(false) }
    var professionDropdownExpanded by remember { mutableStateOf(false) }
    var districtDropdownExpanded by remember { mutableStateOf(false) }
    var nidaNumber by remember { mutableStateOf("") }
    var location by remember { mutableStateOf("Mbeya City, Tanzania") }
    var bio by remember { mutableStateOf("") }
    var skillsInput by remember { mutableStateOf("") }
    var passwordInput by remember { mutableStateOf("") }

    var errorMessage by remember { mutableStateOf<String?>(null) }

    val tanzaniaProfessionsList = listOf(
        "CLEANING_SANITATION" to ("Usafi na Mazingira (Cleaning)" to "Cleaning & Sanitation"),
        "SECURITY" to ("Ulinzi na Usalama (Security)" to "Security Guard & Protection"),
        "DOMESTIC_WORK" to ("Kazi za Ndani (Housekeeping)" to "Domestic Help & Housekeeping"),
        "CONSTRUCTION_MASONRY" to ("Ujenzi na Uashi (Masonry)" to "Construction & Masonry"),
        "ELECTRICAL" to ("Fundi Umeme (Electrical)" to "Electrical Services"),
        "PLUMBING" to ("Fundi Bomba (Plumbing)" to "Plumbing Services"),
        "TRANSPORT_DRIVING" to ("Ud Dereva na Usafirishaji" to "Driving & Logistics"),
        "AGRICULTURE" to ("Kilimo na Mifugo (Farming)" to "Agriculture & Farming"),
        "HOSPITALITY_COOKING" to ("Upishi na Hoteli (Cooking)" to "Cooking & Hospitality"),
        "ACCOUNTING_FINANCE" to ("Uhasibu na Fedha (CPA)" to "Accounting & Finance"),
        "IT_SOFTWARE" to ("TEHAMA na Programu (IT)" to "IT & Software"),
        "HEALTHCARE" to ("Afya na Uuguzi (Healthcare)" to "Healthcare & Nursing"),
        "EDUCATION_TEACHING" to ("Ualimu na Masomo" to "Teaching & Tutoring"),
        "MECHANICS" to ("Fundi Gereji na Mitambo" to "Mechanics & Garage"),
        "GENERAL_LABOR" to ("Vibarua na Kazi za Jumla" to "General Labor")
    )

    val tanzaniaDistrictsList = listOf(
        "Mbeya Mjini",
        "Rungwe (Mbeya)",
        "Kyela (Mbeya)",
        "Mbarali (Mbeya)",
        "Kinondoni (Dar es Salaam)",
        "Ilala (Dar es Salaam)",
        "Temeke (Dar es Salaam)",
        "Ubungo (Dar es Salaam)",
        "Kigamboni (Dar es Salaam)",
        "Arusha Mjini",
        "Dodoma Mjini",
        "Mwanza Mjini",
        "Iringa Mjini",
        "Morogoro Mjini",
        "Moshi (Kilimanjaro)"
    )

    AlertDialog(
        onDismissRequest = onDismiss,
        modifier = Modifier
            .fillMaxWidth()
            .testTag("user_registration_dialog")
    ) {
        Surface(
            shape = RoundedCornerShape(16.dp),
            color = SlateSurface,
            tonalElevation = 6.dp,
            modifier = Modifier
                .fillMaxWidth()
                .wrapContentHeight()
        ) {
            Column(
                modifier = Modifier
                    .padding(20.dp)
                    .verticalScroll(rememberScrollState())
            ) {
                // Dialog Header with Branding
                Row(
                    modifier = Modifier.fillMaxWidth(),
                    horizontalArrangement = Arrangement.SpaceBetween,
                    verticalAlignment = Alignment.CenterVertically
                ) {
                    Row(verticalAlignment = Alignment.CenterVertically) {
                        MarketLinkLogoMark(size = 36.dp)
                        Spacer(modifier = Modifier.width(8.dp))
                        Column {
                            MarketLinkLogoText(fontSize = 16f, showTagline = false)
                            Text(
                                text = if (isSwahili) "Usajili wa Mtumiaji Mpya" else "New User Registration",
                                fontSize = 11.sp,
                                fontWeight = FontWeight.Bold,
                                color = BrandBlue
                            )
                        }
                    }

                    IconButton(onClick = onDismiss) {
                        Icon(Icons.Default.Close, contentDescription = "Close", tint = SlateTextSecondary)
                    }
                }

                Divider(modifier = Modifier.padding(vertical = 12.dp))

                if (errorMessage != null) {
                    Surface(
                        color = RedWarning.copy(alpha = 0.1f),
                        border = androidx.compose.foundation.BorderStroke(1.dp, RedWarning),
                        shape = RoundedCornerShape(8.dp),
                        modifier = Modifier
                            .fillMaxWidth()
                            .padding(bottom = 10.dp)
                    ) {
                        Text(
                            text = errorMessage!!,
                            color = RedWarning,
                            fontSize = 11.sp,
                            fontWeight = FontWeight.Bold,
                            modifier = Modifier.padding(8.dp)
                        )
                    }
                }

                // Account Type Selector: Individual Worker vs Company/Organization
                Text(
                    text = if (isSwahili) "Aina ya Usajili *" else "Registration Category *",
                    fontSize = 12.sp,
                    fontWeight = FontWeight.Bold,
                    color = SlateTextPrimary
                )
                Spacer(modifier = Modifier.height(4.dp))

                Row(
                    modifier = Modifier
                        .fillMaxWidth()
                        .background(Color(0xFF1E293B), RoundedCornerShape(10.dp))
                        .padding(4.dp),
                    horizontalArrangement = Arrangement.spacedBy(4.dp)
                ) {
                    Surface(
                        onClick = {
                            accountType = "INDIVIDUAL"
                            selectedRole = UserRole.JOB_SEEKER
                        },
                        color = if (accountType == "INDIVIDUAL") BrandBlue else Color.Transparent,
                        shape = RoundedCornerShape(8.dp),
                        modifier = Modifier.weight(1f)
                    ) {
                        Row(
                            modifier = Modifier.padding(vertical = 10.dp),
                            horizontalArrangement = Arrangement.Center,
                            verticalAlignment = Alignment.CenterVertically
                        ) {
                            Icon(
                                Icons.Default.Person,
                                contentDescription = null,
                                tint = if (accountType == "INDIVIDUAL") Color.White else Color.Gray,
                                modifier = Modifier.size(16.dp)
                            )
                            Spacer(modifier = Modifier.width(6.dp))
                            Text(
                                text = if (isSwahili) "Mtu Binafsi / Mfanyakazi" else "Individual Worker",
                                fontSize = 11.sp,
                                fontWeight = FontWeight.Bold,
                                color = if (accountType == "INDIVIDUAL") Color.White else Color.Gray
                            )
                        }
                    }

                    Surface(
                        onClick = {
                            accountType = "ORGANIZATION"
                            selectedRole = UserRole.EMPLOYER
                        },
                        color = if (accountType == "ORGANIZATION") BrandBlue else Color.Transparent,
                        shape = RoundedCornerShape(8.dp),
                        modifier = Modifier.weight(1f)
                    ) {
                        Row(
                            modifier = Modifier.padding(vertical = 10.dp),
                            horizontalArrangement = Arrangement.Center,
                            verticalAlignment = Alignment.CenterVertically
                        ) {
                            Icon(
                                Icons.Default.Business,
                                contentDescription = null,
                                tint = if (accountType == "ORGANIZATION") Color.White else Color.Gray,
                                modifier = Modifier.size(16.dp)
                            )
                            Spacer(modifier = Modifier.width(6.dp))
                            Text(
                                text = if (isSwahili) "Kampuni / Shirika" else "Company / Org",
                                fontSize = 11.sp,
                                fontWeight = FontWeight.Bold,
                                color = if (accountType == "ORGANIZATION") Color.White else Color.Gray
                            )
                        }
                    }
                }

                Spacer(modifier = Modifier.height(12.dp))

                if (accountType == "ORGANIZATION") {
                    // Organization Name
                    OutlinedTextField(
                        value = orgName,
                        onValueChange = { orgName = it },
                        label = { Text(if (isSwahili) "Jina la Kampuni / Shirika *" else "Company / Organization Name *") },
                        placeholder = { Text("Mbeya Commercial Complex Ltd") },
                        leadingIcon = { Icon(Icons.Default.Business, contentDescription = null, tint = BrandBlue) },
                        singleLine = true,
                        colors = formTextFieldColors(),
                        modifier = Modifier.fillMaxWidth()
                    )

                    Spacer(modifier = Modifier.height(10.dp))

                    // TIN & Sector
                    Row(horizontalArrangement = Arrangement.spacedBy(8.dp)) {
                        OutlinedTextField(
                            value = tinNumber,
                            onValueChange = { tinNumber = it },
                            label = { Text(if (isSwahili) "Namba ya TIN *" else "TIN Number *") },
                            placeholder = { Text("142-990-128") },
                            leadingIcon = { Icon(Icons.Default.Receipt, contentDescription = null, tint = BrandGreen) },
                            singleLine = true,
                            colors = formTextFieldColors(),
                            modifier = Modifier.weight(1f)
                        )

                        OutlinedTextField(
                            value = industrySector,
                            onValueChange = { industrySector = it },
                            label = { Text(if (isSwahili) "Sekta ya Kazi" else "Industry Sector") },
                            leadingIcon = { Icon(Icons.Default.Domain, contentDescription = null, tint = BrandBlue) },
                            singleLine = true,
                            colors = formTextFieldColors(),
                            modifier = Modifier.weight(1f)
                        )
                    }

                    Spacer(modifier = Modifier.height(10.dp))

                    // Representative
                    OutlinedTextField(
                        value = fullName,
                        onValueChange = { fullName = it },
                        label = { Text(if (isSwahili) "Afisa Mwakilishi / Contact Person *" else "Representative Full Name *") },
                        leadingIcon = { Icon(Icons.Default.Person, contentDescription = null, tint = BrandBlue) },
                        singleLine = true,
                        colors = formTextFieldColors(),
                        modifier = Modifier.fillMaxWidth()
                    )
                } else {
                    // Worker Full Name
                    OutlinedTextField(
                        value = fullName,
                        onValueChange = { fullName = it },
                        label = { Text(if (isSwahili) "Majina Kamili *" else "Full Name *") },
                        leadingIcon = { Icon(Icons.Default.Person, contentDescription = null, tint = BrandBlue) },
                        singleLine = true,
                        colors = formTextFieldColors(),
                        modifier = Modifier
                            .fillMaxWidth()
                            .testTag("register_fullname_input")
                    )

                    Spacer(modifier = Modifier.height(10.dp))

                    // Primary Profession Dropdown (for AI Matchmaking)
                    Text(
                        text = if (isSwahili) "Sifa Kuu ya Kazi (AI Matchmaking) *" else "Primary Profession (For AI Targeting) *",
                        fontSize = 12.sp,
                        fontWeight = FontWeight.Bold,
                        color = SlateTextPrimary
                    )
                    Spacer(modifier = Modifier.height(4.dp))

                    val currentProfPair = tanzaniaProfessionsList.firstOrNull { it.first == primaryProfession }
                    val currentProfLabel = if (isSwahili) currentProfPair?.second?.first ?: primaryProfession else currentProfPair?.second?.second ?: primaryProfession

                    Box(modifier = Modifier.fillMaxWidth()) {
                        OutlinedCard(
                            onClick = { professionDropdownExpanded = true },
                            modifier = Modifier.fillMaxWidth()
                        ) {
                            Row(
                                modifier = Modifier
                                    .fillMaxWidth()
                                    .padding(12.dp),
                                horizontalArrangement = Arrangement.SpaceBetween,
                                verticalAlignment = Alignment.CenterVertically
                            ) {
                                Row(verticalAlignment = Alignment.CenterVertically) {
                                    Icon(Icons.Default.WorkOutline, contentDescription = null, tint = BrandGreen)
                                    Spacer(modifier = Modifier.width(8.dp))
                                    Text(
                                        text = currentProfLabel,
                                        fontWeight = FontWeight.Bold,
                                        fontSize = 13.sp,
                                        color = SlateTextPrimary
                                    )
                                }
                                Icon(Icons.Default.ArrowDropDown, contentDescription = null)
                            }
                        }

                        DropdownMenu(
                            expanded = professionDropdownExpanded,
                            onDismissRequest = { professionDropdownExpanded = false }
                        ) {
                            tanzaniaProfessionsList.forEach { (key, names) ->
                                DropdownMenuItem(
                                    text = {
                                        Text(
                                            text = if (isSwahili) names.first else names.second,
                                            fontSize = 13.sp,
                                            fontWeight = if (key == primaryProfession) FontWeight.Bold else FontWeight.Normal
                                        )
                                    },
                                    onClick = {
                                        primaryProfession = key
                                        professionDropdownExpanded = false
                                    }
                                )
                            }
                        }
                    }

                    Spacer(modifier = Modifier.height(10.dp))

                    // NIDA ID Number
                    OutlinedTextField(
                        value = nidaNumber,
                        onValueChange = { nidaNumber = it },
                        label = { Text(if (isSwahili) "Namba ya NIDA (Kitambulisho) *" else "NIDA ID Number *") },
                        placeholder = { Text("19940812-21104-00001-26") },
                        leadingIcon = { Icon(Icons.Default.VerifiedUser, contentDescription = null, tint = BrandGreen) },
                        trailingIcon = {
                            if (nidaNumber.length >= 10) {
                                Surface(
                                    color = BrandGreen.copy(alpha = 0.15f),
                                    shape = RoundedCornerShape(4.dp)
                                ) {
                                    Text(
                                        text = "NIDA OK",
                                        fontSize = 9.sp,
                                        fontWeight = FontWeight.ExtraBold,
                                        color = BrandGreen,
                                        modifier = Modifier.padding(horizontal = 4.dp, vertical = 2.dp)
                                    )
                                }
                            }
                        },
                        singleLine = true,
                        colors = formTextFieldColors(),
                        modifier = Modifier
                            .fillMaxWidth()
                            .testTag("register_nida_input")
                    )
                }

                Spacer(modifier = Modifier.height(10.dp))

                // Phone & Email Row
                Row(horizontalArrangement = Arrangement.spacedBy(8.dp)) {
                    OutlinedTextField(
                        value = phone,
                        onValueChange = { phone = it },
                        label = { Text(if (isSwahili) "Simu (+255) *" else "Phone (+255) *") },
                        leadingIcon = { Icon(Icons.Default.Phone, contentDescription = null, tint = BrandBlue) },
                        singleLine = true,
                        colors = formTextFieldColors(),
                        modifier = Modifier
                            .weight(1f)
                            .testTag("register_phone_input")
                    )

                    OutlinedTextField(
                        value = email,
                        onValueChange = { email = it },
                        label = { Text(if (isSwahili) "Barua Pepe" else "Email") },
                        leadingIcon = { Icon(Icons.Default.Email, contentDescription = null, tint = BrandBlue) },
                        singleLine = true,
                        colors = formTextFieldColors(),
                        modifier = Modifier
                            .weight(1f)
                            .testTag("register_email_input")
                    )
                }

                Spacer(modifier = Modifier.height(10.dp))

                // District / Wilaya (For AI Targeting)
                Text(
                    text = if (isSwahili) "Wilaya / Eneo la Kazi (AI Target) *" else "District / Area of Work *",
                    fontSize = 12.sp,
                    fontWeight = FontWeight.Bold,
                    color = SlateTextPrimary
                )
                Spacer(modifier = Modifier.height(4.dp))

                Box(modifier = Modifier.fillMaxWidth()) {
                    OutlinedCard(
                        onClick = { districtDropdownExpanded = true },
                        modifier = Modifier.fillMaxWidth()
                    ) {
                        Row(
                            modifier = Modifier
                                .fillMaxWidth()
                                .padding(12.dp),
                            horizontalArrangement = Arrangement.SpaceBetween,
                            verticalAlignment = Alignment.CenterVertically
                        ) {
                            Row(verticalAlignment = Alignment.CenterVertically) {
                                Icon(Icons.Default.LocationOn, contentDescription = null, tint = BrandOrange)
                                Spacer(modifier = Modifier.width(8.dp))
                                Text(text = district, fontSize = 13.sp, fontWeight = FontWeight.SemiBold)
                            }
                            Icon(Icons.Default.ArrowDropDown, contentDescription = null)
                        }
                    }

                    DropdownMenu(
                        expanded = districtDropdownExpanded,
                        onDismissRequest = { districtDropdownExpanded = false }
                    ) {
                        tanzaniaDistrictsList.forEach { dist ->
                            DropdownMenuItem(
                                text = { Text(dist, fontSize = 13.sp) },
                                onClick = {
                                    district = dist
                                    location = "$dist, Tanzania"
                                    districtDropdownExpanded = false
                                }
                            )
                        }
                    }
                }

                Spacer(modifier = Modifier.height(10.dp))

                // Skills & Specialization
                OutlinedTextField(
                    value = skillsInput,
                    onValueChange = { skillsInput = it },
                    label = { Text(if (isSwahili) "Ujuzi na Utaalamu (koma)" else "Skills & Specializations (comma separated)") },
                    placeholder = { Text("Usafi wa Maofisi, Ulinzi wa Usiku, Ujenzi") },
                    leadingIcon = { Icon(Icons.Default.Build, contentDescription = null, tint = BrandBlue) },
                    singleLine = true,
                    colors = formTextFieldColors(),
                    modifier = Modifier.fillMaxWidth()
                )

                Spacer(modifier = Modifier.height(10.dp))

                // Password
                OutlinedTextField(
                    value = passwordInput,
                    onValueChange = { passwordInput = it },
                    label = { Text(if (isSwahili) "Weka Neno la Siri *" else "Password / PIN *") },
                    leadingIcon = { Icon(Icons.Default.Lock, contentDescription = null, tint = BrandBlue) },
                    visualTransformation = androidx.compose.ui.text.input.PasswordVisualTransformation(),
                    singleLine = true,
                    colors = formTextFieldColors(),
                    modifier = Modifier.fillMaxWidth()
                )

                Spacer(modifier = Modifier.height(10.dp))

                // Bio / Description
                OutlinedTextField(
                    value = bio,
                    onValueChange = { bio = it },
                    label = { Text(if (isSwahili) "Maelezo ya Wasifu" else "Profile Summary") },
                    minLines = 2,
                    maxLines = 3,
                    colors = formTextFieldColors(),
                    modifier = Modifier.fillMaxWidth()
                )

                Spacer(modifier = Modifier.height(16.dp))

                var isSubmitting by remember { mutableStateOf(false) }

                // Submit Action
                Button(
                    onClick = {
                        val finalName = if (accountType == "ORGANIZATION") orgName.ifBlank { fullName } else fullName
                        if (finalName.isBlank() || phone.isBlank()) {
                            errorMessage = if (isSwahili) "Tafadhali jaza Majina Kamili / Kampuni na Namba ya Simu." else "Please enter Name and Phone Number."
                            return@Button
                        }
                        val parsedSkills = skillsInput.split(",").map { it.trim() }.filter { it.isNotEmpty() }
                        isSubmitting = true
                        errorMessage = null
                        onRegisterSubmit(
                            finalName,
                            phone,
                            email,
                            if (accountType == "ORGANIZATION") UserRole.EMPLOYER else selectedRole,
                            nidaNumber,
                            location,
                            bio.ifBlank {
                                if (accountType == "ORGANIZATION") "Registered organization employer on MarketLink Pro."
                                else "Registered worker in $district specializing in $primaryProfession."
                            },
                            parsedSkills.ifEmpty { listOf(primaryProfession, "Services") },
                            accountType,
                            orgName,
                            tinNumber,
                            industrySector,
                            primaryProfession,
                            district,
                            passwordInput.trim().ifEmpty { null }
                        ) { success, err ->
                            isSubmitting = false
                            if (success) {
                                onDismiss()
                            } else {
                                errorMessage = err ?: (if (isSwahili) "Imeshindwa kusajili kwenye seva." else "Failed to register on backend server.")
                            }
                        }
                    },
                    enabled = !isSubmitting,
                    colors = ButtonDefaults.buttonColors(containerColor = BrandBlue, contentColor = Color.White),
                    shape = RoundedCornerShape(10.dp),
                    modifier = Modifier
                        .fillMaxWidth()
                        .height(48.dp)
                        .testTag("submit_user_registration_button")
                ) {
                    if (isSubmitting) {
                        CircularProgressIndicator(color = Color.White, modifier = Modifier.size(20.dp), strokeWidth = 2.dp)
                        Spacer(modifier = Modifier.width(8.dp))
                        Text(if (isSwahili) "Inasajili..." else "Registering...", fontWeight = FontWeight.Bold)
                    } else {
                        Icon(Icons.Default.Check, contentDescription = null)
                        Spacer(modifier = Modifier.width(8.dp))
                        Text(
                            text = if (isSwahili) "Sajili Akaunti Mpya Sasa" else "Register & Switch to Profile",
                            fontWeight = FontWeight.Bold,
                            fontSize = 14.sp
                        )
                    }
                }
            }
        }
    }
}
