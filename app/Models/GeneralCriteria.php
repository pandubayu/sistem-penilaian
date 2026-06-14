<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GeneralCriteria extends Model
{
    protected $table = 'general_criteria';

    protected $fillable = [
        'aspect_name',
        'order_number',
    ];

    public function generalScores(): HasMany
    {
        return $this->hasMany(AssessmentGeneralScore::class, 'criteria_id');
    }

    // Scope: urut sesuai order_number
    public function scopeOrdered($query)
    {
        return $query->orderBy('order_number');
    }
}
