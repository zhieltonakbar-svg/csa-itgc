<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        'upti_id',
        'is_active',
        'profile_photo_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /* ── Role helpers ─────────────────────────────────────── */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCreator(): bool
    {
        return $this->role === 'creator';
    }

    public function isReviewer(): bool
    {
        return $this->role === 'reviewer';
    }

    public function isApprover(): bool
    {
        return $this->role === 'approver';
    }

    /* ── Relationships ────────────────────────────────────── */

    public function assignedControls()
    {
        return $this->hasMany(Control::class, 'assigned_to');
    }

    public function upti()
    {
        return $this->belongsTo(Upti::class, 'upti_id');
    }
}
