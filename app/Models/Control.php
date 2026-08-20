<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Control extends Model
{
    protected $fillable = [
        'uic',
        'assigned_to',
        'application_id',
        'it_category_id',
        'it_control_id',
        'control_description',
        'status_control',
        'status_it_category',
        'berita_acara_path',
        'keterangan_frekuensi',
        'upti',
        'file_type',
        'key_control',
        'year',
        'quarter',
        'submitted_at',
        'reviewed_at',
        'approved_at',
        'reviewer_notes',
        'approver_notes',
    ];

    protected $casts = [
        'key_control'  => 'boolean',
        'submitted_at' => 'datetime',
        'reviewed_at'  => 'datetime',
        'approved_at'  => 'datetime',
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
     * The user assigned to work on this control.
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
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
        'not_started'        => 'Not Started Yet',
        'drafting'           => 'Drafting',
        'ongoing_review'     => 'On Going Review',
        'ongoing_approval'   => 'On Going Approval',
        'return_to_officer'  => 'Return to Officer',
        'return_to_reviewer' => 'Return to Reviewer',
        'completed'          => 'Completed',
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

    /**
     * Workflow: allowed status transitions per role.
     *
     * Returns an array of [from_status => to_status] pairs allowed for the given role.
     * Key   = current status
     * Value = new status after action
     *
     * @param  string $role  admin | creator | reviewer | approver
     * @return array<string, string>  [ currentStatus => nextStatus, ... ]
     */
    public static function allowedTransitions(string $role): array
    {
        return match ($role) {
            'creator' => [
                'not_started'       => 'drafting',        // Start
                'drafting'          => 'ongoing_review',   // Submit
                'return_to_officer' => 'ongoing_review',   // Resubmit
            ],
            'reviewer' => [
                'ongoing_review'     => 'ongoing_approval',  // Approve
                'ongoing_review'     => 'return_to_officer', // Reject  (handled separately by action)
                'return_to_reviewer' => 'ongoing_approval',  // Approve
                'return_to_reviewer' => 'return_to_officer', // Reject  (handled separately by action)
            ],
            'approver' => [
                'ongoing_approval' => 'completed',          // Approve
                'ongoing_approval' => 'return_to_officer',  // Reject
            ],
            default => [], // admin: no workflow transitions
        };
    }

    /**
     * Check if a specific transition is allowed for a role.
     *
     * @param  string $role
     * @param  string $fromStatus
     * @param  string $toStatus
     * @return bool
     */
    public static function isTransitionAllowed(string $role, string $fromStatus, string $toStatus): bool
    {
        $map = [
            'creator' => [
                'not_started'       => ['drafting', 'ongoing_review'],
                'drafting'          => ['ongoing_review'],
                'return_to_officer' => ['ongoing_review'],
            ],
            'reviewer' => [
                'ongoing_review'     => ['ongoing_approval', 'return_to_officer'],
                'return_to_reviewer' => ['ongoing_approval', 'return_to_officer'],
            ],
            'approver' => [
                'ongoing_approval' => ['completed', 'return_to_officer'],
            ],
        ];

        return in_array($toStatus, $map[$role][$fromStatus] ?? [], true);
    }

    /**
     * Get available workflow actions for a user on this control.
     *
     * Returns an array of action descriptors for the UI.
     *
     * @param  \App\Models\User $user
     * @return array
     */
    public function availableActions(User $user): array
    {
        $role   = $user->role;
        $status = $this->status_control ?? 'not_started';
        $actions = [];

        switch ($role) {
            case 'creator':
                // Creator can only act on controls assigned to them (or unassigned)
                if ($this->assigned_to !== null && $this->assigned_to !== $user->id) {
                    return [];
                }
                
                // Action to send to manager when drafted or not started
                if (in_array($status, ['not_started', 'drafting'], true)) {
                    $actions[] = ['action' => 'start', 'label' => 'Send to Manager', 'to' => 'ongoing_review', 'class' => 'btn-workflow-start'];
                }
                
                if ($status === 'return_to_officer') {
                    $actions[] = ['action' => 'resubmit', 'label' => 'Resubmit', 'to' => 'ongoing_review',  'class' => 'btn-workflow-submit'];
                }
                break;

            case 'reviewer':
                if (in_array($status, ['ongoing_review', 'return_to_reviewer'], true)) {
                    $actions[] = ['action' => 'approve', 'label' => 'Approve', 'to' => 'ongoing_approval',  'class' => 'btn-workflow-approve'];
                    $actions[] = ['action' => 'reject',  'label' => 'Reject',  'to' => 'return_to_officer', 'class' => 'btn-workflow-reject'];
                }
                break;

            case 'approver':
                if ($status === 'ongoing_approval') {
                    $actions[] = ['action' => 'approve', 'label' => 'Approve', 'to' => 'completed',         'class' => 'btn-workflow-approve'];
                    $actions[] = ['action' => 'reject',  'label' => 'Reject',  'to' => 'return_to_officer', 'class' => 'btn-workflow-reject'];
                }
                break;

            case 'admin':
            default:
                // Admin has no workflow actions
                break;
        }

        return $actions;
    }

    /**
     * Calculate both IT Category status and pivot completion status based on controls.
     */
    public static function calculateStatus($controls): array
    {
        if ($controls->isEmpty()) {
            return [
                'cat_status'   => 'not_completed',
                'pivot_status' => 'not_complete',
            ];
        }

        $totalCount = $controls->count();
        $completedCount = $controls->filter(fn($c) => $c->status_control === 'completed')->count();

        if ($completedCount === $totalCount) {
            return [
                'cat_status'   => 'completed',
                'pivot_status' => 'complete',
            ];
        } elseif ($completedCount > 0) {
            return [
                'cat_status'   => 'partial_completed',
                'pivot_status' => 'partial',
            ];
        } else {
            return [
                'cat_status'   => 'not_completed',
                'pivot_status' => 'not_complete',
            ];
        }
    }
}
