<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReviewAssumption extends Model
{
    use HasFactory;

    protected $fillable = [
        'review_id',
        'assumption_id',
        'was_correct',
    ];

    protected $casts = [
        'was_correct' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function review()
    {
        return $this->belongsTo(Review::class);
    }

    public function assumption()
    {
        return $this->belongsTo(Assumption::class);
    }
}