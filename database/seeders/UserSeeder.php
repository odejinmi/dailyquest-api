<?php
// database/seeders/UserSeeder.php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create a test user
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'points' => 1000,
            'streak' => 3,
            'last_activity_date' => now(),
        ]);

        // Create some sample users for the leaderboard
        $users = [
            [
                'name' => 'Michael Johnson',
                'email' => 'michael@example.com',
                'points' => 9200,
                'streak' => 15,
            ],
            [
                'name' => 'Emma Wilson',
                'email' => 'emma@example.com',
                'points' => 8750,
                'streak' => 12,
            ],
            [
                'name' => 'Sarah Davis',
                'email' => 'sarah@example.com',
                'points' => 8320,
                'streak' => 9,
            ],
            [
                'name' => 'David Brown',
                'email' => 'david@example.com',
                'points' => 7890,
                'streak' => 7,
            ],
            [
                'name' => 'Jessica Miller',
                'email' => 'jessica@example.com',
                'points' => 7650,
                'streak' => 5,
            ],
            [
                'name' => 'Robert Taylor',
                'email' => 'robert@example.com',
                'points' => 6950,
                'streak' => 3,
            ],
            [
                'name' => 'Lisa Anderson',
                'email' => 'lisa@example.com',
                'points' => 6800,
                'streak' => 2,
            ],
        ];

        foreach ($users as $userData) {
            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password'),
                'points' => $userData['points'],
                'streak' => $userData['streak'],
                'last_activity_date' => now(),
            ]);
        }
    }
}
