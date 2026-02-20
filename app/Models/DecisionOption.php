<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DecisionOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'decision_id',
        'label',
        'pros',
        'cons',
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
}