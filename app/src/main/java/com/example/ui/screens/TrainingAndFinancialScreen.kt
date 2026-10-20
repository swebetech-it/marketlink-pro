package com.example.ui.screens

import androidx.compose.foundation.layout.*
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.items
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
import com.example.data.model.Course
import com.example.data.model.UserProfile
import com.example.ui.theme.*

@Composable
fun TrainingAndFinancialScreen(
    userProfile: UserProfile,
    courses: List<Course>,
    isSwahili: Boolean
) {
    var selectedSectionTab by remember { mutableStateOf(0) } // 0 = Training Hub, 1 = Financial Services

    LazyColumn(
        contentPadding = PaddingValues(bottom = 80.dp),
        verticalArrangement = Arrangement.spacedBy(14.dp),
        modifier = Modifier
            .fillMaxSize()
            .padding(16.dp)
            .testTag("training_financial_screen")
    ) {
        item {
            TabRow(
                selectedTabIndex = selectedSectionTab,
                containerColor = SlateSurface,
                contentColor = EmeraldPrimary
            ) {
                Tab(
                    selected = selectedSectionTab == 0,
                    onClick = { selectedSectionTab = 0 },
                    text = {
                        Text(
                            text = if (isSwahili) "Chuo cha Ujuzi (VETA/SUA)" else "Training & Skills Hub",
                            fontSize = 12.sp,
                            fontWeight = FontWeight.Bold
                        )
                    }
                )
                Tab(
                    selected = selectedSectionTab == 1,
                    onClick = { selectedSectionTab = 1 },
                    text = {
                        Text(
                            text = if (isSwahili) "Mikopo & Benki" else "Financial Products",
                            fontSize = 12.sp,
                            fontWeight = FontWeight.Bold
                        )
                    }
                )
            }
        }

        if (selectedSectionTab == 0) {
            // Training Hero Card
            item {
                Card(
                    shape = RoundedCornerShape(14.dp),
                    colors = CardDefaults.cardColors(containerColor = EmeraldDark),
                    modifier = Modifier.fillMaxWidth()
                ) {
                    Column(modifier = Modifier.padding(16.dp)) {
                        Row(verticalAlignment = Alignment.CenterVertically) {
                            Icon(Icons.Default.Verified, contentDescription = null, tint = GoldAccent)
                            Spacer(modifier = Modifier.width(6.dp))
                            Text(
                                text = if (isSwahili) "Vyeti na Baji za Kidijitali" else "Verifiable Skill Credentials",
                                fontWeight = FontWeight.Bold,
                                fontSize = 14.sp,
                                color = Color.White
                            )
                        }

                        Spacer(modifier = Modifier.height(6.dp))

                        Text(
                            text = if (isSwahili)
                                "Vyeti vyote unavyokamilisha kutoka kwa taasisi zilizothibitishwa vinaongezwa moja kwa moja kwenye Pasipoti yako ya Sifa."
                            else
                                "Completed accredited courses directly append verified digital badges into your Reputation Passport.",
                            fontSize = 12.sp,
                            color = Color.White.copy(alpha = 0.85f)
                        )
                    }
                }
            }

            if (courses.isEmpty()) {
                item {
                    Card(
                        shape = RoundedCornerShape(12.dp),
                        colors = CardDefaults.cardColors(containerColor = SlateSurface),
                        modifier = Modifier
                            .fillMaxWidth()
                            .padding(vertical = 10.dp)
                    ) {
                        Column(
                            horizontalAlignment = Alignment.CenterHorizontally,
                            modifier = Modifier
                                .fillMaxWidth()
                                .padding(24.dp)
                        ) {
                            Icon(Icons.Default.School, contentDescription = null, tint = SlateTextSecondary, modifier = Modifier.size(40.dp))
                            Spacer(modifier = Modifier.height(10.dp))
                            Text(
                                text = if (isSwahili) "Hakuna Kozi Zilizochapishwa Bado" else "No Courses Published Yet",
                                fontWeight = FontWeight.Bold,
                                fontSize = 14.sp
                            )
                            Text(
                                text = if (isSwahili) "Taasisi za mafunzo zitachapisha fursa za mafunzo hivi karibuni."
                                else "Accredited training institutions will post vocational courses here.",
                                fontSize = 12.sp,
                                color = SlateTextSecondary,
                                modifier = Modifier.padding(top = 4.dp)
                            )
                        }
                    }
                }
            } else {
                items(courses, key = { it.id }) { course ->
                    Card(
                        shape = RoundedCornerShape(12.dp),
                        colors = CardDefaults.cardColors(containerColor = SlateSurface),
                        elevation = CardDefaults.cardElevation(defaultElevation = 2.dp),
                        modifier = Modifier.fillMaxWidth()
                    ) {
                        Column(modifier = Modifier.padding(14.dp)) {
                            Row(
                                modifier = Modifier.fillMaxWidth(),
                                horizontalArrangement = Arrangement.SpaceBetween,
                                verticalAlignment = Alignment.CenterVertically
                            ) {
                                Surface(
                                    shape = RoundedCornerShape(6.dp),
                                    color = EmeraldPrimary.copy(alpha = 0.15f)
                                ) {
                                    Text(
                                        text = course.category,
                                        fontSize = 10.sp,
                                        fontWeight = FontWeight.Bold,
                                        color = EmeraldPrimary,
                                        modifier = Modifier.padding(horizontal = 6.dp, vertical = 2.dp)
                                    )
                                }

                                Text(
                                    text = if (course.priceTzs == 0L) "FREE / BURE" else "TZS ${String.format("%,d", course.priceTzs)}",
                                    fontWeight = FontWeight.ExtraBold,
                                    fontSize = 13.sp,
                                    color = GoldAccent
                                )
                            }

                            Spacer(modifier = Modifier.height(8.dp))

                            Text(
                                text = course.title,
                                fontWeight = FontWeight.Bold,
                                fontSize = 15.sp,
                                color = SlateTextPrimary
                            )

                            Text(
                                text = "Provider: ${course.providerName} • ${course.duration}",
                                fontSize = 11.sp,
                                color = SlateTextSecondary
                            )

                            Spacer(modifier = Modifier.height(8.dp))

                            if (course.isEnrolled) {
                                Column {
                                    Row(
                                        modifier = Modifier.fillMaxWidth(),
                                        horizontalArrangement = Arrangement.SpaceBetween
                                    ) {
                                        Text(
                                            text = if (course.certificateIssued) "Certificate Issued ✓" else "Course Progress",
                                            fontSize = 11.sp,
                                            fontWeight = FontWeight.Bold,
                                            color = if (course.certificateIssued) GreenVerified else SlateTextPrimary
                                        )
                                        Text(text = "${course.progressPercent}%", fontSize = 11.sp, fontWeight = FontWeight.Bold)
                                    }
                                    LinearProgressIndicator(
                                        progress = { course.progressPercent / 100f },
                                        modifier = Modifier
                                            .fillMaxWidth()
                                            .height(6.dp),
                                        color = if (course.certificateIssued) GreenVerified else EmeraldPrimary,
                                    )
                                }
                            } else {
                                Button(
                                    onClick = { /* enroll */ },
                                    colors = ButtonDefaults.buttonColors(containerColor = EmeraldPrimary),
                                    shape = RoundedCornerShape(8.dp),
                                    modifier = Modifier.fillMaxWidth()
                                ) {
                                    Text(if (isSwahili) "Jiunge na Mafunzo" else "Enroll in Course", fontSize = 12.sp)
                                }
                            }
                        }
                    }
                }
            }
        } else {
            // Financial Products Section
            item {
                Card(
                    shape = RoundedCornerShape(12.dp),
                    colors = CardDefaults.cardColors(containerColor = SlateSurface)
                ) {
                    Column(modifier = Modifier.padding(14.dp)) {
                        Row(verticalAlignment = Alignment.CenterVertically) {
                            Icon(Icons.Default.CreditScore, contentDescription = null, tint = EmeraldPrimary)
                            Spacer(modifier = Modifier.width(6.dp))
                            Text(
                                text = if (isSwahili) "Tathmini ya Kustahili Mkopo" else "Credit Assessment Score",
                                fontWeight = FontWeight.Bold,
                                fontSize = 14.sp
                            )
                        }

                        Spacer(modifier = Modifier.height(8.dp))

                        Row(
                            modifier = Modifier.fillMaxWidth(),
                            horizontalArrangement = Arrangement.SpaceBetween,
                            verticalAlignment = Alignment.CenterVertically
                        ) {
                            Column {
                                Text(
                                    text = if (isSwahili) "Alama za Pasipoti" else "Passport Score",
                                    fontSize = 11.sp,
                                    color = SlateTextSecondary
                                )
                                Text(
                                    text = "${userProfile.reputationScore} / 1000",
                                    fontWeight = FontWeight.ExtraBold,
                                    fontSize = 18.sp,
                                    color = if (userProfile.reputationScore >= 700) EmeraldPrimary else GoldAccent
                                )
                            }

                            Surface(
                                shape = RoundedCornerShape(8.dp),
                                color = if (userProfile.reputationScore >= 700) GreenVerified else SlateTextSecondary
                            ) {
                                Text(
                                    text = if (userProfile.reputationScore >= 700) "PRE-APPROVED" else "BUILDING TRUST",
                                    fontSize = 10.sp,
                                    fontWeight = FontWeight.Bold,
                                    color = Color.White,
                                    modifier = Modifier.padding(horizontal = 8.dp, vertical = 4.dp)
                                )
                            }
                        }

                        Spacer(modifier = Modifier.height(8.dp))

                        Text(
                            text = if (userProfile.reputationScore >= 700) {
                                if (isSwahili) "Historia yako ya Escrow na NIDA inakustahilisha kupata mikopo ya fedha za uendeshaji kutoka benki washirika."
                                else "Your verified Escrow history & NIDA status qualifies you for pre-approved working capital facilities."
                            } else {
                                if (isSwahili) "Jenga alama zako za Pasipoti kufikia 700+ kwa kukamilisha kazi zilizothibitishwa kupitia Escrow ili kufungua mikopo ya benki."
                                else "Build your Reputation Passport score to 700+ by completing verified Escrow transactions to unlock partner credit products."
                            },
                            fontSize = 12.sp,
                            color = SlateTextSecondary
                        )
                    }
                }
            }

            // Available Partner Loans
            item {
                Column(verticalArrangement = Arrangement.spacedBy(10.dp)) {
                    Text(
                        text = if (isSwahili) "Bidhaa za Kifedha Zinazopatikana:" else "Available Micro-Credit Products:",
                        fontWeight = FontWeight.Bold,
                        fontSize = 14.sp
                    )

                    listOf(
                        Triple("Agri-Input Working Capital Facility", "Banking Partners", "Tier 1: Up to TZS 2,500,000 • Low Interest"),
                        Triple("Tractor & Equipment Lease Facility", "Equipment Partners", "Tier 2: Up to TZS 8,000,000 • Equipment Backed"),
                        Triple("Gig Worker Instant Float", "Mobile Money Rails", "Tier 3: Up to TZS 350,000 • Instant Payout")
                    ).forEach { prod ->
                        Card(
                            shape = RoundedCornerShape(10.dp),
                            colors = CardDefaults.cardColors(containerColor = SlateSurface)
                        ) {
                            Column(modifier = Modifier.padding(12.dp)) {
                                Text(text = prod.first, fontWeight = FontWeight.Bold, fontSize = 13.sp)
                                Text(text = "Category: ${prod.second}", fontSize = 11.sp, color = EmeraldPrimary)
                                Text(text = prod.third, fontSize = 11.sp, color = SlateTextSecondary)

                                Spacer(modifier = Modifier.height(6.dp))

                                Button(
                                    onClick = { /* apply */ },
                                    enabled = userProfile.reputationScore >= 700,
                                    colors = ButtonDefaults.buttonColors(containerColor = GoldAccent, contentColor = EmeraldDark),
                                    shape = RoundedCornerShape(6.dp),
                                    modifier = Modifier.fillMaxWidth()
                                ) {
                                    Text(
                                        text = if (userProfile.reputationScore >= 700) (if (isSwahili) "Omba Mkopo Hapa" else "Apply for Credit Product")
                                        else (if (isSwahili) "Inahitaji Alama 700+" else "Requires 700+ Score"),
                                        fontSize = 11.sp,
                                        fontWeight = FontWeight.Bold
                                    )
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
