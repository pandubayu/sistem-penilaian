<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradingThreshold extends Model
{
    protected $fillable = [
        'employee_level',
        'grade',
        'min_score',
        'max_score',
        'reward_text',
        'punishment_text',
    ];

    protected $casts = [
        'employee_level' => 'integer',
        'min_score' => 'integer',
        'max_score' => 'integer',
    ];

    // Static helper: cari grade berdasarkan level karyawan & total nilai
    public static function findGrade(int $employeeLevel, float $totalScore): ?self
    {
        return self::where('employee_level', $employeeLevel)
            ->where('min_score', '<=', $totalScore)
            ->where(function ($query) use ($totalScore) {
                $query->whereNull('max_score')
                    ->orWhere('max_score', '>=', $totalScore);
            })
            ->first();
    }

    // Accessor: rentang nilai dalam format "151 - 217" atau "≥ 291"
    public function getScoreRangeAttribute(): string
    {
        if ($this->max_score === null) {
            return '≥ ' . $this->min_score;
        }

        return $this->min_score . ' - ' . $this->max_score;
    }
}
