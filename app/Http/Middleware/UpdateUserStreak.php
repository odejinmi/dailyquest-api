<?php
// app/Http/Middleware/UpdateUserStreak.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdateUserStreak
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $today = now()->startOfDay();
            $lastActivity = $user->last_activity_date ? $user->last_activity_date->startOfDay() : null;

            if (!$lastActivity || $lastActivity->diffInDays($today) > 0) {
                $user->updateStreak();
            }
        }

        return $next($request);
    }
}
