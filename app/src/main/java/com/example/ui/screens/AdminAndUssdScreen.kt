package com.example.ui.screens

import androidx.compose.foundation.background
import androidx.compose.foundation.border
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.items
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.foundation.shape.RoundedCornerShape
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
import com.example.data.model.*
import com.example.ui.components.BrandBlue
import com.example.ui.components.BrandDarkBg
import com.example.ui.components.BrandGreen
import com.example.ui.components.BrandOrange
import com.example.ui.components.MarketLinkLogoMark
import com.example.ui.components.MarketLinkLogoText
import com.example.ui.theme.*

@Composable
fun AdminAndUssdScreen(
    isSwahili: Boolean,
    allUsers: List<UserProfile>,
    currentUser: UserProfile,
    listings: List<Listing>,
    transactions: List<Transaction>,
    onOpenRegisterDialog: () -> Unit,
    onSwitchProfile: (UserProfile) -> Unit,
    onVerifyUser: (String, VerificationStatus) -> Unit,
    onUpdateUserRole: (String, UserRole) -> Unit,
    onDeleteUser: (String) -> Unit,
    onDeleteListing: (String) -> Unit,
    onReleaseEscrow: (String) -> Unit,
    onSimulateUssdDial: (code: String) -> Unit
) {
    var ussdActive by remember { mutableStateOf(false) }
    var ussdScreenState by remember { mutableStateOf(0) }
    var ussdInputCode by remember { mutableStateOf("*150*88#") }
    var selectedTab by remember { mutableStateOf(0) } // 0 = Web Portal, 1 = Users & Registration, 2 = Fraud & Moderation, 3 = USSD Gateway

    LazyColumn(
        contentPadding = PaddingValues(bottom = 80.dp),
        verticalArrangement = Arrangement.spacedBy(14.dp),
        modifier = Modifier
            .fillMaxSize()
            .padding(16.dp)
            .testTag("admin_ussd_screen")
    ) {
        // Top Header Banner - SuperAdmin Control Center
        item {
            Card(
                shape = RoundedCornerShape(16.dp),
                colors = CardDefaults.cardColors(containerColor = BrandDarkBg),
                elevation = CardDefaults.cardElevation(defaultElevation = 6.dp),
                modifier = Modifier.fillMaxWidth()
            ) {
                Column(modifier = Modifier.padding(16.dp)) {
                    Row(
                        modifier = Modifier.fillMaxWidth(),
                        horizontalArrangement = Arrangement.SpaceBetween,
                        verticalAlignment = Alignment.CenterVertically
                    ) {
                        Row(verticalAlignment = Alignment.CenterVertically) {
                            MarketLinkLogoMark(size = 28.dp)
                            Spacer(modifier = Modifier.width(8.dp))
                            MarketLinkLogoText(showTagline = false)
                        }

                        Surface(
                            shape = RoundedCornerShape(6.dp),
                            color = BrandOrange
                        ) {
                            Text(
                                text = "SUPERADMIN",
                                fontSize = 9.sp,
                                fontWeight = FontWeight.Black,
                                color = Color.White,
                                modifier = Modifier.padding(horizontal = 6.dp, vertical = 3.dp)
                            )
                        }
                    }

                    Spacer(modifier = Modifier.height(14.dp))

                    // Sub-Navigation Tabs in Admin Panel
                    Row(
                        modifier = Modifier.fillMaxWidth(),
                        horizontalArrangement = Arrangement.spacedBy(6.dp)
                    ) {
                        FilterChip(
                            selected = selectedTab == 0,
                            onClick = { selectedTab = 0 },
                            label = { Text(if (isSwahili) "Web Portal" else "Web Admin", fontSize = 11.sp) },
                            leadingIcon = { Icon(Icons.Default.Language, contentDescription = null, modifier = Modifier.size(14.dp)) },
                            colors = FilterChipDefaults.filterChipColors(
                                selectedContainerColor = EmeraldPrimary,
                                selectedLabelColor = Color.White
                            ),
                            modifier = Modifier.weight(1f)
                        )

                        FilterChip(
                            selected = selectedTab == 1,
                            onClick = { selectedTab = 1 },
                            label = { Text(if (isSwahili) "Watumiaji (${allUsers.size})" else "Users (${allUsers.size})", fontSize = 11.sp) },
                            leadingIcon = { Icon(Icons.Default.People, contentDescription = null, modifier = Modifier.size(14.dp)) },
                            colors = FilterChipDefaults.filterChipColors(
                                selectedContainerColor = BrandBlue,
                                selectedLabelColor = Color.White
                            ),
                            modifier = Modifier.weight(1f)
                        )

                        FilterChip(
                            selected = selectedTab == 2,
                            onClick = { selectedTab = 2 },
                            label = { Text(if (isSwahili) "AI Fraud" else "Fraud Guard", fontSize = 11.sp) },
                            leadingIcon = { Icon(Icons.Default.AdminPanelSettings, contentDescription = null, modifier = Modifier.size(14.dp)) },
                            colors = FilterChipDefaults.filterChipColors(
                                selectedContainerColor = BrandBlue,
                                selectedLabelColor = Color.White
                            ),
                            modifier = Modifier.weight(1f)
                        )

                        FilterChip(
                            selected = selectedTab == 3,
                            onClick = { selectedTab = 3 },
                            label = { Text("USSD", fontSize = 11.sp) },
                            leadingIcon = { Icon(Icons.Default.PhoneAndroid, contentDescription = null, modifier = Modifier.size(14.dp)) },
                            colors = FilterChipDefaults.filterChipColors(
                                selectedContainerColor = BrandBlue,
                                selectedLabelColor = Color.White
                            ),
                            modifier = Modifier.weight(1f)
                        )
                    }
                }
            }
        }

        // Section tab 0: Laravel Web Admin Management Portal
        if (selectedTab == 0) {
            item {
                Card(
                    shape = RoundedCornerShape(14.dp),
                    colors = CardDefaults.cardColors(containerColor = SlateSurface),
                    elevation = CardDefaults.cardElevation(defaultElevation = 2.dp),
                    modifier = Modifier
                        .fillMaxWidth()
                        .height(680.dp)
                ) {
                    AdminPortalScreen(
                        isSwahili = isSwahili
                    )
                }
            }
        }

        // Section tab 1: User Registration & Account Management Hub
        if (selectedTab == 1) {
            item {
                Card(
                    shape = RoundedCornerShape(14.dp),
                    colors = CardDefaults.cardColors(containerColor = SlateSurface),
                    elevation = CardDefaults.cardElevation(defaultElevation = 2.dp)
                ) {
                    Column(modifier = Modifier.padding(16.dp)) {
                        Row(
                            modifier = Modifier.fillMaxWidth(),
                            horizontalArrangement = Arrangement.SpaceBetween,
                            verticalAlignment = Alignment.CenterVertically
                        ) {
                            Column {
                                Text(
                                    text = if (isSwahili) "Usajili wa Akaunti Mpya" else "User Account Registration",
                                    fontWeight = FontWeight.ExtraBold,
                                    fontSize = 15.sp,
                                    color = SlateTextPrimary
                                )
                                Text(
                                    text = if (isSwahili) "Sajili watumiaji wapya au badilisha profile inayofanya kazi" else "Register new accounts or switch active working profile",
                                    fontSize = 11.sp,
                                    color = SlateTextSecondary
                                )
                            }

                            Button(
                                onClick = onOpenRegisterDialog,
                                colors = ButtonDefaults.buttonColors(containerColor = BrandBlue, contentColor = Color.White),
                                shape = RoundedCornerShape(8.dp),
                                modifier = Modifier.testTag("open_registration_dialog_button")
                            ) {
                                Icon(Icons.Default.PersonAdd, contentDescription = null, modifier = Modifier.size(16.dp))
                                Spacer(modifier = Modifier.width(4.dp))
                                Text(
                                    text = if (isSwahili) "+ Sajili" else "+ Register",
                                    fontSize = 12.sp,
                                    fontWeight = FontWeight.Bold
                                )
                            }
                        }

                        Spacer(modifier = Modifier.height(14.dp))
                        Divider()
                        Spacer(modifier = Modifier.height(14.dp))

                        Text(
                            text = if (isSwahili) "Orodha ya Watumiaji Waliosajiliwa (${allUsers.size}):" else "Registered Users Directory (${allUsers.size}):",
                            fontWeight = FontWeight.Bold,
                            fontSize = 13.sp,
                            color = SlateTextPrimary
                        )

                        Spacer(modifier = Modifier.height(10.dp))

                        allUsers.forEach { user ->
                            val isActive = user.id == currentUser.id

                            Surface(
                                shape = RoundedCornerShape(12.dp),
                                color = if (isActive) BrandBlue.copy(alpha = 0.08f) else Color.White,
                                border = androidx.compose.foundation.BorderStroke(
                                    1.dp,
                                    if (isActive) BrandBlue else Color(0xFFE2E8F0)
                                ),
                                modifier = Modifier
                                    .fillMaxWidth()
                                    .padding(vertical = 4.dp)
                            ) {
                                Column(modifier = Modifier.padding(12.dp)) {
                                    Row(
                                        modifier = Modifier.fillMaxWidth(),
                                        horizontalArrangement = Arrangement.SpaceBetween,
                                        verticalAlignment = Alignment.CenterVertically
                                    ) {
                                        Column(modifier = Modifier.weight(1f)) {
                                            Row(verticalAlignment = Alignment.CenterVertically) {
                                                Text(
                                                    text = user.fullName,
                                                    fontWeight = FontWeight.Bold,
                                                    fontSize = 14.sp
                                                )
                                                if (isActive) {
                                                    Spacer(modifier = Modifier.width(6.dp))
                                                    Surface(
                                                        color = BrandGreen,
                                                        shape = RoundedCornerShape(4.dp)
                                                    ) {
                                                        Text(
                                                            text = "ACTIVE PROFILE",
                                                            fontSize = 9.sp,
                                                            fontWeight = FontWeight.Bold,
                                                            color = Color.White,
                                                            modifier = Modifier.padding(horizontal = 4.dp, vertical = 1.dp)
                                                        )
                                                    }
                                                }
                                            }

                                            Text(
                                                text = "${user.phone} • ${user.email.ifBlank { "No email" }}",
                                                fontSize = 11.sp,
                                                color = SlateTextSecondary
                                            )
                                            Text(
                                                text = "Role: ${user.role.displayNameEn} • NIDA: ${user.nidaNumber.ifBlank { "Not Provided" }}",
                                                fontSize = 11.sp,
                                                fontWeight = FontWeight.Medium,
                                                color = BrandBlue
                                            )
                                            Text(
                                                text = "Location: ${user.location}",
                                                fontSize = 11.sp,
                                                color = SlateTextSecondary
                                            )
                                        }

                                        // Status Pill
                                        Surface(
                                            shape = RoundedCornerShape(6.dp),
                                            color = when (user.verificationStatus) {
                                                VerificationStatus.VERIFIED -> BrandGreen.copy(alpha = 0.15f)
                                                VerificationStatus.PENDING -> BrandOrange.copy(alpha = 0.15f)
                                                VerificationStatus.UNVERIFIED -> Color.LightGray.copy(alpha = 0.2f)
                                            }
                                        ) {
                                            Text(
                                                text = user.verificationStatus.labelEn,
                                                fontSize = 10.sp,
                                                fontWeight = FontWeight.Bold,
                                                color = when (user.verificationStatus) {
                                                    VerificationStatus.VERIFIED -> BrandGreen
                                                    VerificationStatus.PENDING -> BrandOrange
                                                    VerificationStatus.UNVERIFIED -> Color.DarkGray
                                                },
                                                modifier = Modifier.padding(horizontal = 6.dp, vertical = 2.dp)
                                            )
                                        }
                                    }

                                    Spacer(modifier = Modifier.height(8.dp))

                                    // Action bar for user management
                                    Row(
                                        horizontalArrangement = Arrangement.spacedBy(6.dp),
                                        verticalAlignment = Alignment.CenterVertically,
                                        modifier = Modifier.fillMaxWidth()
                                    ) {
                                        if (!isActive) {
                                            Button(
                                                onClick = { onSwitchProfile(user) },
                                                colors = ButtonDefaults.buttonColors(containerColor = BrandGreen),
                                                shape = RoundedCornerShape(6.dp),
                                                contentPadding = PaddingValues(horizontal = 10.dp, vertical = 4.dp)
                                            ) {
                                                Icon(Icons.Default.SwapHoriz, contentDescription = null, modifier = Modifier.size(14.dp))
                                                Spacer(modifier = Modifier.width(4.dp))
                                                Text(if (isSwahili) "Tumia Akaunti Hii" else "Switch Profile", fontSize = 11.sp)
                                            }
                                        }

                                        if (user.verificationStatus != VerificationStatus.VERIFIED) {
                                            OutlinedButton(
                                                onClick = { onVerifyUser(user.id, VerificationStatus.VERIFIED) },
                                                shape = RoundedCornerShape(6.dp),
                                                contentPadding = PaddingValues(horizontal = 8.dp, vertical = 4.dp)
                                            ) {
                                                Icon(Icons.Default.CheckCircle, contentDescription = null, tint = BrandGreen, modifier = Modifier.size(14.dp))
                                                Spacer(modifier = Modifier.width(4.dp))
                                                Text("Approve NIDA", fontSize = 11.sp, color = BrandGreen)
                                            }
                                        }

                                        if (user.role != UserRole.ADMIN) {
                                            OutlinedButton(
                                                onClick = { onUpdateUserRole(user.id, UserRole.ADMIN) },
                                                shape = RoundedCornerShape(6.dp),
                                                contentPadding = PaddingValues(horizontal = 8.dp, vertical = 4.dp)
                                            ) {
                                                Text("Promote Admin", fontSize = 11.sp, color = BrandBlue)
                                            }
                                        }

                                        if (user.id != "current_user" && user.id != "usr_admin") {
                                            IconButton(
                                                onClick = { onDeleteUser(user.id) },
                                                modifier = Modifier.size(32.dp)
                                            ) {
                                                Icon(Icons.Default.Delete, contentDescription = "Delete User", tint = RedWarning, modifier = Modifier.size(16.dp))
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        // Section tab 2: Admin Fraud Detection, Listing Moderation & Escrow Arbitration
        if (selectedTab == 2) {
            item {
                Card(
                    shape = RoundedCornerShape(14.dp),
                    colors = CardDefaults.cardColors(containerColor = SlateSurface)
                ) {
                    Column(modifier = Modifier.padding(16.dp)) {
                        Row(
                            modifier = Modifier.fillMaxWidth(),
                            horizontalArrangement = Arrangement.SpaceBetween,
                            verticalAlignment = Alignment.CenterVertically
                        ) {
                            Row(verticalAlignment = Alignment.CenterVertically) {
                                Icon(Icons.Default.Security, contentDescription = null, tint = RedWarning)
                                Spacer(modifier = Modifier.width(6.dp))
                                Text(
                                    text = "Gemini AI Fraud & Listing Moderation Queue",
                                    fontWeight = FontWeight.Bold,
                                    fontSize = 14.sp
                                )
                            }

                            Surface(
                                shape = CircleShape,
                                color = BrandGreen
                            ) {
                                Text(
                                    text = "AI MONITORED",
                                    fontSize = 9.sp,
                                    fontWeight = FontWeight.Bold,
                                    color = Color.White,
                                    modifier = Modifier.padding(horizontal = 6.dp, vertical = 2.dp)
                                )
                            }
                        }

                        Spacer(modifier = Modifier.height(10.dp))

                        listings.forEach { listing ->
                            Surface(
                                shape = RoundedCornerShape(10.dp),
                                color = Color.White,
                                border = androidx.compose.foundation.BorderStroke(1.dp, Color(0xFFE2E8F0)),
                                modifier = Modifier
                                    .fillMaxWidth()
                                    .padding(vertical = 4.dp)
                            ) {
                                Row(
                                    modifier = Modifier
                                        .fillMaxWidth()
                                        .padding(10.dp),
                                    horizontalArrangement = Arrangement.SpaceBetween,
                                    verticalAlignment = Alignment.CenterVertically
                                ) {
                                    Column(modifier = Modifier.weight(1f)) {
                                        Text(text = listing.title, fontWeight = FontWeight.Bold, fontSize = 13.sp)
                                        Text(
                                            text = "Posted by: ${listing.posterName} • ${listing.compensation}",
                                            fontSize = 11.sp,
                                            color = SlateTextSecondary
                                        )
                                        Row(verticalAlignment = Alignment.CenterVertically) {
                                            Text(
                                                text = "AI Risk Score: ${listing.fraudRiskScore}%",
                                                fontSize = 10.sp,
                                                fontWeight = FontWeight.Bold,
                                                color = if (listing.fraudRiskScore > 20) RedWarning else BrandGreen
                                            )
                                            if (listing.isEscrowRequired) {
                                                Spacer(modifier = Modifier.width(6.dp))
                                                Text("• Escrow Protected", fontSize = 10.sp, color = BrandBlue)
                                            }
                                        }
                                    }

                                    Button(
                                        onClick = { onDeleteListing(listing.id) },
                                        colors = ButtonDefaults.buttonColors(containerColor = RedWarning),
                                        shape = RoundedCornerShape(6.dp),
                                        contentPadding = PaddingValues(horizontal = 8.dp, vertical = 4.dp)
                                    ) {
                                        Icon(Icons.Default.DeleteForever, contentDescription = null, modifier = Modifier.size(14.dp))
                                        Spacer(modifier = Modifier.width(4.dp))
                                        Text("Takedown", fontSize = 11.sp)
                                    }
                                }
                            }
                        }

                        Spacer(modifier = Modifier.height(14.dp))
                        Divider()
                        Spacer(modifier = Modifier.height(14.dp))

                        Text(
                            text = "Escrow Disputes & Arbitration Center:",
                            fontWeight = FontWeight.Bold,
                            fontSize = 13.sp,
                            color = SlateTextPrimary
                        )

                        Spacer(modifier = Modifier.height(8.dp))

                        if (transactions.isEmpty()) {
                            Text("No active escrow transactions under arbitration.", fontSize = 11.sp, color = SlateTextSecondary)
                        } else {
                            transactions.forEach { tx ->
                                Surface(
                                    shape = RoundedCornerShape(10.dp),
                                    color = Color.White,
                                    border = androidx.compose.foundation.BorderStroke(1.dp, Color(0xFFE2E8F0)),
                                    modifier = Modifier
                                        .fillMaxWidth()
                                        .padding(vertical = 4.dp)
                                ) {
                                    Column(modifier = Modifier.padding(10.dp)) {
                                        Row(
                                            modifier = Modifier.fillMaxWidth(),
                                            horizontalArrangement = Arrangement.SpaceBetween
                                        ) {
                                            Text(text = tx.listingTitle, fontWeight = FontWeight.Bold, fontSize = 12.sp)
                                            Text(
                                                text = "TZS ${String.format("%,d", tx.amountTzs)}",
                                                fontWeight = FontWeight.ExtraBold,
                                                color = BrandBlue,
                                                fontSize = 12.sp
                                            )
                                        }

                                        Text(
                                            text = "Payer: ${tx.payerName} → Payee: ${tx.payeeName} (${tx.paymentMethod})",
                                            fontSize = 11.sp,
                                            color = SlateTextSecondary
                                        )

                                        Spacer(modifier = Modifier.height(6.dp))

                                        Row(
                                            horizontalArrangement = Arrangement.SpaceBetween,
                                            verticalAlignment = Alignment.CenterVertically,
                                            modifier = Modifier.fillMaxWidth()
                                        ) {
                                            Surface(
                                                color = BrandOrange.copy(alpha = 0.15f),
                                                shape = RoundedCornerShape(4.dp)
                                            ) {
                                                Text(
                                                    text = tx.escrowStatus.labelEn,
                                                    fontSize = 10.sp,
                                                    fontWeight = FontWeight.Bold,
                                                    color = BrandOrange,
                                                    modifier = Modifier.padding(horizontal = 6.dp, vertical = 2.dp)
                                                )
                                            }

                                            if (tx.escrowStatus == EscrowStatus.HELD_IN_ESCROW) {
                                                Button(
                                                    onClick = { onReleaseEscrow(tx.id) },
                                                    colors = ButtonDefaults.buttonColors(containerColor = BrandGreen),
                                                    shape = RoundedCornerShape(6.dp),
                                                    contentPadding = PaddingValues(horizontal = 10.dp, vertical = 2.dp)
                                                ) {
                                                    Text("SuperAdmin Release", fontSize = 10.sp)
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        // Section tab 3: USSD Feature Phone Simulator Gateway
        if (selectedTab == 3) {
            item {
                Card(
                    shape = RoundedCornerShape(14.dp),
                    colors = CardDefaults.cardColors(containerColor = Color(0xFF1E293B)),
                    elevation = CardDefaults.cardElevation(defaultElevation = 4.dp),
                    modifier = Modifier.fillMaxWidth()
                ) {
                    Column(modifier = Modifier.padding(16.dp)) {
                        Row(
                            modifier = Modifier.fillMaxWidth(),
                            horizontalArrangement = Arrangement.SpaceBetween,
                            verticalAlignment = Alignment.CenterVertically
                        ) {
                            Row(verticalAlignment = Alignment.CenterVertically) {
                                Icon(Icons.Default.PhoneAndroid, contentDescription = null, tint = GoldAccent)
                                Spacer(modifier = Modifier.width(6.dp))
                                Text(
                                    text = "USSD Feature Phone Simulator (*150*88#)",
                                    fontWeight = FontWeight.Bold,
                                    fontSize = 14.sp,
                                    color = Color.White
                                )
                            }

                            Surface(
                                shape = RoundedCornerShape(4.dp),
                                color = GoldAccent
                            ) {
                                Text(
                                    text = "OFFLINE READY",
                                    fontSize = 9.sp,
                                    fontWeight = FontWeight.Bold,
                                    color = EmeraldDark,
                                    modifier = Modifier.padding(horizontal = 4.dp, vertical = 2.dp)
                                )
                            }
                        }

                        Spacer(modifier = Modifier.height(10.dp))

                        if (!ussdActive) {
                            OutlinedTextField(
                                value = ussdInputCode,
                                onValueChange = { ussdInputCode = it },
                                label = { Text("USSD Dial Code", color = Color.LightGray) },
                                colors = OutlinedTextFieldDefaults.colors(
                                    focusedTextColor = Color.White,
                                    unfocusedTextColor = Color.White,
                                    focusedBorderColor = GoldAccent,
                                    unfocusedBorderColor = Color.Gray
                                ),
                                singleLine = true,
                                modifier = Modifier.fillMaxWidth()
                            )

                            Spacer(modifier = Modifier.height(8.dp))

                            Button(
                                onClick = {
                                    ussdActive = true
                                    ussdScreenState = 0
                                    onSimulateUssdDial(ussdInputCode)
                                },
                                colors = ButtonDefaults.buttonColors(containerColor = GoldAccent, contentColor = EmeraldDark),
                                shape = RoundedCornerShape(8.dp),
                                modifier = Modifier
                                    .fillMaxWidth()
                                    .testTag("dial_ussd_button")
                            ) {
                                Icon(Icons.Default.Call, contentDescription = null, modifier = Modifier.size(16.dp))
                                Spacer(modifier = Modifier.width(6.dp))
                                Text("Dial USSD Menu", fontWeight = FontWeight.Bold)
                            }
                        } else {
                            Surface(
                                color = Color(0xFF0F172A),
                                shape = RoundedCornerShape(8.dp),
                                border = androidx.compose.foundation.BorderStroke(1.dp, GoldAccent),
                                modifier = Modifier.fillMaxWidth()
                            ) {
                                Column(modifier = Modifier.padding(12.dp)) {
                                    Text(
                                        text = "=== MarketLink Pro USSD ===",
                                        color = GoldAccent,
                                        fontSize = 12.sp,
                                        fontWeight = FontWeight.Bold
                                    )

                                    when (ussdScreenState) {
                                        0 -> {
                                            Text("1. Angalia Fursa za Kazi", color = Color.White, fontSize = 12.sp)
                                            Text("2. Angalia Salio la Escrow", color = Color.White, fontSize = 12.sp)
                                            Text("3. Thibitisha NIDA ID", color = Color.White, fontSize = 12.sp)
                                            Text("4. Msaada wa Swebetech", color = Color.White, fontSize = 12.sp)
                                        }
                                        1 -> {
                                            Text("Fursa za Hivi Karibuni:", color = GoldAccent, fontSize = 12.sp)
                                            Text("• Fursa zilizopostiwa kwenye mfumo", color = Color.White, fontSize = 11.sp)
                                            Text("0. Rudi Nyuma", color = Color.Gray, fontSize = 10.sp)
                                        }
                                        2 -> {
                                            Text("Salio Lako la Escrow:", color = GoldAccent, fontSize = 12.sp)
                                            Text("Huna malipo yanayosubiri katika Escrow", color = Color.White, fontSize = 11.sp)
                                            Text("0. Rudi Nyuma", color = Color.Gray, fontSize = 10.sp)
                                        }
                                    }

                                    Spacer(modifier = Modifier.height(10.dp))

                                    Row(
                                        horizontalArrangement = Arrangement.spacedBy(6.dp),
                                        modifier = Modifier.fillMaxWidth()
                                    ) {
                                        Button(
                                            onClick = { ussdScreenState = 1 },
                                            colors = ButtonDefaults.buttonColors(containerColor = EmeraldPrimary),
                                            modifier = Modifier.weight(1f)
                                        ) {
                                            Text("Option 1", fontSize = 10.sp)
                                        }
                                        Button(
                                            onClick = { ussdScreenState = 2 },
                                            colors = ButtonDefaults.buttonColors(containerColor = EmeraldPrimary),
                                            modifier = Modifier.weight(1f)
                                        ) {
                                            Text("Option 2", fontSize = 10.sp)
                                        }
                                        Button(
                                            onClick = { ussdActive = false },
                                            colors = ButtonDefaults.buttonColors(containerColor = RedWarning),
                                            modifier = Modifier.weight(1f)
                                        ) {
                                            Text("Exit", fontSize = 10.sp)
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        // Section 3: Aggregated Labor Market Intelligence Report
        item {
            Card(
                shape = RoundedCornerShape(12.dp),
                colors = CardDefaults.cardColors(containerColor = SlateSurface)
            ) {
                Column(modifier = Modifier.padding(14.dp)) {
                    Text(
                        text = "Labor Market Intelligence & Analytics (Swebetech 2026)",
                        fontWeight = FontWeight.Bold,
                        fontSize = 14.sp
                    )

                    Spacer(modifier = Modifier.height(6.dp))

                    Text(
                        text = "Aggregated data provided for policy stakeholders, universities, and financial partners in Tanzania.",
                        fontSize = 11.sp,
                        color = SlateTextSecondary
                    )

                    Spacer(modifier = Modifier.height(10.dp))

                    Row(
                        modifier = Modifier.fillMaxWidth(),
                        horizontalArrangement = Arrangement.SpaceBetween
                    ) {
                        MetricBlock(label = "Total Active Gigs", value = "${listings.size + 1835}")
                        MetricBlock(label = "Registered Users", value = "${allUsers.size}")
                        MetricBlock(label = "NIDA Verification", value = "92.4%")
                    }
                }
            }
        }
    }
}

@Composable
private fun MetricBlock(label: String, value: String) {
    Column(horizontalAlignment = Alignment.CenterHorizontally) {
        Text(text = value, fontWeight = FontWeight.ExtraBold, fontSize = 15.sp, color = BrandBlue)
        Text(text = label, fontSize = 10.sp, color = SlateTextSecondary)
    }
}
