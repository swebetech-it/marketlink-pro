package com.example.ui.screens

import androidx.compose.animation.AnimatedVisibility
import androidx.compose.foundation.background
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.LazyRow
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
import com.example.data.model.Listing
import com.example.data.model.OpportunityCategory
import com.example.data.model.UserProfile
import com.example.data.model.UserRole
import com.example.ui.components.ListingCard
import com.example.ui.components.MarketLinkLogoMark
import com.example.ui.components.MarketLinkLogoText
import com.example.ui.components.OpportunityHeatmap
import com.example.ui.theme.formTextFieldColors
import com.example.ui.components.BrandDarkBg
import com.example.ui.components.BrandBlue
import com.example.ui.components.BrandLightBlue
import com.example.ui.components.BrandGreen
import com.example.ui.components.BrandOrange
import com.example.ui.theme.*

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun HomeScreen(
    userProfile: UserProfile,
    isSwahili: Boolean,
    selectedRole: UserRole,
    searchQuery: String,
    selectedCategory: OpportunityCategory?,
    selectedRegion: String,
    listings: List<Listing>,
    myOrganizationListings: List<Listing> = emptyList(),
    isViewingPublicDirectory: Boolean = false,
    onTogglePublicDirectory: ((Boolean) -> Unit)? = null,
    onSearchChange: (String) -> Unit,
    onCategorySelect: (OpportunityCategory?) -> Unit,
    onRegionSelect: (String) -> Unit,
    onListingClick: (Listing) -> Unit,
    onQuickApply: (Listing) -> Unit,
    onCreateListingClick: () -> Unit,
    onDeleteMyListing: ((String) -> Unit)? = null,
    onTriggerSync: (((Boolean) -> Unit) -> Unit)? = null,
    onOpenServerSettings: (() -> Unit)? = null
) {
    var showHeatmap by remember { mutableStateOf(false) }
    var isSyncing by remember { mutableStateOf(false) }
    var syncMessage by remember { mutableStateOf<String?>(null) }
    var filterOnlyMyMatches by remember { mutableStateOf(false) }
    var listingToDelete by remember { mutableStateOf<Listing?>(null) }

    val isOrganizationUser = userProfile.accountType == "ORGANIZATION" ||
            selectedRole in listOf(UserRole.EMPLOYER, UserRole.SERVICE_CLIENT, UserRole.FARMER_COOP)

    Scaffold(
        floatingActionButton = {
            FloatingActionButton(
                onClick = onCreateListingClick,
                containerColor = if (isOrganizationUser) BrandOrange else GoldAccent,
                contentColor = if (isOrganizationUser) Color.White else EmeraldDark,
                modifier = Modifier
                    .testTag("create_listing_fab")
                    .padding(bottom = 8.dp)
            ) {
                Row(
                    modifier = Modifier.padding(horizontal = 12.dp),
                    verticalAlignment = Alignment.CenterVertically
                ) {
                    Icon(Icons.Default.Add, contentDescription = "Add Listing")
                    Spacer(modifier = Modifier.width(4.dp))
                    Text(
                        text = if (isSwahili) "Chapa Fursa" else "Post Opportunity",
                        fontWeight = FontWeight.Bold,
                        fontSize = 12.sp
                    )
                }
            }
        }
    ) { innerPadding ->
        LazyColumn(
            contentPadding = PaddingValues(bottom = 80.dp),
            verticalArrangement = Arrangement.spacedBy(14.dp),
            modifier = Modifier
                .fillMaxSize()
                .padding(innerPadding)
                .padding(horizontal = 16.dp)
                .testTag("home_screen_feed")
        ) {
            item {
                Spacer(modifier = Modifier.height(8.dp))

                if (isOrganizationUser) {
                    // Organization Tenant Workspace Header Banner
                    Surface(
                        shape = RoundedCornerShape(16.dp),
                        color = BrandDarkBg,
                        border = androidx.compose.foundation.BorderStroke(1.5.dp, BrandOrange.copy(alpha = 0.5f))
                    ) {
                        Column(modifier = Modifier.padding(14.dp)) {
                            Row(
                                modifier = Modifier.fillMaxWidth(),
                                verticalAlignment = Alignment.CenterVertically,
                                horizontalArrangement = Arrangement.SpaceBetween
                            ) {
                                Row(
                                    verticalAlignment = Alignment.CenterVertically,
                                    modifier = Modifier.weight(1f)
                                ) {
                                    MarketLinkLogoMark(size = 40.dp)
                                    Spacer(modifier = Modifier.width(10.dp))
                                    Column {
                                        MarketLinkLogoText(fontSize = 15f, showTagline = true)
                                        Spacer(modifier = Modifier.height(2.dp))
                                        Text(
                                            text = if (isSwahili) "Nafasi ya Shirika / Mwajiri" else "Organization Tenant Workspace",
                                            fontSize = 10.sp,
                                            color = BrandOrange,
                                            fontWeight = FontWeight.Bold
                                        )
                                        Text(
                                            text = userProfile.organizationName.ifBlank { userProfile.fullName },
                                            fontSize = 13.sp,
                                            color = Color.White,
                                            fontWeight = FontWeight.ExtraBold
                                        )
                                    }
                                }

                                Surface(
                                    shape = RoundedCornerShape(6.dp),
                                    color = BrandOrange
                                ) {
                                    Text(
                                        text = "ISOLATED TENANT",
                                        fontSize = 8.sp,
                                        fontWeight = FontWeight.Black,
                                        color = Color.White,
                                        modifier = Modifier.padding(horizontal = 6.dp, vertical = 3.dp)
                                    )
                                }
                            }

                            Spacer(modifier = Modifier.height(10.dp))

                            // Organization Details Chips
                            Row(
                                modifier = Modifier.fillMaxWidth(),
                                horizontalArrangement = Arrangement.spacedBy(6.dp)
                            ) {
                                Surface(
                                    shape = RoundedCornerShape(4.dp),
                                    color = Color(0xFF1E293B)
                                ) {
                                    Text(
                                        text = "TIN: ${userProfile.tinNumber.ifBlank { "Registered Org" }}",
                                        fontSize = 10.sp,
                                        color = Color.White.copy(alpha = 0.85f),
                                        fontWeight = FontWeight.Medium,
                                        modifier = Modifier.padding(horizontal = 6.dp, vertical = 2.dp)
                                    )
                                }

                                Surface(
                                    shape = RoundedCornerShape(4.dp),
                                    color = Color(0xFF1E293B)
                                ) {
                                    Text(
                                        text = userProfile.industrySector.ifBlank { "Industry" },
                                        fontSize = 10.sp,
                                        color = Color.White.copy(alpha = 0.85f),
                                        fontWeight = FontWeight.Medium,
                                        modifier = Modifier.padding(horizontal = 6.dp, vertical = 2.dp)
                                    )
                                }

                                Surface(
                                    shape = RoundedCornerShape(4.dp),
                                    color = Color(0xFF1E293B)
                                ) {
                                    Text(
                                        text = userProfile.district.ifBlank { userProfile.location },
                                        fontSize = 10.sp,
                                        color = Color.White.copy(alpha = 0.85f),
                                        fontWeight = FontWeight.Medium,
                                        modifier = Modifier.padding(horizontal = 6.dp, vertical = 2.dp)
                                    )
                                }
                            }

                            Spacer(modifier = Modifier.height(10.dp))

                            // Strict Multi-Tenant Security & Boundary Guarantee
                            Surface(
                                shape = RoundedCornerShape(8.dp),
                                color = Color(0xFF0F172A),
                                border = androidx.compose.foundation.BorderStroke(1.dp, BrandGreen.copy(alpha = 0.3f))
                            ) {
                                Row(
                                    modifier = Modifier.padding(horizontal = 8.dp, vertical = 6.dp),
                                    verticalAlignment = Alignment.CenterVertically
                                ) {
                                    Icon(
                                        imageVector = Icons.Default.Shield,
                                        contentDescription = null,
                                        tint = BrandGreen,
                                        modifier = Modifier.size(14.dp)
                                    )
                                    Spacer(modifier = Modifier.width(6.dp))
                                    val dbText = if (!userProfile.tenantDatabase.isNullOrBlank()) " (${userProfile.tenantDatabase})" else ""
                                    Text(
                                        text = if (isSwahili)
                                            "🔒 Ulinzi Kamili (Database-Per-Tenant$dbText): Nafasi hii ni ya shirika lako pekee. Database yako imeanzishwa ikiwa safi kabisa na unajaza data zako mwenyewe bila muingiliano wa mashirika mengine."
                                        else
                                            "🔒 Dedicated Tenant Database Active$dbText: Strictly isolated workspace with 100% private database schema and zero cross-organization leakage.",
                                        fontSize = 10.sp,
                                        color = Color.White.copy(alpha = 0.9f)
                                    )
                                }
                            }
                        }
                    }
                } else {
                    // Standard MarketLink PRO Branding Welcome Banner for Workers
                    Surface(
                        shape = RoundedCornerShape(16.dp),
                        color = BrandDarkBg,
                        border = androidx.compose.foundation.BorderStroke(1.dp, BrandBlue.copy(alpha = 0.35f))
                    ) {
                        Row(
                            modifier = Modifier
                                .fillMaxWidth()
                                .padding(12.dp),
                            verticalAlignment = Alignment.CenterVertically,
                            horizontalArrangement = Arrangement.SpaceBetween
                        ) {
                            Row(
                                verticalAlignment = Alignment.CenterVertically,
                                modifier = Modifier.weight(1f)
                            ) {
                                MarketLinkLogoMark(size = 40.dp)
                                Spacer(modifier = Modifier.width(10.dp))
                                Column {
                                    MarketLinkLogoText(fontSize = 15f, showTagline = true)
                                    Spacer(modifier = Modifier.height(2.dp))
                                    Text(
                                        text = if (isSwahili) "Karibu tena, ${userProfile.fullName}!" else "Welcome back, ${userProfile.fullName}!",
                                        fontSize = 11.sp,
                                        color = Color.White.copy(alpha = 0.85f),
                                        fontWeight = FontWeight.Medium
                                    )
                                }
                            }

                            Surface(
                                shape = CircleShape,
                                color = BrandOrange
                            ) {
                                Text(
                                    text = "${userProfile.reputationScore} PTS",
                                    fontSize = 10.sp,
                                    fontWeight = FontWeight.ExtraBold,
                                    color = Color.White,
                                    modifier = Modifier.padding(horizontal = 8.dp, vertical = 4.dp)
                                )
                            }
                        }
                    }
                }
            }

            // Mode Selector for Organizations: My Organization Space vs Public Directory
            if (isOrganizationUser) {
                item {
                    Row(
                        modifier = Modifier.fillMaxWidth(),
                        horizontalArrangement = Arrangement.spacedBy(8.dp)
                    ) {
                        FilterChip(
                            selected = !isViewingPublicDirectory,
                            onClick = { onTogglePublicDirectory?.invoke(false) },
                            leadingIcon = {
                                Icon(
                                    imageVector = Icons.Default.Apartment,
                                    contentDescription = null,
                                    modifier = Modifier.size(14.dp),
                                    tint = if (!isViewingPublicDirectory) Color.White else BrandOrange
                                )
                            },
                            label = {
                                Text(
                                    text = if (isSwahili) "Nafasi Yangu ya Taasisi (${myOrganizationListings.size})" else "My Organization Space (${myOrganizationListings.size})",
                                    fontSize = 11.sp,
                                    fontWeight = FontWeight.Bold
                                )
                            },
                            colors = FilterChipDefaults.filterChipColors(
                                selectedContainerColor = BrandOrange,
                                selectedLabelColor = Color.White,
                                containerColor = SlateSurface,
                                labelColor = SlateTextPrimary
                            ),
                            modifier = Modifier.weight(1f)
                        )

                        FilterChip(
                            selected = isViewingPublicDirectory,
                            onClick = { onTogglePublicDirectory?.invoke(true) },
                            leadingIcon = {
                                Icon(
                                    imageVector = Icons.Default.Language,
                                    contentDescription = null,
                                    modifier = Modifier.size(14.dp),
                                    tint = if (isViewingPublicDirectory) Color.White else BrandBlue
                                )
                            },
                            label = {
                                Text(
                                    text = if (isSwahili) "Soko Kuu la Kitaifa" else "National Market Feed",
                                    fontSize = 11.sp,
                                    fontWeight = FontWeight.Bold
                                )
                            },
                            colors = FilterChipDefaults.filterChipColors(
                                selectedContainerColor = BrandBlue,
                                selectedLabelColor = Color.White,
                                containerColor = SlateSurface,
                                labelColor = SlateTextPrimary
                            ),
                            modifier = Modifier.weight(1f)
                        )
                    }
                }
            }

            // If not Organization user, or if viewing the Public Directory: Show Search and Filters
            if (!isOrganizationUser || isViewingPublicDirectory) {
                // Public Marketplace Notice Banner for Organizations
                if (isOrganizationUser && isViewingPublicDirectory) {
                    item {
                        Surface(
                            color = Color(0xFF0F172A),
                            shape = RoundedCornerShape(10.dp),
                            border = androidx.compose.foundation.BorderStroke(1.dp, BrandBlue.copy(alpha = 0.3f))
                        ) {
                            Row(
                                modifier = Modifier
                                    .fillMaxWidth()
                                    .padding(horizontal = 12.dp, vertical = 8.dp),
                                verticalAlignment = Alignment.CenterVertically
                            ) {
                                Icon(
                                    imageVector = Icons.Default.Info,
                                    contentDescription = null,
                                    tint = BrandBlue,
                                    modifier = Modifier.size(16.dp)
                                )
                                Spacer(modifier = Modifier.width(8.dp))
                                Text(
                                    text = if (isSwahili)
                                        "Unatazama Saraka ya Soko Kuu la Kitaifa (Soma Tu). Kila fursa inaonyesha wazi shirika lililoichapa."
                                    else
                                        "Browsing National Public Directory (Read-Only). Every listing explicitly displays its posting organization.",
                                    fontSize = 11.sp,
                                    color = Color.White.copy(alpha = 0.9f)
                                )
                            }
                        }
                    }
                }

                // Search Bar
                item {
                    OutlinedTextField(
                        value = searchQuery,
                        onValueChange = onSearchChange,
                        placeholder = {
                            Text(
                                text = if (isSwahili) "Tafuta kazi, ujuzi, au eneo (mf. Mbeya, Solar)..." else "Search jobs, skills, or region (e.g. Mbeya, Solar)...",
                                fontSize = 13.sp
                            )
                        },
                        leadingIcon = {
                            Icon(Icons.Default.Search, contentDescription = null, tint = EmeraldPrimary)
                        },
                        trailingIcon = {
                            if (searchQuery.isNotEmpty()) {
                                IconButton(onClick = { onSearchChange("") }) {
                                    Icon(Icons.Default.Clear, contentDescription = "Clear")
                                }
                            }
                        },
                        shape = RoundedCornerShape(12.dp),
                        colors = formTextFieldColors(),
                        modifier = Modifier
                            .fillMaxWidth()
                            .testTag("search_input")
                    )
                }

                // Category Horizontal Chips
                item {
                    Column {
                        Text(
                            text = if (isSwahili) "Aina ya Fursa:" else "Categories:",
                            fontSize = 12.sp,
                            fontWeight = FontWeight.Bold,
                            color = SlateTextSecondary
                        )
                        Spacer(modifier = Modifier.height(6.dp))
                        LazyRow(
                            horizontalArrangement = Arrangement.spacedBy(8.dp),
                            modifier = Modifier.fillMaxWidth()
                        ) {
                            item {
                                FilterChip(
                                    selected = selectedCategory == null && !filterOnlyMyMatches,
                                    onClick = {
                                        onCategorySelect(null)
                                        filterOnlyMyMatches = false
                                    },
                                    label = { Text(if (isSwahili) "Fursa Zote" else "All Opportunities", fontSize = 11.sp) },
                                    colors = FilterChipDefaults.filterChipColors(
                                        selectedContainerColor = EmeraldPrimary,
                                        selectedLabelColor = Color.White
                                    )
                                )
                            }
                            if (!isOrganizationUser) {
                                item {
                                    FilterChip(
                                        selected = filterOnlyMyMatches,
                                        onClick = {
                                            filterOnlyMyMatches = !filterOnlyMyMatches
                                            if (filterOnlyMyMatches) onCategorySelect(null)
                                        },
                                        leadingIcon = {
                                            Icon(
                                                Icons.Default.AutoAwesome,
                                                contentDescription = null,
                                                modifier = Modifier.size(13.dp),
                                                tint = if (filterOnlyMyMatches) EmeraldDark else GoldAccent
                                            )
                                        },
                                        label = {
                                            Text(
                                                if (isSwahili) "Zinazonifaa (AI Matched)" else "My Career Matches",
                                                fontSize = 11.sp,
                                                fontWeight = FontWeight.Bold
                                            )
                                        },
                                        colors = FilterChipDefaults.filterChipColors(
                                            selectedContainerColor = GoldAccent,
                                            selectedLabelColor = EmeraldDark,
                                            containerColor = EmeraldDark.copy(alpha = 0.15f),
                                            labelColor = EmeraldPrimary
                                        )
                                    )
                                }
                            }
                            items(OpportunityCategory.values()) { cat ->
                                FilterChip(
                                    selected = selectedCategory == cat && !filterOnlyMyMatches,
                                    onClick = {
                                        filterOnlyMyMatches = false
                                        onCategorySelect(if (selectedCategory == cat) null else cat)
                                    },
                                    label = { Text(if (isSwahili) cat.labelSw else cat.labelEn, fontSize = 11.sp) },
                                    colors = FilterChipDefaults.filterChipColors(
                                        selectedContainerColor = EmeraldPrimary,
                                        selectedLabelColor = Color.White
                                    )
                                )
                            }
                        }
                    }
                }

                // AI Spotlight Box for Workers
                if (!isOrganizationUser) {
                    item {
                        Card(
                            shape = RoundedCornerShape(12.dp),
                            colors = CardDefaults.cardColors(containerColor = EmeraldDark),
                            modifier = Modifier.fillMaxWidth()
                        ) {
                            Column(modifier = Modifier.padding(14.dp)) {
                                Row(
                                    modifier = Modifier.fillMaxWidth(),
                                    horizontalArrangement = Arrangement.SpaceBetween,
                                    verticalAlignment = Alignment.CenterVertically
                                ) {
                                    Row(verticalAlignment = Alignment.CenterVertically) {
                                        Icon(
                                            imageVector = Icons.Default.AutoAwesome,
                                            contentDescription = null,
                                            tint = GoldAccent,
                                            modifier = Modifier.size(18.dp)
                                        )
                                        Spacer(modifier = Modifier.width(6.dp))
                                        Text(
                                            text = if (isSwahili) "Injini ya Akili Mnemba (Linkora AI)" else "Linkora AI Smart Matcher",
                                            fontWeight = FontWeight.Bold,
                                            fontSize = 13.sp,
                                            color = Color.White
                                        )
                                    }

                                    TextButton(onClick = { showHeatmap = !showHeatmap }) {
                                        Icon(
                                            imageVector = if (showHeatmap) Icons.Default.ExpandLess else Icons.Default.Map,
                                            contentDescription = null,
                                            tint = GoldAccent,
                                            modifier = Modifier.size(14.dp)
                                        )
                                        Spacer(modifier = Modifier.width(4.dp))
                                        Text(
                                            text = if (showHeatmap) "Funga Ramani" else if (isSwahili) "Ramani ya Uhitaji" else "Demand Map",
                                            fontSize = 11.sp,
                                            color = GoldAccent
                                        )
                                    }
                                }

                                Text(
                                    text = if (isSwahili)
                                        "Linkora AI inalinganisha ujuzi wako na hali yako ya NIDA na fursa bora zaidi zenye ulinzi wa Escrow."
                                    else
                                        "Linkora AI analyzes your verified NIDA identity & skills against active regional demand for highest placement.",
                                    fontSize = 11.sp,
                                    color = Color.White.copy(alpha = 0.85f)
                                )
                            }
                        }
                    }

                    if (userProfile.primaryProfession.isNotBlank() || userProfile.district.isNotBlank()) {
                        item {
                            Surface(
                                color = Color(0xFF0F172A),
                                shape = RoundedCornerShape(10.dp),
                                border = androidx.compose.foundation.BorderStroke(1.dp, Color(0xFF334155))
                            ) {
                                Row(
                                    modifier = Modifier
                                        .fillMaxWidth()
                                        .padding(horizontal = 12.dp, vertical = 8.dp),
                                    verticalAlignment = Alignment.CenterVertically
                                ) {
                                    Icon(
                                        imageVector = Icons.Default.FilterCenterFocus,
                                        contentDescription = null,
                                        tint = GoldAccent,
                                        modifier = Modifier.size(16.dp)
                                    )
                                    Spacer(modifier = Modifier.width(8.dp))
                                    Text(
                                        text = if (isSwahili)
                                            "AI Matchmaker inakuelekeza kazi za '${userProfile.primaryProfession}' katika eneo la '${userProfile.district.ifBlank { userProfile.location }}'."
                                        else
                                            "AI Matchmaker targeting jobs for '${userProfile.primaryProfession}' in '${userProfile.district.ifBlank { userProfile.location }}'.",
                                        fontSize = 11.sp,
                                        color = Color.White.copy(alpha = 0.9f)
                                    )
                                }
                            }
                        }
                    }

                    item {
                        AnimatedVisibility(visible = showHeatmap) {
                            OpportunityHeatmap(
                                isSwahili = isSwahili,
                                selectedRegion = selectedRegion,
                                listings = listings,
                                onRegionSelect = onRegionSelect
                            )
                        }
                    }
                }
            }

            // SECTION A: Organization Workspace Mode (Dedicated, Isolated, Multi-Tenant)
            if (isOrganizationUser && !isViewingPublicDirectory) {
                if (myOrganizationListings.isEmpty()) {
                    // FRESH & EMPTY STATE FOR NEW ORGANIZATION
                    item {
                        Card(
                            shape = RoundedCornerShape(16.dp),
                            colors = CardDefaults.cardColors(containerColor = SlateSurface),
                            border = androidx.compose.foundation.BorderStroke(1.5.dp, BrandOrange.copy(alpha = 0.35f)),
                            modifier = Modifier
                                .fillMaxWidth()
                                .padding(vertical = 8.dp)
                        ) {
                            Column(
                                horizontalAlignment = Alignment.CenterHorizontally,
                                modifier = Modifier
                                    .fillMaxWidth()
                                    .padding(24.dp)
                            ) {
                                Surface(
                                    shape = CircleShape,
                                    color = BrandOrange.copy(alpha = 0.15f),
                                    modifier = Modifier.size(64.dp)
                                ) {
                                    Box(contentAlignment = Alignment.Center) {
                                        Icon(
                                            Icons.Default.Apartment,
                                            contentDescription = null,
                                            tint = BrandOrange,
                                            modifier = Modifier.size(34.dp)
                                        )
                                    }
                                }

                                Spacer(modifier = Modifier.height(14.dp))

                                Text(
                                    text = if (isSwahili) "Nafasi ya Shirika Lako Iko Tayari na Tupu" else "Your Organization Space is Fresh & Empty",
                                    fontWeight = FontWeight.ExtraBold,
                                    fontSize = 16.sp,
                                    color = SlateTextPrimary,
                                    textAlign = androidx.compose.ui.text.style.TextAlign.Center
                                )

                                Spacer(modifier = Modifier.height(8.dp))

                                Text(
                                    text = if (isSwahili)
                                        "Umekamilisha usajili kikamilifu! Kama inavyotakiwa kwenye mfumo salama wa Multi-Tenant, nafasi hii haina data zilizochanganywa kutoka mashirika mengine. Nafasi hii ni tupu kwa ajili yako ili uanze kuchapa na kusimamia fursa zako mwenyewe bila muingiliano wowote."
                                    else
                                        "Your organization profile is registered! In our multi-tenant architecture, your workspace is completely clean and independent. No seeded data or opportunities from other organizations exist here. You can now post and manage your own opportunities.",
                                    fontSize = 12.sp,
                                    color = SlateTextSecondary,
                                    textAlign = androidx.compose.ui.text.style.TextAlign.Center
                                )

                                Spacer(modifier = Modifier.height(20.dp))

                                Button(
                                    onClick = onCreateListingClick,
                                    colors = ButtonDefaults.buttonColors(containerColor = BrandOrange, contentColor = Color.White),
                                    shape = RoundedCornerShape(10.dp),
                                    modifier = Modifier.fillMaxWidth(0.85f)
                                ) {
                                    Icon(Icons.Default.AddCircle, contentDescription = null, modifier = Modifier.size(18.dp), tint = Color.White)
                                    Spacer(modifier = Modifier.width(6.dp))
                                    Text(
                                        text = if (isSwahili) "+ Chapa Fursa ya Kwanza ya Shirika" else "+ Post Your First Opportunity",
                                        fontWeight = FontWeight.Bold,
                                        fontSize = 13.sp,
                                        color = Color.White
                                    )
                                }
                            }
                        }
                    }
                } else {
                    // Organization's own posted listings
                    item {
                        Row(
                            modifier = Modifier.fillMaxWidth(),
                            horizontalArrangement = Arrangement.SpaceBetween,
                            verticalAlignment = Alignment.CenterVertically
                        ) {
                            Text(
                                text = "${myOrganizationListings.size} " + (if (isSwahili) "Fursa Zilizochapishwa na Shirika Lako" else "Active Postings by Your Organization"),
                                fontWeight = FontWeight.Bold,
                                fontSize = 15.sp,
                                color = SlateTextPrimary
                            )

                            Button(
                                onClick = onCreateListingClick,
                                colors = ButtonDefaults.buttonColors(containerColor = BrandOrange, contentColor = Color.White),
                                shape = RoundedCornerShape(8.dp),
                                contentPadding = PaddingValues(horizontal = 10.dp, vertical = 4.dp)
                            ) {
                                Icon(Icons.Default.Add, contentDescription = null, modifier = Modifier.size(14.dp))
                                Spacer(modifier = Modifier.width(4.dp))
                                Text(if (isSwahili) "+ Chapa Mpya" else "+ Post New", fontSize = 11.sp, fontWeight = FontWeight.Bold)
                            }
                        }
                    }

                    items(myOrganizationListings, key = { it.id }) { listing ->
                        ListingCard(
                            listing = listing,
                            isSwahili = isSwahili,
                            isOwner = true,
                            onCardClick = { onListingClick(listing) },
                            onQuickApplyClick = {},
                            onDeleteClick = { listingToDelete = listing }
                        )
                    }
                }
            } else {
                // SECTION B: Public Marketplace Feed (For Workers, or Organizations viewing Public Directory)
                val displayListings = if (filterOnlyMyMatches) listings.filter { it.matchScore >= 60 } else listings

                item {
                    Column(verticalArrangement = Arrangement.spacedBy(4.dp)) {
                        Row(
                            modifier = Modifier.fillMaxWidth(),
                            horizontalArrangement = Arrangement.SpaceBetween,
                            verticalAlignment = Alignment.CenterVertically
                        ) {
                            Row(verticalAlignment = Alignment.CenterVertically) {
                                Text(
                                    text = "${displayListings.size} ${if (isSwahili) "Fursa Zilizopatikana Sokoni" else "Active Market Opportunities"}",
                                    fontWeight = FontWeight.Bold,
                                    fontSize = 15.sp,
                                    color = SlateTextPrimary
                                )
                                if (onTriggerSync != null) {
                                    Spacer(modifier = Modifier.width(6.dp))
                                    IconButton(
                                        onClick = {
                                            isSyncing = true
                                            syncMessage = null
                                            onTriggerSync { success ->
                                                isSyncing = false
                                                syncMessage = if (success)
                                                    (if (isSwahili) "✓ Fursa zimesasishwa kikamilifu!" else "✓ Opportunities refreshed successfully!")
                                                else
                                                    (if (isSwahili) "✗ Hitilafu ya mtandao. Tafadhali jaribu tena." else "✗ Network error. Please try again.")
                                            }
                                        },
                                        modifier = Modifier.size(28.dp)
                                    ) {
                                        if (isSyncing) {
                                            CircularProgressIndicator(modifier = Modifier.size(16.dp), strokeWidth = 2.dp, color = EmeraldPrimary)
                                        } else {
                                            Icon(Icons.Default.Refresh, contentDescription = "Refresh", tint = EmeraldPrimary, modifier = Modifier.size(18.dp))
                                        }
                                    }
                                }
                            }

                            if (selectedRegion != "All Regions") {
                                Surface(
                                    shape = RoundedCornerShape(12.dp),
                                    color = EmeraldPrimary.copy(alpha = 0.1f)
                                ) {
                                    Text(
                                        text = selectedRegion,
                                        fontSize = 10.sp,
                                        fontWeight = FontWeight.Bold,
                                        color = EmeraldPrimary,
                                        modifier = Modifier.padding(horizontal = 8.dp, vertical = 2.dp)
                                    )
                                }
                            }
                        }

                        if (syncMessage != null) {
                            Text(
                                text = syncMessage!!,
                                fontSize = 11.sp,
                                fontWeight = FontWeight.Bold,
                                color = if (syncMessage!!.startsWith("✓")) BrandGreen else RedWarning
                            )
                        }
                    }
                }

                if (displayListings.isEmpty()) {
                    item {
                        Card(
                            shape = RoundedCornerShape(12.dp),
                            colors = CardDefaults.cardColors(containerColor = SlateSurface),
                            modifier = Modifier
                                .fillMaxWidth()
                                .padding(vertical = 12.dp)
                        ) {
                            Column(
                                horizontalAlignment = Alignment.CenterHorizontally,
                                modifier = Modifier
                                    .fillMaxWidth()
                                    .padding(24.dp)
                            ) {
                                Icon(
                                    imageVector = Icons.Default.WorkOutline,
                                    contentDescription = null,
                                    tint = EmeraldPrimary,
                                    modifier = Modifier.size(44.dp)
                                )
                                Spacer(modifier = Modifier.height(10.dp))
                                Text(
                                    text = if (filterOnlyMyMatches)
                                        (if (isSwahili) "Hakuna fursa zinazoendana moja kwa moja na taaluma yako kwa sasa." else "No opportunities matching your exact career profile at this time.")
                                    else
                                        (if (isSwahili) "Hakuna fursa zilizopatikana kwa sasa." else "No opportunities found at this time."),
                                    fontWeight = FontWeight.Bold,
                                    fontSize = 14.sp,
                                    color = SlateTextPrimary,
                                    textAlign = androidx.compose.ui.text.style.TextAlign.Center
                                )
                                Spacer(modifier = Modifier.height(6.dp))
                                Text(
                                    text = if (filterOnlyMyMatches)
                                        (if (isSwahili) "Bofya 'Fursa Zote' kuona kazi zote au bofya kitufe cha chini kupakia upya." else "Tap 'All Opportunities' to explore the full market feed or refresh below.")
                                    else
                                        (if (isSwahili) "Tafadhali jaribu kubadilisha vigezo au bofya kitufe cha chini kupakia upya." else "Try adjusting your filters or tap below to refresh the feed."),
                                    fontSize = 12.sp,
                                    color = SlateTextSecondary,
                                    textAlign = androidx.compose.ui.text.style.TextAlign.Center
                                )

                                Spacer(modifier = Modifier.height(16.dp))

                                if (onTriggerSync != null) {
                                    Button(
                                        onClick = {
                                            isSyncing = true
                                            syncMessage = null
                                            onTriggerSync { success ->
                                                isSyncing = false
                                                syncMessage = if (success)
                                                    (if (isSwahili) "✓ Fursa zimesasishwa kikamilifu!" else "✓ Feed refreshed!")
                                                else
                                                    (if (isSwahili) "✗ Hitilafu ya mtandao. Jaribu tena." else "✗ Network error. Try again.")
                                            }
                                        },
                                        enabled = !isSyncing,
                                        colors = ButtonDefaults.buttonColors(containerColor = EmeraldPrimary, contentColor = Color.White),
                                        shape = RoundedCornerShape(10.dp),
                                        modifier = Modifier.fillMaxWidth(0.7f)
                                    ) {
                                        if (isSyncing) {
                                            CircularProgressIndicator(color = Color.White, modifier = Modifier.size(16.dp), strokeWidth = 2.dp)
                                            Spacer(modifier = Modifier.width(6.dp))
                                            Text(if (isSwahili) "Inapakia..." else "Refreshing...", fontSize = 12.sp, color = Color.White)
                                        } else {
                                            Icon(Icons.Default.Refresh, contentDescription = null, modifier = Modifier.size(16.dp), tint = Color.White)
                                            Spacer(modifier = Modifier.width(6.dp))
                                            Text(if (isSwahili) "Pakia Upya Fursa" else "Refresh Opportunities", fontSize = 12.sp, fontWeight = FontWeight.Bold, color = Color.White)
                                        }
                                    }
                                }
                            }
                        }
                    }
                } else {
                    items(displayListings, key = { it.id }) { listing ->
                        val isMine = listing.posterId == userProfile.id ||
                                (userProfile.organizationName.isNotBlank() && listing.posterName.equals(userProfile.organizationName, ignoreCase = true))

                        ListingCard(
                            listing = listing,
                            isSwahili = isSwahili,
                            isOwner = isMine,
                            onCardClick = { onListingClick(listing) },
                            onQuickApplyClick = { onQuickApply(listing) },
                            onDeleteClick = if (isMine) { { listingToDelete = listing } } else null
                        )
                    }
                }
            }
        }
    }

    // Opportunity Deletion Confirmation Modal
    if (listingToDelete != null) {
        AlertDialog(
            onDismissRequest = { listingToDelete = null },
            icon = {
                Icon(
                    imageVector = Icons.Default.DeleteForever,
                    contentDescription = null,
                    tint = RedWarning,
                    modifier = Modifier.size(32.dp)
                )
            },
            title = {
                Text(
                    text = if (isSwahili) "Thibitisha Kufuta Fursa?" else "Confirm Delete Opportunity?",
                    fontWeight = FontWeight.Bold
                )
            },
            text = {
                Text(
                    text = if (isSwahili)
                        "Je, unathibitisha kuondoa fursa ya '${listingToDelete!!.title}'? Fursa hii itaondolewa mara moja kwenye mfumo na kwenye data za umma."
                    else
                        "Are you sure you want to delete '${listingToDelete!!.title}'? This opportunity will be removed from your workspace and the public directory immediately.",
                    fontSize = 13.sp
                )
            },
            confirmButton = {
                Button(
                    onClick = {
                        val id = listingToDelete!!.id
                        listingToDelete = null
                        onDeleteMyListing?.invoke(id)
                    },
                    colors = ButtonDefaults.buttonColors(containerColor = RedWarning)
                ) {
                    Text(if (isSwahili) "Ndio, Futa Fursa" else "Yes, Delete", color = Color.White, fontWeight = FontWeight.Bold)
                }
            },
            dismissButton = {
                TextButton(onClick = { listingToDelete = null }) {
                    Text(if (isSwahili) "Ghairi" else "Cancel")
                }
            }
        )
    }
}
