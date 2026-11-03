package com.example.ui.screens

import android.content.ClipData
import android.content.ClipboardManager
import android.content.Context
import android.content.Intent
import androidx.compose.foundation.background
import androidx.compose.foundation.border
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.grid.GridCells
import androidx.compose.foundation.lazy.grid.LazyVerticalGrid
import androidx.compose.foundation.lazy.grid.items
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.*
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.platform.testTag
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.compose.ui.window.Dialog
import coil.compose.AsyncImage
import com.example.data.model.UserProfile
import com.example.data.model.VerificationStatus
import com.example.ui.components.BrandBlue
import com.example.ui.components.BrandGreen
import com.example.ui.components.BrandOrange
import com.example.ui.components.ReputationPassportCard
import com.example.ui.theme.*
import kotlinx.coroutines.launch

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun ReputationPassportScreen(
    userProfile: UserProfile,
    isSwahili: Boolean,
    onRequestNidaVerification: (nidaNum: String) -> Unit,
    onVouchClick: () -> Unit,
    onUploadAvatar: ((String) -> Unit)? = null,
    onLogoutClick: (() -> Unit)? = null
) {
    val context = LocalContext.current
    val coroutineScope = rememberCoroutineScope()
    var showNidaDialog by remember { mutableStateOf(false) }
    var showAvatarDialog by remember { mutableStateOf(false) }
    var nidaInput by remember { mutableStateOf(userProfile.nidaNumber) }
    val snackbarHostState = remember { SnackbarHostState() }
    var showExportToast by remember { mutableStateOf(false) }

    val presetAvatars = listOf(
        "https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=256&q=80",
        "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=256&q=80",
        "https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=256&q=80",
        "https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=256&q=80",
        "https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=256&q=80",
        "https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=256&q=80"
    )

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
            // Main Digital Passport Card with Avatar Click
            item {
                ReputationPassportCard(
                    profile = userProfile,
                    isSwahili = isSwahili,
                    onVouchClick = onVouchClick,
                    onExportPassportClick = { showExportToast = true },
                    onAvatarClick = { showAvatarDialog = true }
                )
            }

            // Referral Program: Invite & Earn Card
            item {
                Card(
                    shape = RoundedCornerShape(14.dp),
                    colors = CardDefaults.cardColors(containerColor = SlateSurface),
                    elevation = CardDefaults.cardElevation(defaultElevation = 2.dp),
                    modifier = Modifier.fillMaxWidth().testTag("referral_program_card")
                ) {
                    Column(modifier = Modifier.padding(16.dp)) {
                        Row(
                            verticalAlignment = Alignment.CenterVertically,
                            horizontalArrangement = Arrangement.SpaceBetween,
                            modifier = Modifier.fillMaxWidth()
                        ) {
                            Row(verticalAlignment = Alignment.CenterVertically) {
                                Surface(
                                    shape = CircleShape,
                                    color = BrandOrange.copy(alpha = 0.15f),
                                    modifier = Modifier.size(36.dp)
                                ) {
                                    Box(contentAlignment = Alignment.Center) {
                                        Icon(
                                            imageVector = Icons.Default.CardGiftcard,
                                            contentDescription = null,
                                            tint = BrandOrange,
                                            modifier = Modifier.size(20.dp)
                                        )
                                    }
                                }
                                Spacer(modifier = Modifier.width(10.dp))
                                Column {
                                    Text(
                                        text = if (isSwahili) "Alika Marafiki & Jipatie Bonasi" else "Invite & Earn Rewards",
                                        fontWeight = FontWeight.Bold,
                                        fontSize = 15.sp,
                                        color = SlateTextPrimary
                                    )
                                    Text(
                                        text = if (isSwahili) "Pata TZS 2,000 + alama 25 za sifa kwa kila rafiki" else "Get TZS 2,000 + 25 reputation points per referral",
                                        fontSize = 11.sp,
                                        color = SlateTextSecondary
                                    )
                                }
                            }
                        }

                        Spacer(modifier = Modifier.height(14.dp))

                        // Referral Code Display Box
                        val code = userProfile.referralCode.ifBlank { "LNK-LINKORA" }
                        Surface(
                            shape = RoundedCornerShape(10.dp),
                            color = SlateBackground,
                            border = androidx.compose.foundation.BorderStroke(1.dp, BrandBlue.copy(alpha = 0.4f)),
                            modifier = Modifier.fillMaxWidth()
                        ) {
                            Row(
                                modifier = Modifier
                                    .fillMaxWidth()
                                    .padding(horizontal = 14.dp, vertical = 10.dp),
                                horizontalArrangement = Arrangement.SpaceBetween,
                                verticalAlignment = Alignment.CenterVertically
                            ) {
                                Column {
                                    Text(
                                        text = if (isSwahili) "Namba Yako ya Mwaliko" else "Your Referral Code",
                                        fontSize = 10.sp,
                                        color = SlateTextSecondary,
                                        fontWeight = FontWeight.Medium
                                    )
                                    Text(
                                        text = code,
                                        fontSize = 18.sp,
                                        fontWeight = FontWeight.ExtraBold,
                                        letterSpacing = 1.sp,
                                        color = BrandBlue
                                    )
                                }

                                Row {
                                    IconButton(
                                        onClick = {
                                            val clipboard = context.getSystemService(Context.CLIPBOARD_SERVICE) as ClipboardManager
                                            clipboard.setPrimaryClip(ClipData.newPlainText("Linkora Referral Code", code))
                                            coroutineScope.launch {
                                                snackbarHostState.showSnackbar(
                                                    if (isSwahili) "Namba ya mwaliko ($code) imenakiliwa!" else "Referral code ($code) copied to clipboard!"
                                                )
                                            }
                                        }
                                    ) {
                                        Icon(Icons.Default.ContentCopy, contentDescription = "Copy Code", tint = BrandBlue)
                                    }

                                    IconButton(
                                        onClick = {
                                            val shareMessage = if (isSwahili) {
                                                "Habari! Jiunge na Linkora (MarketLink Pro) upate kazi, vibarua na fursa za biashara Tanzania nzima. Tumia namba yangu ya mwaliko *$code* kupata bonasi: https://linkora.co.tz"
                                            } else {
                                                "Hello! Join Linkora (MarketLink Pro) to discover verified jobs and skilled gigs in Tanzania. Use my referral code *$code* to get bonus rewards: https://linkora.co.tz"
                                            }
                                            val sendIntent = Intent().apply {
                                                action = Intent.ACTION_SEND
                                                putExtra(Intent.EXTRA_TEXT, shareMessage)
                                                type = "text/plain"
                                            }
                                            context.startActivity(Intent.createChooser(sendIntent, if (isSwahili) "Shiriki Namba ya Mwaliko" else "Share Referral Code"))
                                        }
                                    ) {
                                        Icon(Icons.Default.Share, contentDescription = "Share Code", tint = BrandGreen)
                                    }
                                }
                            }
                        }

                        Spacer(modifier = Modifier.height(12.dp))

                        // Stats Summary
                        Row(
                            modifier = Modifier.fillMaxWidth(),
                            horizontalArrangement = Arrangement.SpaceBetween
                        ) {
                            Column(horizontalAlignment = Alignment.CenterHorizontally, modifier = Modifier.weight(1f)) {
                                Text(
                                    text = "${userProfile.referralCount}",
                                    fontWeight = FontWeight.ExtraBold,
                                    fontSize = 16.sp,
                                    color = BrandGreen
                                )
                                Text(
                                    text = if (isSwahili) "Waliojiunga" else "Invited",
                                    fontSize = 11.sp,
                                    color = SlateTextSecondary
                                )
                            }
                            Column(horizontalAlignment = Alignment.CenterHorizontally, modifier = Modifier.weight(1f)) {
                                Text(
                                    text = "TZS ${userProfile.referralEarningsTzs}",
                                    fontWeight = FontWeight.ExtraBold,
                                    fontSize = 16.sp,
                                    color = BrandOrange
                                )
                                Text(
                                    text = if (isSwahili) "Mapato" else "Earnings",
                                    fontSize = 11.sp,
                                    color = SlateTextSecondary
                                )
                            }
                            Column(horizontalAlignment = Alignment.CenterHorizontally, modifier = Modifier.weight(1f)) {
                                Text(
                                    text = "+${userProfile.referralCount * 25}",
                                    fontWeight = FontWeight.ExtraBold,
                                    fontSize = 16.sp,
                                    color = BrandBlue
                                )
                                Text(
                                    text = if (isSwahili) "Alama za Sifa" else "Reputation",
                                    fontSize = 11.sp,
                                    color = SlateTextSecondary
                                )
                            }
                        }
                    }
                }
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
                                color = when (userProfile.verificationStatus) {
                                    VerificationStatus.VERIFIED -> GreenVerified
                                    VerificationStatus.PENDING -> AmberPending
                                    VerificationStatus.UNVERIFIED -> RedWarning
                                },
                                modifier = Modifier.padding(start = 4.dp)
                            ) {
                                Text(
                                    text = if (isSwahili) userProfile.verificationStatus.labelSw else userProfile.verificationStatus.labelEn,
                                    fontSize = 11.sp,
                                    fontWeight = FontWeight.Bold,
                                    color = Color.White,
                                    modifier = Modifier.padding(horizontal = 8.dp, vertical = 4.dp)
                                )
                            }
                        }

                        Spacer(modifier = Modifier.height(8.dp))

                        Text(
                            text = if (userProfile.verificationStatus == VerificationStatus.VERIFIED)
                                if (isSwahili) "NIDA: ${userProfile.nidaNumber} (Imethibitishwa kupitia API ya Taifa)" else "NIDA ID: ${userProfile.nidaNumber} (Verified via National Database)"
                            else
                                if (isSwahili) "Unganisha NIDA yako kupandisha sifa na kuongeza nafasi ya kuaminiwa na waajiri na wateja." else "Connect your official NIDA ID to boost your score to Tier 1 Trust status.",
                            fontSize = 12.sp,
                            color = SlateTextSecondary
                        )

                        if (userProfile.verificationStatus != VerificationStatus.VERIFIED) {
                            Spacer(modifier = Modifier.height(10.dp))
                            Button(
                                onClick = { showNidaDialog = true },
                                colors = ButtonDefaults.buttonColors(containerColor = EmeraldPrimary, contentColor = Color.White),
                                shape = RoundedCornerShape(8.dp),
                                modifier = Modifier.testTag("verify_nida_button")
                            ) {
                                Icon(Icons.Default.VerifiedUser, contentDescription = null, modifier = Modifier.size(16.dp))
                                Spacer(modifier = Modifier.width(6.dp))
                                Text(if (isSwahili) "Thibitisha NIDA Sasa" else "Verify NIDA Identity")
                            }
                        }
                    }
                }
            }

            // Trust & Escrow History Card
            item {
                Card(
                    shape = RoundedCornerShape(12.dp),
                    colors = CardDefaults.cardColors(containerColor = SlateSurface),
                    elevation = CardDefaults.cardElevation(defaultElevation = 2.dp)
                ) {
                    Column(modifier = Modifier.padding(14.dp)) {
                        Row(verticalAlignment = Alignment.CenterVertically) {
                            Icon(
                                imageVector = Icons.Default.Shield,
                                contentDescription = null,
                                tint = EmeraldPrimary,
                                modifier = Modifier.size(20.dp)
                            )
                            Spacer(modifier = Modifier.width(6.dp))
                            Text(
                                text = if (isSwahili) "Historia ya Uaminifu na Malipo" else "Trust & Escrow Track Record",
                                fontWeight = FontWeight.Bold,
                                fontSize = 14.sp,
                                color = SlateTextPrimary
                            )
                        }

                        Spacer(modifier = Modifier.height(12.dp))

                        Row(
                            modifier = Modifier.fillMaxWidth(),
                            horizontalArrangement = Arrangement.SpaceAround
                        ) {
                            StatMiniBox(
                                value = "${userProfile.completedGigs}",
                                label = if (isSwahili) "Kazi Zilizokamilika" else "Completed Gigs",
                                color = EmeraldPrimary
                            )
                            StatMiniBox(
                                value = "${userProfile.totalTransactions}",
                                label = if (isSwahili) "Miamala ya Escrow" else "Escrow Deals",
                                color = BrandBlue
                            )
                            StatMiniBox(
                                value = "${userProfile.rating} ★",
                                label = if (isSwahili) "Kiwango cha Nyota" else "Star Rating",
                                color = GoldAccent
                            )
                        }
                    }
                }
            }

            // Skills & Endorsements
            item {
                Card(
                    shape = RoundedCornerShape(12.dp),
                    colors = CardDefaults.cardColors(containerColor = SlateSurface),
                    elevation = CardDefaults.cardElevation(defaultElevation = 2.dp)
                ) {
                    Column(modifier = Modifier.padding(14.dp)) {
                        Row(verticalAlignment = Alignment.CenterVertically) {
                            Icon(
                                imageVector = Icons.Default.Psychology,
                                contentDescription = null,
                                tint = EmeraldPrimary,
                                modifier = Modifier.size(20.dp)
                            )
                            Spacer(modifier = Modifier.width(6.dp))
                            Text(
                                text = if (isSwahili) "Ujuzi na Vyeti Vilivyothibitishwa" else "Verified Skills & Badges",
                                fontWeight = FontWeight.Bold,
                                fontSize = 14.sp,
                                color = SlateTextPrimary
                            )
                        }

                        Spacer(modifier = Modifier.height(8.dp))

                        Row(
                            modifier = Modifier.fillMaxWidth(),
                            horizontalArrangement = Arrangement.spacedBy(6.dp)
                        ) {
                            userProfile.skills.take(4).forEach { skill ->
                                Surface(
                                    shape = RoundedCornerShape(6.dp),
                                    color = SlateBackground,
                                    border = androidx.compose.foundation.BorderStroke(1.dp, EmeraldLight.copy(alpha = 0.4f))
                                ) {
                                    Text(
                                        text = skill,
                                        fontSize = 11.sp,
                                        color = EmeraldDark,
                                        fontWeight = FontWeight.Medium,
                                        modifier = Modifier.padding(horizontal = 8.dp, vertical = 4.dp)
                                    )
                                }
                            }
                        }
                    }
                }
            }

            // Logout Option
            if (onLogoutClick != null) {
                item {
                    Button(
                        onClick = onLogoutClick,
                        colors = ButtonDefaults.buttonColors(containerColor = RedWarning.copy(alpha = 0.1f), contentColor = RedWarning),
                        border = androidx.compose.foundation.BorderStroke(1.dp, RedWarning),
                        shape = RoundedCornerShape(10.dp),
                        modifier = Modifier.fillMaxWidth().height(44.dp)
                    ) {
                        Icon(Icons.Default.Logout, contentDescription = null, modifier = Modifier.size(16.dp))
                        Spacer(modifier = Modifier.width(6.dp))
                        Text(if (isSwahili) "Toka kwenye Akaunti" else "Sign Out of Account")
                    }
                }
            }
        }
    }

    // Avatar Selection Dialog
    if (showAvatarDialog) {
        Dialog(onDismissRequest = { showAvatarDialog = false }) {
            Surface(
                shape = RoundedCornerShape(16.dp),
                color = SlateSurface,
                modifier = Modifier.fillMaxWidth().padding(16.dp)
            ) {
                Column(modifier = Modifier.padding(18.dp)) {
                    Text(
                        text = if (isSwahili) "Badilisha Picha ya Wasifu" else "Update Profile Picture",
                        fontWeight = FontWeight.Bold,
                        fontSize = 16.sp,
                        color = SlateTextPrimary
                    )
                    Text(
                        text = if (isSwahili) "Chagua picha ya wasifu inayokufaa au weka kiungo:" else "Select a preset avatar or enter custom URL:",
                        fontSize = 12.sp,
                        color = SlateTextSecondary
                    )

                    Spacer(modifier = Modifier.height(14.dp))

                    LazyVerticalGrid(
                        columns = GridCells.Fixed(3),
                        horizontalArrangement = Arrangement.spacedBy(10.dp),
                        verticalArrangement = Arrangement.spacedBy(10.dp),
                        modifier = Modifier.height(180.dp)
                    ) {
                        items(presetAvatars) { avatarUrl ->
                            Surface(
                                shape = CircleShape,
                                border = androidx.compose.foundation.BorderStroke(
                                    2.dp,
                                    if (userProfile.avatarUrl == avatarUrl) BrandGreen else Color.Transparent
                                ),
                                modifier = Modifier
                                    .size(56.dp)
                                    .clickable {
                                        onUploadAvatar?.invoke(avatarUrl)
                                        showAvatarDialog = false
                                        coroutineScope.launch {
                                            snackbarHostState.showSnackbar(
                                                if (isSwahili) "Picha ya wasifu imesasishwa!" else "Profile picture updated!"
                                            )
                                        }
                                    }
                            ) {
                                AsyncImage(
                                    model = avatarUrl,
                                    contentDescription = "Avatar Preset",
                                    contentScale = ContentScale.Crop,
                                    modifier = Modifier.fillMaxSize().clip(CircleShape)
                                )
                            }
                        }
                    }

                    Spacer(modifier = Modifier.height(12.dp))

                    TextButton(
                        onClick = { showAvatarDialog = false },
                        modifier = Modifier.align(Alignment.End)
                    ) {
                        Text(if (isSwahili) "Funga" else "Close")
                    }
                }
            }
        }
    }

    // NIDA Verification Input Dialog
    if (showNidaDialog) {
        AlertDialog(
            onDismissRequest = { showNidaDialog = false },
            title = {
                Text(
                    text = if (isSwahili) "Uhakiki wa Kitambulisho cha NIDA" else "Verify NIDA Identity",
                    fontWeight = FontWeight.Bold
                )
            },
            text = {
                Column {
                    Text(
                        text = if (isSwahili)
                            "Weka namba yako ya NIDA ya tarakimu 20 kwa ajili ya uhakiki wa moja kwa moja."
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

@Composable
fun StatMiniBox(value: String, label: String, color: Color) {
    Column(horizontalAlignment = Alignment.CenterHorizontally) {
        Text(text = value, fontWeight = FontWeight.Bold, fontSize = 16.sp, color = color)
        Text(text = label, fontSize = 11.sp, color = SlateTextSecondary)
    }
}
