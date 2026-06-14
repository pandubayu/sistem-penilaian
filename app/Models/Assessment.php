<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessment extends Model
{
    protected $fillable = [
        'mapping_id',
        'period_id',
        'assessor_id',
        'employee_id',
        'assessment_date',
        'total_score',
        'average_score',
        'grade',
        'notes',
    ];

    protected $casts = [
        'assessment_date' => 'date',
        'total_score' => 'decimal:2',
        'average_score' => 'decimal:2',
    ];

    public function mapping(): BelongsTo
    {
        return $this->belongsTo(AssessorMapping::class, 'mapping_id');
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }

    public function assessor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'assessor_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function technicalScores(): HasMany
    {
        return $this->hasMany(AssessmentTechnicalScore::class);
    }

    public function generalScores(): HasMany
    {
        return $this->hasMany(AssessmentGeneralScore::class);
    }

    // Accessor: badge warna grade
    public function getGradeBadgeAttribute(): string
    {
        return match ($this->grade) {
            'A' => 'bg-green-100 text-green-800',
            'B' => 'bg-blue-100 text-blue-800',
            'C' => 'bg-amber-100 text-amber-800',
            'D' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    // Method: hitung ulang total & average dari semua skor, lalu tentukan grade
    public function recalculateScore(): void
    {
        $totalTechnical = $this->technicalScores()->sum('score');
        $totalGeneral = $this->generalScores()->sum('score');
        $totalScore = $totalTechnical + $totalGeneral;

        $totalCriteria = $this->technicalScores()->count() + $this->generalScores()->count();
        $average = $totalCriteria > 0 ? $totalScore / $totalCriteria : 0;

        $this->total_score = $totalScore;
        $this->average_score = round($average, 2);
        $this->save();
    }
}
