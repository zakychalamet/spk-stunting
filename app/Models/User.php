<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'role',
        'password',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function ahpCalculations(): HasMany
    {
        return $this->hasMany(AhpCalculation::class, 'created_by');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->role === 'administrator';
    }

    public function isBidan(): bool
    {
        return $this->role === 'bidan';
    }

    public function isAhliGizi(): bool
    {
        return $this->role === 'ahli_gizi';
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'admin', 'administrator' => 'Administrator',
            'bidan' => 'Bidan',
            'ahli_gizi' => 'Ahli Gizi',
            default => ucfirst($this->role),
        };
    }
}
