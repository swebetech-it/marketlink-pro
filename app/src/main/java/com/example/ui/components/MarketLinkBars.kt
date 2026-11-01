package com.example.ui.components

import androidx.compose.foundation.Canvas
import androidx.compose.foundation.background
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.*
import androidx.compose.material.icons.outlined.*
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.geometry.Offset
import androidx.compose.ui.geometry.Size
import androidx.compose.ui.graphics.Brush
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.graphics.Path
import androidx.compose.ui.graphics.drawscope.Stroke
import androidx.compose.ui.platform.testTag
import androidx.compose.ui.res.painterResource
import androidx.compose.ui.text.SpanStyle
import androidx.compose.ui.text.buildAnnotatedString
import androidx.compose.ui.text.font.FontFamily
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.withStyle
import androidx.compose.ui.unit.Dp
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.compose.ui.window.DialogProperties
import com.example.R
import com.example.data.model.UserRole
import com.example.data.model.VerificationStatus
import com.example.ui.theme.*

// Exact logo brand colors from uploaded image
val BrandBlue = Color(0xFF0072FF)
val BrandLightBlue = Color(0xFF00A3FF)
val BrandGreen = Color(0xFF00C853)
val BrandOrange = Color(0xFFFF6D00)
val BrandDarkBg = Color(0xFF070B14)

@Composable
fun MarketLinkLogoMark(
    modifier: Modifier = Modifier,
    size: Dp = 32.dp
) {
    Box(
        modifier = modifier.size(size),
        contentAlignment = Alignment.Center
    ) {
        Icon(
            painter = painterResource(id = R.drawable.ic_launcher_foreground),
            contentDescription = "Linkora Logo Emblem",
            tint = Color.Unspecified,
            modifier = Modifier.fillMaxSize()
        )
    }
}

