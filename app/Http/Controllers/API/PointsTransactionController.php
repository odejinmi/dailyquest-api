<?php

// app/Http/Controllers/API/PointsTransactionController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PointsTransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $transactions = $user->pointsTransactions()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($transactions);
    }

    public function adReward(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'ad_type' => 'required|string|in:rewarded,interstitial,banner',
            'points' => 'required|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        $adType = $request->ad_type;
        $points = $request->points;

        // Add points for watching ad
        $transaction = $user->addPoints(
            $points,
            'ad_view',
            "Watched {$adType} ad",
            null,
            null
        );

        return response()->json([
            'message' => 'Points added successfully',
            'transaction' => $transaction,
            'user_points' => $user->points,
        ]);
    }
}

