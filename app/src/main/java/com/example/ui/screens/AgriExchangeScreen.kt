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
import com.example.data.model.Listing
import com.example.data.model.OpportunityCategory
import com.example.ui.components.ListingCard
import com.example.ui.theme.*

@Composable
fun AgriExchangeScreen(
    listings: List<Listing>,
    isSwahili: Boolean,
    onListingClick: (Listing) -> Unit,
    onQuickApply: (Listing) -> Unit,
    onPostAgriNeedClick: () -> Unit
) {
    val agriListings = remember(listings) {
        listings.filter { it.category == OpportunityCategory.AGRI_LABOR || it.category == OpportunityCategory.EQUIPMENT_RENTAL }
    }

    var selectedTab by remember { mutableStateOf(0) } // 0 = Seasonal Labor, 1 = Tractor & Equipment Rental

    LazyColumn(
        contentPadding = PaddingValues(bottom = 80.dp),
        verticalArrangement = Arrangement.spacedBy(14.dp),
        modifier = Modifier
            .fillMaxSize()
            .padding(16.dp)
            .testTag("agri_exchange_screen")
    ) {
        // Hero Header Banner
        item {
            Card(
                shape = RoundedCornerShape(14.dp),
                colors = CardDefaults.cardColors(containerColor = EmeraldDark),
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
                            Icon(
                                imageVector = Icons.Default.Agriculture,
                                contentDescription = null,
                                tint = GoldAccent,
                                modifier = Modifier.size(26.dp)
                            )
                            Spacer(modifier = Modifier.width(8.dp))
                            Column {
                                Text(
                                    text = if (isSwahili) "Soko la Kibarua cha Kilimo na Zana" else "Agricultural Workforce Exchange",
                                    fontWeight = FontWeight.Bold,
                                    fontSize = 16.sp,
                                    color = Color.White
                                )
                                Text(
                                    text = if (isSwahili) "AMCOS, Wakulima na Wamiliki wa Mitambo" else "For Farmers, AMCOS Cooperatives & Equipment Owners",
                                    fontSize = 11.sp,
                                    color = Color.White.copy(alpha = 0.8f)
                                )
                            }
                        }

                        Surface(
                            shape = RoundedCornerShape(8.dp),
                            color = GoldAccent
                        ) {
                            Text(
                                text = "AMCOS",
                                fontSize = 10.sp,
                                fontWeight = FontWeight.Bold,
                                color = EmeraldDark,
                                modifier = Modifier.padding(horizontal = 6.dp, vertical = 2.dp)
                            )
                        }
                    }

                    Spacer(modifier = Modifier.height(12.dp))

                    Text(
                        text = if (isSwahili)
                            "Pata wafanyakazi wa msimu wa kuvuna mpunga, mahindi, chai au kodi matrekta yenye diski za kulima kwa bei iliyolindwa na Escrow."
                        else
                            "Hire verified seasonal harvest crews for paddy, maize, tea or rent heavy tractors & irrigation pumps with escrow protection.",
                        fontSize = 12.sp,
                        color = Color.White.copy(alpha = 0.9f)
                    )

                    Spacer(modifier = Modifier.height(12.dp))

                    Button(
                        onClick = onPostAgriNeedClick,
                        colors = ButtonDefaults.buttonColors(containerColor = GoldAccent, contentColor = EmeraldDark),
                        shape = RoundedCornerShape(8.dp),
                        modifier = Modifier
                            .fillMaxWidth()
                            .testTag("post_agri_need_button")
                    ) {
                        Icon(Icons.Default.AddCircle, contentDescription = null, modifier = Modifier.size(16.dp))
                        Spacer(modifier = Modifier.width(6.dp))
                        Text(
                            text = if (isSwahili) "Weka Ombi la Wafanyakazi / Trekta" else "Post Seasonal Need or Tractor Rental",
                            fontWeight = FontWeight.Bold,
                            fontSize = 12.sp
                        )
                    }
                }
            }
        }

        // Segment Tabs
        item {
            TabRow(
                selectedTabIndex = selectedTab,
                containerColor = SlateSurface,
                contentColor = EmeraldPrimary
            ) {
                Tab(
                    selected = selectedTab == 0,
                    onClick = { selectedTab = 0 },
                    text = {
                        Text(
                            text = if (isSwahili) "Wafanyakazi wa Msimu (${agriListings.count { it.category == OpportunityCategory.AGRI_LABOR }})" else "Seasonal Labor (${agriListings.count { it.category == OpportunityCategory.AGRI_LABOR }})",
                            fontSize = 12.sp,
                            fontWeight = FontWeight.Bold
                        )
                    }
                )
                Tab(
                    selected = selectedTab == 1,
                    onClick = { selectedTab = 1 },
                    text = {
                        Text(
                            text = if (isSwahili) "Kodi Zana & Matrekta (${agriListings.count { it.category == OpportunityCategory.EQUIPMENT_RENTAL }})" else "Equipment Rental (${agriListings.count { it.category == OpportunityCategory.EQUIPMENT_RENTAL }})",
                            fontSize = 12.sp,
                            fontWeight = FontWeight.Bold
                        )
                    }
                )
            }
        }

        // Crop / Region Quick Highlights Card
        item {
            Surface(
                shape = RoundedCornerShape(10.dp),
                color = GreenVerified.copy(alpha = 0.08f),
                border = androidx.compose.foundation.BorderStroke(1.dp, GreenVerified.copy(alpha = 0.3f))
            ) {
                Row(
                    modifier = Modifier
                        .fillMaxWidth()
                        .padding(12.dp),
                    verticalAlignment = Alignment.CenterVertically,
                    horizontalArrangement = Arrangement.SpaceBetween
                ) {
                    Row(verticalAlignment = Alignment.CenterVertically) {
                        Icon(Icons.Default.Grass, contentDescription = null, tint = GreenVerified)
                        Spacer(modifier = Modifier.width(8.dp))
                        Column {
                            Text(
                                text = if (isSwahili) "Soko la Kilimo na Mitambo ya Shamba" else "Agricultural Workforce & Machinery Exchange",
                                fontWeight = FontWeight.Bold,
                                fontSize = 12.sp,
                                color = GreenVerified
                            )
                            Text(
                                text = if (isSwahili) "Fursa za vibarua vya msimu na ukodishaji wa matrekta na zana" else "Seasonal labor opportunities, tractor rentals and farm equipment listings",
                                fontSize = 11.sp,
                                color = SlateTextSecondary
                            )
                        }
                    }
                }
            }
        }

        // Filtered List
        val currentDisplayListings = agriListings.filter { listing ->
            if (selectedTab == 0) listing.category == OpportunityCategory.AGRI_LABOR
            else listing.category == OpportunityCategory.EQUIPMENT_RENTAL
        }

        if (currentDisplayListings.isEmpty()) {
            item {
                Card(
                    shape = RoundedCornerShape(12.dp),
                    colors = CardDefaults.cardColors(containerColor = SlateSurface),
                    modifier = Modifier.fillMaxWidth()
                ) {
                    Column(
                        horizontalAlignment = Alignment.CenterHorizontally,
                        modifier = Modifier.padding(24.dp)
                    ) {
                        Icon(Icons.Default.Agriculture, contentDescription = null, tint = SlateTextSecondary, modifier = Modifier.size(36.dp))
                        Spacer(modifier = Modifier.height(8.dp))
                        Text(
                            text = if (isSwahili) "Hakuna matangazo kwa sasa katika sehemu hii." else "No active agricultural listings in this category.",
                            fontWeight = FontWeight.Bold,
                            fontSize = 13.sp
                        )
                    }
                }
            }
        } else {
            items(currentDisplayListings, key = { it.id }) { listing ->
                ListingCard(
                    listing = listing,
                    isSwahili = isSwahili,
                    onCardClick = { onListingClick(listing) },
                    onQuickApplyClick = { onQuickApply(listing) }
                )
            }
        }
    }
}
