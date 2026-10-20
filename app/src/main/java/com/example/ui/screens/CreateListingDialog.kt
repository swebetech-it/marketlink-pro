package com.example.ui.screens

import androidx.compose.foundation.layout.*
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.foundation.verticalScroll
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.ArrowDropDown
import androidx.compose.material.icons.filled.AutoAwesome
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.platform.testTag
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import com.example.data.model.OpportunityCategory
import com.example.ui.theme.EmeraldPrimary
import com.example.ui.theme.GoldAccent
import com.example.ui.theme.formTextFieldColors

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun CreateListingDialog(
    isSwahili: Boolean,
    onDismiss: () -> Unit,
    onSubmit: (
        title: String,
        category: OpportunityCategory,
        description: String,
        compensation: String,
        amountTzs: Long,
        location: String,
        skills: List<String>,
        isEscrow: Boolean,
        cropType: String?,
        equipmentDetails: String?,
        targetProfession: String?,
        jobTypeLevel: String,
        district: String?
    ) -> Unit
) {
    var title by remember { mutableStateOf("") }
    var selectedCategory by remember { mutableStateOf(OpportunityCategory.SKILLED_SERVICE) }
    var selectedProfessionCode by remember { mutableStateOf("CLEANING_SANITATION") }
    var professionDropdownExpanded by remember { mutableStateOf(false) }
    var isProfessionalJob by remember { mutableStateOf(false) }
    var description by remember { mutableStateOf("") }
    var compensation by remember { mutableStateOf("") }
    var amountTzsStr by remember { mutableStateOf("25000") }
    var location by remember { mutableStateOf("Mbeya City, Tanzania") }
    var district by remember { mutableStateOf("Mbeya Mjini") }
    var skillsStr by remember { mutableStateOf("Usafi wa Ofisi, Kudeki, Kuosha") }
    var isEscrowRequired by remember { mutableStateOf(true) }
    var cropType by remember { mutableStateOf("") }
    var equipmentDetails by remember { mutableStateOf("") }

    val professionsList = listOf(
        Pair("CLEANING_SANITATION", if (isSwahili) "🧹 Usafi na Mazingira" else "🧹 Cleaning & Sanitation"),
        Pair("SECURITY", if (isSwahili) "🛡️ Ulinzi na Usalama" else "🛡️ Security & Guarding"),
        Pair("DOMESTIC_WORK", if (isSwahili) "🏠 Kazi za Ndani" else "🏠 Domestic Work"),
        Pair("CONSTRUCTION_MASONRY", if (isSwahili) "🧱 Ujenzi na Useremala" else "🧱 Construction & Masonry"),
        Pair("ELECTRICAL", if (isSwahili) "⚡ Umeme na Sola" else "⚡ Electrical & Solar"),
        Pair("PLUMBING", if (isSwahili) "🚰 Mabomba ya Maji" else "🚰 Plumbing Services"),
        Pair("TRANSPORT_DRIVING", if (isSwahili) "🚗 Udereva na Usafirishaji" else "🚗 Driving & Logistics"),
        Pair("AGRICULTURE", if (isSwahili) "🌾 Kilimo na Uvunaji" else "🌾 Agriculture & Harvest"),
        Pair("HOSPITALITY_COOKING", if (isSwahili) "🍳 Upishi na Hoteli" else "🍳 Hospitality & Cooking"),
        Pair("ACCOUNTING_FINANCE", if (isSwahili) "📊 Uhasibu na Fedha" else "📊 Accounting & Finance"),
        Pair("IT_SOFTWARE", if (isSwahili) "💻 Tehama na Programu" else "💻 IT & Software"),
        Pair("HEALTHCARE", if (isSwahili) "🩺 Afya na Uuguzi" else "🩺 Healthcare & Nursing"),
        Pair("EDUCATION_TEACHING", if (isSwahili) "📚 Ualimu na Masomo" else "📚 Teaching & Tutoring"),
        Pair("MECHANICS", if (isSwahili) "🔧 Ufundi Magari na Mitambo" else "🔧 Mechanics & Automotive"),
        Pair("GENERAL_LABOR", if (isSwahili) "⚙️ Kibarua cha Jumla" else "⚙️ General Labor")
    )

    AlertDialog(
        onDismissRequest = onDismiss,
        title = {
            Row(verticalAlignment = Alignment.CenterVertically) {
                Icon(Icons.Default.AutoAwesome, contentDescription = null, tint = GoldAccent, modifier = Modifier.size(22.dp))
                Spacer(modifier = Modifier.width(6.dp))
                Text(
                    text = if (isSwahili) "Tangaza Kazi (Targeted Match)" else "Post Opportunity (Targeted Match)",
                    fontWeight = FontWeight.Bold,
                    fontSize = 17.sp,
                    color = EmeraldPrimary
                )
            }
        },
        text = {
            Column(
                modifier = Modifier
                    .verticalScroll(rememberScrollState())
                    .fillMaxWidth(),
                verticalArrangement = Arrangement.spacedBy(10.dp)
            ) {
                // Info banner about targeted AI dispatch
                Surface(
                    shape = RoundedCornerShape(8.dp),
                    color = EmeraldPrimary.copy(alpha = 0.1f)
                ) {
                    Text(
                        text = if (isSwahili)
                            "💡 AI itatambua na kuwataarifu moja kwa moja wafanyakazi wa fani hii waliopo eneo la kazi bila kuwasumbua wengine."
                        else
                            "💡 AI will specifically match and notify qualified candidates in this location via App & SMS.",
                        fontSize = 11.sp,
                        color = EmeraldPrimary,
                        modifier = Modifier.padding(8.dp)
                    )
                }

                OutlinedTextField(
                    value = title,
                    onValueChange = { title = it },
                    label = { Text(if (isSwahili) "Kichwa cha Kazi / Fursa *" else "Opportunity Title *") },
                    placeholder = { Text(if (isSwahili) "Mfano: Mfanyakazi wa Usafi wa Ofisi" else "e.g. Office Cleaning Specialist") },
                    singleLine = true,
                    colors = formTextFieldColors(),
                    modifier = Modifier
                        .fillMaxWidth()
                        .testTag("input_listing_title")
                )

                // Target Profession Dropdown
                Text(
                    text = if (isSwahili) "Fani ya Kazi (Ili kuwafikia walengwa sahihi) *" else "Target Profession (For targeted match) *",
                    fontSize = 12.sp,
                    fontWeight = FontWeight.Bold
                )

                Box(modifier = Modifier.fillMaxWidth()) {
                    OutlinedCard(
                        onClick = { professionDropdownExpanded = true },
                        modifier = Modifier.fillMaxWidth()
                    ) {
                        Row(
                            modifier = Modifier
                                .fillMaxWidth()
                                .padding(12.dp),
                            horizontalArrangement = Arrangement.SpaceBetween,
                            verticalAlignment = Alignment.CenterVertically
                        ) {
                            val selectedName = professionsList.firstOrNull { it.first == selectedProfessionCode }?.second ?: selectedProfessionCode
                            Text(text = selectedName, fontWeight = FontWeight.Bold, fontSize = 13.sp)
                            Icon(Icons.Default.ArrowDropDown, contentDescription = null)
                        }
                    }

                    DropdownMenu(
                        expanded = professionDropdownExpanded,
                        onDismissRequest = { professionDropdownExpanded = false }
                    ) {
                        professionsList.forEach { (code, name) ->
                            DropdownMenuItem(
                                text = { Text(name, fontSize = 13.sp) },
                                onClick = {
                                    selectedProfessionCode = code
                                    professionDropdownExpanded = false
                                    // Autofill suggested skills for fast input
                                    when (code) {
                                        "CLEANING_SANITATION" -> skillsStr = "Usafi wa Ofisi, Kudeki, Kuosha Mazulia"
                                        "SECURITY" -> skillsStr = "Ulinzi, CCTV, Doria za Usiku"
                                        "ELECTRICAL" -> skillsStr = "Wiring, Sola, Umeme wa Majumbani"
                                        "ACCOUNTING_FINANCE" -> skillsStr = "Uhasibu, TRA Tax, Tally Prime"
                                        "AGRICULTURE" -> skillsStr = "Uvunaji, Upaliliaji, Kilimo"
                                    }
                                }
                            )
                        }
                    }
                }

                // Job Level Selection (Professional vs Non-Professional)
                Row(
                    verticalAlignment = Alignment.CenterVertically,
                    horizontalArrangement = Arrangement.SpaceBetween,
                    modifier = Modifier.fillMaxWidth()
                ) {
                    Text(
                        text = if (isSwahili) "Kiwango cha Kazi:" else "Job Level:",
                        fontSize = 12.sp,
                        fontWeight = FontWeight.Bold
                    )
                    FilterChip(
                        selected = !isProfessionalJob,
                        onClick = { isProfessionalJob = false },
                        label = { Text(if (isSwahili) "Kazi ya Kawaida / Kibarua" else "Non-Professional / Gig", fontSize = 11.sp) }
                    )
                    FilterChip(
                        selected = isProfessionalJob,
                        onClick = { isProfessionalJob = true },
                        label = { Text(if (isSwahili) "Kazi ya Kitaalamu" else "Professional", fontSize = 11.sp) }
                    )
                }

                // Category Selection
                Text(
                    text = if (isSwahili) "Aina ya Fursa:" else "Category:",
                    fontSize = 12.sp,
                    fontWeight = FontWeight.Bold
                )
                Row(modifier = Modifier.fillMaxWidth()) {
                    ScrollableTabRow(
                        selectedTabIndex = OpportunityCategory.values().indexOf(selectedCategory),
                        edgePadding = 0.dp
                    ) {
                        OpportunityCategory.values().forEach { cat ->
                            Tab(
                                selected = selectedCategory == cat,
                                onClick = { selectedCategory = cat },
                                text = {
                                    Text(
                                        text = if (isSwahili) cat.labelSw else cat.labelEn,
                                        fontSize = 11.sp
                                    )
                                }
                            )
                        }
                    }
                }

                OutlinedTextField(
                    value = description,
                    onValueChange = { description = it },
                    label = { Text(if (isSwahili) "Maelezo ya Kazi na Majukumu *" else "Job Description & Tasks *") },
                    maxLines = 3,
                    colors = formTextFieldColors(),
                    modifier = Modifier
                        .fillMaxWidth()
                        .testTag("input_listing_description")
                )

                Row(
                    modifier = Modifier.fillMaxWidth(),
                    horizontalArrangement = Arrangement.spacedBy(8.dp)
                ) {
                    OutlinedTextField(
                        value = location,
                        onValueChange = { location = it },
                        label = { Text(if (isSwahili) "Mkoa / Mji *" else "Region / City *") },
                        singleLine = true,
                        colors = formTextFieldColors(),
                        modifier = Modifier.weight(1f)
                    )

                    OutlinedTextField(
                        value = district,
                        onValueChange = { district = it },
                        label = { Text(if (isSwahili) "Wilaya / Mtaa" else "District / Area") },
                        singleLine = true,
                        colors = formTextFieldColors(),
                        modifier = Modifier.weight(1f)
                    )
                }

                Row(
                    modifier = Modifier.fillMaxWidth(),
                    horizontalArrangement = Arrangement.spacedBy(8.dp)
                ) {
                    OutlinedTextField(
                        value = compensation,
                        onValueChange = { compensation = it },
                        label = { Text(if (isSwahili) "Malipo (mfano: TZS 25,000 / siku)" else "Pay (e.g. TZS 25,000 / day)") },
                        singleLine = true,
                        colors = formTextFieldColors(),
                        modifier = Modifier.weight(1.2f)
                    )

                    OutlinedTextField(
                        value = amountTzsStr,
                        onValueChange = { amountTzsStr = it },
                        label = { Text("Kiasi TZS") },
                        singleLine = true,
                        colors = formTextFieldColors(),
                        modifier = Modifier.weight(0.8f)
                    )
                }

                OutlinedTextField(
                    value = skillsStr,
                    onValueChange = { skillsStr = it },
                    label = { Text(if (isSwahili) "Sifa na Ujuzi (tenganisha kwa koma)" else "Required Skills (comma separated)") },
                    singleLine = true,
                    colors = formTextFieldColors(),
                    modifier = Modifier.fillMaxWidth()
                )

                if (selectedCategory == OpportunityCategory.AGRI_LABOR) {
                    OutlinedTextField(
                        value = cropType,
                        onValueChange = { cropType = it },
                        label = { Text(if (isSwahili) "Aina ya Zao (mfano: Mpunga, Mahindi, Chai)" else "Crop Type (e.g., Paddy, Tea)") },
                        singleLine = true,
                        colors = formTextFieldColors(),
                        modifier = Modifier.fillMaxWidth()
                    )
                }

                if (selectedCategory == OpportunityCategory.EQUIPMENT_RENTAL) {
                    OutlinedTextField(
                        value = equipmentDetails,
                        onValueChange = { equipmentDetails = it },
                        label = { Text(if (isSwahili) "Maelezo ya Zana / Trekta" else "Tractor / Equipment Specs") },
                        singleLine = true,
                        colors = formTextFieldColors(),
                        modifier = Modifier.fillMaxWidth()
                    )
                }

                Row(
                    verticalAlignment = Alignment.CenterVertically,
                    modifier = Modifier.fillMaxWidth()
                ) {
                    Checkbox(
                        checked = isEscrowRequired,
                        onCheckedChange = { isEscrowRequired = it }
                    )
                    Spacer(modifier = Modifier.width(4.dp))
                    Text(
                        text = if (isSwahili) "Tumia Mfumo wa Escrow (Kinga ya Malipo)" else "Require Escrow Payment Protection",
                        fontSize = 12.sp,
                        fontWeight = FontWeight.Medium
                    )
                }
            }
        },
        confirmButton = {
            Button(
                onClick = {
                    if (title.isNotBlank() && description.isNotBlank()) {
                        val skillsList = skillsStr.split(",").map { it.trim() }.filter { it.isNotEmpty() }
                        val amt = amountTzsStr.toLongOrNull() ?: 25000L
                        onSubmit(
                            title,
                            selectedCategory,
                            description,
                            compensation.ifBlank { "TZS $amt / task" },
                            amt,
                            location,
                            skillsList,
                            isEscrowRequired,
                            cropType.ifBlank { null },
                            equipmentDetails.ifBlank { null },
                            selectedProfessionCode,
                            if (isProfessionalJob) "PROFESSIONAL" else "NON_PROFESSIONAL",
                            district.ifBlank { null }
                        )
                    }
                },
                colors = ButtonDefaults.buttonColors(containerColor = EmeraldPrimary),
                shape = RoundedCornerShape(8.dp),
                modifier = Modifier.testTag("submit_listing_button")
            ) {
                Text(if (isSwahili) "Tangaza Kazi (Wafikie Wafanyakazi)" else "Publish & Dispatch Match")
            }
        },
        dismissButton = {
            TextButton(onClick = onDismiss) {
                Text(if (isSwahili) "Ghairi" else "Cancel")
            }
        }
    )
}
