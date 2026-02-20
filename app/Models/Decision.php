<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Decision extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'context',
        'chosen_option',
        'confidence_score',
        'review_date',
        'status',
    ];

    protected $casts = [
        'review_date' => 'date',
        'confidence_score' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function options()
    {
        return $this->hasMany(DecisionOption::class);
    }

    public function assumptions()
    {
        return $this->hasMany(Assumption::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class)
                    ->withTimestamps();
    }
}