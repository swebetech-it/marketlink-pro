<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Listing;
use App\Models\VoiceInterview;

class VoiceJobAnnouncementTest extends TestCase
{
    use RefreshDatabase;
    public function test_voice_job_announcement_and_interview_pipeline()
    {
        // 1. Ensure a qualified candidate and an employer exist in Mbeya
        $employer = User::updateOrCreate(
            ['id' => 'emp_101'],
            [
                'full_name' => 'Jacob Mwansasu',
                'phone' => '+255711999888',
                'email' => 'jacob@swebetech.com',
                'role' => 'EMPLOYER',
                'account_type' => 'ORGANIZATION',
                'organization_name' => 'SwebeTech Enterprises',
                'location' => 'Mbeya',
                'district' => 'Soweto',
                'verification_status' => 'VERIFIED',
            ]
        );

        $candidate = User::updateOrCreate(
            ['id' => 'cand_seremala_101'],
            [
                'full_name' => 'Juma Mwambene Fundi',
                'phone' => '+255755123456',
                'email' => 'juma.seremala@test.com',
                'role' => 'JOB_SEEKER',
                'account_type' => 'INDIVIDUAL',
                'primary_profession' => 'CONSTRUCTION_MASONRY',
                'location' => 'Mbeya',
                'district' => 'Soweto',
                'bio' => 'Fundi seremala mzoefu wa milango, samani, na upauji wa mbao.',
                'cv_text' => 'Elimu ya VETA Mbeya. Zaidi ya miaka 5 ya uzoefu katika ujenzi wa milango ya mbao ngumu, meza na samani za ofisi. Nimemaliza miradi zaidi ya 30 bila dosari.',
                'skills' => ['Useremala', 'Kutengeneza Milango', 'Uzoefu wa Mbao', 'Samani'],
                'rating' => 4.9,
                'reputation_score' => 920,
                'completed_gigs' => 24,
                'verification_status' => 'VERIFIED',
            ]
        );

        // 2. Announce Job by Voice
        $spokenAnnouncement = "Nahitaji fundi seremala mwenye uzoefu wa kutengeneza milango ya mbao hapa Mbeya Soweto kwa siku 3, malipo ni laki moja na nusu kuanzia kesho.";

        $response = $this->postJson('/api/voice/announce-job', [
            'speech_text' => $spokenAnnouncement,
            'auto_interview' => true,
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('success', true);

        $listingData = $response->json('data.listing');
        $this->assertNotEmpty($listingData['id']);
        $this->assertEquals('CONSTRUCTION_MASONRY', $listingData['target_profession']);
        $this->assertEquals(150000, $listingData['compensation_amount_tzs']);

        $interviewData = $response->json('data.interview');
        $this->assertNotNull($interviewData);
        $this->assertEquals('cand_seremala_101', $interviewData['candidate_id']);
        $this->assertCount(3, $interviewData['questions']);

        $interviewId = $interviewData['id'];

        // 3. Test Simulating Candidate Interview Responses
        $simResponse = $this->postJson("/api/voice/interviews/{$interviewId}/simulate", [
            'answers' => [
                0 => "Ndio, nina uzoefu wa miaka mitano wa useremala na milango ya mbao ngumu hapa Mbeya.",
                1 => "Nipo tayari kuanza kesho asubuhi Soweto kwa siku zote 3.",
                2 => "Nina vifaa vyangu vyote binafsi na malipo ya laki moja na nusu yapo sawa.",
            ]
        ]);

        $simResponse->assertStatus(200);
        $simResponse->assertJsonPath('success', true);
        $simResponse->assertJsonPath('data.status', 'COMPLETED');
        $this->assertGreaterThanOrEqual(80, $simResponse->json('data.overall_score'));
        $this->assertEquals('HIGHLY_RECOMMENDED', $simResponse->json('data.recommendation'));
        $this->assertNotEmpty($simResponse->json('data.ai_summary'));
    }
}
