<?php
// app/Providers/AchievementServiceProvider.php
namespace App\Providers;

use App\Models\Achievement;
use App\Models\User;
use Illuminate\Support\ServiceProvider;

class AchievementServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Listen for challenge completion
        \Event::listen('challenge.completed', function ($userId, $challengeId, $category) {
            $user = User::find($userId);
            if (!$user) return;

            // Check for category-specific achievements
            $this->checkCategoryAchievements($user, $category);

            // Check for total challenges completed
            $this->checkTotalChallengesAchievements($user);
        });

        // Listen for reward claims
        \Event::listen('reward.claimed', function ($userId, $rewardId) {
            $user = User::find($userId);
            if (!$user) return;

            // Check for reward achievements
            $this->checkRewardAchievements($user);
        });

        // Listen for friend connections
        \Event::listen('friend.added', function ($userId) {
            $user = User::find($userId);
            if (!$user) return;

            // Check for social achievements
            $this->checkSocialAchievements($user);
        });
    }

    /**
     * Check for category-specific achievements
     */
    private function checkCategoryAchievements(User $user, $category)
    {
        // Count completed challenges in this category
        $count = $user->challenges()
            ->wherePivot('is_completed', true)
            ->where('category', $category)
            ->count();

        // Find achievements for this category
        $achievements = Achievement::where('category', 'category_specific')
            ->whereJsonContains('criteria->category', $category)
            ->get();

        foreach ($achievements as $achievement) {
            $criteria = json_decode($achievement->criteria);
            if (isset($criteria->category_challenges) && $count >= $criteria->category_challenges) {
                $user->unlockAchievement($achievement);
            }
        }
    }

    /**
     * Check for total challenges completed achievements
     */
    private function checkTotalChallengesAchievements(User $user)
    {
        // Count total completed challenges
        $count = $user->challenges()
            ->wherePivot('is_completed', true)
            ->count();

        // Find achievements for total challenges
        $achievements = Achievement::where('category', 'challenges')->get();

        foreach ($achievements as $achievement) {
            $criteria = json_decode($achievement->criteria);
            if (isset($criteria->completed_challenges) && $count >= $criteria->completed_challenges) {
                $user->unlockAchievement($achievement);
            }
        }
    }

    /**
     * Check for reward achievements
     */
    private function checkRewardAchievements(User $user)
    {
        // Count claimed rewards
        $count = $user->rewards()->count();

        // Find achievements for rewards
        $achievements = Achievement::where('category', 'rewards')->get();

        foreach ($achievements as $achievement) {
            $criteria = json_decode($achievement->criteria);
            if (isset($criteria->claimed_rewards) && $count >= $criteria->claimed_rewards) {
                $user->unlockAchievement($achievement);
            }
        }
    }

    /**
     * Check for social achievements
     */
    private function checkSocialAchievements(User $user)
    {
        // Count friends
        $count = $user->friends()
            ->wherePivot('status', 'accepted')
            ->count();

        // Find achievements for social
        $achievements = Achievement::where('category', 'social')->get();

        foreach ($achievements as $achievement) {
            $criteria = json_decode($achievement->criteria);
            if (isset($criteria->friends_count) && $count >= $criteria->friends_count) {
                $user->unlockAchievement($achievement);
            }
        }
    }
}
