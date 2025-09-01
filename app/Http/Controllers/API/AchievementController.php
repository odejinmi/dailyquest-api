<?php

// app/Http/Controllers/API/AchievementController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    public function index(Request $request)
    {
        $achievements = Achievement::all();
        $user = $request->user();

        // Add unlock status for the authenticated user
        $userAchievements = $user->achievements()->pluck('achievement_id')->toArray();

        $achievements->each(function ($achievement) use ($userAchievements) {
            $achievement->is_unlocked = in_array($achievement->id, $userAchievements);
        });

        return response()->json(['achievements' => $achievements]);
    }

    public function unlocked(Request $request)
    {
        $user = $request->user();

        $unlockedAchievements = $user->achievements()
            ->withPivot('unlocked_at')
            ->orderBy('user_achievements.unlocked_at', 'desc')
            ->get();

        return response()->json(['unlocked_achievements' => $unlockedAchievements]);
    }
}

