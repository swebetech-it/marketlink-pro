package com.example

import androidx.compose.ui.test.junit4.createComposeRule
import androidx.compose.ui.test.onNodeWithTag
import androidx.compose.ui.test.performClick
import androidx.compose.ui.test.performScrollTo
import androidx.compose.ui.test.performTextInput
import com.example.ui.screens.CreateListingDialog
import com.example.ui.screens.UserRegistrationDialog
import com.example.ui.theme.MarketLinkProTheme
import org.junit.Rule
import org.junit.Test
import org.junit.runner.RunWith
import org.robolectric.RobolectricTestRunner
import org.robolectric.annotation.Config

@RunWith(RobolectricTestRunner::class)
@Config(sdk = [34])
class DialogsRobolectricTest {

    @get:Rule
    val composeTestRule = createComposeRule()

    @Test
    fun testUserRegistrationDialogTyping() {
        var submitted = false
        composeTestRule.setContent {
            MarketLinkProTheme {
                UserRegistrationDialog(
                    isSwahili = false,
                    onDismiss = {},
                    onRegisterSubmit = { _, _, _, _, _, _, _, _, _, _, _, _, _, _, _, onResult ->
                        submitted = true
                        onResult(true, null)
                    }
                )
            }
        }

        // Test typing into Full Name
        composeTestRule.onNodeWithTag("register_fullname_input").performTextInput("Amina Bakari")

        // Test typing into Phone
        composeTestRule.onNodeWithTag("register_phone_input").performTextInput("755123456")

        // Test typing into Email
        composeTestRule.onNodeWithTag("register_email_input").performTextInput("amina@example.com")

        // Test clicking submit button with scroll
        composeTestRule.onNodeWithTag("submit_user_registration_button").performScrollTo().performClick()

        assert(submitted)
    }

    @Test
    fun testCreateListingDialogTyping() {
        var created = false
        composeTestRule.setContent {
            MarketLinkProTheme {
                CreateListingDialog(
                    isSwahili = false,
                    onDismiss = {},
                    onSubmit = { _, _, _, _, _, _, _, _, _, _, _, _, _ ->
                        created = true
                    }
                )
            }
        }

        // Test typing title
        composeTestRule.onNodeWithTag("input_listing_title").performTextInput("Solar Technician Needed")

        // Test typing description
        composeTestRule.onNodeWithTag("input_listing_description").performTextInput("Need an experienced technician to service 5KW solar array.")

        // Test submit
        composeTestRule.onNodeWithTag("submit_listing_button").performClick()

        assert(created)
    }
}
