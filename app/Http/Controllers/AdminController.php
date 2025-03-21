<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SurfSpot;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * Display all the users for admins.
     */
    public function manageUsers()
    {
        $users = User::paginate(5);
        return view('dashboard', compact('users'));
    }

    /**
     * Update a user's role.
     */
    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:admin,user',
        ]);

        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();

        return redirect()->route('dashboard')->with('success', 'User role updated successfully.');
    }


    /**
     * Delete a user.
     */
    public function destroyUser($id)
    {

        $user = User::findOrFail($id);
    
        if (auth()->id() === $user->id) {
            return redirect()->route('dashboard')->with('error', 'You cannot delete yourself!');
        }

        $user->delete();
        return redirect()->route('dashboard')->with('success', 'User deleted successfully.');
    }

    /**
     * Delete a surf spot.
     */
    public function destroySurfSpot($id)
    {
        $surfSpot = SurfSpot::findOrFail($id);
        $surfSpot->delete();

        \Log::info("Surf spot deleted successfully with ID: $id");
        return redirect()->route('dashboard')->with('success', 'Surf spot deleted successfully.');
    }

    /**
     * Delete a comment.
     */
    public function destroyComment($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        \Log::info("Comment deleted successfully with ID: $id");
        return redirect()->route('dashboard')->with('success', 'Comment deleted successfully.');
    }
}