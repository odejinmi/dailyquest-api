<?php

// app/Models/Reward.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'points_cost',
        'image_url',
        'category',
        'reward_type',
        'reward_data',
        'is_active',
        'stock',
    ];

    protected $casts = [
        'reward_data' => 'array',
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_rewards')
            ->withPivot('status', 'redemption_code', 'claimed_at', 'delivered_at', 'expires_at')
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('stock')
                    ->orWhere('stock', '>', 0);
            });
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function decrementStock()
    {
        if ($this->stock !== null) {
            $this->decrement('stock');
        }
    }
}

