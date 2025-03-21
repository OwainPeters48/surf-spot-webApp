<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SurfSpot;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|max:255',
        ]);

        // Notify all users
        $users = User::all();
        foreach ($users as $user) {
            $user->notify(new \App\Notifications\UserNotification($request->message));
        }

        // Return a JSON response for AJAX
        return response()->json([
            'message' => $request->message,
            'time' => now()->diffForHumans(),
        ], 200);
    }

    public function notifySurfSpotAction($action, SurfSpot $surfSpot)
    {
        $message = match ($action) {
            'created' => "A new surf spot '{$surfSpot->name}' has been added!",
            'updated' => "The surf spot '{$surfSpot->name}' has been updated!",
            default => null,
        };

        if ($message) {
            $users = User::all();
            foreach ($users as $user) {
                $user->notify(new \App\Notifications\UserNotification($message));
            }
        }
    }

    public function notifyCommentAction($surfSpotName, $comment)
    {
        $message = "A new comment was added to the surf spot '{$surfSpotName}' by {$comment->user->name}.";

        $users = User::all(); 
        foreach ($users as $user) {
            $user->notify(new \App\Notifications\UserNotification($message));
        }
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);

        if (!$notification) {
            \Log::error("Notification not found for ID: {$id}");
            return response()->json(['message' => 'Notification not found'], 404);
        }

        $notification->markAsRead();

        return response()->json(['message' => 'Notification marked as read']);
    }

    public function notifyFavouriteAction(SurfSpot $surfSpot, User $user)
    {
        if ($surfSpot->user_id !== $user->id) {
            $message = "{$user->name} favourited your surf spot '{$surfSpot->name}'!";
            $surfSpot->user->notify(new \App\Notifications\SurfSpotFavouritedNotification($user, $surfSpot));
        }
    }    

}
