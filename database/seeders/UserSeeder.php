<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SurfSpot;
use App\Models\Comment;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the users table with example data.
     */
    public function run()
    {
        // Create an admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create 5 random users
        $users = User::factory()->count(5)->create();

        // Create surf spots and comments for each user
        foreach ($users as $user) {
            $surfSpots = SurfSpot::factory()->count(2)->create(['user_id' => $user->id]);

            foreach ($surfSpots as $surfSpot) {
                Comment::factory()->count(3)->create([
                    'surf_spot_id' => $surfSpot->id,
                    'user_id' => $user->id,
                ]);
            }
        }
    }
}
