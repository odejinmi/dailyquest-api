<?php
// database/seeders/AchievementSeeder.php
namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    public function run()
    {
        $achievements = [
            [
                'title' => 'First Win',
                'description' => 'Complete your first challenge',
                'icon' => 'emoji_events',
                'category' => 'challenges',
                'points_reward' => 50,
                'criteria' => [
                    'completed_challenges' => 1,
                ],
            ],
            [
                'title' => 'Challenge Master',
                'description' => 'Complete 10 challenges',
                'icon' => 'psychology',
                'category' => 'challenges',
                'points_reward' => 100,
                'criteria' => [
                    'completed_challenges' => 10,
                ],
            ],
            [
                'title' => 'Challenge Expert',
                'description' => 'Complete 50 challenges',
                'icon' => 'psychology',
                'category' => 'challenges',
                'points_reward' => 250,
                'criteria' => [
                    'completed_challenges' => 50,
                ],
            ],
            [
                // Continuing database/seeders/AchievementSeeder.php
                'title' => '3-Day Streak',
                'description' => 'Complete challenges for 3 consecutive days',
                'icon' => 'local_fire_department',
                'category' => 'streak',
                'points_reward' => 75,
                'criteria' => [
                    'streak_days' => 3,
                ],
            ],
            [
                'title' => '7-Day Streak',
                'description' => 'Complete challenges for 7 consecutive days',
                'icon' => 'local_fire_department',
                'category' => 'streak',
                'points_reward' => 150,
                'criteria' => [
                    'streak_days' => 7,
                ],
            ],
            [
                'title' => '30-Day Streak',
                'description' => 'Complete challenges for 30 consecutive days',
                'icon' => 'local_fire_department',
                'category' => 'streak',
                'points_reward' => 500,
                'criteria' => [
                    'streak_days' => 30,
                ],
            ],
            [
                'title' => 'First Reward',
                'description' => 'Claim your first reward',
                'icon' => 'card_giftcard',
                'category' => 'rewards',
                'points_reward' => 50,
                'criteria' => [
                    'claimed_rewards' => 1,
                ],
            ],
            [
                'title' => 'Collector',
                'description' => 'Claim 5 different rewards',
                'icon' => 'card_giftcard',
                'category' => 'rewards',
                'points_reward' => 200,
                'criteria' => [
                    'claimed_rewards' => 5,
                ],
            ],
            [
                'title' => 'Social Butterfly',
                'description' => 'Add 5 friends',
                'icon' => 'people',
                'category' => 'social',
                'points_reward' => 100,
                'criteria' => [
                    'friends_count' => 5,
                ],
            ],
            [
                'title' => 'Brain Master',
                'description' => 'Complete 10 brain game challenges',
                'icon' => 'psychology',
                'category' => 'category_specific',
                'points_reward' => 150,
                'criteria' => [
                    'category_challenges' => 10,
                    'category' => 'Brain Games',
                ],
            ],
            [
                'title' => 'Zen Master',
                'description' => 'Complete 10 mindfulness challenges',
                'icon' => 'self_improvement',
                'category' => 'category_specific',
                'points_reward' => 150,
                'criteria' => [
                    'category_challenges' => 10,
                    'category' => 'Mindfulness',
                ],
            ],
            [
                'title' => 'Fitness Guru',
                'description' => 'Complete 10 fitness challenges',
                'icon' => 'fitness_center',
                'category' => 'category_specific',
                'points_reward' => 150,
                'criteria' => [
                    'category_challenges' => 10,
                    'category' => 'Fitness',
                ],
            ],
            [
                'title' => 'Knowledge Seeker',
                'description' => 'Complete 10 knowledge challenges',
                'icon' => 'lightbulb',
                'category' => 'category_specific',
                'points_reward' => 150,
                'criteria' => [
                    'category_challenges' => 10,
                    'category' => 'Knowledge',
                ],
            ],
        ];

        foreach ($achievements as $achievement) {
            Achievement::create($achievement);
        }
    }
}
