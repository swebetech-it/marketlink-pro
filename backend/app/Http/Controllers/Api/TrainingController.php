<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\User;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    public function index()
    {
        $currentUser = User::where('id', 'current_user')->first() ?? User::first();
        $userId = $currentUser ? $currentUser->id : 'usr_101';

        $courses = Course::all()->map(function ($c) use ($userId) {
            $enrollment = CourseEnrollment::where('course_id', $c->id)
                                          ->where('user_id', $userId)
                                          ->first();
            return [
                'id' => (string)$c->id,
                'title' => $c->title,
                'providerName' => $c->provider_name,
                'category' => $c->category,
                'duration' => $c->duration,
                'priceTzs' => (int)$c->price_tzs,
                'rating' => (float)$c->rating,
                'isEnrolled' => $enrollment ? (bool)$enrollment->is_enrolled : false,
                'progressPercent' => $enrollment ? (int)$enrollment->progress_percent : 0,
                'certificateIssued' => $enrollment ? (bool)$enrollment->certificate_issued : false,
                'skillBadgeName' => $c->skill_badge_name,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $courses,
        ]);
    }

    public function enroll(string $id)
    {
        $currentUser = User::where('id', 'current_user')->first() ?? User::first();
        $userId = $currentUser ? $currentUser->id : 'usr_101';

        $enrollment = CourseEnrollment::updateOrCreate(
            ['course_id' => $id, 'user_id' => $userId],
            ['is_enrolled' => true, 'progress_percent' => 10]
        );

        $course = Course::findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Enrolled in training course successfully',
            'data' => [
                'id' => (string)$course->id,
                'title' => $course->title,
                'providerName' => $course->provider_name,
                'category' => $course->category,
                'duration' => $course->duration,
                'priceTzs' => (int)$course->price_tzs,
                'rating' => (float)$course->rating,
                'isEnrolled' => true,
                'progressPercent' => 10,
                'certificateIssued' => false,
                'skillBadgeName' => $course->skill_badge_name,
            ],
        ]);
    }
}
