<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Period extends Model
{
    protected $fillable = [
        'name',
        'type',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function mappings(): HasMany
    {
        return $this->hasMany(AssessorMapping::class);
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class);
    }

    // Accessor: format tanggal "1 Jan 2026 - 31 Mar 2026"
    public function getDateRangeAttribute(): string
    {
        return $this->start_date->translatedFormat('d M Y') . ' - ' . $this->end_date->translatedFormat('d M Y');
    }

    // Scope: hanya periode aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
