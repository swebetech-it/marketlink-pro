package com.example

import androidx.compose.ui.test.junit4.createComposeRule
import androidx.compose.ui.test.onNodeWithTag
import androidx.compose.ui.test.performClick
import androidx.compose.ui.test.performTextInput
import com.example.ui.screens.AuthScreen
import com.example.ui.theme.MarketLinkProTheme
import org.junit.Rule
import org.junit.Test
import org.junit.runner.RunWith
import org.robolectric.RobolectricTestRunner
import org.robolectric.annotation.Config

@RunWith(RobolectricTestRunner::class)
@Config(sdk = [34])
class AuthScreenRobolectricTest {

    @get:Rule
    val composeTestRule = createComposeRule()

    @Test
    fun testAuthScreenTypingAndTabSwitching() {
        composeTestRule.setContent {
            MarketLinkProTheme {
                AuthScreen(
                    isSwahili = false,
                    allUsers = emptyList(),
                    onLanguageToggle = {},
                    onSignInSuccess = {},
                    onSignInWithCredentials = { _, _, onResult -> onResult(false, "Invalid") },
                    onRegisterSubmit = { _, _, _, _, _, _, _, _, _, _, _, _, _, _, _, onResult ->
                        onResult(true, null)
                    }
                )
            }
        }

        // Test typing in phone field
        composeTestRule.onNodeWithTag("signin_phone_input").performTextInput("+255712345678")

        // Test typing in password field
        composeTestRule.onNodeWithTag("signin_password_input").performTextInput("123456")

        // Switch to Sign Up tab
        composeTestRule.onNodeWithTag("auth_tab_sign_up").performClick()

        // Type in Sign Up full name
        composeTestRule.onNodeWithTag("signup_fullname_input").performTextInput("Juma Ali")

        // Type in Sign Up phone
        composeTestRule.onNodeWithTag("signup_phone_input").performTextInput("+255788990011")

        // Click Sign Up submit
        composeTestRule.onNodeWithTag("sign_up_submit_button").performClick()
    }
}
