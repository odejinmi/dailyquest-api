<?php
//// app/Services/PushNotificationService.php
//namespace App\Services;
//
//use App\Models\User;
////use Kreait\Firebase\Messaging\CloudMessage;
////use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
//
//class PushNotificationService
//{
//    protected $messaging;
//
//    public function __construct()
//    {
//        $this->messaging = app('firebase.messaging');
//    }
//
//    public function sendToUser(User $user, string $title, string $body, array $data = [])
//    {
//        if (!$user->device_token) {
//            return false;
//        }
//
////        $notification = FirebaseNotification::create($title, $body);
////
////        $message = CloudMessage::withTarget('token', $user->device_token)
////            ->withNotification($notification)
////            ->withData($data);
////
////        try {
////            $this->messaging->send($message);
////            return true;
////        } catch (\Exception $e) {
////            \Log::error('Failed to send push notification: ' . $e->getMessage());
////            return false;
////        }
//        return true;
//    }
//
//    public function sendToMultipleUsers(array $userIds, string $title, string $body, array $data = [])
//    {
//        $users = User::whereIn('id', $userIds)
//            ->whereNotNull('device_token')
//            ->get();
//
//        $tokens = $users->pluck('device_token')->toArray();
//
//        if (empty($tokens)) {
//            return false;
//        }
//
////        $notification = FirebaseNotification::create($title, $body);
////
////        $message = CloudMessage::new()
////            ->withNotification($notification)
////            ->withData($data);
////
////        try {
////            $this->messaging->sendMulticast($message, $tokens);
////            return true;
////        } catch (\Exception $e) {
////            \Log::error('Failed to send multicast push notification: ' . $e->getMessage());
////            return false;
////        }
//        return true;
//    }
//}
