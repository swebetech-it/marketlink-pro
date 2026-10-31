package com.example

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.activity.enableEdgeToEdge
import androidx.activity.viewModels
import androidx.compose.foundation.layout.*
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.Lock
import androidx.compose.material.icons.filled.Person
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Modifier
import androidx.compose.ui.platform.testTag
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.lifecycle.compose.collectAsStateWithLifecycle
import com.example.data.model.Listing
import com.example.ui.components.MarketLinkBottomBar
import com.example.ui.components.MarketLinkTopBar
import com.example.ui.components.NavigationTab
import com.example.ui.screens.*
import com.example.ui.theme.EmeraldPrimary
import com.example.ui.theme.MarketLinkProTheme
import com.example.ui.viewmodel.MarketLinkViewModel

class MainActivity : ComponentActivity() {

    private val viewModel: MarketLinkViewModel by viewModels()

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()

        // Initialize ApiClient with persisted base URL
        com.example.data.remote.ApiClient.init(applicationContext)

        // Schedule automated background sync with Laravel backend
        com.example.data.worker.MarketLinkSyncWorker.schedulePeriodicSync(applicationContext)

        setContent {
            MarketLinkProTheme {
                val uiState by viewModel.uiState.collectAsStateWithLifecycle()

                var currentTab by remember { mutableStateOf(NavigationTab.OPPORTUNITIES) }
                var activeListingDetail by remember { mutableStateOf<Listing?>(null) }
                var showCreateDialog by remember { mutableStateOf(false) }
                var showRegisterDialog by remember { mutableStateOf(false) }
                var showNotificationSheet by remember { mutableStateOf(false) }
                var showLogoutDialog by remember { mutableStateOf(false) }

                if (!uiState.isLoggedIn) {
                    AuthScreen(
                        isSwahili = uiState.isSwahili,
                        allUsers = uiState.allUsers,
                        onLanguageToggle = { viewModel.setLanguage(it) },
                        onSignInSuccess = { profile -> viewModel.signInUser(profile) },
                        onSignInWithCredentials = { login, password, onResult ->
                            viewModel.signInWithCredentials(login, password, onResult)
                        },
                        onUpdateBaseUrl = { url, onComplete ->
                            viewModel.updateServerBaseUrl(url, onComplete)
                        },
                        onTriggerSync = { onComplete ->
                            viewModel.triggerManualSync(onComplete)
                        },
                        onRegisterSubmit = { name, phone, email, role, nida, loc, bio, skills, accountType, orgName, tin, sector, primaryProf, district, password, onResult ->
                            viewModel.registerUser(
                                fullName = name,
                                phone = phone,
                                email = email,
                                role = role,
                                nidaNumber = nida,
                                location = loc,
                                bio = bio,
                                skills = skills,
                                accountType = accountType,
                                organizationName = orgName,
                                tinNumber = tin,
                                industrySector = sector,
                                primaryProfession = primaryProf,
                                district = district,
                                password = password,
                                onResult = onResult
                            )
                        }
                    )
                } else {
                    Scaffold(
                        topBar = {
                        MarketLinkTopBar(
                            selectedRole = uiState.selectedRole,
                            verificationStatus = uiState.userProfile?.verificationStatus ?: com.example.data.model.VerificationStatus.VERIFIED,
                            isSwahili = uiState.isSwahili,
                            unreadNotifCount = uiState.notifications.count { !it.isRead },
                            onRoleSelected = { viewModel.setUserRole(it) },
                            onLanguageToggle = { viewModel.setLanguage(it) },
                            onNotificationClick = { showNotificationSheet = true },
                            onOpenRegister = { showRegisterDialog = true },
                            onLogoutClick = { showLogoutDialog = true }
                        )
                    },
                    bottomBar = {
                        if (activeListingDetail == null) {
                            MarketLinkBottomBar(
                                currentTab = currentTab,
                                isSwahili = uiState.isSwahili,
                                onTabSelected = { currentTab = it }
                            )
                        }
                    },
                    modifier = Modifier.fillMaxSize()
                ) { innerPadding ->
                    Box(modifier = Modifier.padding(innerPadding)) {
                        val currentProfile = uiState.userProfile
                        val detail = activeListingDetail

                        if (detail != null) {
                            val isOwner = detail.posterId == currentProfile.id ||
                                (currentProfile.organizationName.isNotBlank() && detail.posterName.equals(currentProfile.organizationName, ignoreCase = true))

                            ListingDetailScreen(
                                listing = detail,
                                isSwahili = uiState.isSwahili,
                                isAiAnalyzing = uiState.isAiAnalyzing,
                                aiMatchScore = uiState.aiMatchScore,
                                aiMatchExplanation = uiState.aiMatchExplanation,
                                aiFraudWarning = uiState.aiFraudWarning,
                                generatedPitchText = uiState.generatedPitchText,
                                isOwner = isOwner,
                                onBackClick = {
                                    activeListingDetail = null
                                    viewModel.selectListingDetail(null)
                                },
                                onGeneratePitch = {
                                    viewModel.generateAiPitch(detail.title)
                                },
                                onApplySubmit = { pitch ->
                                    viewModel.applyToListing(
                                        listingId = detail.id,
                                        title = detail.title,
                                        pitch = pitch
                                    )
                                    activeListingDetail = null
                                },
                                onDepositEscrowClick = { method ->
                                    viewModel.depositEscrow(
                                        listingId = detail.id,
                                        title = detail.title,
                                        payeeName = detail.posterName,
                                        amount = detail.compensationAmountTzs,
                                        method = method
                                    )
                                    activeListingDetail = null
                                },
                                onDeleteListingClick = {
                                    viewModel.deleteMyListing(detail.id)
                                    activeListingDetail = null
                                }
                            )
                        } else {
                            when (currentTab) {
                                NavigationTab.OPPORTUNITIES -> {
                                    HomeScreen(
                                        userProfile = currentProfile,
                                        isSwahili = uiState.isSwahili,
                                        selectedRole = uiState.selectedRole,
                                        searchQuery = uiState.searchQuery,
                                        selectedCategory = uiState.selectedCategory,
                                        selectedRegion = uiState.selectedRegion,
                                        listings = uiState.listings,
                                        myOrganizationListings = uiState.myOrganizationListings,
                                        isViewingPublicDirectory = uiState.isViewingPublicDirectory,
                                        onTogglePublicDirectory = { viewModel.setViewingPublicDirectory(it) },
                                        onSearchChange = { viewModel.setSearchQuery(it) },
                                        onCategorySelect = { viewModel.setCategoryFilter(it) },
                                        onRegionSelect = { viewModel.setRegionFilter(it) },
                                        onListingClick = { listing ->
                                            activeListingDetail = listing
                                            viewModel.selectListingDetail(listing)
                                        },
                                        onQuickApply = { listing ->
                                            activeListingDetail = listing
                                            viewModel.selectListingDetail(listing)
                                        },
                                        onCreateListingClick = { showCreateDialog = true },
                                        onDeleteMyListing = { viewModel.deleteMyListing(it) },
                                        onTriggerSync = { onComplete ->
                                            viewModel.triggerManualSync(onComplete)
                                        }
                                    )
                                }

                                NavigationTab.AGRI_EXCHANGE -> {
                                    AgriExchangeScreen(
                                        listings = uiState.listings,
                                        isSwahili = uiState.isSwahili,
                                        onListingClick = { listing ->
                                            activeListingDetail = listing
                                            viewModel.selectListingDetail(listing)
                                        },
                                        onQuickApply = { listing ->
                                            activeListingDetail = listing
                                            viewModel.selectListingDetail(listing)
                                        },
                                        onPostAgriNeedClick = { showCreateDialog = true }
                                    )
                                }

                                NavigationTab.REPUTATION -> {
                                    ReputationPassportScreen(
                                        userProfile = currentProfile,
                                        isSwahili = uiState.isSwahili,
                                        onRequestNidaVerification = { viewModel.requestNidaVerification(it) },
                                        onVouchClick = { viewModel.vouchForUser() },
                                        onLogoutClick = { showLogoutDialog = true }
                                    )
                                }

                                NavigationTab.ESCROW -> {
                                    EscrowPaymentsScreen(
                                        transactions = uiState.transactions,
                                        isSwahili = uiState.isSwahili,
                                        onReleaseEscrowClick = { viewModel.releaseEscrow(it) },
                                        onDisputeClick = { viewModel.disputeEscrow(it) }
                                    )
                                }

                                NavigationTab.HUB -> {
                                    if (uiState.selectedRole == com.example.data.model.UserRole.ADMIN) {
                                        AdminAndUssdScreen(
                                            isSwahili = uiState.isSwahili,
                                            allUsers = uiState.allUsers,
                                            currentUser = currentProfile,
                                            listings = uiState.listings,
                                            transactions = uiState.transactions,
                                            onOpenRegisterDialog = { showRegisterDialog = true },
                                            onSwitchProfile = { viewModel.switchActiveProfile(it) },
                                            onVerifyUser = { id, status -> viewModel.adminVerifyUser(id, status) },
                                            onUpdateUserRole = { id, role -> viewModel.adminUpdateUserRole(id, role) },
                                            onDeleteUser = { id -> viewModel.adminDeleteUser(id) },
                                            onDeleteListing = { id -> viewModel.adminDeleteListing(id) },
                                            onReleaseEscrow = { id -> viewModel.releaseEscrow(id) },
                                            onSimulateUssdDial = { viewModel.simulateUssdDial(it) }
                                        )
                                    } else {
                                        TrainingAndFinancialScreen(
                                            userProfile = currentProfile,
                                            courses = uiState.courses,
                                            isSwahili = uiState.isSwahili
                                        )
                                    }
                                }
                            }
                        }

                        // Logout Confirmation Modal
                        if (showLogoutDialog) {
                            AlertDialog(
                                onDismissRequest = { showLogoutDialog = false },
                                icon = {
                                    Icon(
                                        imageVector = androidx.compose.material.icons.Icons.Default.Lock,
                                        contentDescription = null,
                                        tint = com.example.ui.theme.RedWarning,
                                        modifier = androidx.compose.ui.Modifier.size(32.dp)
                                    )
                                },
                                title = {
                                    Text(
                                        text = if (uiState.isSwahili) "Unathibitisha Kuondoka?" else "Confirm Sign Out?",
                                        fontWeight = androidx.compose.ui.text.font.FontWeight.Bold
                                    )
                                },
                                text = {
                                    Text(
                                        text = if (uiState.isSwahili)
                                            "Utatoka kwenye akaunti ya '${currentProfile.fullName}'. Unaweza kuingia tena au kusajili akaunti mpya muda wowote."
                                        else
                                            "You are signing out of '${currentProfile.fullName}'. You can log in or register a new profile at any time.",
                                        fontSize = 13.sp
                                    )
                                },
                                confirmButton = {
                                    Button(
                                        onClick = {
                                            viewModel.logout()
                                            showLogoutDialog = false
                                            showRegisterDialog = true
                                        },
                                        colors = ButtonDefaults.buttonColors(containerColor = com.example.ui.theme.RedWarning),
                                        modifier = androidx.compose.ui.Modifier.testTag("confirm_logout_button")
                                    ) {
                                        Text(if (uiState.isSwahili) "Ndiyo, Ondoka" else "Yes, Sign Out", color = androidx.compose.ui.graphics.Color.White)
                                    }
                                },
                                dismissButton = {
                                    OutlinedButton(onClick = { showLogoutDialog = false }) {
                                        Text(if (uiState.isSwahili) "Ghairi" else "Cancel")
                                    }
                                },
                                modifier = androidx.compose.ui.Modifier.testTag("logout_confirmation_dialog")
                            )
                        }

                        // User Registration Modal
                        if (showRegisterDialog) {
                            UserRegistrationDialog(
                                isSwahili = uiState.isSwahili,
                                onDismiss = { showRegisterDialog = false },
                                onRegisterSubmit = { name, phone, email, role, nida, loc, bio, skills, accountType, orgName, tin, sector, primaryProf, district, password, onResult ->
                                    viewModel.registerUser(
                                        fullName = name,
                                        phone = phone,
                                        email = email,
                                        role = role,
                                        nidaNumber = nida,
                                        location = loc,
                                        bio = bio,
                                        skills = skills,
                                        accountType = accountType,
                                        organizationName = orgName,
                                        tinNumber = tin,
                                        industrySector = sector,
                                        primaryProfession = primaryProf,
                                        district = district,
                                        password = password,
                                        onResult = onResult
                                    )
                                }
                            )
                        }

                        // Create Listing Modal
                        if (showCreateDialog) {
                            CreateListingDialog(
                                isSwahili = uiState.isSwahili,
                                onDismiss = { showCreateDialog = false },
                                onSubmit = { title, cat, desc, comp, amt, loc, skills, isEscrow, crop, equip, targetProf, jobLevel, dist ->
                                    viewModel.createNewListing(
                                        title = title,
                                        category = cat,
                                        description = desc,
                                        compensation = comp,
                                        amountTzs = amt,
                                        location = loc,
                                        skills = skills,
                                        isEscrow = isEscrow,
                                        cropType = crop,
                                        equipDetails = equip,
                                        targetProfession = targetProf,
                                        jobTypeLevel = jobLevel,
                                        district = dist
                                    )
                                    showCreateDialog = false
                                }
                            )
                        }

                        // Notification Sheet Modal
                        if (showNotificationSheet) {
                            AlertDialog(
                                onDismissRequest = { showNotificationSheet = false },
                                title = {
                                    Text(
                                        text = if (uiState.isSwahili) "Aarifa za Mfumo" else "System Notifications",
                                        fontSize = 16.sp,
                                        fontWeight = androidx.compose.ui.text.font.FontWeight.Bold
                                    )
                                },
                                text = {
                                    Column(verticalArrangement = Arrangement.spacedBy(8.dp)) {
                                        uiState.notifications.forEach { notif ->
                                            Card(
                                                shape = androidx.compose.foundation.shape.RoundedCornerShape(8.dp),
                                                colors = CardDefaults.cardColors(containerColor = androidx.compose.ui.graphics.Color(0xFFF1F5F9))
                                            ) {
                                                Column(modifier = Modifier.padding(10.dp)) {
                                                    Row(
                                                        modifier = Modifier.fillMaxWidth(),
                                                        horizontalArrangement = Arrangement.SpaceBetween
                                                    ) {
                                                        Text(text = notif.title, fontWeight = androidx.compose.ui.text.font.FontWeight.Bold, fontSize = 12.sp)
                                                        Text(text = notif.channel, fontSize = 10.sp, color = EmeraldPrimary)
                                                    }
                                                    Text(text = notif.body, fontSize = 11.sp)
                                                }
                                            }
                                        }
                                    }
                                },
                                confirmButton = {
                                    TextButton(onClick = { showNotificationSheet = false }) {
                                        Text(if (uiState.isSwahili) "Funga" else "Close")
                                    }
                                }
                            )
                        }
                    }
                }
            }
        }
    }
}
}