@Composable
fun MarketLinkLogoText(
    fontSize: Float = 18f,
    showTagline: Boolean = false
) {
    Column {
        Row(verticalAlignment = Alignment.CenterVertically) {
            Text(
                text = buildAnnotatedString {
                    withStyle(SpanStyle(color = Color.White, fontWeight = FontWeight.ExtraBold)) {
                        append("Link")
                    }
                    withStyle(SpanStyle(color = BrandGreen, fontWeight = FontWeight.ExtraBold)) {
                        append("ora")
                    }
                },
                fontSize = fontSize.sp,
                fontFamily = FontFamily.SansSerif
            )
        }
        if (showTagline) {
            Row(
                verticalAlignment = Alignment.CenterVertically,
                modifier = Modifier.padding(top = 1.dp)
            ) {
                Divider(
                    color = Color.White.copy(alpha = 0.3f),
                    modifier = Modifier
                        .weight(1f)
                        .height(0.5.dp)
                )
                Text(
                    text = " CONNECT • WORK • TRUST ",
                    fontSize = (fontSize * 0.45f).sp,
                    fontWeight = FontWeight.Bold,
                    color = Color.White.copy(alpha = 0.85f),
                    letterSpacing = 1.sp
                )
                Divider(
                    color = Color.White.copy(alpha = 0.3f),
                    modifier = Modifier
                        .weight(1f)
                        .height(0.5.dp)
                )
            }
        }
    }
}

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun MarketLinkTopBar(
    selectedRole: UserRole,
    verificationStatus: VerificationStatus,
    isSwahili: Boolean,
    unreadNotifCount: Int,
    onRoleSelected: (UserRole) -> Unit,
    onLanguageToggle: (Boolean) -> Unit,
    onNotificationClick: () -> Unit,
    onOpenRegister: (() -> Unit)? = null,
    onServerSettingsClick: (() -> Unit)? = null,
    onLogoutClick: (() -> Unit)? = null
) {
    var roleDropdownExpanded by remember { mutableStateOf(false) }

    TopAppBar(
        colors = TopAppBarDefaults.topAppBarColors(
            containerColor = BrandDarkBg,
            titleContentColor = Color.White,
            actionIconContentColor = Color.White
        ),
        title = {
            Row(
                verticalAlignment = Alignment.CenterVertically,
                horizontalArrangement = Arrangement.Start
            ) {
                MarketLinkLogoMark(size = 38.dp)
                Spacer(modifier = Modifier.width(8.dp))
                MarketLinkLogoText(fontSize = 17f, showTagline = true)
            }
        },
        actions = {
            // NIDA Verification Badge
            Surface(
                color = when (verificationStatus) {
                    VerificationStatus.VERIFIED -> GreenVerified
                    VerificationStatus.PENDING -> AmberPending
                    VerificationStatus.UNVERIFIED -> RedWarning
                },
                shape = CircleShape,
                modifier = Modifier.padding(end = 4.dp)
            ) {
                Row(
                    modifier = Modifier.padding(horizontal = 6.dp, vertical = 3.dp),
                    verticalAlignment = Alignment.CenterVertically
                ) {
                    Icon(
                        imageVector = if (verificationStatus == VerificationStatus.VERIFIED) Icons.Default.Verified else Icons.Default.Shield,
                        contentDescription = "NIDA Status",
                        tint = Color.White,
                        modifier = Modifier.size(11.dp)
                    )
                    Spacer(modifier = Modifier.width(2.dp))
                    Text(
                        text = if (verificationStatus == VerificationStatus.VERIFIED) "NIDA" else "KYC",
                        fontSize = 9.sp,
                        fontWeight = FontWeight.Bold,
                        color = Color.White
                    )
                }
            }

            // Language Switcher Toggle
            FilterChip(
                selected = isSwahili,
                onClick = { onLanguageToggle(!isSwahili) },
                label = {
                    Text(
                        text = if (isSwahili) "SW 🇹🇿" else "EN 🇬🇧",
                        fontSize = 10.sp,
                        fontWeight = FontWeight.Bold,
                        color = Color.White
                    )
                },
                colors = FilterChipDefaults.filterChipColors(
                    containerColor = Color(0xFF1E293B),
                    selectedContainerColor = BrandOrange,
                    selectedLabelColor = Color.White
                ),
                modifier = Modifier
                    .testTag("language_toggle")
                    .padding(end = 2.dp)
            )



            // Role Selector Button
            Box {
                IconButton(
                    onClick = { roleDropdownExpanded = true },
                    modifier = Modifier.testTag("role_dropdown_button")
                ) {
                    Icon(
                        imageVector = Icons.Default.AccountCircle,
                        contentDescription = "Select Role",
                        tint = BrandOrange
                    )
                }

                DropdownMenu(
                    expanded = roleDropdownExpanded,
                    onDismissRequest = { roleDropdownExpanded = false }
                ) {
                    Text(
                        text = if (isSwahili) "Chagua Aina ya Akaunti:" else "Switch Role View:",
                        fontSize = 12.sp,
                        fontWeight = FontWeight.Bold,
                        modifier = Modifier.padding(horizontal = 12.dp, vertical = 6.dp),
                        color = SlateTextSecondary
                    )
                    HorizontalDivider()
                    UserRole.values().forEach { role ->
                        DropdownMenuItem(
                            text = {
                                Text(
                                    text = if (isSwahili) role.displayNameSw else role.displayNameEn,
                                    fontSize = 13.sp,
                                    fontWeight = if (role == selectedRole) FontWeight.Bold else FontWeight.Normal
                                )
                            },
                            onClick = {
                                onRoleSelected(role)
                                roleDropdownExpanded = false
                            },
                            leadingIcon = {
                                if (role == selectedRole) {
                                    Icon(Icons.Default.Check, contentDescription = null, tint = BrandBlue)
                                }
                            }
                        )
                    }
                    if (onServerSettingsClick != null) {
                        HorizontalDivider()
                        DropdownMenuItem(
                            text = {
                                Text(
                                    text = if (isSwahili) "⚙️ Mipangilio ya Seva & Sync" else "⚙️ Server URL & Live Sync",
                                    fontSize = 13.sp,
                                    fontWeight = FontWeight.Bold,
                                    color = BrandBlue
                                )
                            },
                            leadingIcon = {
                                Icon(Icons.Default.Dns, contentDescription = null, tint = BrandBlue)
                            },
                            onClick = {
                                roleDropdownExpanded = false
                                onServerSettingsClick()
                            }
                        )
                    }
                    if (onOpenRegister != null) {
                        HorizontalDivider()
                        DropdownMenuItem(
                            text = {
                                Text(
                                    text = if (isSwahili) "+ Sajili Mtumiaji Mpya" else "+ Register New User",
                                    fontSize = 13.sp,
                                    fontWeight = FontWeight.Bold,
                                    color = BrandGreen
                                )
                            },
                            leadingIcon = {
                                Icon(Icons.Default.PersonAdd, contentDescription = null, tint = BrandGreen)
                            },
                            onClick = {
                                roleDropdownExpanded = false
                                onOpenRegister()
                            }
                        )
                    }
                    if (onLogoutClick != null) {
                        HorizontalDivider()
                        DropdownMenuItem(
                            text = {
                                Text(
                                    text = if (isSwahili) "Ondoka (Logout)" else "Logout / Sign Out",
                                    fontSize = 13.sp,
                                    fontWeight = FontWeight.Bold,
                                    color = RedWarning
                                )
                            },
                            leadingIcon = {
                                Icon(Icons.Default.Lock, contentDescription = null, tint = RedWarning)
                            },
                            onClick = {
                                roleDropdownExpanded = false
                                onLogoutClick()
                            },
                            modifier = Modifier.testTag("logout_menu_item")
                        )
                    }
                }
            }

            // Notification Bell with Badge
            IconButton(
                onClick = onNotificationClick,
                modifier = Modifier.testTag("notifications_button")
            ) {
                BadgedBox(
                    badge = {
                        if (unreadNotifCount > 0) {
                            Badge(containerColor = BrandOrange, contentColor = Color.White) {
                                Text(text = unreadNotifCount.toString())
                            }
                        }
                    }
                ) {
                    Icon(
                        imageVector = Icons.Outlined.Notifications,
                        contentDescription = "Notifications",
                        tint = Color.White
                    )
                }
            }
        }
    )
}

