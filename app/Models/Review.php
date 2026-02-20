<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'decision_id',
        'was_successful',
        'outcome_notes',
        'reviewed_at',
    ];

    protected $casts = [
        'was_successful' => 'boolean',
        'reviewed_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function decision()
    {
        return $this->belongsTo(Decision::class);
    }

    public function assumptionEvaluations()
    {
        return $this->hasMany(ReviewAssumption::class);
    }
}