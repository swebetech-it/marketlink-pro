package com.example.ui.screens

import androidx.compose.foundation.background
import androidx.compose.foundation.border
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.foundation.verticalScroll
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.*
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Brush
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.platform.testTag
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.input.PasswordVisualTransformation
import androidx.compose.ui.text.input.VisualTransformation
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import com.example.data.model.UserProfile
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
fun AuthScreen(
    isSwahili: Boolean,
    allUsers: List<UserProfile>,
    onLanguageToggle: (Boolean) -> Unit,
    onSignInSuccess: (UserProfile) -> Unit,
    onSignInWithCredentials: ((String, String?, (Boolean, String?) -> Unit) -> Unit)? = null,
    onUpdateBaseUrl: ((String, (Boolean) -> Unit) -> Unit)? = null,
    onTriggerSync: (((Boolean) -> Unit) -> Unit)? = null,
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
    var selectedAuthTab by remember { mutableStateOf(0) } // 0 = Sign In, 1 = Sign Up

    // Sign In State
    var signInPhoneOrEmail by remember { mutableStateOf("") }
    var signInPassword by remember { mutableStateOf("") }
    var passwordVisible by remember { mutableStateOf(false) }
    var signInErrorMessage by remember { mutableStateOf<String?>(null) }
    var isSigningIn by remember { mutableStateOf(false) }
    var isSyncingServer by remember { mutableStateOf(false) }
    var syncFeedbackMessage by remember { mutableStateOf<String?>(null) }
    var showServerConfigDialog by remember { mutableStateOf(false) }
    var currentServerUrl by remember { mutableStateOf(com.example.data.remote.ApiClient.baseUrl) }
    var serverUrlInput by remember { mutableStateOf(com.example.data.remote.ApiClient.baseUrl) }

    // Sign Up State
    var signUpAccountType by remember { mutableStateOf("INDIVIDUAL") } // "INDIVIDUAL" or "ORGANIZATION"
    var signUpFullName by remember { mutableStateOf("") }
    var signUpOrgName by remember { mutableStateOf("") }
    var signUpTinNumber by remember { mutableStateOf("") }
    var signUpIndustrySector by remember { mutableStateOf("Agribusiness & Commercial") }
    var signUpPrimaryProfession by remember { mutableStateOf("IT_SOFTWARE") }
    var signUpDistrict by remember { mutableStateOf("Mbeya Mjini") }
    var signUpPhone by remember { mutableStateOf("+255 ") }
    var signUpEmail by remember { mutableStateOf("") }
    var signUpRole by remember { mutableStateOf(UserRole.JOB_SEEKER) }
    var roleDropdownExpanded by remember { mutableStateOf(false) }
    var professionDropdownExpanded by remember { mutableStateOf(false) }
    var districtDropdownExpanded by remember { mutableStateOf(false) }
    var signUpNida by remember { mutableStateOf("") }
    var signUpLocation by remember { mutableStateOf("Mbeya City, Tanzania") }
    var regionDropdownExpanded by remember { mutableStateOf(false) }
    var signUpBio by remember { mutableStateOf("") }
    var signUpSkills by remember { mutableStateOf("") }
    var signUpPassword by remember { mutableStateOf("") }
    var signUpErrorMessage by remember { mutableStateOf<String?>(null) }

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

    val tanzaniaRegions = listOf(
        "Mbeya City, Tanzania",
        "Kilombero, Morogoro",
        "Dar es Salaam (Kinondoni)",
        "Arusha City, Tanzania",
        "Dodoma Capital, Tanzania",
        "Mwanza City, Tanzania",
        "Songwe Region, Tanzania",
        "Iringa Municipality",
        "Tanga City, Tanzania",
        "Kilimanjaro (Moshi)"
    )

    Surface(
        color = SlateBackground,
        modifier = Modifier
            .fillMaxSize()
            .testTag("auth_first_page")
    ) {
        Column(
            modifier = Modifier
                .fillMaxSize()
                .verticalScroll(rememberScrollState())
        ) {
            // Top Hero Header Banner
            Box(
                modifier = Modifier
                    .fillMaxWidth()
                    .background(
                        brush = Brush.verticalGradient(
                            colors = listOf(
                                BrandDarkBg,
                                Color(0xFF0F172A),
                                SlateBackground
                            )
                        )
                    )
                    .padding(horizontal = 20.dp, vertical = 24.dp)
            ) {
                Column(modifier = Modifier.fillMaxWidth()) {
                    // Language Switcher Row
                    Row(
                        modifier = Modifier.fillMaxWidth(),
                        horizontalArrangement = Arrangement.SpaceBetween,
                        verticalAlignment = Alignment.CenterVertically
                    ) {
                        Surface(
                            color = BrandBlue.copy(alpha = 0.2f),
                            shape = RoundedCornerShape(20.dp),
                            border = androidx.compose.foundation.BorderStroke(1.dp, BrandBlue.copy(alpha = 0.4f))
                        ) {
                            Row(
                                modifier = Modifier.padding(horizontal = 10.dp, vertical = 4.dp),
                                verticalAlignment = Alignment.CenterVertically
                            ) {
                                Icon(Icons.Default.VerifiedUser, contentDescription = null, tint = BrandGreen, modifier = Modifier.size(14.dp))
                                Spacer(modifier = Modifier.width(4.dp))
                                Text(
                                    text = "NIDA & Escrow Secured",
                                    color = Color.White,
                                    fontSize = 11.sp,
                                    fontWeight = FontWeight.Bold
                                )
                            }
                        }

                        // Language Toggle
                        Row(
                            verticalAlignment = Alignment.CenterVertically,
                            modifier = Modifier
                                .clickable { onLanguageToggle(!isSwahili) }
                                .padding(4.dp)
                        ) {
                            Icon(
                                Icons.Default.Language,
                                contentDescription = "Language",
                                tint = GoldAccent,
                                modifier = Modifier.size(18.dp)
                            )
                            Spacer(modifier = Modifier.width(4.dp))
                            Text(
                                text = if (isSwahili) "SW (Swahili)" else "EN (English)",
                                color = GoldAccent,
                                fontSize = 12.sp,
                                fontWeight = FontWeight.ExtraBold
                            )
                        }
                    }

                    Spacer(modifier = Modifier.height(20.dp))

                    // Brand Title
                    Row(
                        verticalAlignment = Alignment.CenterVertically,
                        horizontalArrangement = Arrangement.Center,
                        modifier = Modifier.fillMaxWidth()
                    ) {
                        MarketLinkLogoMark(size = 48.dp)
                        Spacer(modifier = Modifier.width(12.dp))
                        Column {
                            MarketLinkLogoText(fontSize = 22f, showTagline = true)
                            Text(
                                text = if (isSwahili) "Mfumo wa Fursa za Kazi, Ujuzi na Escrow" else "National Opportunity & Labor Market Hub",
                                color = SlateTextSecondary,
                                fontSize = 11.sp
                            )
                        }
                    }

                    Spacer(modifier = Modifier.height(24.dp))

                    // Segmented Tab Control: Sign In vs Sign Up
                    Surface(
                        color = Color(0xFF1E293B),
                        shape = RoundedCornerShape(12.dp),
                        modifier = Modifier.fillMaxWidth()
                    ) {
                        Row(
                            modifier = Modifier
                                .fillMaxWidth()
                                .padding(4.dp)
                        ) {
                            Surface(
                                onClick = { selectedAuthTab = 0 },
                                shape = RoundedCornerShape(8.dp),
                                color = if (selectedAuthTab == 0) BrandBlue else Color.Transparent,
                                modifier = Modifier
                                    .weight(1f)
                                    .testTag("auth_tab_sign_in")
                            ) {
                                Box(
                                    contentAlignment = Alignment.Center,
                                    modifier = Modifier.padding(vertical = 12.dp)
                                ) {
                                    Row(verticalAlignment = Alignment.CenterVertically) {
                                        Icon(
                                            Icons.Default.Login,
                                            contentDescription = null,
                                            tint = if (selectedAuthTab == 0) Color.White else Color.Gray,
                                            modifier = Modifier.size(16.dp)
                                        )
                                        Spacer(modifier = Modifier.width(6.dp))
                                        Text(
                                            text = if (isSwahili) "Ingia (Sign In)" else "Sign In",
                                            fontWeight = FontWeight.Bold,
                                            fontSize = 13.sp,
                                            color = if (selectedAuthTab == 0) Color.White else Color.Gray
                                        )
                                    }
                                }
                            }

                            Surface(
                                onClick = { selectedAuthTab = 1 },
                                shape = RoundedCornerShape(8.dp),
                                color = if (selectedAuthTab == 1) BrandBlue else Color.Transparent,
                                modifier = Modifier
                                    .weight(1f)
                                    .testTag("auth_tab_sign_up")
                            ) {
                                Box(
                                    contentAlignment = Alignment.Center,
                                    modifier = Modifier.padding(vertical = 12.dp)
                                ) {
                                    Row(verticalAlignment = Alignment.CenterVertically) {
                                        Icon(
                                            Icons.Default.PersonAdd,
                                            contentDescription = null,
                                            tint = if (selectedAuthTab == 1) Color.White else Color.Gray,
                                            modifier = Modifier.size(16.dp)
                                        )
                                        Spacer(modifier = Modifier.width(6.dp))
                                        Text(
                                            text = if (isSwahili) "Sajili (Sign Up)" else "Sign Up",
                                            fontWeight = FontWeight.Bold,
                                            fontSize = 13.sp,
                                            color = if (selectedAuthTab == 1) Color.White else Color.Gray
                                        )
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // Main Auth Form Content Area
            Box(
                modifier = Modifier
                    .fillMaxWidth()
                    .padding(16.dp)
            ) {
                if (selectedAuthTab == 0) {
                    // SIGN IN VIEW
                    Card(
                        shape = RoundedCornerShape(16.dp),
                        colors = CardDefaults.cardColors(containerColor = SlateSurface),
                        elevation = CardDefaults.cardElevation(defaultElevation = 2.dp),
                        modifier = Modifier.fillMaxWidth()
                    ) {
                        Column(modifier = Modifier.padding(20.dp)) {
                            Text(
                                text = if (isSwahili) "Karibu Tena!" else "Welcome Back!",
                                fontWeight = FontWeight.ExtraBold,
                                fontSize = 18.sp,
                                color = SlateTextPrimary
                            )
                            Text(
                                text = if (isSwahili) "Ingiza namba ya simu au barua pepe ili kuingia" else "Enter your registered phone or email to sign in",
                                fontSize = 12.sp,
                                color = SlateTextSecondary
                            )

                            Spacer(modifier = Modifier.height(16.dp))

                            if (signInErrorMessage != null) {
                                Surface(
                                    color = RedWarning.copy(alpha = 0.1f),
                                    border = androidx.compose.foundation.BorderStroke(1.dp, RedWarning),
                                    shape = RoundedCornerShape(8.dp),
                                    modifier = Modifier
                                        .fillMaxWidth()
                                        .padding(bottom = 12.dp)
                                ) {
                                    Text(
                                        text = signInErrorMessage!!,
                                        color = RedWarning,
                                        fontSize = 11.sp,
                                        fontWeight = FontWeight.Bold,
                                        modifier = Modifier.padding(10.dp)
                                    )
                                }
                            }

                            // Phone or Email Input
                            OutlinedTextField(
                                value = signInPhoneOrEmail,
                                onValueChange = { signInPhoneOrEmail = it },
                                label = { Text(if (isSwahili) "Namba ya Simu / Email" else "Phone Number or Email") },
                                leadingIcon = { Icon(Icons.Default.Phone, contentDescription = null, tint = BrandBlue) },
                                singleLine = true,
                                colors = formTextFieldColors(),
                                modifier = Modifier
                                    .fillMaxWidth()
                                    .testTag("signin_phone_input")
                            )

                            Spacer(modifier = Modifier.height(12.dp))

                            // Password Input
                            OutlinedTextField(
                                value = signInPassword,
                                onValueChange = { signInPassword = it },
                                label = { Text(if (isSwahili) "Neno la Siri / PIN" else "Password or PIN") },
                                leadingIcon = { Icon(Icons.Default.Lock, contentDescription = null, tint = BrandBlue) },
                                trailingIcon = {
                                    IconButton(onClick = { passwordVisible = !passwordVisible }) {
                                        Icon(
                                            imageVector = if (passwordVisible) Icons.Default.Visibility else Icons.Default.VisibilityOff,
                                            contentDescription = "Toggle password",
                                            tint = BrandBlue
                                        )
                                    }
                                },
                                visualTransformation = if (passwordVisible) VisualTransformation.None else PasswordVisualTransformation(),
                                singleLine = true,
                                colors = formTextFieldColors(),
                                modifier = Modifier
                                    .fillMaxWidth()
                                    .testTag("signin_password_input")
                            )

                            Spacer(modifier = Modifier.height(18.dp))

                            // Sign In Button
                            Button(
                                onClick = {
                                    if (signInPhoneOrEmail.isBlank()) {
                                        signInErrorMessage = if (isSwahili) "Ingiza namba ya simu au barua pepe." else "Please enter phone or email."
                                        return@Button
                                    }
                                    val inputClean = signInPhoneOrEmail.trim()
                                    val passwordClean = signInPassword.trim().ifEmpty { null }
                                    isSigningIn = true
                                    signInErrorMessage = null
                                    if (onSignInWithCredentials != null) {
                                        onSignInWithCredentials(inputClean, passwordClean) { success, err ->
                                            isSigningIn = false
                                            if (!success) {
                                                signInErrorMessage = err ?: (if (isSwahili) "Akaunti haijapatikana au nenosiri si sahihi." else "Account not found or password incorrect.")
                                            }
                                        }
                                    } else {
                                        isSigningIn = false
                                        signInErrorMessage = if (isSwahili) "Hitilafu ya mtandao. Tafadhali jaribu tena." else "Network error. Please try again."
                                    }
                                },
                                enabled = !isSigningIn,
                                colors = ButtonDefaults.buttonColors(containerColor = BrandBlue, contentColor = Color.White),
                                shape = RoundedCornerShape(10.dp),
                                modifier = Modifier
                                    .fillMaxWidth()
                                    .height(48.dp)
                                    .testTag("sign_in_submit_button")
                            ) {
                                if (isSigningIn) {
                                    CircularProgressIndicator(color = Color.White, modifier = Modifier.size(20.dp), strokeWidth = 2.dp)
                                    Spacer(modifier = Modifier.width(8.dp))
                                    Text(
                                        text = if (isSwahili) "Inaingia..." else "Authenticating...",
                                        fontWeight = FontWeight.Bold,
                                        fontSize = 15.sp
                                    )
                                } else {
                                    Icon(Icons.Default.ArrowForward, contentDescription = null)
                                    Spacer(modifier = Modifier.width(8.dp))
                                    Text(
                                        text = if (isSwahili) "Ingia Kwenye Akaunti" else "Sign In to Account",
                                        fontWeight = FontWeight.Bold,
                                        fontSize = 15.sp
                                    )
                                }
                            }

                            Spacer(modifier = Modifier.height(20.dp))
                            Divider()
                            Spacer(modifier = Modifier.height(16.dp))

                            Row(
                                modifier = Modifier.fillMaxWidth(),
                                horizontalArrangement = Arrangement.Center,
                                verticalAlignment = Alignment.CenterVertically
                            ) {
                                Text(
                                    text = if (isSwahili) "Huna akaunti bado? " else "Don't have an account yet? ",
                                    fontSize = 13.sp,
                                    color = SlateTextSecondary
                                )
                                TextButton(onClick = { selectedAuthTab = 1 }) {
                                    Text(
                                        text = if (isSwahili) "Jisajili Hapa" else "Sign Up Here",
                                        fontWeight = FontWeight.Bold,
                                        fontSize = 13.sp,
                                        color = BrandBlue
                                    )
                                }
                            }
                        }
                    }
                } else {
                    // SIGN UP VIEW
                    Card(
                        shape = RoundedCornerShape(16.dp),
                        colors = CardDefaults.cardColors(containerColor = SlateSurface),
                        elevation = CardDefaults.cardElevation(defaultElevation = 2.dp),
                        modifier = Modifier.fillMaxWidth()
                    ) {
                        Column(modifier = Modifier.padding(20.dp)) {
                            Text(
                                text = if (isSwahili) "Tengeneza Akaunti Mpya" else "Create New Account",
                                fontWeight = FontWeight.ExtraBold,
                                fontSize = 18.sp,
                                color = SlateTextPrimary
                            )
                            Text(
                                text = if (isSwahili) "Sajili maelezo yako na NIDA ID ili kuanza kutumia Linkora" else "Register your profile with NIDA ID to start using Linkora",
                                fontSize = 12.sp,
                                color = SlateTextSecondary
                            )

                            Spacer(modifier = Modifier.height(16.dp))

                            if (signUpErrorMessage != null) {
                                Surface(
                                    color = RedWarning.copy(alpha = 0.1f),
                                    border = androidx.compose.foundation.BorderStroke(1.dp, RedWarning),
                                    shape = RoundedCornerShape(8.dp),
                                    modifier = Modifier
                                        .fillMaxWidth()
                                        .padding(bottom = 12.dp)
                                ) {
                                    Text(
                                        text = signUpErrorMessage!!,
                                        color = RedWarning,
                                        fontSize = 11.sp,
                                        fontWeight = FontWeight.Bold,
                                        modifier = Modifier.padding(10.dp)
                                    )
                                }
                            }

                            // Account Type Toggle: Individual Worker vs Company/Organization
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
                                        signUpAccountType = "INDIVIDUAL"
                                        signUpRole = UserRole.JOB_SEEKER
                                    },
                                    color = if (signUpAccountType == "INDIVIDUAL") BrandBlue else Color.Transparent,
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
                                            tint = if (signUpAccountType == "INDIVIDUAL") Color.White else Color.Gray,
                                            modifier = Modifier.size(16.dp)
                                        )
                                        Spacer(modifier = Modifier.width(6.dp))
                                        Text(
                                            text = if (isSwahili) "Mtu Binafsi / Mfanyakazi" else "Individual Worker",
                                            fontSize = 11.sp,
                                            fontWeight = FontWeight.Bold,
                                            color = if (signUpAccountType == "INDIVIDUAL") Color.White else Color.Gray
                                        )
                                    }
                                }

                                Surface(
                                    onClick = {
                                        signUpAccountType = "ORGANIZATION"
                                        signUpRole = UserRole.EMPLOYER
                                    },
                                    color = if (signUpAccountType == "ORGANIZATION") BrandBlue else Color.Transparent,
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
                                            tint = if (signUpAccountType == "ORGANIZATION") Color.White else Color.Gray,
                                            modifier = Modifier.size(16.dp)
                                        )
                                        Spacer(modifier = Modifier.width(6.dp))
                                        Text(
                                            text = if (isSwahili) "Kampuni / Shirika" else "Company / Org",
                                            fontSize = 11.sp,
                                            fontWeight = FontWeight.Bold,
                                            color = if (signUpAccountType == "ORGANIZATION") Color.White else Color.Gray
                                        )
                                    }
                                }
                            }

                            Spacer(modifier = Modifier.height(12.dp))

                            if (signUpAccountType == "ORGANIZATION") {
                                // Organization Name
                                OutlinedTextField(
                                    value = signUpOrgName,
                                    onValueChange = { signUpOrgName = it },
                                    label = { Text(if (isSwahili) "Jina la Kampuni / Shirika *" else "Company / Organization Name *") },
                                    placeholder = { Text("Mbeya Commercial Complex Ltd") },
                                    leadingIcon = { Icon(Icons.Default.Business, contentDescription = null, tint = BrandBlue) },
                                    singleLine = true,
                                    colors = formTextFieldColors(),
                                    modifier = Modifier.fillMaxWidth()
                                )

                                Spacer(modifier = Modifier.height(10.dp))

                                // TIN Number & Industry Sector
                                Row(horizontalArrangement = Arrangement.spacedBy(8.dp)) {
                                    OutlinedTextField(
                                        value = signUpTinNumber,
                                        onValueChange = { signUpTinNumber = it },
                                        label = { Text(if (isSwahili) "Namba ya TIN *" else "TIN Number *") },
                                        placeholder = { Text("142-990-128") },
                                        leadingIcon = { Icon(Icons.Default.Receipt, contentDescription = null, tint = BrandGreen) },
                                        singleLine = true,
                                        colors = formTextFieldColors(),
                                        modifier = Modifier.weight(1f)
                                    )

                                    OutlinedTextField(
                                        value = signUpIndustrySector,
                                        onValueChange = { signUpIndustrySector = it },
                                        label = { Text(if (isSwahili) "Sekta ya Kazi" else "Industry Sector") },
                                        leadingIcon = { Icon(Icons.Default.Domain, contentDescription = null, tint = BrandBlue) },
                                        singleLine = true,
                                        colors = formTextFieldColors(),
                                        modifier = Modifier.weight(1f)
                                    )
                                }

                                Spacer(modifier = Modifier.height(10.dp))

                                // Representative / Contact Person
                                OutlinedTextField(
                                    value = signUpFullName,
                                    onValueChange = { signUpFullName = it },
                                    label = { Text(if (isSwahili) "Afisa Mwakilishi / Contact Person *" else "Representative Full Name *") },
                                    leadingIcon = { Icon(Icons.Default.Person, contentDescription = null, tint = BrandBlue) },
                                    singleLine = true,
                                    colors = formTextFieldColors(),
                                    modifier = Modifier.fillMaxWidth()
                                )
                            } else {
                                // Individual Worker Full Name
                                OutlinedTextField(
                                    value = signUpFullName,
                                    onValueChange = { signUpFullName = it },
                                    label = { Text(if (isSwahili) "Majina Kamili *" else "Full Name *") },
                                    leadingIcon = { Icon(Icons.Default.Person, contentDescription = null, tint = BrandBlue) },
                                    singleLine = true,
                                    colors = formTextFieldColors(),
                                    modifier = Modifier
                                        .fillMaxWidth()
                                        .testTag("signup_fullname_input")
                                )

                                Spacer(modifier = Modifier.height(10.dp))

                                // Sifa Kuu ya Kazi (Primary Profession - For AI Matchmaking)
                                Text(
                                    text = if (isSwahili) "Sifa Kuu ya Kazi (AI Matchmaking) *" else "Primary Profession (For AI Targeting) *",
                                    fontSize = 12.sp,
                                    fontWeight = FontWeight.Bold,
                                    color = SlateTextPrimary
                                )
                                Spacer(modifier = Modifier.height(4.dp))

                                val currentProfPair = tanzaniaProfessionsList.firstOrNull { it.first == signUpPrimaryProfession }
                                val currentProfLabel = if (isSwahili) currentProfPair?.second?.first ?: signUpPrimaryProfession else currentProfPair?.second?.second ?: signUpPrimaryProfession

                                Box(modifier = Modifier.fillMaxWidth()) {
                                    OutlinedCard(
                                        onClick = { professionDropdownExpanded = true },
                                        colors = CardDefaults.outlinedCardColors(containerColor = Color.White),
                                        border = androidx.compose.foundation.BorderStroke(1.dp, Color(0xFFCBD5E1)),
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
                                            Icon(Icons.Default.ArrowDropDown, contentDescription = null, tint = SlateTextPrimary)
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
                                                        fontWeight = if (key == signUpPrimaryProfession) FontWeight.Bold else FontWeight.Normal
                                                    )
                                                },
                                                onClick = {
                                                    signUpPrimaryProfession = key
                                                    professionDropdownExpanded = false
                                                }
                                            )
                                        }
                                    }
                                }

                                Spacer(modifier = Modifier.height(10.dp))

                                // NIDA ID (for individual)
                                OutlinedTextField(
                                    value = signUpNida,
                                    onValueChange = { signUpNida = it },
                                    label = { Text(if (isSwahili) "Namba ya NIDA (Kitambulisho) *" else "NIDA ID Number *") },
                                    placeholder = { Text("19950510-33201-00004-12") },
                                    leadingIcon = { Icon(Icons.Default.VerifiedUser, contentDescription = null, tint = BrandGreen) },
                                    trailingIcon = {
                                        if (signUpNida.length >= 10) {
                                            Surface(
                                                color = BrandGreen.copy(alpha = 0.15f),
                                                shape = RoundedCornerShape(4.dp)
                                            ) {
                                                Text(
                                                    text = "VERIFIED",
                                                    fontSize = 9.sp,
                                                    fontWeight = FontWeight.Bold,
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
                                        .testTag("signup_nida_input")
                                )
                            }

                            Spacer(modifier = Modifier.height(10.dp))

                            // Phone & Email Row
                            Row(horizontalArrangement = Arrangement.spacedBy(8.dp)) {
                                OutlinedTextField(
                                    value = signUpPhone,
                                    onValueChange = { signUpPhone = it },
                                    label = { Text(if (isSwahili) "Simu (+255) *" else "Phone (+255) *") },
                                    leadingIcon = { Icon(Icons.Default.Phone, contentDescription = null, tint = BrandBlue) },
                                    singleLine = true,
                                    colors = formTextFieldColors(),
                                    modifier = Modifier
                                        .weight(1f)
                                        .testTag("signup_phone_input")
                                )

                                OutlinedTextField(
                                    value = signUpEmail,
                                    onValueChange = { signUpEmail = it },
                                    label = { Text(if (isSwahili) "Email" else "Email") },
                                    leadingIcon = { Icon(Icons.Default.Email, contentDescription = null, tint = BrandBlue) },
                                    singleLine = true,
                                    colors = formTextFieldColors(),
                                    modifier = Modifier
                                        .weight(1f)
                                        .testTag("signup_email_input")
                                )
                            }

                            Spacer(modifier = Modifier.height(10.dp))

                            // District / Wilaya (For AI Matchmaking Target)
                            Text(
                                text = if (isSwahili) "Wilaya / Eneo la Kazi (AI Target) *" else "District / Operational Area *",
                                fontSize = 12.sp,
                                fontWeight = FontWeight.Bold,
                                color = SlateTextPrimary
                            )
                            Spacer(modifier = Modifier.height(4.dp))

                            Box(modifier = Modifier.fillMaxWidth()) {
                                OutlinedCard(
                                    onClick = { districtDropdownExpanded = true },
                                    colors = CardDefaults.outlinedCardColors(containerColor = Color.White),
                                    border = androidx.compose.foundation.BorderStroke(1.dp, Color(0xFFCBD5E1)),
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
                                            Text(text = signUpDistrict, fontSize = 13.sp, fontWeight = FontWeight.SemiBold, color = SlateTextPrimary)
                                        }
                                        Icon(Icons.Default.ArrowDropDown, contentDescription = null, tint = SlateTextPrimary)
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
                                                signUpDistrict = dist
                                                signUpLocation = "$dist, Tanzania"
                                                districtDropdownExpanded = false
                                            }
                                        )
                                    }
                                }
                            }

                            Spacer(modifier = Modifier.height(10.dp))

                            // Skills
                            OutlinedTextField(
                                value = signUpSkills,
                                onValueChange = { signUpSkills = it },
                                label = { Text(if (isSwahili) "Ujuzi na Utaalamu wa Kazi" else "Skills & Expertise") },
                                placeholder = { Text("Usafi wa Maofisi, Usafi wa Zulia, Utupaji Taka") },
                                leadingIcon = { Icon(Icons.Default.Build, contentDescription = null, tint = BrandBlue) },
                                singleLine = true,
                                colors = formTextFieldColors(),
                                modifier = Modifier.fillMaxWidth()
                            )

                            Spacer(modifier = Modifier.height(10.dp))

                            // Password
                            OutlinedTextField(
                                value = signUpPassword,
                                onValueChange = { signUpPassword = it },
                                label = { Text(if (isSwahili) "Weka Neno la Siri *" else "Create Password / PIN *") },
                                leadingIcon = { Icon(Icons.Default.Lock, contentDescription = null, tint = BrandBlue) },
                                visualTransformation = PasswordVisualTransformation(),
                                singleLine = true,
                                colors = formTextFieldColors(),
                                modifier = Modifier
                                    .fillMaxWidth()
                                    .testTag("signup_password_input")
                            )

                            Spacer(modifier = Modifier.height(18.dp))

                            // Create Account Submit Button
                            Button(
                                onClick = {
                                    val finalName = if (signUpAccountType == "ORGANIZATION") {
                                        signUpOrgName.ifBlank { signUpFullName }
                                    } else {
                                        signUpFullName
                                    }
                                    if (finalName.isBlank() || signUpPhone.isBlank()) {
                                        signUpErrorMessage = if (isSwahili) "Jaza Majina Kamili / Jina la Kampuni na Namba ya Simu." else "Please enter Name and Phone."
                                        return@Button
                                    }
                                    val skillsList = signUpSkills.split(",").map { it.trim() }.filter { it.isNotEmpty() }
                                    isSigningIn = true
                                    signUpErrorMessage = null
                                    onRegisterSubmit(
                                        finalName,
                                        signUpPhone,
                                        signUpEmail,
                                        if (signUpAccountType == "ORGANIZATION") UserRole.EMPLOYER else signUpRole,
                                        signUpNida,
                                        signUpLocation,
                                        signUpBio.ifBlank {
                                            if (signUpAccountType == "ORGANIZATION") "Registered organization employer on Linkora."
                                            else "Registered worker in $signUpDistrict specializing in $signUpPrimaryProfession."
                                        },
                                        skillsList.ifEmpty { listOf(signUpPrimaryProfession, "Services") },
                                        signUpAccountType,
                                        signUpOrgName,
                                        signUpTinNumber,
                                        signUpIndustrySector,
                                        signUpPrimaryProfession,
                                        signUpDistrict,
                                        signUpPassword.trim().ifEmpty { null }
                                    ) { success, err ->
                                        isSigningIn = false
                                        if (!success) {
                                            signUpErrorMessage = err ?: (if (isSwahili) "Imeshindwa kusajili akaunti. Tafadhali hakiki taarifa zako." else "Registration failed. Please verify your details.")
                                        }
                                    }
                                },
                                enabled = !isSigningIn,
                                colors = ButtonDefaults.buttonColors(containerColor = BrandGreen, contentColor = Color.White),
                                shape = RoundedCornerShape(10.dp),
                                modifier = Modifier
                                    .fillMaxWidth()
                                    .height(48.dp)
                                    .testTag("sign_up_submit_button")
                            ) {
                                if (isSigningIn) {
                                    CircularProgressIndicator(color = Color.White, modifier = Modifier.size(20.dp), strokeWidth = 2.dp)
                                    Spacer(modifier = Modifier.width(8.dp))
                                    Text(
                                        text = if (isSwahili) "Inasajili..." else "Registering...",
                                        fontWeight = FontWeight.Bold,
                                        fontSize = 15.sp
                                    )
                                } else {
                                    Icon(Icons.Default.Check, contentDescription = null)
                                    Spacer(modifier = Modifier.width(8.dp))
                                    Text(
                                        text = if (isSwahili) "Sajili & Ingia Sasa" else "Sign Up & Access Platform",
                                        fontWeight = FontWeight.Bold,
                                        fontSize = 15.sp
                                    )
                                }
                            }
                        }
                    }
                }
            }

            Spacer(modifier = Modifier.height(32.dp))
        }
    }
}
