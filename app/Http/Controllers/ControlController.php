<?php

namespace App\Http\Controllers;

use App\Models\Control;
use App\Models\ControlEvidence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class ControlController extends Controller
{
    /**
     * Store control(s).
     *
     * Hanya ADMIN yang boleh membuat Control.
     */
    public function store(Request $request)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menambahkan Control.',
            ], 403);
        }

        try {
            $validated = $request->validate([
                'application_id'        => 'required|exists:applications,id',
                'it_category_id'        => 'required|exists:it_categories,id',
                'control_description'   => 'required|string',
                'year'                  => 'required|integer',
                'quarter'               => 'required|string',

                'uptis'                 => 'required|array|min:1',
                'uptis.*'               => 'required|string|max:255|exists:uptis,name',

                'keterangan_frekuensi'  => 'nullable|string|max:255',
                'key_control'           => 'nullable|string|max:255',

                'evidences.*'           => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
                'file_type'             => 'nullable|string|max:255',
                'file_types.*'          => 'nullable|string|max:255',
            ]);

            $selectedUptis = collect($validated['uptis'])
                ->map(fn ($upti) => trim($upti))
                ->filter()
                ->unique()
                ->values();

            if ($selectedUptis->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pilih minimal satu UPTI.',
                ], 422);
            }

            $createdControls = DB::transaction(function () use (
                $validated,
                $selectedUptis
            ) {
                $createdControls = [];

                foreach ($selectedUptis as $uptiName) {

                    $existingIds = Control::query()
                        ->where('upti', $uptiName)
                        ->where('it_control_id', 'like', 'C-IT-%')
                        ->pluck('it_control_id');

                    $maxSequence = 0;

                    foreach ($existingIds as $existingId) {
                        if (
                            preg_match(
                                '/^C-IT-(\d+)$/',
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

                    $nextSequence = $maxSequence + 1;

                    $nextControlId = 'C-IT-' . str_pad(
                        (string) $nextSequence,
                        2,
                        '0',
                        STR_PAD_LEFT
                    );

                    $control = Control::create([
                        'application_id'        => $validated['application_id'],
                        'it_category_id'        => $validated['it_category_id'],
                        'it_control_id'         => $nextControlId,
                        'control_description'   => $validated['control_description'],
                        'status_control'        => 'not_started',
                        'status_it_category'    => 'not_completed',
                        'keterangan_frekuensi'  => $validated['keterangan_frekuensi'] ?? null,
                        'upti'                  => $uptiName,
                        'key_control'           => $validated['key_control'] ?? null,
                        'file_type'             => $validated['file_type'] ?? null,
                        'year'                  => $validated['year'],
                        'quarter'               => $validated['quarter'],
                    ]);

                    $createdControls[] = $control;
                }

                return $createdControls;
            });

            /*
             * Add Control seharusnya tidak upload evidence.
             * Tetap dipertahankan untuk kompatibilitas lama,
             * tetapi hanya ADMIN yang dapat masuk ke method ini.
             */
            if (
                $request->hasFile('evidences') &&
                !empty($createdControls)
            ) {
                $this->storeEvidenceFiles(
                    $createdControls[0],
                    $request
                );
            }

            $firstControl = $createdControls[0];

            $this->recalculateCategoryStatus(
                $firstControl->application_id,
                $firstControl->it_category_id,
                $firstControl->year,
                $firstControl->quarter
            );

            foreach ($createdControls as $control) {
                $control->refresh();
                $control->load('evidences');
            }

            return response()->json([
                'success'  => true,
                'message'  => count($createdControls) . ' Control(s) created successfully.',
                'controls' => $createdControls,
            ]);

        } catch (ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors'  => $e->errors(),
            ], 422);

        } catch (\Throwable $e) {

            Log::error('Add Control failed', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create control: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Return next Control ID for each selected UPTI.
     *
     * Hanya ADMIN yang membutuhkan preview Control ID.
     */
    public function nextControlIds(Request $request)
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
                'uptis.*' => 'required|string|max:255|exists:uptis,name',
            ]);

            $selectedUptis = collect($validated['uptis'])
                ->map(fn ($upti) => trim($upti))
                ->filter()
                ->unique()
                ->values();

            $controlIds = [];

            foreach ($selectedUptis as $uptiName) {

                $existingIds = Control::query()
                    ->where('upti', $uptiName)
                    ->where('it_control_id', 'like', 'C-IT-%')
                    ->pluck('it_control_id');

                $maxSequence = 0;

                foreach ($existingIds as $existingId) {

                    if (
                        preg_match(
                            '/^C-IT-(\d+)$/',
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

                $nextSequence = $maxSequence + 1;

                $controlIds[$uptiName] = 'C-IT-' . str_pad(
                    (string) $nextSequence,
                    2,
                    '0',
                    STR_PAD_LEFT
                );
            }

            return response()->json([
                'success'     => true,
                'control_ids' => $controlIds,
            ]);

        } catch (ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors'  => $e->errors(),
            ], 422);

        } catch (\Throwable $e) {

            Log::error('Generate Control IDs failed', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate Control IDs.',
            ], 500);
        }
    }

    /**
     * Update an existing control.
     *
     * ADMIN:
     * - boleh edit data Control
     * - boleh mengubah File Type evidence
     * - boleh upload evidence
     *
     * OFFICER / CREATOR:
     * - tidak boleh edit data Control
     * - tidak boleh mengubah File Type evidence lama
     * - hanya boleh upload evidence
     */
    public function update(Request $request, Control $control)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $user = auth()->user();

        /*
         * ============================================================
         * ADMIN
         * ============================================================
         */
        if ($user->isAdmin()) {

            try {
                $validated = $request->validate([
                    'application_id'         => 'required|exists:applications,id',
                    'it_category_id'         => 'required|exists:it_categories,id',
                    'it_control_id'          => 'required|string|max:255',
                    'status_control'         => 'required|in:not_started,drafting,ongoing_review,ongoing_approval,return_to_officer,return_to_reviewer,completed',
                    'keterangan_frekuensi'   => 'nullable|string|max:255',
                    'upti'                   => 'nullable|string|max:255',
                    'key_control'            => 'nullable|string|max:255',
                    'control_description'    => 'required|string',
                    'file_type'              => 'nullable|string|max:255',
                    'evidences.*'            => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
                    'file_types.*'           => 'nullable|string|max:255',
                    'existing_file_types.*'  => 'nullable|string|max:255',
                ]);

                $duplicateExists = Control::query()
                    ->where('application_id', $validated['application_id'])
                    ->where('it_control_id', $validated['it_control_id'])
                    ->where('year', $control->year)
                    ->where('quarter', $control->quarter)
                    ->where('id', '!=', $control->id)
                    ->exists();

                if ($duplicateExists) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Control ID already exists for this application in the given period.',
                    ], 422);
                }

                /*
                 * Simpan status sebelum perubahan.
                 */
                $oldStatus = $control->status_control;

                $control->update([
                    'application_id'        => $validated['application_id'],
                    'it_category_id'        => $validated['it_category_id'],
                    'it_control_id'         => $validated['it_control_id'],
                    'status_control'        => $validated['status_control'],
                    'keterangan_frekuensi'  => $validated['keterangan_frekuensi'] ?? null,
                    'upti'                  => $validated['upti'] ?? null,
                    'key_control'           => $validated['key_control'] ?? null,
                    'file_type'             => $validated['file_type'] ?? null,
                    'control_description'   => $validated['control_description'],
                ]);

                /*
                 * ADMIN boleh mengubah File Type evidence lama.
                 *
                 * Jika File Type evidence lama diubah,
                 * status Control kembali ke review.
                 */
                $fileTypeChanged = false;

                if (
                    $request->has('existing_file_types') &&
                    is_array($request->existing_file_types)
                ) {
                    foreach (
                        $request->existing_file_types
                        as $evidenceId => $fileType
                    ) {
                        $evidence = ControlEvidence::query()
                            ->where('id', $evidenceId)
                            ->where('control_id', $control->id)
                            ->first();

                        if (!$evidence) {
                            continue;
                        }

                        if ((string) $evidence->file_type !== (string) $fileType) {
                            $fileTypeChanged = true;

                            $evidence->update([
                                'file_type' => $fileType,
                            ]);
                        }
                    }
                }

                /*
                 * Jika ADMIN mengubah File Type:
                 * Return to Review dan harus melalui Senior Manager.
                 */
                if ($fileTypeChanged) {
                    $control->update([
                        'status_control' => 'ongoing_review',
                        'reviewed_at'    => null,
                        'approved_at'    => null,
                    ]);
                }

                /*
                 * ADMIN boleh upload evidence.
                 */
                if ($request->hasFile('evidences')) {
                    $this->storeEvidenceFiles($control, $request);
                }

                $this->recalculateCategoryStatus(
                    $control->application_id,
                    $control->it_category_id,
                    $control->year,
                    $control->quarter
                );

                $control->refresh();
                $control->load('evidences');

                return response()->json([
                    'success' => true,
                    'message' => $fileTypeChanged
                        ? 'File Type berhasil diubah. Control dikembalikan ke Review.'
                        : 'Control updated successfully.',
                    'control' => $control,
                ]);

            } catch (ValidationException $e) {

                return response()->json([
                    'success' => false,
                    'message' => 'Validation Error',
                    'errors'  => $e->errors(),
                ], 422);

            } catch (\Throwable $e) {

                Log::error('Admin update Control failed', [
                    'control_id' => $control->id,
                    'message'    => $e->getMessage(),
                    'trace'      => $e->getTraceAsString(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update control: ' . $e->getMessage(),
                ], 500);
            }
        }

        /*
         * ============================================================
         * OFFICER / CREATOR
         * ============================================================
         *
         * Officer TIDAK BOLEH mengubah:
         * - Application
         * - IT Category
         * - Control ID
         * - Control Description
         * - Status Control
         * - UPTI
         * - Key Control
         * - Frekuensi
         * - File Type evidence lama
         *
         * Officer hanya boleh upload evidence baru.
         */
        if (
            $user->role === 'creator' ||
            $user->role === 'officer'
        ) {

            try {
                $validated = $request->validate([
                    'evidences.*'  => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
                    'file_types.*' => 'nullable|string|max:255',
                ]);

                if (!$request->hasFile('evidences')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Officer hanya dapat mengupload evidence.',
                    ], 422);
                }

                /*
                 * Officer tidak boleh mengirim existing_file_types.
                 */
                if (
                    $request->has('existing_file_types') &&
                    is_array($request->existing_file_types) &&
                    count($request->existing_file_types) > 0
                ) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Officer tidak diperbolehkan mengubah File Type evidence yang sudah ada.',
                    ], 403);
                }

                /*
                 * Officer tidak boleh mengirim perubahan data Control.
                 * Field-field tersebut diabaikan dan tidak pernah di-update.
                 */
                $this->storeEvidenceFiles($control, $request);

                $control->refresh();
                $control->load('evidences');

                return response()->json([
                    'success' => true,
                    'message' => 'Evidence uploaded successfully.',
                    'control' => $control,
                ]);

            } catch (ValidationException $e) {

                return response()->json([
                    'success' => false,
                    'message' => 'Validation Error',
                    'errors'  => $e->errors(),
                ], 422);

            } catch (\Throwable $e) {

                Log::error('Officer upload evidence failed', [
                    'control_id' => $control->id,
                    'message'    => $e->getMessage(),
                    'trace'      => $e->getTraceAsString(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload evidence: ' . $e->getMessage(),
                ], 500);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Anda tidak memiliki akses untuk mengubah Control.',
        ], 403);
    }

    /**
     * Update control status.
     *
     * HANYA Reviewer dan Approver.
     */
    public function updateStatus(Request $request, Control $control)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $user = auth()->user();

        if (!in_array($user->role, ['reviewer', 'approver'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk mengubah Status Control.',
            ], 403);
        }

        try {
            $validated = $request->validate([
                'status_control' => [
                    'required',
                    'in:ongoing_review,ongoing_approval,completed,return_to_officer,return_to_reviewer',
                ],
            ]);

            $currentStatus = $control->status_control;
            $newStatus     = $validated['status_control'];

            /*
             * Pastikan transition sesuai role.
             */
            if (
                !Control::isTransitionAllowed(
                    $user->role,
                    $currentStatus,
                    $newStatus
                )
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Perubahan Status Control tidak sesuai dengan workflow role Anda.',
                ], 403);
            }

            /*
             * Reviewer
             */
            if ($user->role === 'reviewer') {

                if (
                    $newStatus === 'ongoing_approval'
                ) {
                    $control->reviewed_at = now();
                    $control->reviewer_notes = $request->input(
                        'reviewer_notes'
                    );
                }

                if (
                    $newStatus === 'return_to_officer'
                ) {
                    $control->reviewed_at = now();
                    $control->reviewer_notes = $request->input(
                        'reviewer_notes'
                    );
                }
            }

            /*
             * Approver / Senior Manager
             */
            if ($user->role === 'approver') {

                if ($newStatus === 'completed') {
                    $control->approved_at = now();
                    $control->approver_notes = $request->input(
                        'approver_notes'
                    );
                }

                if ($newStatus === 'return_to_officer') {
                    $control->approver_notes = $request->input(
                        'approver_notes'
                    );
                }
            }

            $control->status_control = $newStatus;
            $control->save();

            $this->recalculateCategoryStatus(
                $control->application_id,
                $control->it_category_id,
                $control->year,
                $control->quarter
            );

            $control->refresh();
            $control->load('evidences');

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully.',
                'control' => $control,
            ]);

        } catch (ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors'  => $e->errors(),
            ], 422);
        }
    }

    /**
     * Get all evidence files belonging to a control.
     */
    public function getEvidences(Control $control)
    {
        $control->load('evidences');

        return response()->json([
            'success'   => true,
            'control'   => $control,
            'evidences' => $control->evidences,
        ]);
    }

    /**
     * Delete one control.
     *
     * HANYA ADMIN.
     */
    public function destroy(Control $control)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus Control.',
            ], 403);
        }

        $applicationId = $control->application_id;
        $categoryId    = $control->it_category_id;
        $year          = $control->year;
        $quarter       = $control->quarter;

        foreach ($control->evidences as $evidence) {
            Storage::disk('public')->delete($evidence->file_path);
        }

        $control->evidences()->delete();
        $control->delete();

        $this->recalculateCategoryStatus(
            $applicationId,
            $categoryId,
            $year,
            $quarter
        );

        return response()->json([
            'success' => true,
            'message' => 'Control deleted successfully.',
        ]);
    }

    /**
     * Delete all controls for the current assessment context.
     *
     * HANYA ADMIN.
     */
    public function destroyAll(Request $request)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus Control.',
            ], 403);
        }

        $validated = $request->validate([
            'application_id' => 'required|exists:applications,id',
            'it_category_id' => 'required|exists:it_categories,id',
            'year'           => 'required|integer',
            'quarter'        => 'required|string',
        ]);

        $controls = Control::query()
            ->where('application_id', $validated['application_id'])
            ->where('it_category_id', $validated['it_category_id'])
            ->where('year', $validated['year'])
            ->where('quarter', $validated['quarter'])
            ->get();

        foreach ($controls as $control) {

            foreach ($control->evidences as $evidence) {
                Storage::disk('public')->delete($evidence->file_path);
            }

            $control->evidences()->delete();
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
            'message' => 'All controls deleted successfully.',
        ]);
    }

    /**
     * Download Berita Acara.
     */
    public function downloadBeritaAcara(Control $control)
    {
        $control->load([
            'evidences',
            'application',
            'itCategory',
        ]);

        $officerEvidence = $control->evidences
            ->where('file_type', '!=', 'Berita Acara')
            ->first();

        $officerName =
            $officerEvidence?->uploaded_by ??
            '( Officer / Creator )';

        $reviewer = \App\Models\User::where(
            'role',
            'reviewer'
        )->first();

        $approver = \App\Models\User::where(
            'role',
            'approver'
        )->first();

        $reviewerName =
            $reviewer?->name ??
            '( Manager / Reviewer )';

        $approverName =
            $approver?->name ??
            '( Senior Manager / Approver )';

        $html = view('pdf.berita_acara', [
            'control'      => $control,
            'officerName'  => $officerName,
            'reviewerName' => $reviewerName,
            'approverName' => $approverName,
        ])->render();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHtml($html)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => false,
                'defaultFont'          => 'Helvetica',
            ]);

        $filename =
            'BeritaAcara_' .
            $control->it_control_id .
            '_' .
            strtoupper($control->quarter) .
            $control->year .
            '.pdf';

        return $pdf->stream($filename);
    }

    /**
     * Store evidence files and create their database records.
     */
    private function storeEvidenceFiles(
        Control $control,
        Request $request
    ): void {
        $fileTypes = $request->input('file_types', []);
        $defaultFileType = $request->input('file_type');

        foreach ($request->file('evidences', []) as $index => $file) {

            $path = $file->store('evidence', 'public');

            $fileType = !empty($fileTypes[$index])
                ? $fileTypes[$index]
                : $defaultFileType;

            $evidence = ControlEvidence::create([
                'control_id'    => $control->id,
                'file_name'     => basename($path),
                'file_path'     => $path,
                'original_name' => $file->getClientOriginalName(),
                'file_type'     => $fileType,
                'mime_type'     => $file->getClientMimeType()
                    ?: $file->getMimeType(),
                'size'          => $file->getSize(),
                'uploaded_by'   => auth()->user()?->name ?? 'System',
            ]);

            /*
             * Office document preview.
             */
            $extension = strtolower(
                $file->getClientOriginalExtension()
            );

            if (
                in_array(
                    $extension,
                    ['doc', 'docx', 'xls', 'xlsx'],
                    true
                ) &&
                class_exists(\App\Services\DocumentConverter::class)
            ) {
                try {

                    $pdfContent =
                        \App\Services\DocumentConverter::convertToPdf(
                            Storage::disk('public')->path($path),
                            $extension
                        );

                    if ($pdfContent) {

                        $previewPath =
                            'evidence/previews/preview_' .
                            $evidence->id .
                            '.pdf';

                        Storage::disk('public')->put(
                            $previewPath,
                            $pdfContent
                        );
                    }

                } catch (\Throwable $e) {

                    Log::warning(
                        'Office document PDF preview generation failed.',
                        [
                            'evidence_id' => $evidence->id,
                            'message'     => $e->getMessage(),
                        ]
                    );
                }
            }
        }
    }

    /**
     * Recalculate IT Category status for a specific assessment context.
     */
    private function recalculateCategoryStatus(
        $applicationId,
        $categoryId,
        $year,
        $quarter
    ): void {
        $controls = Control::query()
            ->where('application_id', $applicationId)
            ->where('it_category_id', $categoryId)
            ->where('year', $year)
            ->where('quarter', $quarter)
            ->get();

        if ($controls->isEmpty()) {

            $categoryStatus = 'not_completed';
            $pivotStatus    = 'not_complete';

        } else {

            $allCompleted = $controls->every(
                fn ($control) =>
                $control->status_control === 'completed'
            );

            $allNotStarted = $controls->every(
                fn ($control) =>
                $control->status_control === 'not_started'
            );

            if ($allCompleted) {

                $categoryStatus = 'completed';
                $pivotStatus    = 'complete';

            } elseif ($allNotStarted) {

                $categoryStatus = 'not_completed';
                $pivotStatus    = 'not_complete';

            } else {

                $categoryStatus = 'partial_completed';
                $pivotStatus    = 'partial';
            }
        }

        Control::query()
            ->where('application_id', $applicationId)
            ->where('it_category_id', $categoryId)
            ->where('year', $year)
            ->where('quarter', $quarter)
            ->update([
                'status_it_category' => $categoryStatus,
            ]);

        $application =
            \App\Models\Application::find($applicationId);

        if ($application) {

            $application->itCategories()
                ->updateExistingPivot(
                    $categoryId,
                    [
                        'completion_status' => $pivotStatus,
                    ]
                );
        }
    }
}