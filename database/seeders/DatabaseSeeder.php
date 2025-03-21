<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with initial data.
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            SurfSpotSeeder::class,
            CommentSeeder::class,
        ]);
    }
}
