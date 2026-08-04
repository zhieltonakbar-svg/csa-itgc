<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Control extends Model
{
    protected $fillable = [
        'uic',
        'application_id',
        'it_category_id',
        'it_control_id',
        'control_description',
        'status_control',
        'status_it_category',
        'keterangan_frekuensi',
        'upti',
        'file_type',
        'key_control',
        'year',
        'quarter',
    ];

    /**
     * The application this control belongs to.
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * The IT category this control belongs to.
     */
    public function itCategory(): BelongsTo
    {
        return $this->belongsTo(ItCategory::class);
    }

    /**
     * The evidence files attached to this control.
     */
    public function evidences()
    {
        return $this->hasMany(ControlEvidence::class);
    }

    /* ── Status Control label map ─────────────────────────────────── */
    public static array $statusLabels = [
        'not_started'      => 'Not Started Yet',
        'ongoing_review'   => 'On Going Review',
        'ongoing_approval' => 'On Going Approval',
        'completed'        => 'Completed',
    ];

    /**
     * Human-readable status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return self::$statusLabels[$this->status_control] ?? 'Not Started Yet';
    }

    /* ── IT Category Status label map ─────────────────────────────── */
    public static array $itCategoryStatusLabels = [
        'not_completed'     => 'Not Completed',
        'partial_completed' => 'Partial Completed',
        'completed'         => 'Completed',
    ];

    /**
     * Human-readable IT category status label.
     */
    public function getItCategoryStatusLabelAttribute(): string
    {
        return self::$itCategoryStatusLabels[$this->status_it_category] ?? 'Not Completed';
    }
}