@Composable
fun BackendServerConfigDialog(
    isSwahili: Boolean,
    currentUrl: String,
    onDismiss: () -> Unit,
    onSaveAndSync: (String, (Boolean) -> Unit) -> Unit
) {
    var urlInput by remember { mutableStateOf(currentUrl) }
    var isTesting by remember { mutableStateOf(false) }
    var feedbackMessage by remember { mutableStateOf<String?>(null) }
    var isSuccess by remember { mutableStateOf<Boolean?>(null) }

    AlertDialog(
        onDismissRequest = onDismiss,
        properties = DialogProperties(
            usePlatformDefaultWidth = false,
            dismissOnClickOutside = false,
            dismissOnBackPress = true
        ),
        title = {
            Row(verticalAlignment = Alignment.CenterVertically) {
                Icon(Icons.Default.Dns, contentDescription = null, tint = BrandBlue)
                Spacer(modifier = Modifier.width(8.dp))
                Text(
                    text = if (isSwahili) "Mipangilio ya Seva (Backend Server)" else "Backend Server Connection",
                    fontWeight = FontWeight.Bold,
                    fontSize = 16.sp
                )
            }
        },
        text = {
            Column(verticalArrangement = Arrangement.spacedBy(10.dp)) {
                Text(
                    text = if (isSwahili)
                        "Weka anwani ya seva ya Laravel ili kupata data moja kwa moja (listings, users, courses)."
                    else
                        "Configure the Laravel backend URL to synchronize opportunities directly from the live database.",
                    fontSize = 12.sp,
                    color = SlateTextSecondary
                )

                OutlinedTextField(
                    value = urlInput,
                    onValueChange = {
                        urlInput = it
                        feedbackMessage = null
                        isSuccess = null
                    },
                    label = { Text("Server Base URL") },
                    singleLine = true,
                    colors = formTextFieldColors(),
                    modifier = Modifier.fillMaxWidth()
                )

                Text(
                    text = if (isSwahili) "Mipangilio ya Haraka (Presets):" else "Quick Presets:",
                    fontSize = 11.sp,
                    fontWeight = FontWeight.Bold
                )

                Row(
                    modifier = Modifier.fillMaxWidth(),
                    horizontalArrangement = Arrangement.spacedBy(6.dp)
                ) {
                    AssistChip(
                        onClick = { urlInput = "https://api.linkora.co.tz/" },
                        label = { Text("Linkora Cloud (api.linkora.co.tz)", fontSize = 10.sp, fontWeight = FontWeight.Bold) }
                    )
                }

                Row(
                    modifier = Modifier.fillMaxWidth(),
                    horizontalArrangement = Arrangement.spacedBy(6.dp)
                ) {
                    AssistChip(
                        onClick = { urlInput = "http://10.0.2.2:8000/" },
                        label = { Text("Emulator (10.0.2.2)", fontSize = 10.sp) }
                    )
                    AssistChip(
                        onClick = { urlInput = "http://127.0.0.1:8000/" },
                        label = { Text("ADB Reverse", fontSize = 10.sp) }
                    )
                }

                if (feedbackMessage != null) {
                    Surface(
                        color = if (isSuccess == true) BrandGreen.copy(alpha = 0.15f) else RedWarning.copy(alpha = 0.15f),
                        shape = RoundedCornerShape(8.dp),
                        modifier = Modifier.fillMaxWidth()
                    ) {
                        Text(
                            text = feedbackMessage!!,
                            color = if (isSuccess == true) BrandGreen else RedWarning,
                            fontSize = 12.sp,
                            fontWeight = FontWeight.Bold,
                            modifier = Modifier.padding(8.dp)
                        )
                    }
                }
            }
        },
        confirmButton = {
            Button(
                onClick = {
                    isTesting = true
                    feedbackMessage = null
                    onSaveAndSync(urlInput) { success ->
                        isTesting = false
                        isSuccess = success
                        if (success) {
                            feedbackMessage = if (isSwahili) "Imefanikiwa! Data zimesawazishwa kutoka kwenye seva." else "Success! Live opportunities synced from backend."
                        } else {
                            feedbackMessage = if (isSwahili) "Imeshindwa kuunganisha kwenye $urlInput. Hakikisha seva inafanya kazi." else "Failed to connect to $urlInput. Verify server is running."
                        }
                    }
                },
                enabled = !isTesting,
                colors = ButtonDefaults.buttonColors(containerColor = BrandBlue)
            ) {
                if (isTesting) {
                    CircularProgressIndicator(color = Color.White, modifier = Modifier.size(16.dp), strokeWidth = 2.dp)
                    Spacer(modifier = Modifier.width(6.dp))
                    Text(if (isSwahili) "Inasawazisha..." else "Syncing...")
                } else {
                    Icon(Icons.Default.CloudSync, contentDescription = null, modifier = Modifier.size(16.dp))
                    Spacer(modifier = Modifier.width(6.dp))
                    Text(if (isSwahili) "Hifadhi & Sawazisha" else "Save & Sync Now", color = Color.White)
                }
            }
        },
        dismissButton = {
            TextButton(onClick = onDismiss) {
                Text(if (isSwahili) "Funga" else "Close")
            }
        }
    )
}

