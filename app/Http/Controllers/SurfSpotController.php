<?php

namespace App\Http\Controllers;

use App\Models\SurfSpot;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Notifications\UserNotification;
use App\Models\User;
use App\Models\Comment;

class SurfSpotController extends Controller
{
    protected $weatherService;


    /**
     * Display a paginated list of surf spots.
     */
    public function index()
    {
        $surfSpots = SurfSpot::paginate(3);
        $popularSpots = SurfSpot::orderBy('view_count', 'desc')->take(5)->get();

        return view('surf_spots.index', compact('surfSpots', 'popularSpots'));
    }

    /**
     * Show details of a specific surf spot.
     */
    public function show($id)
    {
        $surfSpot = SurfSpot::with('comments.user')->findOrFail($id);

       
        return view('surf_spots.show', compact('surfSpot'));
    }

    /**
     * Dashboard for all users.
     */
    public function dashboard(Request $request)
    {
        $userComments = Comment::with('surfSpot')
            ->where('user_id', Auth::id())
            ->latest()
            ->take(5)
            ->get();
        $allSurfSpots = SurfSpot::paginate(3, ['*'], 'surf_page');

        $users = Auth::user()->role === 'admin'
            ? User::paginate(5, ['*'], 'user_page')
            : collect();

        $surfPage = $request->input('surf_page', $allSurfSpots->currentPage());
        $userPage = $request->input('user_page', $users instanceof \Illuminate\Pagination\Paginator ? $users->currentPage() : 1);

        $notifications = Auth::user()->notifications()->latest()->get();
        $likedSurfSpots = Auth::user()->favouriteSurfSpots;

        return view('dashboard', [
            'userComments' => $userComments,
            'allSurfSpots' => $allSurfSpots,
            'users' => $users,
            'notifications' => $notifications,
            'surfPage' => $surfPage,
            'userPage' => $userPage,
            'likedSurfSpots' => $likedSurfSpots,
        ]);
    }

    /**
     * Store a new surf spot.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'location' => 'required',
            'description' => 'required',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
        ]);

        $validatedData['user_id'] = Auth::id();

        $surfSpot = SurfSpot::create($validatedData);

        \Log::info('Created Surf Spot:', $surfSpot->toArray());

        $users = User::all();
        foreach ($users as $user) {
            $user->notify(new \App\Notifications\UserNotification("A new surf spot, {$surfSpot->name}, has been added!"));
        }

        return response()->json(['message' => 'Surf spot created successfully!', 'surfSpot' => $surfSpot], 201);
    }

    /**
     * Update an existing surf spot.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'location' => 'required',
            'description' => 'required',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
        ]);

        $surfSpot = SurfSpot::findOrFail($id);
        $surfSpot->update($request->only(['name', 'location', 'description', 'difficulty']));

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new UserNotification("Surf spot {$surfSpot->name} was updated."));
        }

        return response()->json(['message' => 'Surf spot updated successfully!', 'surfSpot' => $surfSpot]);
    }

    /**
     * Delete a surf spot.
     */
    public function destroy($id)
    {
        $surfSpot = SurfSpot::findOrFail($id);
        $surfSpot->delete();

        return response()->json(['message' => 'Surf spot deleted successfully!'], 200);
    }

    public function toggleFavourite($id)
    {
        $surfSpot = SurfSpot::findOrFail($id);
        $user = Auth::user();

        // Check if the surf spot is already favourited
        if ($user->favouriteSurfSpots()->where('surf_spot_id', $id)->exists()) {
            $user->favouriteSurfSpots()->detach($id);
            return response()->json(['message' => 'Surf spot removed from favourites!']);
        } else {
            // Add to favourites
            $user->favouriteSurfSpots()->attach($id);

            if ($surfSpot->user_id !== $user->id) {
                $surfSpot->user->notify(new \App\Notifications\SurfSpotFavouritedNotification(
                    $user, $surfSpot));
            }

            return response()->json(['message' => 'Surf spot added to favourites!']);
        }
    }

}
