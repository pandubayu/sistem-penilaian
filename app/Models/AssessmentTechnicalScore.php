<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentTechnicalScore extends Model
{
    protected $table = 'assessment_technical_scores';

    protected $fillable = [
        'assessment_id',
        'criteria_id',
        'score',
    ];

    protected $casts = [
        'score' => 'integer',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function criteria(): BelongsTo
    {
        return $this->belongsTo(TechnicalCriteria::class, 'criteria_id');
    }

    // Accessor: label skala nilai (1-4)
    public function getScoreLabelAttribute(): string
    {
        return match ($this->score) {
            1 => 'Tidak Memuaskan',
            2 => 'Perlu Peningkatan',
            3 => 'Cukup Memuaskan',
            4 => 'Sesuai Harapan',
            default => '-',
        };
    }
}
