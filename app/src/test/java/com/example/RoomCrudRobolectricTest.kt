package com.example

import android.content.Context
import androidx.room.Room
import androidx.test.core.app.ApplicationProvider
import com.example.data.local.MarketLinkDatabase
import com.example.data.model.*
import kotlinx.coroutines.flow.first
import kotlinx.coroutines.runBlocking
import org.junit.After
import org.junit.Assert.*
import org.junit.Before
import org.junit.Test
import org.junit.runner.RunWith
import org.robolectric.RobolectricTestRunner
import org.robolectric.annotation.Config
import java.io.IOException

@RunWith(RobolectricTestRunner::class)
@Config(sdk = [34])
class RoomCrudRobolectricTest {

    private lateinit var db: MarketLinkDatabase

    @Before
    fun createDb() {
        val context = ApplicationProvider.getApplicationContext<Context>()
        db = Room.inMemoryDatabaseBuilder(context, MarketLinkDatabase::class.java)
            .allowMainThreadQueries()
            .build()
    }

    @After
    @Throws(IOException::class)
    fun closeDb() {
        db.close()
    }

    @Test
    fun testUserProfileCrud() = runBlocking {
        val dao = db.userProfileDao()

        // 1. CREATE
        val profile = UserProfile(
            id = "usr_test_1",
            fullName = "Juma Bakari",
            phone = "+255755112233",
            email = "juma@linkora.co.tz",
            role = UserRole.JOB_SEEKER,
            accountType = "INDIVIDUAL",
            verificationStatus = VerificationStatus.VERIFIED,
            location = "Mbeya City",
            bio = "Skilled electrician and solar installer",
            skills = listOf("Solar", "Electrical"),
            rating = 4.8f,
            reputationScore = 910
        )
        dao.insertOrUpdateProfile(profile)

        // 2. READ
        val fetched = dao.getUserProfileOnce("usr_test_1")
        assertNotNull(fetched)
        assertEquals("Juma Bakari", fetched?.fullName)
        assertEquals(910, fetched?.reputationScore)

        // 3. UPDATE
        val updated = profile.copy(reputationScore = 950, communityVouches = 5)
        dao.insertOrUpdateProfile(updated)
        val fetchedUpdated = dao.getUserProfileOnce("usr_test_1")
        assertEquals(950, fetchedUpdated?.reputationScore)
        assertEquals(5, fetchedUpdated?.communityVouches)

        // 4. DELETE
        dao.deleteUserProfile("usr_test_1")
        val fetchedDeleted = dao.getUserProfileOnce("usr_test_1")
        assertNull(fetchedDeleted)
    }

    @Test
    fun testListingCrud() = runBlocking {
        val dao = db.listingDao()

        // 1. CREATE
        val listing = Listing(
            id = "lst_test_1",
            posterId = "emp_101",
            posterName = "AgriCoop Tanzania",
            posterVerification = VerificationStatus.VERIFIED,
            posterRating = 4.9f,
            category = OpportunityCategory.AGRI_LABOR,
            title = "Maize Harvesting Crew",
            description = "10 laborers needed for 50-acre harvest in Ihemi.",
            location = "Iringa, Tanzania",
            compensation = "TZS 30,000 / day",
            compensationAmountTzs = 30000L,
            requiredSkills = listOf("Harvesting", "Bagging"),
            datePosted = "2026-11-01",
            deadline = "2026-11-10",
            isFeatured = true
        )
        dao.insertListing(listing)

        // 2. READ
        val fetched = dao.getListingById("lst_test_1")
        assertNotNull(fetched)
        assertEquals("Maize Harvesting Crew", fetched?.title)
        assertEquals(30000L, fetched?.compensationAmountTzs)

        val allListings = dao.getAllListings().first()
        assertTrue(allListings.any { it.id == "lst_test_1" })

        // 3. DELETE
        dao.deleteListing("lst_test_1")
        val deleted = dao.getListingById("lst_test_1")
        assertNull(deleted)
    }

    @Test
    fun testApplicationAndTransactionCrud() = runBlocking {
        val appDao = db.applicationDao()
        val txDao = db.transactionDao()

        // Application CRUD
        val app = Application(
            id = "app_test_1",
            listingId = "lst_test_1",
            listingTitle = "Maize Harvesting",
            applicantId = "usr_test_1",
            applicantName = "Juma Bakari",
            pitchMessage = "Ready with full crew tomorrow",
            status = "Applied",
            appliedDate = "2026-11-01"
        )
        appDao.insertApplication(app)
        val allApps = appDao.getAllApplications().first()
        assertTrue(allApps.any { it.id == "app_test_1" })

        // Transaction CRUD
        val tx = Transaction(
            id = "tx_test_1",
            listingId = "lst_test_1",
            listingTitle = "Maize Harvesting",
            payerName = "AgriCoop",
            payeeName = "Juma Bakari",
            amountTzs = 300000L,
            feeTzs = 15000L,
            escrowStatus = EscrowStatus.HELD_IN_ESCROW,
            paymentMethod = "M-Pesa",
            timestamp = "2026-11-01 10:00",
            auditCode = "AUD-12345"
        )
        txDao.insertTransaction(tx)
        var allTxs = txDao.getAllTransactions().first()
        assertEquals(1, allTxs.size)
        assertEquals(EscrowStatus.HELD_IN_ESCROW, allTxs.first().escrowStatus)

        // Update Escrow Status
        txDao.updateEscrowStatus("tx_test_1", EscrowStatus.RELEASED)
        allTxs = txDao.getAllTransactions().first()
        assertEquals(EscrowStatus.RELEASED, allTxs.first().escrowStatus)
    }
}
