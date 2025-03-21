<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SurfSpot;

class SurfSpotFactory extends Factory
{
    /**
     * The name of the model linked to the factory.
     *
     * @var string
     */
    protected $model = SurfSpot::class;

    /**
     * Define the model's default state.
     */
    public function definition()
    {
        $spots = [
            ['name' => 'Fistral Beach', 'location' => 'Cornwall, England', 'description' => 
            'Famous surf spot with big waves.', 'difficulty' => 'Advanced'],
            ['name' => 'Freshwater West', 'location' => 'Pembrokeshire, Wales', 'description' => 
            'Beautiful beach, great for all levels.', 'difficulty' => 'Intermediate'],
            ['name' => 'Llangennith', 'location' => 'Gower, Wales', 'description' => 
            'Long beach, perfect for beginners.', 'difficulty' => 'Beginner'],
            ['name' => 'Rest Bay', 'location' => 'Porthcawl, Wales', 'description' => 
            'Good waves with scenic views.', 'difficulty' => 'Intermediate']
        ];

        $spot = $this->faker->randomElement($spots); // Removed unique()

        return [
            'name' => $spot['name'],
            'location' => $spot['location'],
            'description' => $spot['description'],
            'difficulty' => $spot['difficulty'],
            'view_count' => $this->faker->numberBetween(0, 100),
            'user_id' => null,
        ];
    }
}
