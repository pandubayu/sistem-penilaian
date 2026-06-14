<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AssessorMapping extends Model
{
    protected $table = 'assessor_mappings';

    protected $fillable = [
        'period_id',
        'assessor_id',
        'employee_id',
        'assessor_type',
        'is_done',
    ];

    protected $casts = [
        'is_done' => 'boolean',
    ];

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }

    // Karyawan yang bertugas sebagai penilai
    public function assessor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'assessor_id');
    }

    // Karyawan yang dinilai
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function assessment(): HasOne
    {
        return $this->hasOne(Assessment::class, 'mapping_id');
    }

    // Accessor: label tipe penilai
    public function getAssessorTypeLabelAttribute(): string
    {
        return $this->assessor_type === 'atasan' ? 'Atasan Langsung' : 'Rekan Kerja';
    }

    // Accessor: status badge untuk Blade
    public function getStatusBadgeAttribute(): string
    {
        return $this->is_done
            ? 'bg-green-100 text-green-800'
            : 'bg-amber-100 text-amber-800';
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->is_done ? 'Sudah Dinilai' : 'Belum Dinilai';
    }
}
