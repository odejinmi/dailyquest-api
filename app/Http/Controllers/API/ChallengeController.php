<?php

// app/Http/Controllers/API/ChallengeController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ChallengeResource;

class ChallengeController extends Controller
{
//    public function index(Request $request)
//    {
//        $query = Challenge::active();
//
//        // Filter by category if provided
//        if ($request->has('category')) {
//            $query->byCategory($request->category);
//        }
//
//        $challenges = $query->get();
//
//        // Add completion status for the authenticated user
//        $user = $request->user();
//        $challenges->each(function ($challenge) use ($user) {
//            $userChallenge = $user->challenges()->where('challenge_id', $challenge->id)->first();
//            $challenge->is_completed = $userChallenge ? $userChallenge->pivot->is_completed : false;
//            $challenge->completed_at = $userChallenge ? $userChallenge->pivot->completed_at : null;
//        });
//
//        return response()->json(['challenges' => $challenges]);
//    }

    public function index(Request $request)
    {
        $query = Challenge::active();

        // Filter by category if provided
        if ($request->has('category')) {
            $query->byCategory($request->category);
        }

        $challenges = $query->get();

        return ChallengeResource::collection($challenges);
    }

//    public function show(Request $request, $id)
//    {
//        $challenge = Challenge::findOrFail($id);
//
//        // Add completion status for the authenticated user
//        $user = $request->user();
//        $userChallenge = $user->challenges()->where('challenge_id', $challenge->id)->first();
//        $challenge->is_completed = $userChallenge ? $userChallenge->pivot->is_completed : false;
//        $challenge->completed_at = $userChallenge ? $userChallenge->pivot->completed_at : null;
//
//        return response()->json(['challenge' => $challenge]);
//    }

// Update the show method
    public function show(Request $request, $id)
    {
        $challenge = Challenge::findOrFail($id);

        return new ChallengeResource($challenge);
    }
    public function complete(Request $request, $id)
    {
        $challenge = Challenge::findOrFail($id);
        $user = $request->user();

        // Check if challenge is already completed
        $userChallenge = $user->challenges()->where('challenge_id', $challenge->id)->first();
        if ($userChallenge && $userChallenge->pivot->is_completed) {
            return response()->json(['message' => 'Challenge already completed'], 422);
        }

        // Complete the challenge
        $user->challenges()->syncWithoutDetaching([
            $challenge->id => [
                'is_completed' => true,
                'completed_at' => now(),
            ]
        ]);

        // Award points
        $user->addPoints(
            $challenge->points,
            'challenge_completion',
            "Completed challenge: {$challenge->title}",
            $challenge->id,
            Challenge::class
        );

        // Create notification
        $user->notifications()->create([
            'title' => 'Challenge Completed!',
            'message' => "You've completed the {$challenge->title} challenge and earned {$challenge->points} points!",
            'type' => 'challenge',
            'icon' => $challenge->icon,
            'data' => json_encode(['challenge_id' => $challenge->id]),
        ]);

//        // Check for achievements
//        $this->checkChallengeAchievements($user);
        // Dispatch event for achievement checking
        event('challenge.completed', [$user->id, $challenge->id, $challenge->category]);

        return response()->json([
            'message' => 'Challenge completed successfully',
            'points_earned' => $challenge->points,
            'user_points' => $user->points,
        ]);
    }

    private function checkChallengeAchievements(User $user)
    {
        // Get total completed challenges
        $completedCount = $user->challenges()->wherePivot('is_completed', true)->count();

        // Check for challenge count achievements
        $achievements = \App\Models\Achievement::where('category', 'challenges')->get();

        foreach ($achievements as $achievement) {
            $criteria = json_decode($achievement->criteria);
            if (isset($criteria->completed_challenges) && $completedCount >= $criteria->completed_challenges) {
                $user->unlockAchievement($achievement);
            }
        }
    }

//    public function daily(Request $request)
//    {
//        $user = $request->user();
//
//        // Get today's date
//        $today = now()->startOfDay();
//
//        // Get active challenges
//        $challenges = Challenge::active()
//            ->inRandomOrder()
//            ->limit(5) // Limit to 5 daily challenges
//            ->get();
//
//        // Add completion status for the authenticated user
//        $challenges->each(function ($challenge) use ($user, $today) {
//            $userChallenge = $user->challenges()
//                ->where('challenge_id', $challenge->id)
//                ->first();
//
//            $challenge->is_completed = $userChallenge && $userChallenge->pivot->is_completed &&
//                $userChallenge->pivot->completed_at->startOfDay()->equalTo($today);
//            $challenge->completed_at = $userChallenge ? $userChallenge->pivot->completed_at : null;
//        });
//
//        return response()->json([
//            'daily_challenges' => $challenges,
//            'date' => $today->toDateString(),
//            'streak' => $user->streak,
//        ]);
//    }

// Update the daily method
    public function daily(Request $request)
    {
        $user = $request->user();
        $userId = $user->id;
        $today = now()->startOfDay()->toDateString();

        // Use cache for daily challenges
        $cacheKey = "daily_challenges_{$userId}_{$today}";

        return Cache::remember($cacheKey, now()->endOfDay(), function () use ($user, $today) {
            // Get active challenges
            $challenges = Challenge::active()
                ->inRandomOrder()
                ->limit(5) // Limit to 5 daily challenges
                ->get();

            return response()->json([
                'daily_challenges' => ChallengeResource::collection($challenges),
                'date' => $today,
                'streak' => $user->streak,
            ]);
        });
    }
}

