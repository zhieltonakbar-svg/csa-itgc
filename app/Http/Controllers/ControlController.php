<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Control;
use App\Models\ControlEvidence;
use App\Models\User;
use App\Notifications\ControlWorkflowNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ControlController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user || !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Only Admin can add Control.',
            ], 403);
        }

        try {
            $validated = $request->validate([
                'application_ids' => 'required|array|min:1',
                'application_ids.*' => 'required|exists:applications,id',
                'it_category_id' => 'required|exists:it_categories,id',
                'control_description' => 'required|string',
                'year' => 'required|integer',
                'quarter' => 'required|string|in:q1,q2,q3,q4',
                'uptis' => 'required|array|min:1',
                'uptis.*' => 'required|string|max:255|exists:uptis,name',
                'keterangan_frekuensi' => 'nullable|string|max:255',
                'key_control' => 'nullable|string|max:255',
            ]);

            $selectedUptis = collect($validated['uptis'])
                ->map(fn ($value) => trim((string) $value))
                ->filter()
                ->unique()
                ->values();

            $selectedApplicationIds = collect($validated['application_ids'])
                ->map(fn ($value) => (int) $value)
                ->unique()
                ->values();

            if ($selectedUptis->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please select at least one UPTI.',
                ], 422);
            }

            if ($selectedApplicationIds->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please select at least one Application.',
                ], 422);
            }

            $applications = Application::query()
                ->where('is_active', true)
                ->with('upti')
                ->whereIn('id', $selectedApplicationIds)
                ->get();

            if ($applications->count() !== $selectedApplicationIds->count()) {
                return response()->json([
                    'success' => false,
                    'message' => 'One or more selected applications are invalid.',
                ], 422);
            }

            $applicationByUpti = [];

            foreach ($applications as $application) {
                $mappedUpti = trim(
                    (string) optional($application->upti)->name
                );

                if ($mappedUpti === '') {
                    continue;
                }

                $applicationByUpti[
                    strtolower($mappedUpti)
                ][] = $application;
            }

            foreach ($selectedUptis as $uptiName) {
                if (
                    empty(
                        $applicationByUpti[
                            strtolower($uptiName)
                        ]
                    )
                ) {
                    return response()->json([
                        'success' => false,
                        'message' =>
                            "No Application is mapped to UPTI {$uptiName}.",
                    ], 422);
                }
            }

            $createdControls = DB::transaction(function () use (
                $validated,
                $selectedUptis,
                $applications
            ) {
                $createdControls = [];

                foreach ($applications as $application) {
                    $mappedUpti = trim(
                        (string) optional($application->upti)->name
                    );

                    if ($mappedUpti === '') {
                        continue;
                    }

                    if (
                        !$selectedUptis->contains(
                            fn ($upti) =>
                                strcasecmp(
                                    $upti,
                                    $mappedUpti
                                ) === 0
                        )
                    ) {
                        continue;
                    }

                    $existingIds = Control::query()
                        ->whereRaw(
                            'LOWER(TRIM(upti)) = LOWER(TRIM(?))',
                            [$mappedUpti]
                        )
                        ->where(
                            'it_control_id',
                            'like',
                            'C-IT-%'
                        )
                        ->lockForUpdate()
                        ->pluck('it_control_id');

                    $maxSequence = 0;

                    foreach ($existingIds as $existingId) {
                        if (
                            preg_match(
                                '/^C-IT-(\d+)$/i',
                                trim((string) $existingId),
                                $matches
                            )
                        ) {
                            $sequence = (int) $matches[1];

                            if ($sequence > $maxSequence) {
                                $maxSequence = $sequence;
                            }
                        }
                    }

                    $controlId = 'C-IT-' . str_pad(
                        (string) ($maxSequence + 1),
                        2,
                        '0',
                        STR_PAD_LEFT
                    );

                    $control = Control::create([
                        'application_id' => $application->id,
                        'it_category_id' => $validated['it_category_id'],
                        'it_control_id' => $controlId,
                        'control_description' =>
                            $validated['control_description'],
                        'status_control' => 'not_started',
                        'status_it_category' => 'not_completed',
                        'keterangan_frekuensi' =>
                            $validated['keterangan_frekuensi'] ?? null,
                        'upti' => $mappedUpti,
                        'key_control' =>
                            $validated['key_control'] ?? null,
                        'year' => $validated['year'],
                        'quarter' => $validated['quarter'],
                    ]);

                    $createdControls[] = $control;
                }

                return $createdControls;
            });

            foreach ($createdControls as $control) {
                $this->recalculateCategoryStatus(
                    $control->application_id,
                    $control->it_category_id,
                    $control->year,
                    $control->quarter
                );
            }

            foreach ($createdControls as $control) {
                $control->load([
                    'application.upti',
                    'itCategory',
                    'evidences',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' =>
                    count($createdControls) .
                    ' control(s) created successfully.',
                'controls' => $createdControls,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Add Control failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create control.',
            ], 500);
        }
    }

    /**
     * Return active Applications mapped to the selected UPTI(s).
     *
     * Used by the "Add Control" form (Admin) so the Application
     * choices react to which UPTI(s) are checked.
     *
     * Hanya ADMIN yang membutuhkan endpoint ini.
     */
    public function applicationsByUptis(Request $request)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses.',
            ], 403);
        }

        try {
            $validated = $request->validate([
                'uptis'   => 'required|array|min:1',
                'uptis.*' => 'required|string|max:255',
            ]);

            $uptiNames = collect($validated['uptis'])
                ->map(fn ($upti) => trim($upti))
                ->filter()
                ->unique()
                ->values();

            $uptiIds = \App\Models\Upti::query()
                ->whereIn('name', $uptiNames)
                ->pluck('id');

            $applications = Application::query()
                ->where('is_active', true)
                ->whereIn('upti_id', $uptiIds)
                ->with('upti')
                ->orderBy('name')
                ->get()
                ->map(function ($app) {
                    return [
                        'id'        => $app->id,
                        'name'      => $app->name,
                        'upti_name' => $app->upti->name ?? '',
                    ];
                });

            return response()->json([
                'success'      => true,
                'applications' => $applications,
            ]);

        } catch (ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors'  => $e->errors(),
            ], 422);

        } catch (\Throwable $e) {

            Log::error('Fetch applications by UPTIs failed', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch applications.',
            ], 500);
        }
    }

    public function nextControlIds(Request $request)
    {
        $user = auth()->user();

        if (!$user || !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Only Admin can generate Control IDs.',
            ], 403);
        }

        try {
            $validated = $request->validate([
                'uptis' => 'required|array|min:1',
                'uptis.*' => 'required|string|max:255|exists:uptis,name',
            ]);

            $selectedUptis = collect($validated['uptis'])
                ->map(fn ($value) => trim((string) $value))
                ->filter()
                ->unique()
                ->values();

            $controlIds = [];

            foreach ($selectedUptis as $uptiName) {
                $existingIds = Control::query()
                    ->whereRaw(
                        'LOWER(TRIM(upti)) = LOWER(TRIM(?))',
                        [$uptiName]
                    )
                    ->where(
                        'it_control_id',
                        'like',
                        'C-IT-%'
                    )
                    ->pluck('it_control_id');

                $maxSequence = 0;

                foreach ($existingIds as $existingId) {
                    if (
                        preg_match(
                            '/^C-IT-(\d+)$/i',
                            trim((string) $existingId),
                            $matches
                        )
                    ) {
                        $sequence = (int) $matches[1];

                        if ($sequence > $maxSequence) {
                            $maxSequence = $sequence;
                        }
                    }
                }

                $controlIds[$uptiName] =
                    'C-IT-' .
                    str_pad(
                        (string) ($maxSequence + 1),
                        2,
                        '0',
                        STR_PAD_LEFT
                    );
            }

            return response()->json([
                'success' => true,
                'control_ids' => $controlIds,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Generate Control ID failed', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate Control IDs.',
            ], 500);
        }
    }

    public function getApplicationsByUptis(Request $request)
    {
        $user = auth()->user();

        if (!$user || !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Only Admin can access application mappings.',
            ], 403);
        }

        try {
            $validated = $request->validate([
                'uptis' => 'required|array|min:1',
                'uptis.*' => 'required|string|max:255|exists:uptis,name',
            ]);

            $selectedUptis = collect($validated['uptis'])
                ->map(fn ($value) => trim((string) $value))
                ->filter()
                ->unique()
                ->values();

            $applications = Application::query()
                ->where('is_active', true)
                ->with('upti')
                ->whereHas('upti', function ($query) use ($selectedUptis) {
                    $query->where(function ($q) use ($selectedUptis) {
                        foreach ($selectedUptis as $upti) {
                            $q->orWhereRaw(
                                'LOWER(TRIM(name)) = LOWER(TRIM(?))',
                                [$upti]
                            );
                        }
                    });
                })
                ->orderBy('name')
                ->get();

            $result = $applications->map(function ($application) {
                return [
                    'id' => $application->id,
                    'name' => $application->name,
                    'upti_id' => $application->upti_id,
                    'upti_name' =>
                        optional($application->upti)->name,
                ];
            })->values();

            return response()->json([
                'success' => true,
                'applications' => $result,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Get applications by UPTI failed', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load application mapping.',
            ], 500);
        }
    }

    public function update(Request $request, Control $control)
    {
        try {
            $user = auth()->user();

            if ($user->isCreator() || $user->role === 'officer') {
                if (!$this->userCanAccessControl($user, $control)) {
                    return response()->json([
                        'success' => false,
                        'message' =>
                            'You do not have access to this control.',
                    ], 403);
                }

                $editableStatuses = [
                    'not_started',
                    'drafting',
                    'return_to_officer',
                ];

                if (!in_array(
                    $control->status_control,
                    $editableStatuses,
                    true
                )) {
                    return response()->json([
                        'success' => false,
                        'message' =>
                            'Evidence cannot be uploaded for the current control status.',
                    ], 403);
                }

                $request->validate([
                    'evidences' => 'required',
                    'evidences.*' =>
                        'file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
                    'file_types' => 'required|array',
                    'file_types.*' =>
                        'required|string|max:255',
                ]);

                if (!$request->hasFile('evidences')) {
                    return response()->json([
                        'success' => false,
                        'message' =>
                            'Please select at least one evidence file.',
                    ], 422);
                }

                $this->storeEvidenceFiles(
                    $control,
                    $request
                );

                if (in_array(
                    $control->status_control,
                    [
                        'not_started',
                        'return_to_officer',
                    ],
                    true
                )) {
                    $control->status_control = 'drafting';
                    $control->save();
                }

                $this->recalculateCategoryStatus(
                    $control->application_id,
                    $control->it_category_id,
                    $control->year,
                    $control->quarter
                );

                $control->refresh();

                $control->load([
                    'application.upti',
                    'itCategory',
                    'evidences',
                ]);

                return response()->json([
                    'success' => true,
                    'message' =>
                        'Evidence uploaded successfully.',
                    'control' => $control,
                ]);
            }

            if (!$user->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' =>
                        'You do not have permission to edit this control.',
                ], 403);
            }

            $validated = $request->validate([
                'application_id' =>
                    'required|exists:applications,id',
                'it_category_id' =>
                    'required|exists:it_categories,id',
                'it_control_id' =>
                    'required|string|max:255',
                'keterangan_frekuensi' =>
                    'nullable|string|max:255',
                'upti' =>
                    'nullable|string|max:255',
                'key_control' =>
                    'nullable|string|max:255',
                'control_description' =>
                    'required|string',
                'file_type' =>
                    'nullable|string|max:255',
                'evidences.*' =>
                    'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
                'file_types.*' =>
                    'nullable|string|max:255',
                'existing_file_types.*' =>
                    'nullable|string|max:255',
            ]);

            $application = Application::query()
                ->where('is_active', true)
                ->with('upti')
                ->find($validated['application_id']);

            if (!$application) {
                return response()->json([
                    'success' => false,
                    'message' => 'Application not found.',
                ], 404);
            }

            $mappedUpti =
                trim((string) optional($application->upti)->name);

            if ($mappedUpti === '') {
                return response()->json([
                    'success' => false,
                    'message' =>
                        'Selected application has no valid UPTI mapping.',
                ], 422);
            }

            $control->update([
                'application_id' =>
                    $validated['application_id'],
                'it_category_id' =>
                    $validated['it_category_id'],
                'it_control_id' =>
                    $validated['it_control_id'],
                'keterangan_frekuensi' =>
                    $validated['keterangan_frekuensi'] ?? null,
                'upti' =>
                    $mappedUpti,
                'key_control' =>
                    $validated['key_control'] ?? null,
                'file_type' =>
                    $validated['file_type'] ?? null,
                'control_description' =>
                    $validated['control_description'],
            ]);

            if (
                $request->has('existing_file_types') &&
                is_array($request->existing_file_types)
            ) {
                foreach (
                    $request->existing_file_types as $evidenceId => $fileType
                ) {
                    ControlEvidence::query()
                        ->where(
                            'id',
                            $evidenceId
                        )
                        ->where(
                            'control_id',
                            $control->id
                        )
                        ->update([
                            'file_type' => $fileType,
                        ]);
                }
            }

            if ($request->hasFile('evidences')) {
                $this->storeEvidenceFiles(
                    $control,
                    $request
                );
            }

            $this->recalculateCategoryStatus(
                $control->application_id,
                $control->it_category_id,
                $control->year,
                $control->quarter
            );

            $control->refresh();

            $control->load([
                'application.upti',
                'itCategory',
                'evidences',
            ]);

            return response()->json([
                'success' => true,
                'message' =>
                    'Control updated successfully.',
                'control' => $control,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Update Control failed', [
                'control_id' => $control->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' =>
                    'Failed to update control.',
            ], 500);
        }
    }

    public function updateStatus(
        Request $request,
        Control $control
    ) {
        return response()->json([
            'success' => false,
            'message' =>
                'Control status is managed automatically by the workflow.',
        ], 403);
    }

    public function transition(
        Request $request,
        Control $control
    ) {
        try {
            $user = auth()->user();

            if (!$this->userCanAccessControl($user, $control)) {
                return response()->json([
                    'success' => false,
                    'message' =>
                        'You do not have access to this control.',
                ], 403);
            }

            $validated = $request->validate([
                'to_status' => 'required|string',
                'notes' => 'nullable|string',
            ]);

            $fromStatus =
                $control->status_control ??
                'not_started';

            $toStatus =
                $validated['to_status'];

            if ($user->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' =>
                        'Admin cannot change Control status through workflow.',
                ], 403);
            }

            $role = match ($user->role) {
                'creator',
                'officer' => 'creator',
                'reviewer' => 'reviewer',
                'approver' => 'approver',
                default => $user->role,
            };

            if (!Control::isTransitionAllowed(
                $role,
                $fromStatus,
                $toStatus
            )) {
                return response()->json([
                    'success' => false,
                    'message' =>
                        'This workflow transition is not allowed.',
                ], 403);
            }

            $notes =
                trim(
                    (string) (
                        $validated['notes']
                        ?? ''
                    )
                );

            if (
                in_array(
                    $toStatus,
                    [
                        'ongoing_approval',
                        'completed',
                        'return_to_officer',
                        'return_to_reviewer',
                    ],
                    true
                )
            ) {
                $wordCount = count(
                    preg_split(
                        '/\s+/',
                        $notes,
                        -1,
                        PREG_SPLIT_NO_EMPTY
                    )
                );

                if ($wordCount < 3) {
                    return response()->json([
                        'success' => false,
                        'message' =>
                            'Notes must contain at least 3 words.',
                    ], 422);
                }
            }

            if ($user->isReviewer()) {
                $control->reviewer_notes =
                    $notes;
            }

            if ($user->isApprover()) {
                $control->approver_notes =
                    $notes;
            }

            if ($toStatus === 'ongoing_review') {
                $control->submitted_at =
                    now();
            }

            if ($toStatus === 'ongoing_approval') {
                $control->reviewed_at =
                    now();
            }

            if ($toStatus === 'completed') {
                $control->approved_at =
                    now();
            }

            $control->status_control =
                $toStatus;

            $control->save();

            $this->recalculateCategoryStatus(
                $control->application_id,
                $control->it_category_id,
                $control->year,
                $control->quarter
            );

            $targetUsers =
                $this->getWorkflowRecipients(
                    $control,
                    $toStatus
                );

            if ($targetUsers->isNotEmpty()) {
                $url =
                    route(
                        'dashboard',
                        [
                            'year' =>
                                $control->year,
                            'quarter' =>
                                $control->quarter,
                            'application_id' =>
                                $control->application_id,
                        ]
                    );

                $label =
                    Control::$statusLabels[$toStatus]
                    ?? $toStatus;

                $message =
                    "Control {$control->it_control_id} is now {$label}.";

                Notification::send(
                    $targetUsers,
                    new ControlWorkflowNotification(
                        $message,
                        $url,
                        $control->id
                    )
                );
            }

            $control->refresh();

            return response()->json([
                'success' => true,
                'message' =>
                    'Control status updated successfully.',
                'control' => $control,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Control transition failed', [
                'control_id' =>
                    $control->id,
                'message' =>
                    $e->getMessage(),
                'trace' =>
                    $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' =>
                    'Failed to update control workflow.',
            ], 500);
        }
    }

    public function getEvidences(
        Control $control
    ) {
        $user = auth()->user();

        if (!$this->userCanAccessControl(
            $user,
            $control
        )) {
            return response()->json([
                'success' => false,
                'message' =>
                    'You do not have access to this control.',
            ], 403);
        }

        $control->load('evidences');

        return response()->json([
            'success' => true,
            'control' => $control,
            'evidences' =>
                $control->evidences,
        ]);
    }

    public function destroy(
        Control $control
    ) {
        $user =
            auth()->user();

        if (
            !$user ||
            !$user->isAdmin()
        ) {
            return response()->json([
                'success' => false,
                'message' =>
                    'Only Admin can delete Control.',
            ], 403);
        }

        $applicationId =
            $control->application_id;

        $categoryId =
            $control->it_category_id;

        $year =
            $control->year;

        $quarter =
            $control->quarter;

        foreach (
            $control->evidences
            as $evidence
        ) {
            Storage::disk('public')
                ->delete(
                    $evidence->file_path
                );

            Storage::disk('public')
                ->delete(
                    'evidence/previews/preview_' .
                    $evidence->id .
                    '.pdf'
                );
        }

        $control
            ->evidences()
            ->delete();

        $control->delete();

        $this->recalculateCategoryStatus(
            $applicationId,
            $categoryId,
            $year,
            $quarter
        );

        return response()->json([
            'success' => true,
            'message' =>
                'Control deleted successfully.',
        ]);
    }

    public function destroyAll(
        Request $request
    ) {
        $user =
            auth()->user();

        if (
            !$user ||
            !$user->isAdmin()
        ) {
            return response()->json([
                'success' => false,
                'message' =>
                    'Only Admin can delete controls.',
            ], 403);
        }

        $validated =
            $request->validate([
                'application_id' =>
                    'required|exists:applications,id',
                'it_category_id' =>
                    'required|exists:it_categories,id',
                'year' =>
                    'required|integer',
                'quarter' =>
                    'required|string|in:q1,q2,q3,q4',
            ]);

        $controls =
            Control::query()
                ->where(
                    'application_id',
                    $validated['application_id']
                )
                ->where(
                    'it_category_id',
                    $validated['it_category_id']
                )
                ->where(
                    'year',
                    $validated['year']
                )
                ->where(
                    'quarter',
                    $validated['quarter']
                )
                ->get();

        foreach (
            $controls as $control
        ) {
            foreach (
                $control->evidences
                as $evidence
            ) {
                Storage::disk('public')
                    ->delete(
                        $evidence->file_path
                    );

                Storage::disk('public')
                    ->delete(
                        'evidence/previews/preview_' .
                        $evidence->id .
                        '.pdf'
                    );
            }

            $control
                ->evidences()
                ->delete();

            $control->delete();
        }

        $this->recalculateCategoryStatus(
            $validated['application_id'],
            $validated['it_category_id'],
            $validated['year'],
            $validated['quarter']
        );

        return response()->json([
            'success' => true,
            'message' =>
                'All controls deleted successfully.',
        ]);
    }

    public function downloadBeritaAcara(
        Control $control
    ) {
        $user =
            auth()->user();

        if (
            !$this->userCanAccessControl(
                $user,
                $control
            )
        ) {
            abort(
                403,
                'You do not have access to this control.'
            );
        }

        $control->load([
            'evidences',
            'application',
            'itCategory',
        ]);

        $officerEvidence =
            $control->evidences
                ->where(
                    'file_type',
                    '!=',
                    'Berita Acara'
                )
                ->first();

        $officerName =
            $officerEvidence?->uploaded_by
            ?? '( Officer / Creator )';

        $uptiId =
            $control->application?->upti_id;

        $reviewer =
            User::query()
                ->where(
                    'role',
                    'reviewer'
                )
                ->when(
                    $uptiId,
                    fn ($query) =>
                        $query->where(
                            'upti_id',
                            $uptiId
                        )
                )
                ->first();

        $approver =
            User::query()
                ->where(
                    'role',
                    'approver'
                )
                ->when(
                    $uptiId,
                    fn ($query) =>
                        $query->where(
                            'upti_id',
                            $uptiId
                        )
                )
                ->first();

        $reviewerName =
            $reviewer?->name
            ?? '( Manager / Reviewer )';

        $approverName =
            $approver?->name
            ?? '( Senior Manager / Approver )';

        $html =
            view(
                'pdf.berita_acara',
                [
                    'control' =>
                        $control,
                    'officerName' =>
                        $officerName,
                    'reviewerName' =>
                        $reviewerName,
                    'approverName' =>
                        $approverName,
                ]
            )->render();

        $pdf =
            \Barryvdh\DomPDF\Facade\Pdf::loadHtml(
                $html
            )
            ->setPaper(
                'a4',
                'portrait'
            )
            ->setOptions([
                'isHtml5ParserEnabled' =>
                    true,
                'isRemoteEnabled' =>
                    false,
                'defaultFont' =>
                    'Helvetica',
            ]);

        $filename =
            'BeritaAcara_' .
            $control->it_control_id .
            '_' .
            strtoupper(
                $control->quarter
            ) .
            $control->year .
            '.pdf';

        return $pdf->stream(
            $filename
        );
    }

    private function storeEvidenceFiles(
        Control $control,
        Request $request
    ): void {
        $fileTypes =
            $request->input(
                'file_types',
                []
            );

        $defaultFileType =
            $request->input(
                'file_type'
            );

        foreach (
            $request->file(
                'evidences',
                []
            ) as $index => $file
        ) {
            $path =
                $file->store(
                    'evidence',
                    'public'
                );

            $fileType =
                !empty(
                    $fileTypes[$index]
                )
                ? $fileTypes[$index]
                : $defaultFileType;

            $evidence =
                ControlEvidence::create([
                    'control_id' =>
                        $control->id,
                    'file_name' =>
                        basename($path),
                    'file_path' =>
                        $path,
                    'original_name' =>
                        $file->getClientOriginalName(),
                    'file_type' =>
                        $fileType,
                    'mime_type' =>
                        $file->getClientMimeType()
                        ?: $file->getMimeType(),
                    'size' =>
                        $file->getSize(),
                    'uploaded_by' =>
                        auth()->user()?->name
                        ?? 'System',
                ]);

            $extension =
                strtolower(
                    $file->getClientOriginalExtension()
                );

            if (
                in_array(
                    $extension,
                    [
                        'doc',
                        'docx',
                        'xls',
                        'xlsx',
                    ],
                    true
                ) &&
                class_exists(
                    \App\Services\DocumentConverter::class
                )
            ) {
                try {
                    $pdfContent =
                        \App\Services\DocumentConverter::convertToPdf(
                            Storage::disk('public')
                                ->path($path),
                            $extension
                        );

                    if ($pdfContent) {
                        $previewPath =
                            'evidence/previews/preview_' .
                            $evidence->id .
                            '.pdf';

                        Storage::disk('public')
                            ->put(
                                $previewPath,
                                $pdfContent
                            );
                    }
                } catch (\Throwable $e) {
                    Log::warning(
                        'Office document PDF preview generation failed.',
                        [
                            'evidence_id' =>
                                $evidence->id,
                            'message' =>
                                $e->getMessage(),
                        ]
                    );
                }
            }
        }
    }

    private function userCanAccessControl(
        ?User $user,
        Control $control
    ): bool {
        if (!$user) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        $userUpti =
            trim(
                (string) optional(
                    $user->upti
                )->name
            );

        $controlUpti =
            trim(
                (string) $control->upti
            );

        if (
            $userUpti === '' ||
            $controlUpti === ''
        ) {
            return false;
        }

        return strcasecmp(
            $userUpti,
            $controlUpti
        ) === 0;
    }

    private function getWorkflowRecipients(
        Control $control,
        string $toStatus
    ) {
        $targetRoles = [];

        if ($toStatus === 'ongoing_review') {
            $targetRoles = [
                'reviewer',
            ];
        }

        if ($toStatus === 'ongoing_approval') {
            $targetRoles = [
                'approver',
            ];
        }

        if ($toStatus === 'return_to_officer') {
            $targetRoles = [
                'creator',
                'officer',
            ];
        }

        if ($toStatus === 'return_to_reviewer') {
            $targetRoles = [
                'reviewer',
            ];
        }

        if (!$targetRoles) {
            return collect();
        }

        $controlUpti =
            trim(
                (string) $control->upti
            );

        if ($controlUpti === '') {
            return collect();
        }

        return User::query()
            ->whereIn(
                'role',
                $targetRoles
            )
            ->whereHas(
                'upti',
                function ($query) use (
                    $controlUpti
                ) {
                    $query->whereRaw(
                        'LOWER(TRIM(name)) = LOWER(TRIM(?))',
                        [$controlUpti]
                    );
                }
            )
            ->get();
    }

    private function recalculateCategoryStatus(
        $applicationId,
        $categoryId,
        $year,
        $quarter
    ): void {
        $controls =
            Control::query()
                ->where(
                    'application_id',
                    $applicationId
                )
                ->where(
                    'it_category_id',
                    $categoryId
                )
                ->where(
                    'year',
                    $year
                )
                ->where(
                    'quarter',
                    $quarter
                )
                ->get();

        if ($controls->isEmpty()) {
            $categoryStatus =
                'not_completed';

            $pivotStatus =
                'not_complete';
        } else {
            $allCompleted =
                $controls->every(
                    fn ($control) =>
                        $control->status_control ===
                        'completed'
                );

            $allNotStarted =
                $controls->every(
                    fn ($control) =>
                        $control->status_control ===
                        'not_started'
                );

            if ($allCompleted) {
                $categoryStatus =
                    'completed';

                $pivotStatus =
                    'complete';
            } elseif ($allNotStarted) {
                $categoryStatus =
                    'not_completed';

                $pivotStatus =
                    'not_complete';
            } else {
                $categoryStatus =
                    'partial_completed';

                $pivotStatus =
                    'partial';
            }
        }

        Control::query()
            ->where(
                'application_id',
                $applicationId
            )
            ->where(
                'it_category_id',
                $categoryId
            )
            ->where(
                'year',
                $year
            )
            ->where(
                'quarter',
                $quarter
            )
            ->update([
                'status_it_category' =>
                    $categoryStatus,
            ]);

        $application =
            Application::find(
                $applicationId
            );

        if ($application) {
            $application
                ->itCategories()
                ->updateExistingPivot(
                    $categoryId,
                    [
                        'completion_status' =>
                            $pivotStatus,
                    ]
                );
        }
    }
}