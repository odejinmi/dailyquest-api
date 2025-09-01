<?php

// app/Models/Notification.php
namespace App\Models;

//use App\Services\PushNotificationService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'icon',
        'is_read',
        'data',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Add this method to the Notification model
    public static function boot()
    {
        parent::boot();

        static::created(function ($notification) {
            // Send push notification when a new notification is created
            try {
//                $pushService = app(PushNotificationService::class);
//                $pushService->sendToUser(
//                    $notification->user,
//                    $notification->title,
//                    $notification->message,
//                    $notification->data ? (array)$notification->data : []
//                );
            } catch (\Exception $e) {
                \Log::error('Failed to send push notification: ' . $e->getMessage());
            }
        });
    }
}

