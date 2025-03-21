<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model 
{
    use HasFactory;

     /**
     * The comment attributes.
     */
    protected $fillable = [
        'content', 
        'surf_spot_id', 
        'user_id'
    ];

      /**
     * Get the surf spot that this comment is linked to.
     */
    public function surfSpot() 
    {
        return $this->belongsTo(SurfSpot::class, 'surf_spot_id');
    }

/**
     * Get the user who created the comment.
     */

    public function user() 
    {
        return $this->belongsTo(User::class);
    }
}
