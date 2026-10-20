package com.example.ui.components

import androidx.compose.animation.core.animateFloatAsState
import androidx.compose.foundation.background
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.Place
import androidx.compose.material3.*
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.platform.testTag
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import com.example.ui.theme.*

data class RegionDemand(
    val regionName: String,
    val openOpportunities: Int,
    val topSector: String,
    val intensity: Float // 0.0 to 1.0
)

@Composable
fun OpportunityHeatmap(
    isSwahili: Boolean,
    selectedRegion: String,
    listings: List<com.example.data.model.Listing> = emptyList(),
    onRegionSelect: (String) -> Unit
) {
    val regionNames = listOf("Mbeya", "Dar es Salaam", "Morogoro", "Dodoma", "Arusha", "Mwanza")
    val totalListings = listings.size.coerceAtLeast(1)

    val regions = regionNames.map { region ->
        val count = listings.count { it.location.contains(region, ignoreCase = true) }
        val intensity = if (listings.isNotEmpty() && count > 0) (count.toFloat() / totalListings.toFloat()).coerceIn(0.2f, 1.0f) else 0.0f
        RegionDemand(
            regionName = region,
            openOpportunities = count,
            topSector = if (count > 0) "$count ${if (isSwahili) "Fursa" else "Open"}" else if (isSwahili) "0 Fursa" else "0 Open",
            intensity = intensity
        )
    }

    Card(
        shape = RoundedCornerShape(12.dp),
        colors = CardDefaults.cardColors(containerColor = SlateSurface),
        elevation = CardDefaults.cardElevation(defaultElevation = 2.dp),
        modifier = Modifier
            .fillMaxWidth()
            .testTag("opportunity_heatmap")
    ) {
        Column(modifier = Modifier.padding(14.dp)) {
            Row(
                modifier = Modifier.fillMaxWidth(),
                horizontalArrangement = Arrangement.SpaceBetween,
                verticalAlignment = Alignment.CenterVertically
            ) {
                Row(verticalAlignment = Alignment.CenterVertically) {
                    Icon(
                        imageVector = Icons.Default.Place,
                        contentDescription = null,
                        tint = EmeraldPrimary,
                        modifier = Modifier.size(18.dp)
                    )
                    Spacer(modifier = Modifier.width(6.dp))
                    Text(
                        text = if (isSwahili) "Ramani ya Uhitaji wa Kazi Tanzania" else "Tanzania Labor Demand Heatmap",
                        fontWeight = FontWeight.Bold,
                        fontSize = 14.sp,
                        color = SlateTextPrimary
                    )
                }

                TextButton(onClick = { onRegionSelect("All Regions") }) {
                    Text(
                        text = if (isSwahili) "Onyesha Zote" else "Reset Filter",
                        fontSize = 11.sp,
                        color = EmeraldPrimary
                    )
                }
            }

            Spacer(modifier = Modifier.height(10.dp))

            // Region demand bars
            Column(verticalArrangement = Arrangement.spacedBy(8.dp)) {
                regions.forEach { reg ->
                    val isSelected = selectedRegion.contains(reg.regionName, ignoreCase = true)
                    val animatedWidth = animateFloatAsState(targetValue = reg.intensity, label = "BarWidth")

                    Surface(
                        shape = RoundedCornerShape(8.dp),
                        color = if (isSelected) EmeraldPrimary.copy(alpha = 0.12f) else Color(0xFFF1F5F9),
                        border = if (isSelected) androidx.compose.foundation.BorderStroke(1.dp, EmeraldPrimary) else null,
                        modifier = Modifier
                            .fillMaxWidth()
                            .clickable { onRegionSelect(reg.regionName) }
                    ) {
                        Column(modifier = Modifier.padding(8.dp)) {
                            Row(
                                modifier = Modifier.fillMaxWidth(),
                                horizontalArrangement = Arrangement.SpaceBetween
                            ) {
                                Text(
                                    text = reg.regionName,
                                    fontSize = 12.sp,
                                    fontWeight = FontWeight.Bold,
                                    color = if (isSelected) EmeraldPrimary else SlateTextPrimary
                                )
                                Text(
                                    text = "${reg.openOpportunities} ${if (isSwahili) "Fursa" else "Jobs"}",
                                    fontSize = 11.sp,
                                    fontWeight = FontWeight.Bold,
                                    color = EmeraldPrimary
                                )
                            }
                            Spacer(modifier = Modifier.height(4.dp))
                            // Progress bar indicator
                            Box(
                                modifier = Modifier
                                    .fillMaxWidth()
                                    .height(6.dp)
                                    .background(Color(0xFFCBD5E1), shape = RoundedCornerShape(3.dp))
                            ) {
                                Box(
                                    modifier = Modifier
                                        .fillMaxHeight()
                                        .fillMaxWidth(animatedWidth.value)
                                        .background(
                                            if (isSelected) GoldAccent else EmeraldPrimary,
                                            shape = RoundedCornerShape(3.dp)
                                        )
                                )
                            }
                        }
                    }
                }
            }
        }
    }
}
