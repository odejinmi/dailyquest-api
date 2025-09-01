<?php
// app/Console/Commands/UpdateUserStreaks.php
namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UpdateUserStreaks extends Command
{
    protected $signature = 'dailyquest:update-streaks';
    protected $description = 'Update user streaks based on activity';

    public function handle()
    {
        $today = now()->startOfDay();
        $yesterday = $today->copy()->subDay();

        // Get users who were active yesterday
        $activeUsers = User::whereDate('last_activity_date', $yesterday)->get();

        foreach ($activeUsers as $user) {
            $user->updateStreak();
        }

        // Reset streaks for users who weren't active yesterday
        $inactiveUsers = User::where('streak', '>', 0)
            ->where(function ($query) use ($yesterday) {
                $query->whereNull('last_activity_date')
                    ->orWhereDate('last_activity_date', '<', $yesterday);
            })
            ->get();

        foreach ($inactiveUsers as $user) {
            $user->streak = 0;
            $user->save();
        }

        $this->info('Updated streaks for ' . $activeUsers->count() . ' active users.');
        $this->info('Reset streaks for ' . $inactiveUsers->count() . ' inactive users.');
    }
}
