<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Assumption extends Model
{
    use HasFactory;

    protected $fillable = [
        'decision_id',
        'description',
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

    public function reviewEvaluations()
    {
        return $this->hasMany(ReviewAssumption::class);
    }
}