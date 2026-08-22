<?php

namespace App\Http\Controllers;

use App\Models\Control;
use App\Models\ControlEvidence;
use App\Models\User;
use App\Notifications\ControlWorkflowNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EvidenceController extends Controller
{
    /**
     * View or download the evidence file.
     */
    public function show(ControlEvidence $evidence)
    {
        $this->checkEvidenceAccess($evidence);

        $path = Storage::disk('public')->path($evidence->file_path);

        if (!file_exists($path)) {
            $path = storage_path('app/public/' . $evidence->file_path);

            if (!file_exists($path)) {
                abort(404, 'File not found.');
            }
        }

        $mime = $evidence->mime_type ?: (mime_content_type($path) ?: 'application/octet-stream');

        if (str_contains(strtolower($mime), 'pdf')) {
            return response()->file($path, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . rawurlencode($evidence->original_name) . '"'
            ]);
        }

        return response()->download($path, $evidence->original_name, [
            'Content-Type' => $mime,
        ]);
    }

    /**
     * Preview document in read-only mode in a new browser tab.
     */
    public function preview(ControlEvidence $evidence)
    {
        $this->checkEvidenceAccess($evidence);

        $path = Storage::disk('public')->path($evidence->file_path);

        if (!file_exists($path)) {
            $path = storage_path('app/public/' . $evidence->file_path);

            if (!file_exists($path)) {
                abort(404, 'File not found.');
            }
        }

        $evidence->load('control.application', 'control.itCategory');

        return view('evidence.preview', [
            'evidence' => $evidence,
            'control'  => $evidence->control,
        ]);
    }

    /**
     * Stream PDF preview directly in browser
     * (converts Word/Excel on-the-fly or uses cached PDF).
     */
    public function streamPreviewPdf(ControlEvidence $evidence)
    {
        $this->checkEvidenceAccess($evidence);

        $path = Storage::disk('public')->path($evidence->file_path);

        if (!file_exists($path)) {
            $path = storage_path('app/public/' . $evidence->file_path);

            if (!file_exists($path)) {
                abort(404, 'File not found.');
            }
        }

        $extension = strtolower(
            pathinfo($evidence->original_name, PATHINFO_EXTENSION)
        );

        // If already PDF, serve directly
        if ($extension === 'pdf') {
            return response()->file($path, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' .
                    rawurlencode($evidence->original_name) . '"'
            ]);
        }

        // Check if converted preview PDF already exists
        // in storage/app/public/evidence/previews
        $previewFilename = 'preview_' . $evidence->id . '.pdf';
        $previewRelativePath = 'evidence/previews/' . $previewFilename;
        $previewFullPath = Storage::disk('public')->path(
            $previewRelativePath
        );

        if (!file_exists($previewFullPath)) {
            $pdfContent = \App\Services\DocumentConverter::convertToPdf(
                $path,
                $extension
            );

            if ($pdfContent) {
                Storage::disk('public')->put(
                    $previewRelativePath,
                    $pdfContent
                );

                $previewFullPath = Storage::disk('public')->path(
                    $previewRelativePath
                );
            }
        }

        if (file_exists($previewFullPath)) {
            return response()->file($previewFullPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' .
                    rawurlencode(
                        pathinfo(
                            $evidence->original_name,
                            PATHINFO_FILENAME
                        ) . '.pdf'
                    ) . '"'
            ]);
        }

        return response()->file($path, [
            'Content-Type' => $evidence->mime_type ?: 'application/octet-stream',
            'Content-Disposition' => 'inline;'
        ]);
    }

    /**
     * Update evidence file type.
     *
     * ONLY ADMIN can update the File Type
     * of an existing evidence.
     *
     * Officer / Creator can:
     * - upload new evidence
     * - choose File Type when uploading new evidence
     *
     * Officer / Creator CANNOT:
     * - edit File Type of existing evidence
     */
    public function update(Request $request, ControlEvidence $evidence)
    {
        $this->checkEvidenceAccess($evidence);

        $user = auth()->user();
        $control = $evidence->control;

        /*
         * ============================================================
         * PERMISSION
         * ============================================================
         *
         * Existing evidence File Type can ONLY be changed by Admin.
         *
         * This is intentionally checked on the backend so that
         * Officer cannot bypass the restriction through DevTools,
         * Postman, direct HTTP request, etc.
         */
        if (!$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Officer tidak memiliki izin untuk mengubah File Type evidence yang sudah ada.',
            ], 403);
        }

        /*
         * ============================================================
         * VALIDATION
         * ============================================================
         */
        $request->validate([
            'file_type' => 'nullable|string|max:255',
        ]);

        /*
         * ============================================================
         * UPDATE FILE TYPE
         * ============================================================
         */
        $oldFileType = $evidence->file_type;
        $newFileType = $request->file_type;

        $evidence->update([
            'file_type' => $newFileType,
        ]);

        /*
         * ============================================================
         * ADMIN CHANGES FILE TYPE
         * ============================================================
         *
         * If the Control is already in review/approval workflow,
         * changing the File Type will return the Control
         * to the reviewer.
         */
        $isEditableStatus = in_array(
            $control->status_control,
            [
                'not_started',
                'drafting',
                'return_to_officer'
            ],
            true
        );

        if (
            $user->isAdmin() &&
            $oldFileType !== $newFileType &&
            !$isEditableStatus
        ) {
            $control->status_control = 'return_to_reviewer';
            $control->save();

            $managers = User::where('role', 'reviewer')->get();

            $url = route('dashboard');

            if ($control->application) {
                $url = route('dashboard.controls', [
                    'category'       => $control->it_category_id,
                    'upti_id'        => $control->application->upti_id ?? 1,
                    'application_id' => $control->application_id,
                    'year'           => $control->year,
                    'quarter'        => $control->quarter,
                ]);
            }

            $message =
                "Control {$control->it_control_id} has been returned by Admin (File Type changed) for re-review.";

            \Illuminate\Support\Facades\Notification::send(
                $managers,
                new ControlWorkflowNotification(
                    $message,
                    $url,
                    $control->id
                )
            );
        }

        return response()->json([
            'success'  => true,
            'message'  => 'Evidence file type updated successfully.',
            'evidence' => $evidence,
        ]);
    }

    /**
     * Delete the evidence file.
     */
    public function destroy(ControlEvidence $evidence)
    {
        $this->checkEvidenceAccess($evidence);

        $user = auth()->user();

        $control = Control::find($evidence->control_id);

        $creatorEditableStatuses = [
            'not_started',
            'drafting',
            'return_to_officer',
            'ongoing_review',
            'return_to_reviewer'
        ];

        $canDelete =
            $user->isAdmin()
            ||
            (
                $user->isCreator()
                &&
                $control
                &&
                in_array(
                    $control->status_control,
                    $creatorEditableStatuses,
                    true
                )
            );

        if (!$canDelete) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to delete this evidence.',
            ], 403);
        }

        if (Storage::disk('public')->exists($evidence->file_path)) {
            Storage::disk('public')->delete($evidence->file_path);
        }

        $evidence->delete();

        // Revert control status back to officer so they can re-upload
        if ($control) {
            $remainingCount = $control->evidences()->count();

            if ($remainingCount === 0) {
                $control->status_control = 'not_started';
                $control->save();

                $message =
                    'Evidence deleted. Control status returned to Not Started.';

                $newStatus = 'not_started';
            } else {
                if ($user->isCreator()) {
                    $control->status_control = 'drafting';
                    $control->save();

                    $message =
                        'Evidence deleted. Control status returned to Drafting.';

                    $newStatus = 'drafting';
                } else {
                    // Admin
                    $control->status_control = 'return_to_officer';
                    $control->save();

                    $url = route('applications.index');

                    if ($control->assigned_to) {
                        $assignedUser = User::find(
                            $control->assigned_to
                        );

                        if ($assignedUser) {
                            $msgStr =
                                "Control {$control->it_control_id} has been returned for correction because Admin deleted an evidence file.";

                            $assignedUser->notify(
                                new ControlWorkflowNotification(
                                    $msgStr,
                                    $url,
                                    $control->id
                                )
                            );
                        }
                    } else {
                        $users = User::where('role', 'creator')->get();

                        $msgStr =
                            "Control {$control->it_control_id} has been returned for correction because Admin deleted an evidence file.";

                        \Illuminate\Support\Facades\Notification::send(
                            $users,
                            new ControlWorkflowNotification(
                                $msgStr,
                                $url,
                                $control->id
                            )
                        );
                    }

                    $message =
                        'Evidence deleted. Control status returned to Officer.';

                    $newStatus = 'return_to_officer';
                }
            }
        } else {
            $message = 'Evidence deleted.';
            $newStatus = null;
        }

        return response()->json([
            'success'    => true,
            'message'    => $message,
            'new_status' => $newStatus,
        ]);
    }

    /**
     * Check if the authenticated user has access
     * to the given evidence based on their UPTI.
     */
    private function checkEvidenceAccess(ControlEvidence $evidence)
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {
            $evidence->load('control');

            // Check UPTI access
            if (
                !$user->upti
                ||
                stripos(
                    $evidence->control->upti,
                    $user->upti->name
                ) === false
            ) {
                abort(403, 'Unauthorized access to this evidence.');
            }

            // Check workflow status visibility
            $status = $evidence->control->status_control;

            if ($user->isReviewer()) {
                if (
                    in_array(
                        $status,
                        [
                            'not_started',
                            'drafting',
                            'return_to_officer'
                        ]
                    )
                ) {
                    abort(
                        403,
                        'Evidence is not yet available for review.'
                    );
                }
            } elseif ($user->isApprover()) {
                if (
                    !in_array(
                        $status,
                        [
                            'ongoing_approval',
                            'completed'
                        ]
                    )
                ) {
                    abort(
                        403,
                        'Evidence is not yet available for approval.'
                    );
                }
            }
        }
    }
}