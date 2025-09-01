<?php
// database/seeders/RewardSeeder.php
namespace Database\Seeders;

use App\Models\Reward;
use Illuminate\Database\Seeder;

class RewardSeeder extends Seeder
{
    public function run()
    {
        $rewards = [
            [
                'title' => '$5 Amazon Gift Card',
                'description' => 'Redeem your points for an Amazon gift card',
                'points_cost' => 5000,
                'image_url' => 'rewards/amazon.jpg',
                'category' => 'Gift Cards',
                'reward_type' => 'gift_card',
                'reward_data' => [
                    'provider' => 'Amazon',
                    'value' => 5,
                    'currency' => 'USD',
                ],
                'stock' => 100,
            ],
            [
                'title' => '$10 Starbucks Gift Card',
                'description' => 'Enjoy a coffee on us!',
                'points_cost' => 8000,
                'image_url' => 'rewards/starbucks.jpg',
                'category' => 'Gift Cards',
                'reward_type' => 'gift_card',
                'reward_data' => [
                    'provider' => 'Starbucks',
                    'value' => 10,
                    'currency' => 'USD',
                ],
                'stock' => 50,
            ],
            [
                'title' => '1-Month Spotify Premium',
                'description' => 'Enjoy ad-free music for a month',
                'points_cost' => 12000,
                'image_url' => 'rewards/spotify.jpg',
                'category' => 'Subscriptions',
                'reward_type' => 'subscription',
                'reward_data' => [
                    'provider' => 'Spotify',
                    'duration' => '1 month',
                ],
                'stock' => 25,
            ],
            [
                'title' => 'Premium App Theme',
                'description' => 'Unlock exclusive app themes and customizations',
                'points_cost' => 2000,
                'image_url' => 'rewards/theme.jpg',
                'category' => 'In-App',
                'reward_type' => 'in_app',
                'reward_data' => [
                    'feature' => 'premium_themes',
                    'duration' => 'permanent',
                ],
                'stock' => null, // Unlimited
            ],
            [
                'title' => 'Double Points (24 hours)',
                'description' => 'Earn double points on all challenges for 24 hours',
                'points_cost' => 3000,
                'image_url' => 'rewards/double.jpg',
                'category' => 'Boosters',
                'reward_type' => 'booster',
                'reward_data' => [
                    'multiplier' => 2,
                    'duration' => '24 hours',
                ],
                'stock' => null, // Unlimited
            ],
        ];

        foreach ($rewards as $reward) {
            Reward::create($reward);
        }
    }
}
