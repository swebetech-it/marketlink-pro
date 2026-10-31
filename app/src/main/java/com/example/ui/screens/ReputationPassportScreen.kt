package com.example.ui.screens

import androidx.compose.foundation.background
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.lazy.LazyColumn
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
import com.example.data.model.UserProfile
import com.example.data.model.VerificationStatus
import com.example.ui.components.ReputationPassportCard
import com.example.ui.theme.*

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun ReputationPassportScreen(
    userProfile: UserProfile,
    isSwahili: Boolean,
    onRequestNidaVerification: (nidaNum: String) -> Unit,
    onVouchClick: () -> Unit,
    onLogoutClick: (() -> Unit)? = null
) {
    var showNidaDialog by remember { mutableStateOf(false) }
    var nidaInput by remember { mutableStateOf(userProfile.nidaNumber) }
    var snackbarHostState = remember { SnackbarHostState() }
    var showExportToast by remember { mutableStateOf(false) }

    LaunchedEffect(showExportToast) {
        if (showExportToast) {
            snackbarHostState.showSnackbar(
                if (isSwahili) "Pasipoti ya Sifa imepakuliwa kama PDF ya kidijitali!" else "Reputation Passport exported as verifiable Digital PDF!"
            )
            showExportToast = false
        }
    }

    Scaffold(
        snackbarHost = { SnackbarHost(snackbarHostState) }
    ) { innerPadding ->
        LazyColumn(
            contentPadding = PaddingValues(bottom = 80.dp),
            verticalArrangement = Arrangement.spacedBy(14.dp),
            modifier = Modifier
                .fillMaxSize()
                .padding(innerPadding)
                .padding(16.dp)
                .testTag("reputation_passport_screen")
        ) {
            // Main Digital Passport Card
            item {
                ReputationPassportCard(
                    profile = userProfile,
                    isSwahili = isSwahili,
                    onVouchClick = onVouchClick,
                    onExportPassportClick = { showExportToast = true }
                )
            }

            // NIDA Identity Verification Status Section
            item {
                Card(
                    shape = RoundedCornerShape(12.dp),
                    colors = CardDefaults.cardColors(containerColor = SlateSurface),
                    elevation = CardDefaults.cardElevation(defaultElevation = 2.dp)
                ) {
                    Column(modifier = Modifier.padding(14.dp)) {
                        Row(
                            modifier = Modifier.fillMaxWidth(),
                            horizontalArrangement = Arrangement.SpaceBetween,
                            verticalAlignment = Alignment.CenterVertically
                        ) {
                            Row(verticalAlignment = Alignment.CenterVertically) {
                                Icon(
                                    imageVector = Icons.Default.Badge,
                                    contentDescription = null,
                                    tint = EmeraldPrimary,
                                    modifier = Modifier.size(20.dp)
                                )
                                Spacer(modifier = Modifier.width(6.dp))
                                Text(
                                    text = if (isSwahili) "Uhakiki wa Kitambulisho cha Taifa (NIDA)" else "National NIDA Identity Verification",
                                    fontWeight = FontWeight.Bold,
                                    fontSize = 14.sp,
                                    color = SlateTextPrimary
                                )
                            }

                            Surface(
                                shape = CircleShape,
                                color = if (userProfile.verificationStatus == VerificationStatus.VERIFIED) GreenVerified else AmberPending
                            ) {
                                Text(
                                    text = if (userProfile.verificationStatus == VerificationStatus.VERIFIED) "VERIFIED" else "PENDING",
                                    fontSize = 10.sp,
                                    fontWeight = FontWeight.Bold,
                                    color = Color.White,
                                    modifier = Modifier.padding(horizontal = 8.dp, vertical = 2.dp)
                                )
                            }
                        }

                        Spacer(modifier = Modifier.height(8.dp))

                        if (userProfile.nidaNumber.isNotEmpty()) {
                            Text(
                                text = "NIDA No: ${userProfile.nidaNumber}",
                                fontSize = 13.sp,
                                fontWeight = FontWeight.Bold,
                                color = EmeraldPrimary
                            )
                        } else {
                            Text(
                                text = if (isSwahili)
                                    "Bado hujathibitisha Namba yako ya NIDA. Akaunti zilizothibitishwa na NIDA hupata fursa 3x zaidi!"
                                else
                                    "Your NIDA National ID is not linked yet. NIDA-verified accounts receive 3x higher employer priority!",
                                fontSize = 12.sp,
                                color = SlateTextSecondary
                            )
                        }

                        Spacer(modifier = Modifier.height(10.dp))

                        OutlinedButton(
                            onClick = { showNidaDialog = true },
                            shape = RoundedCornerShape(8.dp),
                            colors = ButtonDefaults.outlinedButtonColors(contentColor = EmeraldPrimary),
                            modifier = Modifier
                                .fillMaxWidth()
                                .testTag("update_nida_button")
                        ) {
                            Icon(Icons.Default.Fingerprint, contentDescription = null, modifier = Modifier.size(16.dp))
                            Spacer(modifier = Modifier.width(6.dp))
                            Text(
                                text = if (userProfile.nidaNumber.isNotEmpty())
                                    if (isSwahili) "Badilisha Namba ya NIDA" else "Update NIDA ID"
                                else
                                    if (isSwahili) "Thibitisha NIDA Sasa" else "Verify NIDA National ID",
                                fontWeight = FontWeight.Bold,
                                fontSize = 12.sp
                            )
                        }
                    }
                }
            }

            // Community Vouching & Trust Network
            item {
                Card(
                    shape = RoundedCornerShape(12.dp),
                    colors = CardDefaults.cardColors(containerColor = SlateSurface)
                ) {
                    Column(modifier = Modifier.padding(14.dp)) {
                        Row(verticalAlignment = Alignment.CenterVertically) {
                            Icon(Icons.Default.Groups, contentDescription = null, tint = EmeraldPrimary)
                            Spacer(modifier = Modifier.width(6.dp))
                            Text(
                                text = if (isSwahili) "Mtandao wa Dhamana za Kijamii (Vouches)" else "Community Vouching & Trust Network",
                                fontWeight = FontWeight.Bold,
                                fontSize = 14.sp
                            )
                        }

                        Spacer(modifier = Modifier.height(8.dp))

                        Text(
                            text = if (isSwahili)
                                "Dhamana ${userProfile.communityVouches} kutoka kwa wanajamii na waajiri waliothibitishwa."
                            else
                                "Supported by ${userProfile.communityVouches} community vouches from verified network members.",
                            fontSize = 12.sp,
                            color = SlateTextSecondary
                        )

                        Spacer(modifier = Modifier.height(10.dp))

                        if (userProfile.communityVouches > 0) {
                            Row(horizontalArrangement = Arrangement.spacedBy(6.dp)) {
                                repeat(userProfile.communityVouches.coerceAtMost(4)) { index ->
                                    Surface(
                                        color = Color(0xFFF1F5F9),
                                        shape = RoundedCornerShape(6.dp)
                                    ) {
                                        Row(
                                            modifier = Modifier.padding(horizontal = 8.dp, vertical = 4.dp),
                                            verticalAlignment = Alignment.CenterVertically
                                        ) {
                                            Icon(Icons.Default.CheckCircle, contentDescription = null, tint = GreenVerified, modifier = Modifier.size(12.dp))
                                            Spacer(modifier = Modifier.width(4.dp))
                                            Text(text = "Vouch #${index + 1}", fontSize = 10.sp, fontWeight = FontWeight.Medium)
                                        }
                                    }
                                }
                            }
                        } else {
                            Text(
                                text = if (isSwahili) "Huna dhamana ya jamii bado. Shiriki katika fursa na fanya kazi ili kupata dhamana."
                                else "No community vouches yet. Complete verified gigs to receive community endorsements.",
                                fontSize = 11.sp,
                                color = SlateTextSecondary
                            )
                        }
                    }
                }
            }

            // Work History & Verified Escrow Audit Log
            item {
                Card(
                    shape = RoundedCornerShape(12.dp),
                    colors = CardDefaults.cardColors(containerColor = SlateSurface)
                ) {
                    Column(modifier = Modifier.padding(14.dp)) {
                        Text(
                            text = if (isSwahili) "Kumbukumbu za Kazi na Escrow" else "Verified Work & Escrow History",
                            fontWeight = FontWeight.Bold,
                            fontSize = 14.sp
                        )

                        Spacer(modifier = Modifier.height(8.dp))

                        if (userProfile.completedGigs > 0 || userProfile.totalTransactions > 0) {
                            Column(modifier = Modifier.padding(vertical = 4.dp)) {
                                Text(
                                    text = if (isSwahili) "Jumla ya Kazi Zilizokamilika: ${userProfile.completedGigs}" else "Completed Gigs: ${userProfile.completedGigs}",
                                    fontWeight = FontWeight.Bold,
                                    fontSize = 13.sp
                                )
                                Text(
                                    text = if (isSwahili) "Miamala Iliyopitia Escrow: ${userProfile.totalTransactions}" else "Escrow Transactions: ${userProfile.totalTransactions}",
                                    fontSize = 11.sp,
                                    color = EmeraldPrimary
                                )
                            }
                        } else {
                            Text(
                                text = if (isSwahili) "Huna kumbukumbu za kazi zilizokamilika bado. Tuma maombi kwenye fursa ili kujenga historia yako ya uaminifu."
                                else "No verified work history yet. Apply to opportunities and complete escrow milestones to build your credit score.",
                                fontSize = 11.sp,
                                color = SlateTextSecondary,
                                modifier = Modifier.padding(vertical = 4.dp)
                            )
                        }
                    }
                }
            }

            if (onLogoutClick != null) {
                item {
                    OutlinedButton(
                        onClick = onLogoutClick,
                        colors = ButtonDefaults.outlinedButtonColors(contentColor = RedWarning),
                        shape = RoundedCornerShape(12.dp),
                        modifier = Modifier
                            .fillMaxWidth()
                            .height(48.dp)
                            .testTag("passport_logout_button")
                    ) {
                        Icon(Icons.Default.Lock, contentDescription = null, tint = RedWarning)
                        Spacer(modifier = Modifier.width(8.dp))
                        Text(
                            text = if (isSwahili) "Ondoka kwenye Akaunti (Logout)" else "Logout / Sign Out",
                            fontWeight = FontWeight.Bold,
                            fontSize = 14.sp
                        )
                    }
                }
            }
        }
    }

    // NIDA Verification Dialog
    if (showNidaDialog) {
        AlertDialog(
            onDismissRequest = { showNidaDialog = false },
            title = {
                Text(if (isSwahili) "Ingiza Namba ya NIDA" else "Enter NIDA National ID Number")
            },
            text = {
                Column {
                    Text(
                        text = if (isSwahili)
                            "Weka namba yako ya tarakimu 20 ya NIDA kwa ajili ya uhakiki wa moja kwa moja."
                        else
                            "Enter your 20-digit NIDA National Identification Number for automatic KYC verification.",
                        fontSize = 12.sp,
                        color = SlateTextSecondary
                    )
                    Spacer(modifier = Modifier.height(10.dp))
                    OutlinedTextField(
                        value = nidaInput,
                        onValueChange = { nidaInput = it },
                        label = { Text("NIDA ID Number") },
                        placeholder = { Text("19940812-21104-00001-26") },
                        singleLine = true,
                        colors = formTextFieldColors(),
                        modifier = Modifier
                            .fillMaxWidth()
                            .testTag("input_nida_number")
                    )
                }
            },
            confirmButton = {
                Button(
                    onClick = {
                        if (nidaInput.isNotBlank()) {
                            onRequestNidaVerification(nidaInput)
                            showNidaDialog = false
                        }
                    },
                    colors = ButtonDefaults.buttonColors(containerColor = EmeraldPrimary, contentColor = Color.White),
                    modifier = Modifier.testTag("submit_nida_button")
                ) {
                    Text(if (isSwahili) "Hifadhi NIDA" else "Verify & Save", color = Color.White)
                }
            },
            dismissButton = {
                TextButton(onClick = { showNidaDialog = false }) {
                    Text(if (isSwahili) "Ghairi" else "Cancel")
                }
            }
        )
    }
}
