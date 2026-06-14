<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TechnicalCriteria extends Model
{
    protected $table = 'technical_criteria';

    protected $fillable = [
        'division_id',
        'aspect_name',
        'indicator_1',
        'indicator_2',
        'indicator_3',
        'indicator_4',
        'order_number',
    ];

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function technicalScores(): HasMany
    {
        return $this->hasMany(AssessmentTechnicalScore::class, 'criteria_id');
    }

    // Accessor: ambil semua indikator dalam bentuk array, key = level nilai (1-4)
    public function getIndicatorsAttribute(): array
    {
        return [
            1 => $this->indicator_1,
            2 => $this->indicator_2,
            3 => $this->indicator_3,
            4 => $this->indicator_4,
        ];
    }
}
