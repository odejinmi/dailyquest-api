<?php
//// routes/api.php
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Cache;
//use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\API\AuthController;
//use App\Http\Controllers\API\ChallengeController;
//use App\Http\Controllers\API\RewardController;
//use App\Http\Controllers\API\AchievementController;
//use App\Http\Controllers\API\LeaderboardController;
//use App\Http\Controllers\API\FriendController;
//use App\Http\Controllers\API\NotificationController;
//use App\Http\Controllers\API\PointsTransactionController;
//
///*
//|--------------------------------------------------------------------------
//| API Routes
//|--------------------------------------------------------------------------
//*/
//
//// routes/api.php
//Route::prefix('v1')->group(function () {
//// Public routes
//    Route::middleware('throttle:20,1')->group(function () {
//        Route::post('/register', [AuthController::class, 'register']);
//        Route::post('/login', [AuthController::class, 'login']);
//    });
//
//
//// Protected routes
//    Route::middleware('auth:sanctum', 'throttle:60,1')->group(function () {
//        // Auth
//        Route::post('/logout', [AuthController::class, 'logout']);
//        Route::get('/user', [AuthController::class, 'user']);
//        Route::post('/user/profile', [AuthController::class, 'updateProfile']);
//
//        // Challenges
//        Route::get('/challenges', [ChallengeController::class, 'index']);
//        Route::get('/challenges/daily', [ChallengeController::class, 'daily']);
//        Route::get('/challenges/{id}', [ChallengeController::class, 'show']);
//        Route::post('/challenges/{id}/complete', [ChallengeController::class, 'complete']);
//
//        // Rewards
//        Route::get('/rewards', [RewardController::class, 'index']);
//        Route::get('/rewards/claimed', [RewardController::class, 'claimed']);
//        Route::get('/rewards/{id}', [RewardController::class, 'show']);
//        Route::post('/rewards/{id}/claim', [RewardController::class, 'claim']);
//
//        // Achievements
//        Route::get('/achievements', [AchievementController::class, 'index']);
//        Route::get('/achievements/unlocked', [AchievementController::class, 'unlocked']);
//
//        // Leaderboard
//        Route::get('/leaderboard', [LeaderboardController::class, 'index']);
//
//        // Friends
//        Route::get('/friends', [FriendController::class, 'index']);
//        Route::get('/friends/requests', [FriendController::class, 'requests']);
//        Route::post('/friends/send', [FriendController::class, 'send']);
//        Route::post('/friends/{id}/accept', [FriendController::class, 'accept']);
//        Route::post('/friends/{id}/reject', [FriendController::class, 'reject']);
//        Route::delete('/friends/{id}', [FriendController::class, 'remove']);
//        Route::post('/friends/{id}/block', [FriendController::class, 'block']);
//        Route::post('/friends/{id}/unblock', [FriendController::class, 'unblock']);
//        Route::get('/friends/search', [FriendController::class, 'search']);
//
//        // Notifications
//        Route::get('/notifications', [NotificationController::class, 'index']);
//        Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
//        Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
//        Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
//
//        // Points Transactions
//        Route::get('/points/transactions', [PointsTransactionController::class, 'index']);
//        Route::post('/points/ad-reward', [PointsTransactionController::class, 'adReward']);
//    });
//
//    // routes/api.php
//    Route::get('/health', function () {
//        $databaseStatus = true;
//
//        try {
//            \DB::connection()->getPdo();
//        } catch (\Exception $e) {
//            $databaseStatus = false;
//        }
//
//        $cacheStatus = true;
//
//        try {
//            Cache::put('health_check', true, 10);
//            $cacheStatus = Cache::get('health_check', false);
//        } catch (\Exception $e) {
//            $cacheStatus = false;
//        }
//
//        $status = $databaseStatus && $cacheStatus ? 'ok' : 'error';
//        $httpStatus = $status === 'ok' ? 200 : 503;
//
//        return response()->json([
//            'status' => $status,
//            'services' => [
//                'database' => $databaseStatus ? 'ok' : 'error',
//                'cache' => $cacheStatus ? 'ok' : 'error',
//            ],
//            'version' => config('app.version', '1.0.0'),
//            'timestamp' => now()->toIso8601String(),
//        ], $httpStatus);
//    });
//
//});
