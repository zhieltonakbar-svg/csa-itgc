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

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function itCategory(): BelongsTo
    {
        return $this->belongsTo(ItCategory::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function evidences()
    {
        return $this->hasMany(ControlEvidence::class);
    }

    public static array $statusLabels = [
        'not_started'        => 'Not Started Yet',
        'drafting'           => 'Drafting',
        'ongoing_review'     => 'On Going Review',
        'ongoing_approval'   => 'On Going Approval',
        'return_to_officer'  => 'Return to Officer',
        'return_to_reviewer' => 'Return to Reviewer',
        'completed'          => 'Completed',
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::$statusLabels[$this->status_control]
            ?? 'Not Started Yet';
    }

    public static array $itCategoryStatusLabels = [
        'not_completed'     => 'Not Completed',
        'partial_completed' => 'Partial Completed',
        'completed'         => 'Completed',
    ];

    public function getItCategoryStatusLabelAttribute(): string
    {
        return self::$itCategoryStatusLabels[$this->status_it_category]
            ?? 'Not Completed';
    }

    /**
     * Status Control tidak boleh diedit langsung oleh Officer/Creator.
     * Workflow Officer tetap menggunakan transition().
     */
    public static function allowedTransitions(string $role): array
    {
        return match ($role) {

            'creator' => [
                'not_started'       => 'drafting',
                'drafting'          => 'ongoing_review',
                'return_to_officer' => 'ongoing_review',
            ],

            'reviewer' => [
                'ongoing_review'     => 'ongoing_approval',
                'return_to_reviewer' => 'ongoing_approval',
            ],

            'approver' => [
                'ongoing_approval' => 'completed',
            ],

            default => [],
        };
    }

    public static function isTransitionAllowed(
        string $role,
        string $fromStatus,
        string $toStatus
    ): bool {
        $map = [

            'creator' => [
                'not_started' => [
                    'drafting',
                    'ongoing_review',
                ],

                'drafting' => [
                    'ongoing_review',
                ],

                'return_to_officer' => [
                    'ongoing_review',
                ],
            ],

            'reviewer' => [
                'ongoing_review' => [
                    'ongoing_approval',
                    'return_to_officer',
                ],

                'return_to_reviewer' => [
                    'ongoing_approval',
                    'return_to_officer',
                ],
            ],

            'approver' => [
                'ongoing_approval' => [
                    'completed',
                    'return_to_officer',
                ],
            ],
        ];

        return in_array(
            $toStatus,
            $map[$role][$fromStatus] ?? [],
            true
        );
    }

    /**
     * Officer/Creator tidak mendapatkan aksi untuk
     * mengubah Status Control secara langsung.
     *
     * Perubahan status Officer hanya melalui workflow
     * Send to Manager / Resubmit.
     */
    public function availableActions(User $user): array
    {
        $role   = $user->role;
        $status = $this->status_control ?? 'not_started';
        $actions = [];

        switch ($role) {

            case 'creator':

                if (
                    $this->assigned_to !== null &&
                    $this->assigned_to !== $user->id
                ) {
                    return [];
                }

                if (
                    in_array(
                        $status,
                        ['not_started', 'drafting'],
                        true
                    )
                ) {
                    $actions[] = [
                        'action' => 'start',
                        'label'  => 'Send to Manager',
                        'to'     => 'ongoing_review',
                        'class'  => 'btn-workflow-start',
                    ];
                }

                if ($status === 'return_to_officer') {
                    $actions[] = [
                        'action' => 'resubmit',
                        'label'  => 'Resubmit',
                        'to'     => 'ongoing_review',
                        'class'  => 'btn-workflow-submit',
                    ];
                }

                break;

            case 'reviewer':

                if (
                    in_array(
                        $status,
                        ['ongoing_review', 'return_to_reviewer'],
                        true
                    )
                ) {
                    $actions[] = [
                        'action' => 'approve',
                        'label'  => 'Approve',
                        'to'     => 'ongoing_approval',
                        'class'  => 'btn-workflow-approve',
                    ];

                    $actions[] = [
                        'action' => 'reject',
                        'label'  => 'Reject',
                        'to'     => 'return_to_officer',
                        'class'  => 'btn-workflow-reject',
                    ];
                }

                break;

            case 'approver':

                if ($status === 'ongoing_approval') {
                    $actions[] = [
                        'action' => 'approve',
                        'label'  => 'Approve',
                        'to'     => 'completed',
                        'class'  => 'btn-workflow-approve',
                    ];

                    $actions[] = [
                        'action' => 'reject',
                        'label'  => 'Reject',
                        'to'     => 'return_to_officer',
                        'class'  => 'btn-workflow-reject',
                    ];
                }

                break;

            case 'admin':
            default:
                break;
        }

        return $actions;
    }

    /**
     * Hanya role berikut yang boleh mengubah
     * Status Control secara langsung.
     */
    public function canChangeStatus(User $user): bool
    {
        return in_array(
            $user->role,
            ['reviewer', 'approver'],
            true
        );
    }

    /**
     * Hanya Admin yang boleh mengubah data Control.
     */
    public function canEditControl(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Officer/Creator hanya boleh upload evidence.
     */
    public function canUploadEvidence(User $user): bool
    {
        return in_array(
            $user->role,
            ['creator', 'officer'],
            true
        );
    }

    /**
     * Hanya Admin yang boleh menghapus Control.
     */
    public function canDeleteControl(User $user): bool
    {
        return $user->isAdmin();
    }

    public static function calculateStatus($controls): array
    {
        if ($controls->isEmpty()) {
            return [
                'cat_status'   => 'not_completed',
                'pivot_status' => 'not_complete',
            ];
        }

        $totalCount = $controls->count();

        $completedCount = $controls
            ->filter(
                fn ($c) =>
                    $c->status_control === 'completed'
            )
            ->count();

        if ($completedCount === $totalCount) {
            return [
                'cat_status'   => 'completed',
                'pivot_status' => 'complete',
            ];
        }

        if ($completedCount > 0) {
            return [
                'cat_status'   => 'partial_completed',
                'pivot_status' => 'partial',
            ];
        }

        return [
            'cat_status'   => 'not_completed',
            'pivot_status' => 'not_complete',
        ];
    }
}