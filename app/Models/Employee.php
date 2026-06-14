<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employee extends Model
{
    protected $fillable = [
        'nik',
        'name',
        'division_id',
        'level',
        'contract_status',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'level' => 'integer',
    ];

    // Relasi ke division
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    // Relasi ke akun login
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    // Mapping dimana employee ini DINILAI
    public function mappingsAsEmployee(): HasMany
    {
        return $this->hasMany(AssessorMapping::class, 'employee_id');
    }

    // Mapping dimana employee ini jadi PENILAI
    public function mappingsAsAssessor(): HasMany
    {
        return $this->hasMany(AssessorMapping::class, 'assessor_id');
    }

    // Hasil penilaian yang DITERIMA employee ini
    public function assessmentsReceived(): HasMany
    {
        return $this->hasMany(Assessment::class, 'employee_id');
    }

    // Penilaian yang DIBUAT employee ini sebagai penilai
    public function assessmentsGiven(): HasMany
    {
        return $this->hasMany(Assessment::class, 'assessor_id');
    }

    // Accessor: label level karyawan
    public function getLevelLabelAttribute(): string
    {
        return $this->level == 2 ? 'Ka. Bagian' : 'Operator/Staff';
    }

    // Accessor: badge warna status kontrak (dipakai di Blade)
    public function getContractBadgeAttribute(): string
    {
        return match ($this->contract_status) {
            'Tetap' => 'bg-green-100 text-green-800',
            'Kontrak' => 'bg-blue-100 text-blue-800',
            'Probation' => 'bg-amber-100 text-amber-800',
            'Magang' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    // Mutator: nama selalu disimpan dengan Title Case
    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = ucwords(strtolower(trim($value)));
    }

    // Mutator: NIK selalu uppercase, tanpa spasi
    public function setNikAttribute($value): void
    {
        $this->attributes['nik'] = strtoupper(str_replace(' ', '', $value));
    }
}