enum class NavigationTab(val labelEn: String, val labelSw: String) {
    OPPORTUNITIES("Opportunities", "Fursa"),
    AGRI_EXCHANGE("Agri Exchange", "Kilimo & Zana"),
    REPUTATION("Passport", "Kitambulisho"),
    ESCROW("Payments", "Malipo Escrow"),
    HUB("Hub & Admin", "Elimu & Ziada")
}

@Composable
fun MarketLinkBottomBar(
    currentTab: NavigationTab,
    isSwahili: Boolean,
    onTabSelected: (NavigationTab) -> Unit
) {
    NavigationBar(
        containerColor = Color.White,
        tonalElevation = 8.dp,
        modifier = Modifier.windowInsetsPadding(WindowInsets.navigationBars)
    ) {
        NavigationTab.values().forEach { tab ->
            val selected = currentTab == tab
            val icon = when (tab) {
                NavigationTab.OPPORTUNITIES -> if (selected) Icons.Filled.Work else Icons.Outlined.WorkOutline
                NavigationTab.AGRI_EXCHANGE -> if (selected) Icons.Filled.Agriculture else Icons.Outlined.Agriculture
                NavigationTab.REPUTATION -> if (selected) Icons.Filled.Badge else Icons.Outlined.Badge
                NavigationTab.ESCROW -> if (selected) Icons.Filled.AccountBalanceWallet else Icons.Outlined.AccountBalanceWallet
                NavigationTab.HUB -> if (selected) Icons.Filled.School else Icons.Outlined.School
            }

            NavigationBarItem(
                selected = selected,
                onClick = { onTabSelected(tab) },
                icon = { Icon(imageVector = icon, contentDescription = tab.labelEn) },
                label = {
                    Text(
                        text = if (isSwahili) tab.labelSw else tab.labelEn,
                        fontSize = 10.sp,
                        fontWeight = if (selected) FontWeight.Bold else FontWeight.Normal
                    )
                },
                colors = NavigationBarItemDefaults.colors(
                    selectedIconColor = EmeraldPrimary,
                    selectedTextColor = EmeraldPrimary,
                    indicatorColor = GoldLight
                ),
                modifier = Modifier.testTag("nav_tab_${tab.name.lowercase()}")
            )
        }
    }
}
