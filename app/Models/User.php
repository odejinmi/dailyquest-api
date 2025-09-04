<?php

// app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image',
        'points',
        'streak',
        'last_activity_date',
        'device_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_activity_date' => 'date',
    ];

    public function challenges()
    {
        return $this->belongsToMany(Challenge::class, 'user_challenges')
            ->withPivot('is_completed', 'completed_at')
            ->withTimestamps();
    }

    public function rewards()
    {
        return $this->belongsToMany(Reward::class, 'user_rewards')
            ->withPivot('status', 'redemption_code', 'claimed_at', 'delivered_at', 'expires_at')
            ->withTimestamps();
    }

    public function pointsTransactions()
    {
        return $this->hasMany(PointsTransaction::class);
    }

    public function friends()
    {
        return $this->belongsToMany(User::class, 'friend_connections', 'user_id', 'friend_id')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function friendRequests()
    {
        return $this->belongsToMany(User::class, 'friend_connections', 'friend_id', 'user_id')
            ->wherePivot('status', 'pending')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function achievements()
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
            ->withPivot('unlocked_at')
            ->withTimestamps();
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function addPoints($points, $transactionType, $description, $referenceId = null, $referenceType = null)
    {
        $this->increment('points', $points);

        return $this->pointsTransactions()->create([
            'points' => $points,
            'transaction_type' => $transactionType,
            'description' => $description,
            'reference_id' => $referenceId,
            'reference_type' => $referenceType,
        ]);
    }

    public function spendPoints($points, $transactionType, $description, $referenceId = null, $referenceType = null)
    {
        if ($this->points < $points) {
            return false;
        }

        $this->decrement('points', $points);

        $this->pointsTransactions()->create([
            'points' => -$points,
            'transaction_type' => $transactionType,
            'description' => $description,
            'reference_id' => $referenceId,
            'reference_type' => $referenceType,
        ]);

        return true;
    }

    public function updateStreak()
    {
        $today = now()->startOfDay();
        $lastActivity = $this->last_activity_date ? $this->last_activity_date->startOfDay() : null;

        if (!$lastActivity) {
            // First activity
            $this->streak = 1;
        } elseif ($lastActivity->diffInDays($today) === 0) {
            // Already logged activity today
            return;
        } elseif ($lastActivity->diffInDays($today) === 1) {
            // Consecutive day
            $this->streak++;
        } else {
            // Streak broken
            $this->streak = 1;
        }

        $this->last_activity_date = $today;
        $this->save();

        // Check for streak achievements
        $this->checkStreakAchievements();
    }

    private function checkStreakAchievements()
    {
        // Logic to check and award streak-based achievements
        $streakAchievements = Achievement::where('category', 'streak')->get();

        foreach ($streakAchievements as $achievement) {
            $criteria = $achievement->criteria;
            if (isset($criteria->streak_days) && $this->streak >= $criteria->streak_days) {
                $this->unlockAchievement($achievement);
            }
        }
    }

    public function unlockAchievement(Achievement $achievement)
    {
        // Check if already unlocked
        if ($this->achievements()->where('achievement_id', $achievement->id)->exists()) {
            return;
        }

        // Unlock achievement
        $this->achievements()->attach($achievement->id, ['unlocked_at' => now()]);

        // Award points if applicable
        if ($achievement->points_reward > 0) {
            $this->addPoints(
                $achievement->points_reward,
                'achievement_unlock',
                "Unlocked achievement: {$achievement->title}",
                $achievement->id,
                Achievement::class
            );
        }

        // Create notification
        $this->notifications()->create([
            'title' => 'Achievement Unlocked!',
            'message' => "You've unlocked the {$achievement->title} achievement!",
            'type' => 'achievement',
            'icon' => $achievement->icon,
            'data' => json_encode(['achievement_id' => $achievement->id]),
        ]);
    }
}

