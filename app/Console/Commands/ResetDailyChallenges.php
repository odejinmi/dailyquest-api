<?php
// app/Console/Commands/ResetDailyChallenges.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ResetDailyChallenges extends Command
{
    protected $signature = 'dailyquest:reset-challenges';
    protected $description = 'Reset daily challenges for all users';

    public function handle()
    {
        // Clear all daily challenge caches
        Cache::flush();

        $this->info('Daily challenges reset successfully.');
    }
}
