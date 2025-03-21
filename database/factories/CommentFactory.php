<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\SurfSpot;
use App\Models\Comment;

class CommentFactory extends Factory 
{
    
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     */
    public function definition() 
    {
        $comments = [
            'Great spot to learn, has some great views to look at while you’re waiting for the waves!',
            'A perfect family beach, easy access from the car park and plenty of room in the water and out!',
            'Lovely beach with easy access, although it can get super busy in the summer and hard to find room in the sea.',
            'This place can produce perfect peaks, although it will be heaving when that happens.',
            'I’ve had some very good sessions here, a very consistent spot with good sand banks.',
            'Can be a great place for intermediates to learn, friendly waves while pushing you to get better.',
            'Can be difficult for beginners, this place picks up a lot of swell.',
            'Very good spot, holds surf competitions throughout the year.',
            'Very powerful place, it has a difficult paddle-out when the swell picks up.'
        ];
    
        return [
            'content' => $this->faker->randomElement($comments),
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'surf_spot_id' => SurfSpot::inRandomOrder()->first()?->id ?? SurfSpot::factory(),
        ];
    }
}
