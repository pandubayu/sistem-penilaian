<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'employee_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi ke data karyawan
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    // Log aktivitas yang dibuat user ini
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    // Helper role check
    public function isHr(): bool
    {
        return $this->role === 'hr';
    }

    public function isPenilai(): bool
    {
        return $this->role === 'penilai';
    }

    public function isKaryawan(): bool
    {
        return $this->role === 'karyawan';
    }
}
