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
        'auth_type',
        'username',
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
            'is_active'         => 'boolean',
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

    /**
     * Officer / Creator helper.
     */
    public function isOfficer(): bool
    {
        return $this->role === 'creator' || $this->role === 'officer';
    }

    /**
     * Whether this account authenticates via the company LDAP
     * server instead of a local password.
     */
    public function isLdap(): bool
    {
        return $this->auth_type === 'ldap';
    }

    /**
     * Check whether user is allowed to edit Control master data.
     *
     * Only Admin.
     */
    public function canEditControl(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Check whether user is allowed to change Control status.
     *
     * Only Reviewer and Approver.
     */
    public function canChangeControlStatus(): bool
    {
        return $this->isReviewer() || $this->isApprover();
    }

    /**
     * Check whether user is allowed to upload evidence.
     *
     * Creator/Officer and Admin.
     */
    public function canUploadEvidence(): bool
    {
        return $this->isAdmin() || $this->isOfficer();
    }

    /**
     * Check whether user is allowed to add Control.
     *
     * Only Admin.
     */
    public function canAddControl(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Check whether user is allowed to delete Control.
     *
     * Only Admin.
     */
    public function canDeleteControl(): bool
    {
        return $this->isAdmin();
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