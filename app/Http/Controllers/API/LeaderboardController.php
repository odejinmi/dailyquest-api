<?php

// app/Http/Controllers/API/LeaderboardController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = $request->user();

        // Get time period filter
        $period = $request->get('period', 'week');

        // Get category filter (all, friends, etc.)
        $category = $request->get('category', 'all');

        // Base query
        $query = User::select('id', 'name', 'profile_image', 'points', 'streak');

        // Apply time period filter to points
        switch ($period) {
            case 'today':
                // For a real implementation, you'd need a daily_points column or a points_transactions table
                // This is simplified for the example
                break;

            case 'month':
                // For a real implementation, you'd filter points earned in the current month
                break;

            case 'all_time':
                // Use total points (default)
                break;

            case 'week':
            default:
                // For a real implementation, you'd filter points earned in the current week
                break;
        }

        // Apply category filter
        if ($category === 'friends') {
            // Get IDs of user's friends
            $friendIds = $currentUser->friends()
                ->wherePivot('status', 'accepted')
                ->pluck('friend_id')
                ->toArray();

            // Add current user to the list
            $friendIds[] = $currentUser->id;

            $query->whereIn('id', $friendIds);
        }

        // Get top users
        $topUsers = $query->orderBy('points', 'desc')
            ->limit(100)
            ->get();

        // Find current user's rank
        $currentUserRank = $topUsers->search(function ($user) use ($currentUser) {
            return $user->id === $currentUser->id;
        });

        // If user is not in top 100, add them separately
        if ($currentUserRank === false) {
            // Count users with more points
            $currentUserRank = User::where('points', '>', $currentUser->points)->count();

            // Add current user with rank
            $currentUser->rank = $currentUserRank + 1;
            $topUsers->push($currentUser);
        } else {
            // Add rank to each user
            $topUsers = $topUsers->map(function ($user, $index) {
                $user->rank = $index + 1;
                return $user;
            });
        }

        return response()->json([
            'leaderboard' => $topUsers,
            'current_user' => [
                'id' => $currentUser->id,
                'rank' => $currentUserRank + 1,
            ],
            'period' => $period,
            'category' => $category,
        ]);
    }
}

