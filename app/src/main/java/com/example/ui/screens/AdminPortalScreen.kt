package com.example.ui.screens

import android.annotation.SuppressLint
import android.content.Intent
import android.net.Uri
import android.view.ViewGroup
import android.webkit.WebChromeClient
import android.webkit.WebResourceRequest
import android.webkit.WebView
import android.webkit.WebViewClient
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.*
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.platform.testTag
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.compose.ui.viewinterop.AndroidView
import com.example.ui.theme.*

@SuppressLint("SetJavaScriptEnabled")
@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun AdminPortalScreen(
    isSwahili: Boolean,
    defaultUrl: String = "http://10.0.2.2:8000/admin",
    onCloseClick: (() -> Unit)? = null
) {
    val context = LocalContext.current
    var currentUrl by remember { mutableStateOf(defaultUrl) }
    var webViewInstance by remember { mutableStateOf<WebView?>(null) }
    var isLoading by remember { mutableStateOf(true) }
    var pageTitle by remember { mutableStateOf("Laravel Admin Management Portal") }
    var showUrlEditDialog by remember { mutableStateOf(false) }
    var customUrlInput by remember { mutableStateOf(currentUrl) }

    Column(
        modifier = Modifier
            .fillMaxSize()
            .background(SlateBackground)
            .testTag("admin_portal_screen")
    ) {
        // Top Toolbar
        Surface(
            color = SlateSurface,
            shadowElevation = 4.dp,
            modifier = Modifier.fillMaxWidth()
        ) {
            Column(modifier = Modifier.padding(horizontal = 14.dp, vertical = 10.dp)) {
                Row(
                    modifier = Modifier.fillMaxWidth(),
                    horizontalArrangement = Arrangement.SpaceBetween,
                    verticalAlignment = Alignment.CenterVertically
                ) {
                    Row(verticalAlignment = Alignment.CenterVertically) {
                        if (onCloseClick != null) {
                            IconButton(onClick = onCloseClick) {
                                Icon(Icons.Default.Close, contentDescription = "Close", tint = SlateTextPrimary)
                            }
                        } else {
                            Surface(
                                shape = RoundedCornerShape(8.dp),
                                color = EmeraldPrimary.copy(alpha = 0.15f),
                                modifier = Modifier.size(36.dp)
                            ) {
                                Box(contentAlignment = Alignment.Center) {
                                    Icon(Icons.Default.Web, contentDescription = null, tint = EmeraldPrimary, modifier = Modifier.size(20.dp))
                                }
                            }
                            Spacer(modifier = Modifier.width(10.dp))
                        }

                        Column {
                            Text(
                                text = if (isSwahili) "Tovuti ya Utawala (Admin Portal)" else "Laravel Web Admin Portal",
                                fontWeight = FontWeight.Bold,
                                fontSize = 14.sp,
                                color = SlateTextPrimary
                            )
                            Text(
                                text = currentUrl,
                                fontSize = 10.sp,
                                color = SlateTextSecondary,
                                maxLines = 1
                            )
                        }
                    }

                    Row(verticalAlignment = Alignment.CenterVertically) {
                        // Edit URL Button
                        IconButton(onClick = {
                            customUrlInput = currentUrl
                            showUrlEditDialog = true
                        }) {
                            Icon(Icons.Default.Edit, contentDescription = "Change URL", tint = SlateTextSecondary)
                        }

                        // Refresh Button
                        IconButton(onClick = {
                            webViewInstance?.reload()
                        }) {
                            Icon(Icons.Default.Refresh, contentDescription = "Reload", tint = EmeraldPrimary)
                        }

                        // Open in Chrome/Browser Button
                        IconButton(onClick = {
                            try {
                                val intent = Intent(Intent.ACTION_VIEW, Uri.parse(currentUrl))
                                context.startActivity(intent)
                            } catch (_: Exception) {}
                        }) {
                            Icon(Icons.Default.OpenInBrowser, contentDescription = "Open in Browser", tint = GoldAccent)
                        }
                    }
                }

                if (isLoading) {
                    LinearProgressIndicator(
                        modifier = Modifier
                            .fillMaxWidth()
                            .height(2.dp)
                            .padding(top = 4.dp),
                        color = EmeraldPrimary
                    )
                }
            }
        }

        // Info Banner
        Surface(
            color = Color(0xFF0F172A),
            modifier = Modifier.fillMaxWidth()
        ) {
            Row(
                modifier = Modifier.padding(horizontal = 14.dp, vertical = 6.dp),
                verticalAlignment = Alignment.CenterVertically,
                horizontalArrangement = Arrangement.SpaceBetween
            ) {
                Row(verticalAlignment = Alignment.CenterVertically) {
                    Icon(Icons.Default.Shield, contentDescription = null, tint = EmeraldPrimary, modifier = Modifier.size(14.dp))
                    Spacer(modifier = Modifier.width(6.dp))
                    Text(
                        text = if (isSwahili) "Mfumo wa Usimamizi wa Fursa & Escrow (Web Portal)"
                        else "System Ops: Post opportunities, verify NIDA KYC & audit Escrow",
                        fontSize = 11.sp,
                        color = Color.White
                    )
                }
                Text(
                    text = "Swebetech Admin",
                    fontSize = 10.sp,
                    fontWeight = FontWeight.Bold,
                    color = GoldAccent
                )
            }
        }

        // WebView Embed
        AndroidView(
            factory = { ctx ->
                WebView(ctx).apply {
                    layoutParams = ViewGroup.LayoutParams(
                        ViewGroup.LayoutParams.MATCH_PARENT,
                        ViewGroup.LayoutParams.MATCH_PARENT
                    )
                    settings.javaScriptEnabled = true
                    settings.domStorageEnabled = true
                    settings.loadWithOverviewMode = true
                    settings.useWideViewPort = true
                    settings.setSupportZoom(true)
                    settings.builtInZoomControls = true
                    settings.displayZoomControls = false

                    webChromeClient = object : WebChromeClient() {
                        override fun onReceivedTitle(view: WebView?, title: String?) {
                            if (!title.isNullOrBlank()) {
                                pageTitle = title
                            }
                        }
                    }

                    webViewClient = object : WebViewClient() {
                        override fun onPageStarted(view: WebView?, url: String?, favicon: android.graphics.Bitmap?) {
                            isLoading = true
                            if (url != null) currentUrl = url
                        }

                        override fun onPageFinished(view: WebView?, url: String?) {
                            isLoading = false
                        }

                        override fun shouldOverrideUrlLoading(view: WebView?, request: WebResourceRequest?): Boolean {
                            return false // Load inside WebView
                        }
                    }

                    loadUrl(currentUrl)
                    webViewInstance = this
                }
            },
            update = { view ->
                if (view.url != currentUrl && !currentUrl.isBlank()) {
                    view.loadUrl(currentUrl)
                }
            },
            modifier = Modifier
                .fillMaxSize()
                .weight(1f)
        )
    }

    // Change URL Dialog
    if (showUrlEditDialog) {
        AlertDialog(
            onDismissRequest = { showUrlEditDialog = false },
            title = {
                Text(
                    text = if (isSwahili) "Badilisha Anuani ya Web Portal" else "Configure Web Portal URL",
                    fontWeight = FontWeight.Bold,
                    fontSize = 16.sp
                )
            },
            text = {
                Column(verticalArrangement = Arrangement.spacedBy(8.dp)) {
                    Text(
                        text = if (isSwahili)
                            "Ingiza URL ya Laravel backend (k.m. http://10.0.2.2:8000/admin kwa emulator, au IP ya kompyuta yako):"
                        else
                            "Enter Laravel Web Portal URL (e.g. http://10.0.2.2:8000/admin for emulator, or your local LAN IP):",
                        fontSize = 12.sp,
                        color = SlateTextSecondary
                    )
                    OutlinedTextField(
                        value = customUrlInput,
                        onValueChange = { customUrlInput = it },
                        singleLine = true,
                        colors = formTextFieldColors(),
                        modifier = Modifier.fillMaxWidth()
                    )
                    Row(
                        horizontalArrangement = Arrangement.spacedBy(4.dp),
                        modifier = Modifier.padding(top = 4.dp)
                    ) {
                        SuggestionChip(
                            onClick = { customUrlInput = "http://10.0.2.2:8000/admin" },
                            label = { Text("Emulator (10.0.2.2)", fontSize = 10.sp) }
                        )
                        SuggestionChip(
                            onClick = { customUrlInput = "http://localhost:8000/admin" },
                            label = { Text("Localhost", fontSize = 10.sp) }
                        )
                    }
                }
            },
            confirmButton = {
                Button(
                    onClick = {
                        currentUrl = customUrlInput.trim()
                        showUrlEditDialog = false
                        webViewInstance?.loadUrl(currentUrl)
                    },
                    colors = ButtonDefaults.buttonColors(containerColor = EmeraldPrimary)
                ) {
                    Text(if (isSwahili) "Hifadhi & Fungua" else "Save & Load")
                }
            },
            dismissButton = {
                TextButton(onClick = { showUrlEditDialog = false }) {
                    Text(if (isSwahili) "Ghairi" else "Cancel")
                }
            }
        )
    }
}
