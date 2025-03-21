<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\SurfSpot;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UserNotification;
use App\Models\User;

class CommentController extends Controller
{
    /**
     * Store a new comment for a surf spot.
     */
    public function store(Request $request, $surfSpotId)
    {
        $request->validate([
            'content' => 'required|min:5|max:255',
        ]);

        $surfSpot = SurfSpot::findOrFail($surfSpotId);

        $comment = Comment::create([
            'surf_spot_id' => $surfSpot->id,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        // Notify the surf spot creator.
        if ($surfSpot->user_id && $surfSpot->user_id !== Auth::id()) {
            $surfSpot->user->notify(new \App\Notifications\UserNotification(
                "A new comment was added to your surf spot: {$surfSpot->name}."
            ));
        }

        // Notify admins about the new comment.
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\UserNotification(
                "New comment on {$surfSpot->name}: \"{$comment->content}\"."
            ));
        }

        // Return the response for AJAX
        return response()->json([
            'id' => $comment->id,
            'content' => $comment->content,
            'user' => ['id' => Auth::id(), 'name' => Auth::user()->name],
            'surf_spot' => $surfSpot->name,
        ]);
    }


    /**
     * Delete a comment.
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        if (Auth::user()->role !== 'admin' && Auth::id() !== $comment->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully.',
        ]);
    }

    /**
     * Edit a comment.
     */
    public function edit($id)
    {
        $comment = Comment::findOrFail($id);

        if (Auth::user()->role !== 'admin' && Auth::id() !== $comment->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('comments.edit', compact('comment'));
    }

    /**
     * Update a comment.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|min:5|max:255',
        ]);

        $comment = Comment::findOrFail($id);

        if (Auth::user()->role !== 'admin' && Auth::id() !== $comment->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $comment->update([
            'content' => $request->content,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment updated successfully.',
            'comment' => $comment->content,
        ]);
    }
}
