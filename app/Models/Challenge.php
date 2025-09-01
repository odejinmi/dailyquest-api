<?php

// app/Models/Challenge.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'full_description',
        'steps',
        'points',
        'estimated_minutes',
        'icon',
        'image_url',
        'category',
        'is_active',
        'available_from',
        'available_until',
    ];

    protected $casts = [
        'steps' => 'array',
        'is_active' => 'boolean',
        'available_from' => 'date',
        'available_until' => 'date',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_challenges')
            ->withPivot('is_completed', 'completed_at')
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('available_from')
                    ->orWhere('available_from', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('available_until')
                    ->orWhere('available_until', '>=', now());
            });
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}

