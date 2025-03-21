<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\SurfSpot;
use App\Models\User;

class CommentSeeder extends Seeder
{
    /**
     * Seed the comments table with example data for each surf spot.
    */
    public function run()
    {
        SurfSpot::all()->each(function ($surfSpot) {
            Comment::factory()->count(3)->create([
                'surf_spot_id' => $surfSpot->id,
                'user_id' => User::inRandomOrder()->first()->id, 
            ]);
        });
    }
}
