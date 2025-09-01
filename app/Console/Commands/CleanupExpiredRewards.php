<?php
// app/Console/Commands/CleanupExpiredRewards.php
namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CleanupExpiredRewards extends Command
{
    protected $signature = 'dailyquest:cleanup-rewards';
    protected $description = 'Mark expired rewards as expired';

    public function handle()
    {
        $now = now();

        // Find user rewards that have expired
        $expiredCount = \DB::table('user_rewards')
            ->where('status', 'claimed')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', $now)
            ->update(['status' => 'expired']);

        $this->info('Marked ' . $expiredCount . ' rewards as expired.');
    }
}
