<?php

// app/Http/Controllers/API/FriendController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FriendController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $friends = $user->friends()
            ->wherePivot('status', 'accepted')
            ->get(['id', 'name', 'profile_image', 'points', 'streak']);

        return response()->json(['friends' => $friends]);
    }

    public function requests(Request $request)
    {
        $user = $request->user();

        $friendRequests = $user->friendRequests()
            ->wherePivot('status', 'pending')
            ->get(['id', 'name', 'profile_image']);

        return response()->json(['friend_requests' => $friendRequests]);
    }

    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        $friend = User::where('email', $request->email)->first();

        // Check if trying to add self
        if ($user->id === $friend->id) {
            return response()->json(['message' => 'You cannot add yourself as a friend'], 422);
        }

        // Check if already friends or request pending
        $existingConnection = $user->friends()
            ->where('friend_id', $friend->id)
            ->first();

        if ($existingConnection) {
            if ($existingConnection->pivot->status === 'accepted') {
                return response()->json(['message' => 'You are already friends with this user'], 422);
            } elseif ($existingConnection->pivot->status === 'pending') {
                return response()->json(['message' => 'Friend request already sent'], 422);
            } elseif ($existingConnection->pivot->status === 'blocked') {
                return response()->json(['message' => 'You have blocked this user'], 422);
            }
        }

        // Check if friend has sent a request to user
        $incomingRequest = $friend->friends()
            ->where('friend_id', $user->id)
            ->first();

        if ($incomingRequest && $incomingRequest->pivot->status === 'pending') {
            // Accept the incoming request instead
            $friend->friends()->updateExistingPivot($user->id, ['status' => 'accepted']);

            // Create notification for the friend
            $friend->notifications()->create([
                'title' => 'Friend Request Accepted',
                'message' => "{$user->name} accepted your friend request!",
                'type' => 'friend',
                'icon' => 'people',
                'data' => json_encode(['user_id' => $user->id]),
            ]);

            return response()->json(['message' => 'Friend request accepted']);
        }

        // Send new friend request
        $user->friends()->attach($friend->id, ['status' => 'pending']);

        // Create notification for the friend
        $friend->notifications()->create([
            'title' => 'New Friend Request',
            'message' => "{$user->name} sent you a friend request!",
            'type' => 'friend',
            'icon' => 'people',
            'data' => json_encode(['user_id' => $user->id]),
        ]);

        return response()->json(['message' => 'Friend request sent']);
    }

    public function accept(Request $request, $id)
    {
        $user = $request->user();
        $friend = User::findOrFail($id);

        // Check if there's a pending request
        $friendRequest = $friend->friends()
            ->where('friend_id', $user->id)
            ->wherePivot('status', 'pending')
            ->first();

        if (!$friendRequest) {
            return response()->json(['message' => 'No pending friend request found'], 404);
        }

        // Accept the request
        $friend->friends()->updateExistingPivot($user->id, ['status' => 'accepted']);

        // Create notification for the friend
        $friend->notifications()->create([
            'title' => 'Friend Request Accepted',
            'message' => "{$user->name} accepted your friend request!",
            'type' => 'friend',
            'icon' => 'people',
            'data' => json_encode(['user_id' => $user->id]),
        ]);

        // Dispatch event for achievement checking
        event('friend.added', [$user->id]);
        event('friend.added', [$friend->id]);

        return response()->json(['message' => 'Friend request accepted']);
    }

    public function reject(Request $request, $id)
    {
        $user = $request->user();
        $friend = User::findOrFail($id);

        // Check if there's a pending request
        $friendRequest = $friend->friends()
            ->where('friend_id', $user->id)
            ->wherePivot('status', 'pending')
            ->first();

        if (!$friendRequest) {
            return response()->json(['message' => 'No pending friend request found'], 404);
        }

        // Delete the request
        $friend->friends()->detach($user->id);

        return response()->json(['message' => 'Friend request rejected']);
    }

    public function remove(Request $request, $id)
    {
        $user = $request->user();
        $friend = User::findOrFail($id);

        // Remove the friendship in both directions
        $user->friends()->detach($friend->id);
        $friend->friends()->detach($user->id);

        return response()->json(['message' => 'Friend removed successfully']);
    }

    public function block(Request $request, $id)
    {
        $user = $request->user();
        $friend = User::findOrFail($id);

        // Check if already blocked
        $existingConnection = $user->friends()
            ->where('friend_id', $friend->id)
            ->first();

        if ($existingConnection && $existingConnection->pivot->status === 'blocked') {
            return response()->json(['message' => 'User is already blocked'], 422);
        }

        // Remove any existing connection
        $user->friends()->detach($friend->id);
        $friend->friends()->detach($user->id);

        // Add blocked status
        $user->friends()->attach($friend->id, ['status' => 'blocked']);

        return response()->json(['message' => 'User blocked successfully']);
    }

    public function unblock(Request $request, $id)
    {
        $user = $request->user();
        $friend = User::findOrFail($id);

        // Check if blocked
        $existingConnection = $user->friends()
            ->where('friend_id', $friend->id)
            ->wherePivot('status', 'blocked')
            ->first();

        if (!$existingConnection) {
            return response()->json(['message' => 'User is not blocked'], 422);
        }

        // Remove blocked status
        $user->friends()->detach($friend->id);

        return response()->json(['message' => 'User unblocked successfully']);
    }

    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|min:3',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        $query = $request->query;

        // Search for users by name or email
        $users = User::where('id', '!=', $user->id)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get(['id', 'name', 'profile_image']);

        // Add friendship status
        $users->each(function ($searchedUser) use ($user) {
            $connection = $user->friends()->where('friend_id', $searchedUser->id)->first();
            $reverseConnection = $searchedUser->friends()->where('friend_id', $user->id)->first();

            if ($connection) {
                $searchedUser->friendship_status = $connection->pivot->status;
            } elseif ($reverseConnection && $reverseConnection->pivot->status === 'pending') {
                $searchedUser->friendship_status = 'incoming_request';
            } else {
                $searchedUser->friendship_status = 'none';
            }
        });

        return response()->json(['users' => $users]);
    }
}
