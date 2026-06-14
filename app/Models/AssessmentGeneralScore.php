<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentGeneralScore extends Model
{
    protected $table = 'assessment_general_scores';

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
        return $this->belongsTo(GeneralCriteria::class, 'criteria_id');
    }

    // Accessor: label skala nilai (1-4) — sama seperti technical
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
