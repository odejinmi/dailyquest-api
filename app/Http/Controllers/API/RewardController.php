<?php

// app/Http/Controllers/API/RewardController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RewardController extends Controller
{
    public function index(Request $request)
    {
        $query = Reward::active();

        // Filter by category if provided
        if ($request->has('category')) {
            $query->byCategory($request->category);
        }

        $rewards = $query->get();

        // Add affordability flag for the authenticated user
        $user = $request->user();
        $rewards->each(function ($reward) use ($user) {
            $reward->can_afford = $user->points >= $reward->points_cost;
        });

        return response()->json(['rewards' => $rewards]);
    }

    public function show(Request $request, $id)
    {
        $reward = Reward::findOrFail($id);

        // Add affordability flag for the authenticated user
        $user = $request->user();
        $reward->can_afford = $user->points >= $reward->points_cost;

        return response()->json(['reward' => $reward]);
    }

    public function claim(Request $request, $id)
    {
        $reward = Reward::active()->findOrFail($id);
        $user = $request->user();

        // Check if user has enough points
        if ($user->points < $reward->points_cost) {
            return response()->json(['message' => 'Not enough points to claim this reward'], 422);
        }

        // Check if reward is in stock
        if ($reward->stock !== null && $reward->stock <= 0) {
            return response()->json(['message' => 'This reward is out of stock'], 422);
        }

        // Generate redemption code
        $redemptionCode = $this->generateRedemptionCode($reward);

        // Spend points
        $success = $user->spendPoints(
            $reward->points_cost,
            'reward_redemption',
            "Claimed reward: {$reward->title}",
            $reward->id,
            Reward::class
        );

        if (!$success) {
            return response()->json(['message' => 'Failed to process points transaction'], 500);
        }

        // Claim the reward
        $user->rewards()->attach($reward->id, [
            'status' => 'claimed',
            'redemption_code' => $redemptionCode,
            'claimed_at' => now(),
            'expires_at' => $this->calculateExpiryDate($reward),
        ]);

        // Decrement stock
        $reward->decrementStock();

        // Create notification
        $user->notifications()->create([
            // Continuing app/Http/Controllers/API/RewardController.php
            'title' => 'Reward Claimed!',
            'message' => "You've successfully claimed the {$reward->title} reward!",
            'type' => 'reward',
            'icon' => 'gift',
            'data' => json_encode([
                'reward_id' => $reward->id,
                'redemption_code' => $redemptionCode,
            ]),
        ]);

        // Dispatch event for achievement checking
        event('reward.claimed', [$user->id, $reward->id]);

        return response()->json([
            'message' => 'Reward claimed successfully',
            'redemption_code' => $redemptionCode,
            'user_points' => $user->points,
            'claimed_reward' => $user->rewards()
                ->where('reward_id', $reward->id)
                ->first()
                ->pivot,
        ]);
    }

    private function generateRedemptionCode(Reward $reward)
    {
        // Generate a unique redemption code based on reward type
        $prefix = strtoupper(substr($reward->reward_type, 0, 3));
        $unique = strtoupper(Str::random(8));

        return "{$prefix}-{$unique}";
    }

    private function calculateExpiryDate(Reward $reward)
    {
        // Set expiry date based on reward type
        switch ($reward->reward_type) {
            case 'gift_card':
                return now()->addYear();
            case 'booster':
                return now()->addDay();
            default:
                return now()->addMonths(3);
        }
    }

    public function claimed(Request $request)
    {
        $user = $request->user();

        $claimedRewards = $user->rewards()
            ->with('pivot')
            ->orderBy('user_rewards.claimed_at', 'desc')
            ->get();

        return response()->json(['claimed_rewards' => $claimedRewards]);
    }
}

