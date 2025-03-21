<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SurfSpot;
use App\Models\User;

class SurfSpotSeeder extends Seeder
{
    /**
     * Seed the surf spots table with example data.
     *
     * This method creates surf spots and ensures each one is associated 
     * with a random admin user or fallback to a default admin if no admins exist.
     */
    public function run()
    {
        // Retrieve admin users
        $adminUsers = User::where('role', 'admin')->get();

        // If no admin users exist, create a default admin
        if ($adminUsers->isEmpty()) {
            $defaultAdmin = User::create([
                'name' => 'Default Admin',
                'email' => 'default_admin@example.com',
                'password' => bcrypt('password'), // Default password for testing
                'role' => 'admin',
            ]);

            $adminUsers = collect([$defaultAdmin]); // Wrap in a collection
        }

        // Create surf spots and associate them with random admin users
        SurfSpot::factory()->count(4)->create()->each(function ($surfSpot) use ($adminUsers) {
            $surfSpot->user_id = $adminUsers->random()->id; // Assign to a random admin
            $surfSpot->save(); // Save the association
        });
    }
}
