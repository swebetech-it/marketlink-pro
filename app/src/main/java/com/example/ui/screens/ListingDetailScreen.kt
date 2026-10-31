package com.example.ui.screens

import androidx.compose.foundation.background
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
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.platform.testTag
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import com.example.data.model.Listing
import com.example.data.model.VerificationStatus
import com.example.ui.theme.*

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun ListingDetailScreen(
    listing: Listing,
    isSwahili: Boolean,
    isAiAnalyzing: Boolean,
    aiMatchScore: Int?,
    aiMatchExplanation: String?,
    aiFraudWarning: String?,
    generatedPitchText: String?,
    onBackClick: () -> Unit,
    onGeneratePitch: () -> Unit,
    onApplySubmit: (pitch: String) -> Unit,
    onDepositEscrowClick: (method: String) -> Unit
) {
    var pitchInput by remember { mutableStateOf("") }
    var showApplyDialog by remember { mutableStateOf(false) }
    var showDepositDialog by remember { mutableStateOf(false) }
    var selectedPaymentMethod by remember { mutableStateOf("M-Pesa Escrow") }

    LaunchedEffect(generatedPitchText) {
        if (!generatedPitchText.isNullOrEmpty()) {
            pitchInput = generatedPitchText
        }
    }

    Scaffold(
        topBar = {
            TopAppBar(
                title = { Text(if (isSwahili) "Maelezo ya Fursa" else "Opportunity Details") },
                navigationIcon = {
                    IconButton(onClick = onBackClick) {
                        Icon(Icons.Default.ArrowBack, contentDescription = "Back")
                    }
                },
                colors = TopAppBarDefaults.topAppBarColors(
                    containerColor = EmeraldPrimary,
                    titleContentColor = Color.White,
                    navigationIconContentColor = Color.White
                )
            )
        }
    ) { innerPadding ->
        Column(
            modifier = Modifier
                .fillMaxSize()
                .padding(innerPadding)
                .verticalScroll(rememberScrollState())
                .padding(16.dp)
                .testTag("listing_detail_screen"),
            verticalArrangement = Arrangement.spacedBy(14.dp)
        ) {
            // Main Listing Header Card
            Card(
                shape = RoundedCornerShape(12.dp),
                colors = CardDefaults.cardColors(containerColor = SlateSurface),
                elevation = CardDefaults.cardElevation(defaultElevation = 2.dp)
            ) {
                Column(modifier = Modifier.padding(16.dp)) {
                    Surface(
                        color = EmeraldPrimary.copy(alpha = 0.1f),
                        shape = RoundedCornerShape(6.dp)
                    ) {
                        Text(
                            text = if (isSwahili) listing.category.labelSw else listing.category.labelEn,
                            fontSize = 11.sp,
                            fontWeight = FontWeight.Bold,
                            color = EmeraldPrimary,
                            modifier = Modifier.padding(horizontal = 8.dp, vertical = 3.dp)
                        )
                    }

                    Spacer(modifier = Modifier.height(8.dp))

                    Text(
                        text = listing.title,
                        fontWeight = FontWeight.Bold,
                        fontSize = 18.sp,
                        color = SlateTextPrimary
                    )

                    Spacer(modifier = Modifier.height(8.dp))

                    Row(
                        modifier = Modifier.fillMaxWidth(),
                        horizontalArrangement = Arrangement.SpaceBetween,
                        verticalAlignment = Alignment.CenterVertically
                    ) {
                        Row(verticalAlignment = Alignment.CenterVertically) {
                            Surface(
                                shape = CircleShape,
                                color = EmeraldPrimary,
                                modifier = Modifier.size(32.dp)
                            ) {
                                Box(contentAlignment = Alignment.Center) {
                                    Text(
                                        text = listing.posterName.take(1),
                                        fontWeight = FontWeight.Bold,
                                        color = Color.White
                                    )
                                }
                            }
                            Spacer(modifier = Modifier.width(8.dp))
                            Column {
                                Row(verticalAlignment = Alignment.CenterVertically) {
                                    Text(
                                        text = listing.posterName,
                                        fontWeight = FontWeight.Bold,
                                        fontSize = 13.sp
                                    )
                                    if (listing.posterVerification == VerificationStatus.VERIFIED) {
                                        Spacer(modifier = Modifier.width(4.dp))
                                        Icon(
                                            Icons.Default.Verified,
                                            contentDescription = "Verified",
                                            tint = GreenVerified,
                                            modifier = Modifier.size(14.dp)
                                        )
                                    }
                                }
                                Text(
                                    text = "${if (isSwahili) "Imewekwa" else "Posted"}: ${listing.datePosted}",
                                    fontSize = 11.sp,
                                    color = SlateTextSecondary
                                )
                            }
                        }

                        Text(
                            text = listing.compensation,
                            fontSize = 15.sp,
                            fontWeight = FontWeight.ExtraBold,
                            color = EmeraldPrimary
                        )
                    }

                    Spacer(modifier = Modifier.height(12.dp))
                    Divider()
                    Spacer(modifier = Modifier.height(12.dp))

                    Text(
                        text = if (isSwahili) "Maelezo ya Kazi:" else "Job Description:",
                        fontWeight = FontWeight.Bold,
                        fontSize = 13.sp,
                        color = SlateTextPrimary
                    )
                    Spacer(modifier = Modifier.height(4.dp))
                    Text(
                        text = listing.description,
                        fontSize = 13.sp,
                        color = SlateTextSecondary,
                        lineHeight = 18.sp
                    )

                    if (!listing.agriCropType.isNullOrEmpty()) {
                        Spacer(modifier = Modifier.height(8.dp))
                        Text(
                            text = "🌱 Crop/Agri Focus: ${listing.agriCropType}",
                            fontSize = 12.sp,
                            fontWeight = FontWeight.Bold,
                            color = GreenVerified
                        )
                    }

                    if (!listing.equipmentDetails.isNullOrEmpty()) {
                        Spacer(modifier = Modifier.height(8.dp))
                        Text(
                            text = "🚜 Equipment Specs: ${listing.equipmentDetails}",
                            fontSize = 12.sp,
                            fontWeight = FontWeight.Bold,
                            color = AmberPending
                        )
                    }
                }
            }

            // Gemini AI Match Analysis Card
            Card(
                shape = RoundedCornerShape(12.dp),
                colors = CardDefaults.cardColors(containerColor = EmeraldDark)
            ) {
                Column(modifier = Modifier.padding(14.dp)) {
                    Row(
                        modifier = Modifier.fillMaxWidth(),
                        horizontalArrangement = Arrangement.SpaceBetween,
                        verticalAlignment = Alignment.CenterVertically
                    ) {
                        Row(verticalAlignment = Alignment.CenterVertically) {
                            Icon(
                                Icons.Default.AutoAwesome,
                                contentDescription = null,
                                tint = GoldAccent,
                                modifier = Modifier.size(20.dp)
                            )
                            Spacer(modifier = Modifier.width(6.dp))
                            Text(
                                text = if (isSwahili) "Tathmini ya Akili Mnemba (Gemini AI)" else "Gemini AI Compatibility Score",
                                fontWeight = FontWeight.Bold,
                                fontSize = 13.sp,
                                color = Color.White
                            )
                        }

                        Surface(
                            shape = CircleShape,
                            color = GoldAccent
                        ) {
                            Text(
                                text = "${aiMatchScore ?: listing.matchScore}% Match",
                                fontSize = 11.sp,
                                fontWeight = FontWeight.ExtraBold,
                                color = EmeraldDark,
                                modifier = Modifier.padding(horizontal = 8.dp, vertical = 3.dp)
                            )
                        }
                    }

                    Spacer(modifier = Modifier.height(8.dp))

                    if (isAiAnalyzing) {
                        Row(verticalAlignment = Alignment.CenterVertically) {
                            CircularProgressIndicator(
                                modifier = Modifier.size(16.dp),
                                color = GoldAccent,
                                strokeWidth = 2.dp
                            )
                            Spacer(modifier = Modifier.width(8.dp))
                            Text(
                                text = if (isSwahili) "Gemini inachambua mfumo wa ujuzi wako..." else "Gemini is analyzing your skills & NIDA profile...",
                                fontSize = 12.sp,
                                color = Color.White.copy(alpha = 0.8f)
                            )
                        }
                    } else {
                        Text(
                            text = aiMatchExplanation ?: "High compatibility based on your NIDA verification status and required skills (${listing.requiredSkills.joinToString()}).",
                            fontSize = 12.sp,
                            color = Color.White.copy(alpha = 0.9f)
                        )
                    }
                }
            }

            // Gemini Fraud Safety Monitor Callout
            if (!aiFraudWarning.isNullOrEmpty()) {
                Surface(
                    shape = RoundedCornerShape(10.dp),
                    color = RedWarning.copy(alpha = 0.1f),
                    border = androidx.compose.foundation.BorderStroke(1.dp, RedWarning)
                ) {
                    Row(
                        modifier = Modifier.padding(12.dp),
                        verticalAlignment = Alignment.CenterVertically
                    ) {
                        Icon(Icons.Default.Warning, contentDescription = null, tint = RedWarning)
                        Spacer(modifier = Modifier.width(8.dp))
                        Column {
                            Text(
                                text = if (isSwahili) "Tahadhari ya Usalama ya AI" else "AI Safety Risk Flag",
                                fontWeight = FontWeight.Bold,
                                fontSize = 12.sp,
                                color = RedWarning
                            )
                            Text(
                                text = aiFraudWarning,
                                fontSize = 11.sp,
                                color = RedWarning
                            )
                        }
                    }
                }
            }

            // Required Skills Chips
            Card(
                shape = RoundedCornerShape(12.dp),
                colors = CardDefaults.cardColors(containerColor = SlateSurface)
            ) {
                Column(modifier = Modifier.padding(14.dp)) {
                    Text(
                        text = if (isSwahili) "Ujuzi Unaoitajika:" else "Required Skills:",
                        fontWeight = FontWeight.Bold,
                        fontSize = 13.sp
                    )
                    Spacer(modifier = Modifier.height(8.dp))
                    Row(horizontalArrangement = Arrangement.spacedBy(6.dp)) {
                        listing.requiredSkills.forEach { skill ->
                            Surface(
                                color = EmeraldPrimary.copy(alpha = 0.1f),
                                shape = RoundedCornerShape(6.dp)
                            ) {
                                Text(
                                    text = "✓ $skill",
                                    fontSize = 12.sp,
                                    color = EmeraldPrimary,
                                    fontWeight = FontWeight.Medium,
                                    modifier = Modifier.padding(horizontal = 8.dp, vertical = 4.dp)
                                )
                            }
                        }
                    }
                }
            }

            // Bottom Action Buttons
            Row(
                modifier = Modifier.fillMaxWidth(),
                horizontalArrangement = Arrangement.spacedBy(10.dp)
            ) {
                Button(
                    onClick = { showApplyDialog = true },
                    colors = ButtonDefaults.buttonColors(containerColor = EmeraldPrimary),
                    shape = RoundedCornerShape(10.dp),
                    modifier = Modifier
                        .weight(1f)
                        .testTag("apply_now_button")
                ) {
                    Icon(Icons.Default.Send, contentDescription = null, modifier = Modifier.size(16.dp))
                    Spacer(modifier = Modifier.width(6.dp))
                    Text(
                        text = if (isSwahili) "Omba Fursa Hii" else "Apply to Listing",
                        fontWeight = FontWeight.Bold
                    )
                }

                if (listing.isEscrowRequired) {
                    Button(
                        onClick = { showDepositDialog = true },
                        colors = ButtonDefaults.buttonColors(containerColor = BlueEscrow),
                        shape = RoundedCornerShape(10.dp),
                        modifier = Modifier
                            .weight(1f)
                            .testTag("deposit_escrow_button")
                    ) {
                        Icon(Icons.Default.Lock, contentDescription = null, modifier = Modifier.size(16.dp))
                        Spacer(modifier = Modifier.width(6.dp))
                        Text(
                            text = if (isSwahili) "Weka Escrow" else "Lock Escrow",
                            fontWeight = FontWeight.Bold
                        )
                    }
                }
            }
        }
    }

    // Apply Modal Dialog
    if (showApplyDialog) {
        AlertDialog(
            onDismissRequest = { showApplyDialog = false },
            title = {
                Row(verticalAlignment = Alignment.CenterVertically) {
                    Icon(Icons.Default.Edit, contentDescription = null, tint = EmeraldPrimary)
                    Spacer(modifier = Modifier.width(6.dp))
                    Text(
                        text = if (isSwahili) "Tuma Ombi la Kazi" else "Submit Application",
                        fontSize = 16.sp,
                        fontWeight = FontWeight.Bold
                    )
                }
            },
            text = {
                Column(verticalArrangement = Arrangement.spacedBy(10.dp)) {
                    Text(
                        text = if (isSwahili)
                            "Weka maelezo ya ombi lako au utumie AI kukuandikia barua ya ombi iliyoboreshwa."
                        else
                            "Enter your pitch message or use Gemini AI to generate a polished cover letter.",
                        fontSize = 12.sp,
                        color = SlateTextSecondary
                    )

                    OutlinedButton(
                        onClick = onGeneratePitch,
                        shape = RoundedCornerShape(8.dp),
                        colors = ButtonDefaults.outlinedButtonColors(contentColor = EmeraldPrimary),
                        modifier = Modifier
                            .fillMaxWidth()
                            .testTag("generate_ai_pitch_button")
                    ) {
                        Icon(Icons.Default.AutoAwesome, contentDescription = null, tint = GoldAccent, modifier = Modifier.size(16.dp))
                        Spacer(modifier = Modifier.width(6.dp))
                        Text(
                            text = if (isSwahili) "Tengeneza Ombi kwa Gemini AI" else "Auto-Generate Pitch via Gemini",
                            fontSize = 12.sp,
                            fontWeight = FontWeight.Bold
                        )
                    }

                    OutlinedTextField(
                        value = pitchInput,
                        onValueChange = { pitchInput = it },
                        label = { Text(if (isSwahili) "Maelezo ya Ombi Lako" else "Pitch Message") },
                        maxLines = 5,
                        colors = formTextFieldColors(),
                        modifier = Modifier
                            .fillMaxWidth()
                            .testTag("input_pitch_message")
                    )
                }
            },
            confirmButton = {
                Button(
                    onClick = {
                        if (pitchInput.isNotBlank()) {
                            onApplySubmit(pitchInput)
                            showApplyDialog = false
                        }
                    },
                    colors = ButtonDefaults.buttonColors(containerColor = EmeraldPrimary),
                    modifier = Modifier.testTag("submit_application_button")
                ) {
                    Text(if (isSwahili) "Tuma Ombi" else "Submit Pitch")
                }
            },
            dismissButton = {
                TextButton(onClick = { showApplyDialog = false }) {
                    Text(if (isSwahili) "Ghairi" else "Cancel")
                }
            }
        )
    }

    // Escrow Deposit Dialog
    if (showDepositDialog) {
        AlertDialog(
            onDismissRequest = { showDepositDialog = false },
            title = {
                Row(verticalAlignment = Alignment.CenterVertically) {
                    Icon(Icons.Default.Lock, contentDescription = null, tint = BlueEscrow)
                    Spacer(modifier = Modifier.width(6.dp))
                    Text(
                        text = if (isSwahili) "Amana ya Escrow (M-Pesa / Benki)" else "Lock Escrow Deposit",
                        fontSize = 16.sp,
                        fontWeight = FontWeight.Bold
                    )
                }
            },
            text = {
                Column(verticalArrangement = Arrangement.spacedBy(10.dp)) {
                    Text(
                        text = "Amount: ${listing.compensation}",
                        fontWeight = FontWeight.Bold,
                        color = EmeraldPrimary,
                        fontSize = 14.sp
                    )

                    Text(
                        text = if (isSwahili)
                            "Pesa zitahifadhiwa kwa usalama hadi utakapothibitisha kukamilika kwa kazi."
                        else
                            "Funds will be held securely in Linkora Escrow until work milestone is verified.",
                        fontSize = 12.sp,
                        color = SlateTextSecondary
                    )

                    Text(
                        text = if (isSwahili) "Chagua Njia ya Malipo:" else "Select Payment Rail:",
                        fontSize = 12.sp,
                        fontWeight = FontWeight.Bold
                    )

                    listOf("M-Pesa Escrow 📱", "Tigo Pesa Escrow 📱", "Airtel Money Escrow 📱", "CRDB Bank Transfer 🏦", "NMB Bank Transfer 🏦").forEach { method ->
                        Row(
                            verticalAlignment = Alignment.CenterVertically,
                            modifier = Modifier.fillMaxWidth()
                        ) {
                            RadioButton(
                                selected = selectedPaymentMethod == method,
                                onClick = { selectedPaymentMethod = method }
                            )
                            Spacer(modifier = Modifier.width(4.dp))
                            Text(text = method, fontSize = 13.sp)
                        }
                    }
                }
            },
            confirmButton = {
                Button(
                    onClick = {
                        onDepositEscrowClick(selectedPaymentMethod)
                        showDepositDialog = false
                    },
                    colors = ButtonDefaults.buttonColors(containerColor = BlueEscrow),
                    modifier = Modifier.testTag("confirm_deposit_button")
                ) {
                    Text(if (isSwahili) "Thibitisha Amana" else "Confirm Escrow Deposit")
                }
            },
            dismissButton = {
                TextButton(onClick = { showDepositDialog = false }) {
                    Text(if (isSwahili) "Ghairi" else "Cancel")
                }
            }
        )
    }
}
