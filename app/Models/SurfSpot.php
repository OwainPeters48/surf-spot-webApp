<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Comment;


class SurfSpot extends Model 
{
    use HasFactory;

    /**
     * The surf spot attributes.
     */
    protected $fillable = [
        'name', 
        'location', 
        'description', 
        'difficulty',
        'view_count',
        'user_id',
        'latitude',
        'longitude',
    ];

    /**
     * Get the user who created this surf spot.
     * many-to-one
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

     /**
     * Get the comments linked to this surf spot.
     * one-to-many
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'surf_spot_id');
    }

    /**
     * The users who favourite this surf spot.
     * many-to-many
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'surf_spot_user', 'surf_spot_id', 'user_id');
    }

}
