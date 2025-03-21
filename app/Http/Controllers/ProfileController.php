<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show($id, Request $request)
{
    $user = User::with(['surfSpots', 'comments.surfSpot', 'favouriteSurfSpots'])->findOrFail($id);

    // Handle AJAX request
    if ($request->expectsJson()) {
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'comments' => $user->comments->map(function ($comment) {
                return [
                    'content' => $comment->content,
                    'surf_spot' => $comment->surfSpot->name ?? 'Unknown',
                ];
            }),
            'surf_spots' => $user->surfSpots->map(function ($surfSpot) {
                return [
                    'name' => $surfSpot->name,
                    'location' => $surfSpot->location,
                    'view_count' => $surfSpot->view_count,
                ];
            }),
            'favourite_surf_spots' => $user->favouriteSurfSpots->map(function ($surfSpot) {
                return [
                    'name' => $surfSpot->name,
                    'location' => $surfSpot->location,
                    'likes' => $surfSpot->users()->count(),
                ];
            }),
        ]);
    }

    return view('users.show', [
        'user' => $user,
    ]);
}

}
