package com.example.ui.screens

import androidx.compose.foundation.background
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
import com.example.data.model.EscrowStatus
import com.example.data.model.Transaction
import com.example.ui.theme.*

@Composable
fun EscrowPaymentsScreen(
    transactions: List<Transaction>,
    isSwahili: Boolean,
    onReleaseEscrowClick: (transactionId: String) -> Unit,
    onDisputeClick: (transactionId: String) -> Unit
) {
    val totalInEscrow = remember(transactions) {
        transactions.filter { it.escrowStatus == EscrowStatus.HELD_IN_ESCROW }.sumOf { it.amountTzs }
    }

    val totalReleased = remember(transactions) {
        transactions.filter { it.escrowStatus == EscrowStatus.RELEASED }.sumOf { it.amountTzs }
    }

    var showDisputeDialogForId by remember { mutableStateOf<String?>(null) }

    LazyColumn(
        contentPadding = PaddingValues(bottom = 80.dp),
        verticalArrangement = Arrangement.spacedBy(14.dp),
        modifier = Modifier
            .fillMaxSize()
            .padding(16.dp)
            .testTag("escrow_payments_screen")
    ) {
        // Escrow Balance Summary Hero Card
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
                                imageVector = Icons.Default.AccountBalanceWallet,
                                contentDescription = null,
                                tint = GoldAccent,
                                modifier = Modifier.size(24.dp)
                            )
                            Spacer(modifier = Modifier.width(8.dp))
                            Text(
                                text = if (isSwahili) "Amana Zilizohifadhiwa Escrow" else "Linkora Escrow Vault",
                                fontWeight = FontWeight.Bold,
                                fontSize = 16.sp,
                                color = Color.White
                            )
                        }

                        Surface(
                            shape = RoundedCornerShape(6.dp),
                            color = BlueEscrow
                        ) {
                            Text(
                                text = "5% FEE",
                                fontSize = 10.sp,
                                fontWeight = FontWeight.Bold,
                                color = Color.White,
                                modifier = Modifier.padding(horizontal = 6.dp, vertical = 2.dp)
                            )
                        }
                    }

                    Spacer(modifier = Modifier.height(14.dp))

                    Row(
                        modifier = Modifier.fillMaxWidth(),
                        horizontalArrangement = Arrangement.SpaceBetween
                    ) {
                        Column {
                            Text(
                                text = if (isSwahili) "Zilizoshikiliwa Sasa" else "Currently Held in Escrow",
                                fontSize = 11.sp,
                                color = Color.White.copy(alpha = 0.7f)
                            )
                            Text(
                                text = "TZS ${String.format("%,d", totalInEscrow)}",
                                fontWeight = FontWeight.ExtraBold,
                                fontSize = 20.sp,
                                color = GoldAccent
                            )
                        }

                        Column(horizontalAlignment = Alignment.End) {
                            Text(
                                text = if (isSwahili) "Zilizotolewa Jumla" else "Total Disbursed",
                                fontSize = 11.sp,
                                color = Color.White.copy(alpha = 0.7f)
                            )
                            Text(
                                text = "TZS ${String.format("%,d", totalReleased)}",
                                fontWeight = FontWeight.ExtraBold,
                                fontSize = 16.sp,
                                color = Color.White
                            )
                        }
                    }

                    Spacer(modifier = Modifier.height(10.dp))
                    Divider(color = Color.White.copy(alpha = 0.2f))
                    Spacer(modifier = Modifier.height(10.dp))

                    Text(
                        text = if (isSwahili)
                            "✓ Inaingiliana na M-Pesa, Tigo Pesa, Airtel Money, CRDB na NMB Bank."
                        else
                            "✓ Integrated with M-Pesa, Tigo Pesa, Airtel Money, CRDB & NMB Bank payment rails.",
                        fontSize = 11.sp,
                        color = Color.White.copy(alpha = 0.85f)
                    )
                }
            }
        }

        // Ledger Title Header
        item {
            Row(
                modifier = Modifier.fillMaxWidth(),
                horizontalArrangement = Arrangement.SpaceBetween,
                verticalAlignment = Alignment.CenterVertically
            ) {
                Text(
                    text = if (isSwahili) "Daftari la Miamala la Kidijitali" else "Immutable Transaction Ledger",
                    fontWeight = FontWeight.Bold,
                    fontSize = 15.sp,
                    color = SlateTextPrimary
                )

                Text(
                    text = "${transactions.size} Logs",
                    fontSize = 12.sp,
                    color = SlateTextSecondary
                )
            }
        }

        // Transactions Feed
        items(transactions, key = { it.id }) { tx ->
            Card(
                shape = RoundedCornerShape(12.dp),
                colors = CardDefaults.cardColors(containerColor = SlateSurface),
                elevation = CardDefaults.cardElevation(defaultElevation = 2.dp),
                modifier = Modifier
                    .fillMaxWidth()
                    .testTag("transaction_item_${tx.id}")
            ) {
                Column(modifier = Modifier.padding(14.dp)) {
                    Row(
                        modifier = Modifier.fillMaxWidth(),
                        horizontalArrangement = Arrangement.SpaceBetween,
                        verticalAlignment = Alignment.CenterVertically
                    ) {
                        Text(
                            text = tx.listingTitle,
                            fontWeight = FontWeight.Bold,
                            fontSize = 14.sp,
                            color = SlateTextPrimary
                        )

                        // Status Badge
                        Surface(
                            shape = RoundedCornerShape(6.dp),
                            color = when (tx.escrowStatus) {
                                EscrowStatus.HELD_IN_ESCROW -> BlueEscrow
                                EscrowStatus.RELEASED -> GreenVerified
                                EscrowStatus.DISPUTED -> RedWarning
                                else -> SlateTextSecondary
                            }
                        ) {
                            Text(
                                text = if (isSwahili) tx.escrowStatus.labelSw else tx.escrowStatus.labelEn,
                                fontSize = 10.sp,
                                fontWeight = FontWeight.Bold,
                                color = Color.White,
                                modifier = Modifier.padding(horizontal = 6.dp, vertical = 2.dp)
                            )
                        }
                    }

                    Spacer(modifier = Modifier.height(6.dp))

                    Row(
                        modifier = Modifier.fillMaxWidth(),
                        horizontalArrangement = Arrangement.SpaceBetween
                    ) {
                        Column {
                            Text(text = "Mwajiri/Client: ${tx.payerName}", fontSize = 12.sp, color = SlateTextSecondary)
                            Text(text = "Payee/Worker: ${tx.payeeName}", fontSize = 12.sp, color = SlateTextSecondary)
                            Text(text = "Rail: ${tx.paymentMethod}", fontSize = 11.sp, color = EmeraldPrimary, fontWeight = FontWeight.Bold)
                        }

                        Column(horizontalAlignment = Alignment.End) {
                            Text(
                                text = "TZS ${String.format("%,d", tx.amountTzs)}",
                                fontWeight = FontWeight.ExtraBold,
                                fontSize = 15.sp,
                                color = EmeraldPrimary
                            )
                            Text(
                                text = "Fee: TZS ${String.format("%,d", tx.feeTzs)}",
                                fontSize = 10.sp,
                                color = SlateTextSecondary
                            )
                        }
                    }

                    Spacer(modifier = Modifier.height(6.dp))

                    Text(
                        text = "Audit Hash: ${tx.auditCode} • ${tx.timestamp}",
                        fontSize = 10.sp,
                        color = Color.Gray
                    )

                    if (tx.escrowStatus == EscrowStatus.HELD_IN_ESCROW) {
                        Spacer(modifier = Modifier.height(10.dp))
                        Row(
                            horizontalArrangement = Arrangement.spacedBy(8.dp),
                            modifier = Modifier.fillMaxWidth()
                        ) {
                            Button(
                                onClick = { onReleaseEscrowClick(tx.id) },
                                colors = ButtonDefaults.buttonColors(containerColor = GreenVerified, contentColor = Color.White),
                                shape = RoundedCornerShape(8.dp),
                                modifier = Modifier
                                    .weight(1f)
                                    .testTag("release_escrow_button_${tx.id}")
                            ) {
                                Icon(Icons.Default.Check, contentDescription = null, modifier = Modifier.size(14.dp), tint = Color.White)
                                Spacer(modifier = Modifier.width(4.dp))
                                Text(
                                    text = if (isSwahili) "Toa Pesa kwa Mfanyakazi" else "Release Escrow",
                                    fontSize = 11.sp,
                                    fontWeight = FontWeight.Bold,
                                    color = Color.White
                                )
                            }

                            OutlinedButton(
                                onClick = { showDisputeDialogForId = tx.id },
                                colors = ButtonDefaults.outlinedButtonColors(contentColor = RedWarning),
                                border = androidx.compose.foundation.BorderStroke(1.dp, RedWarning),
                                shape = RoundedCornerShape(8.dp),
                                modifier = Modifier
                                    .weight(1f)
                                    .testTag("flag_dispute_button_${tx.id}")
                            ) {
                                Icon(Icons.Default.ReportProblem, contentDescription = null, modifier = Modifier.size(14.dp))
                                Spacer(modifier = Modifier.width(4.dp))
                                Text(
                                    text = if (isSwahili) "Ripoti Mgogoro" else "Flag Dispute",
                                    fontSize = 11.sp
                                )
                            }
                        }
                    }
                }
            }
        }
    }

    // Dispute Dialog
    showDisputeDialogForId?.let { txId ->
        AlertDialog(
            onDismissRequest = { showDisputeDialogForId = null },
            title = {
                Text(if (isSwahili) "Ripoti Mgogoro wa Escrow" else "Flag Escrow Dispute")
            },
            text = {
                Text(
                    text = if (isSwahili)
                        "Mgogoro huu utasitisha kutoa pesa na utatumwa kwa timu ya usuluhishi ya Swebetech Company Limited kwa upatanishi."
                    else
                        "Flagging this dispute will pause fund disbursement and route transaction logs to Swebetech arbitration review.",
                    fontSize = 12.sp,
                    color = SlateTextSecondary
                )
            },
            confirmButton = {
                Button(
                    onClick = {
                        onDisputeClick(txId)
                        showDisputeDialogForId = null
                    },
                    colors = ButtonDefaults.buttonColors(containerColor = RedWarning, contentColor = Color.White)
                ) {
                    Text(if (isSwahili) "Wasilisha Mgogoro" else "Submit Dispute", color = Color.White)
                }
            },
            dismissButton = {
                TextButton(onClick = { showDisputeDialogForId = null }) {
                    Text(if (isSwahili) "Ghairi" else "Cancel")
                }
            }
        )
    }
}
