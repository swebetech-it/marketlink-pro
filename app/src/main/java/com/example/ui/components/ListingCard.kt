package com.example.ui.components

import androidx.compose.foundation.background
import androidx.compose.foundation.border
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.*
import androidx.compose.material3.*
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.platform.testTag
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import com.example.data.model.Listing
import com.example.data.model.OpportunityCategory
import com.example.data.model.VerificationStatus
import com.example.ui.theme.*

@Composable
fun ListingCard(
    listing: Listing,
    isSwahili: Boolean,
    isOwner: Boolean = false,
    onCardClick: () -> Unit,
    onQuickApplyClick: () -> Unit,
    onDeleteClick: (() -> Unit)? = null
) {
    Card(
        shape = RoundedCornerShape(12.dp),
        colors = CardDefaults.cardColors(containerColor = SlateSurface),
        elevation = CardDefaults.cardElevation(defaultElevation = 2.dp),
        modifier = Modifier
            .fillMaxWidth()
            .clickable { onCardClick() }
            .testTag("listing_card_${listing.id}")
    ) {
        Column(modifier = Modifier.padding(14.dp)) {
            // AI Targeted Match Highlight Banner
            if (listing.isTopMatch || listing.matchScore >= 80) {
                Surface(
                    color = EmeraldPrimary.copy(alpha = 0.12f),
                    border = androidx.compose.foundation.BorderStroke(1.dp, EmeraldPrimary.copy(alpha = 0.45f)),
                    shape = RoundedCornerShape(8.dp),
                    modifier = Modifier
                        .fillMaxWidth()
                        .padding(bottom = 8.dp)
                ) {
                    Row(
                        modifier = Modifier.padding(horizontal = 10.dp, vertical = 5.dp),
                        verticalAlignment = Alignment.CenterVertically,
                        horizontalArrangement = Arrangement.SpaceBetween
                    ) {
                        Row(verticalAlignment = Alignment.CenterVertically) {
                            Icon(
                                imageVector = Icons.Default.Stars,
                                contentDescription = null,
                                tint = EmeraldPrimary,
                                modifier = Modifier.size(15.dp)
                            )
                            Spacer(modifier = Modifier.width(6.dp))
                            Text(
                                text = if (isSwahili) "🎯 Fursa Inayokufaa Wewe Pekee (AI Target)" else "🎯 AI Targeted Match For You",
                                fontSize = 11.sp,
                                fontWeight = FontWeight.Bold,
                                color = EmeraldPrimary
                            )
                        }

                        if (listing.matchedCandidatesCount > 0) {
                            Text(
                                text = "${listing.matchedCandidatesCount} " + if (isSwahili) "Walengwa" else "Targeted",
                                fontSize = 10.sp,
                                fontWeight = FontWeight.SemiBold,
                                color = SlateTextSecondary
                            )
                        }
                    }
                }
            }

            // Header Row: Category Badge & AI Match % Badge
            Row(
                modifier = Modifier.fillMaxWidth(),
                horizontalArrangement = Arrangement.SpaceBetween,
                verticalAlignment = Alignment.CenterVertically
            ) {
                Row(
                    verticalAlignment = Alignment.CenterVertically,
                    horizontalArrangement = Arrangement.spacedBy(6.dp)
                ) {
                    Surface(
                        color = when (listing.category) {
                            OpportunityCategory.EMPLOYMENT -> EmeraldPrimary.copy(alpha = 0.1f)
                            OpportunityCategory.SKILLED_SERVICE -> BlueEscrow.copy(alpha = 0.1f)
                            OpportunityCategory.CASUAL_GIG -> GoldAccent.copy(alpha = 0.2f)
                            OpportunityCategory.BUSINESS_OPP -> Color(0xFF8B5CF6).copy(alpha = 0.1f)
                            OpportunityCategory.AGRI_LABOR -> GreenVerified.copy(alpha = 0.1f)
                            OpportunityCategory.EQUIPMENT_RENTAL -> AmberPending.copy(alpha = 0.1f)
                        },
                        shape = RoundedCornerShape(6.dp)
                    ) {
                        Text(
                            text = if (isSwahili) listing.category.labelSw else listing.category.labelEn,
                            fontSize = 10.sp,
                            fontWeight = FontWeight.Bold,
                            color = when (listing.category) {
                                OpportunityCategory.EMPLOYMENT -> EmeraldPrimary
                                OpportunityCategory.SKILLED_SERVICE -> BlueEscrow
                                OpportunityCategory.CASUAL_GIG -> Color(0xFF92400E)
                                OpportunityCategory.BUSINESS_OPP -> Color(0xFF6D28D9)
                                OpportunityCategory.AGRI_LABOR -> GreenVerified
                                OpportunityCategory.EQUIPMENT_RENTAL -> AmberPending
                            },
                            modifier = Modifier.padding(horizontal = 8.dp, vertical = 3.dp)
                        )
                    }

                    if (listing.jobTypeLevel == "NON_PROFESSIONAL") {
                        Surface(
                            color = Color(0xFFF97316).copy(alpha = 0.12f),
                            shape = RoundedCornerShape(4.dp)
                        ) {
                            Text(
                                text = if (isSwahili) "Isiyo ya Kitaaluma" else "Casual / Non-Prof",
                                fontSize = 9.sp,
                                fontWeight = FontWeight.Bold,
                                color = Color(0xFFEA580C),
                                modifier = Modifier.padding(horizontal = 6.dp, vertical = 2.dp)
                            )
                        }
                    } else if (listing.jobTypeLevel == "PROFESSIONAL") {
                        Surface(
                            color = Color(0xFF2563EB).copy(alpha = 0.12f),
                            shape = RoundedCornerShape(4.dp)
                        ) {
                            Text(
                                text = if (isSwahili) "Kitaaluma" else "Professional",
                                fontSize = 9.sp,
                                fontWeight = FontWeight.Bold,
                                color = Color(0xFF1D4ED8),
                                modifier = Modifier.padding(horizontal = 6.dp, vertical = 2.dp)
                            )
                        }
                    }
                }

                // AI Match Score Pill
                Surface(
                    color = EmeraldPrimary,
                    shape = RoundedCornerShape(12.dp)
                ) {
                    Row(
                        modifier = Modifier.padding(horizontal = 8.dp, vertical = 2.dp),
                        verticalAlignment = Alignment.CenterVertically
                    ) {
                        Icon(
                            imageVector = Icons.Default.AutoAwesome,
                            contentDescription = "AI Match",
                            tint = GoldAccent,
                            modifier = Modifier.size(11.dp)
                        )
                        Spacer(modifier = Modifier.width(3.dp))
                        Text(
                            text = "${listing.matchScore}% Match",
                            fontSize = 10.sp,
                            fontWeight = FontWeight.Bold,
                            color = Color.White
                        )
                    }
                }
            }

            Spacer(modifier = Modifier.height(8.dp))

            // Title
            Text(
                text = listing.title,
                fontWeight = FontWeight.Bold,
                fontSize = 15.sp,
                color = SlateTextPrimary
            )

            Spacer(modifier = Modifier.height(4.dp))

            // Prominent Multi-Tenant Organization Attribution Badge
            Surface(
                color = if (isOwner) BrandOrange.copy(alpha = 0.12f) else BrandBlue.copy(alpha = 0.08f),
                shape = RoundedCornerShape(6.dp),
                border = androidx.compose.foundation.BorderStroke(
                    1.dp,
                    if (isOwner) BrandOrange.copy(alpha = 0.4f) else BrandBlue.copy(alpha = 0.25f)
                ),
                modifier = Modifier.padding(bottom = 6.dp)
            ) {
                Row(
                    modifier = Modifier.padding(horizontal = 8.dp, vertical = 3.dp),
                    verticalAlignment = Alignment.CenterVertically
                ) {
                    Icon(
                        imageVector = if (isOwner) Icons.Default.Stars else Icons.Default.Apartment,
                        contentDescription = null,
                        tint = if (isOwner) BrandOrange else BrandBlue,
                        modifier = Modifier.size(13.dp)
                    )
                    Spacer(modifier = Modifier.width(4.dp))
                    Text(
                        text = if (isOwner)
                            (if (isSwahili) "★ Fursa ya Shirika Lako" else "★ Your Organization Posting")
                        else
                            "${if (isSwahili) "🏢 Shirika" else "🏢 Organization"}: ${listing.posterName}",
                        fontSize = 11.sp,
                        fontWeight = FontWeight.Bold,
                        color = if (isOwner) BrandOrange else BrandBlue
                    )
                    if (listing.posterVerification == VerificationStatus.VERIFIED) {
                        Spacer(modifier = Modifier.width(4.dp))
                        Icon(
                            imageVector = Icons.Default.Verified,
                            contentDescription = "Verified Employer",
                            tint = GreenVerified,
                            modifier = Modifier.size(12.dp)
                        )
                    }
                    Text(
                        text = " • ★ ${listing.posterRating}",
                        fontSize = 11.sp,
                        color = SlateTextSecondary
                    )
                }
            }

            Spacer(modifier = Modifier.height(4.dp))

            // Location & Compensation Highlight Box
            Row(
                modifier = Modifier.fillMaxWidth(),
                horizontalArrangement = Arrangement.SpaceBetween,
                verticalAlignment = Alignment.CenterVertically
            ) {
                Row(verticalAlignment = Alignment.CenterVertically) {
                    Icon(
                        imageVector = Icons.Default.LocationOn,
                        contentDescription = null,
                        tint = EmeraldPrimary,
                        modifier = Modifier.size(14.dp)
                    )
                    Spacer(modifier = Modifier.width(2.dp))
                    Text(
                        text = listing.location,
                        fontSize = 12.sp,
                        color = SlateTextPrimary,
                        fontWeight = FontWeight.Medium
                    )
                }

                Text(
                    text = listing.compensation,
                    fontSize = 13.sp,
                    fontWeight = FontWeight.ExtraBold,
                    color = EmeraldPrimary
                )
            }

            Spacer(modifier = Modifier.height(8.dp))

            // Skills Chips
            Row(
                horizontalArrangement = Arrangement.spacedBy(4.dp),
                modifier = Modifier.fillMaxWidth()
            ) {
                listing.requiredSkills.take(3).forEach { skill ->
                    Surface(
                        color = Color(0xFFF1F5F9),
                        shape = RoundedCornerShape(4.dp)
                    ) {
                        Text(
                            text = skill,
                            fontSize = 10.sp,
                            color = SlateTextSecondary,
                            modifier = Modifier.padding(horizontal = 6.dp, vertical = 2.dp)
                        )
                    }
                }
            }

            Spacer(modifier = Modifier.height(10.dp))

            // Bottom Footer Row: Escrow Protection Tag + Action Buttons
            Row(
                modifier = Modifier.fillMaxWidth(),
                horizontalArrangement = Arrangement.SpaceBetween,
                verticalAlignment = Alignment.CenterVertically
            ) {
                if (listing.isEscrowRequired) {
                    Row(verticalAlignment = Alignment.CenterVertically) {
                        Icon(
                            imageVector = Icons.Default.Lock,
                            contentDescription = "Escrow Protected",
                            tint = BlueEscrow,
                            modifier = Modifier.size(12.dp)
                        )
                        Spacer(modifier = Modifier.width(3.dp))
                        Text(
                            text = if (isSwahili) "Escrow Imekingwa" else "Escrow Secured",
                            fontSize = 11.sp,
                            color = BlueEscrow,
                            fontWeight = FontWeight.Bold
                        )
                    }
                } else {
                    Text(
                        text = if (isSwahili) "Malipo ya Moja kwa Moja" else "Direct Terms",
                        fontSize = 11.sp,
                        color = SlateTextSecondary
                    )
                }

                if (isOwner) {
                    Row(horizontalArrangement = Arrangement.spacedBy(6.dp)) {
                        if (onDeleteClick != null) {
                            OutlinedButton(
                                onClick = onDeleteClick,
                                shape = RoundedCornerShape(8.dp),
                                colors = ButtonDefaults.outlinedButtonColors(contentColor = RedWarning),
                                border = androidx.compose.foundation.BorderStroke(1.dp, RedWarning.copy(alpha = 0.5f)),
                                contentPadding = PaddingValues(horizontal = 8.dp, vertical = 4.dp)
                            ) {
                                Icon(Icons.Default.Delete, contentDescription = null, modifier = Modifier.size(13.dp), tint = RedWarning)
                                Spacer(modifier = Modifier.width(3.dp))
                                Text(
                                    text = if (isSwahili) "Futa" else "Delete",
                                    fontSize = 11.sp,
                                    fontWeight = FontWeight.Bold,
                                    color = RedWarning
                                )
                            }
                        }
                        Button(
                            onClick = onCardClick,
                            shape = RoundedCornerShape(8.dp),
                            colors = ButtonDefaults.buttonColors(containerColor = BrandBlue, contentColor = Color.White),
                            contentPadding = PaddingValues(horizontal = 10.dp, vertical = 4.dp)
                        ) {
                            Icon(Icons.Default.ManageAccounts, contentDescription = null, modifier = Modifier.size(13.dp), tint = Color.White)
                            Spacer(modifier = Modifier.width(3.dp))
                            Text(
                                text = if (isSwahili) "Simamia" else "Manage",
                                fontSize = 11.sp,
                                fontWeight = FontWeight.Bold,
                                color = Color.White
                            )
                        }
                    }
                } else {
                    Button(
                        onClick = onQuickApplyClick,
                        shape = RoundedCornerShape(8.dp),
                        colors = ButtonDefaults.buttonColors(containerColor = EmeraldPrimary, contentColor = Color.White),
                        contentPadding = PaddingValues(horizontal = 12.dp, vertical = 4.dp),
                        modifier = Modifier.testTag("apply_button_${listing.id}")
                    ) {
                        Text(
                            text = if (isSwahili) "Omba Fursa" else "Apply Now",
                            fontSize = 11.sp,
                            fontWeight = FontWeight.Bold,
                            color = Color.White
                        )
                    }
                }
            }
        }
    }
}
