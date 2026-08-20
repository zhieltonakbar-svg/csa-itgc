<?php

namespace App\Http\Controllers;

use App\Models\Control;
use App\Models\ControlEvidence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ControlController extends Controller
{
    /**
     * Store a newly created control record in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'application_id'      => 'required|exists:applications,id',
            'it_category_id'      => 'required|exists:it_categories,id',
            'control_description' => 'required|string',
            'year'                => 'required|integer',
            'quarter'             => 'required|string',
            'status_control'      => 'nullable|in:not_started,ongoing_review,ongoing_approval,completed',
            'keterangan_frekuensi'=> 'nullable|string|max:255',
            'key_control'         => 'nullable|string|max:255',
            'uptis'               => 'nullable|array',
            'uptis.*'             => 'string|max:255',
            'evidences.*'         => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'file_type'           => 'nullable|string|max:255',
            'file_types.*'        => 'nullable|string|max:255',
        ]);

        $appExists = \App\Models\Application::find($request->application_id);
        
        $selectedUptis = $request->input('uptis', []);
        if (empty($selectedUptis) && $appExists && $appExists->upti) {
            $selectedUptis = [$appExists->upti->name];
        }

        if (empty($selectedUptis)) {
            return response()->json([
                'success' => false,
                'message' => 'Pilih minimal satu UPTI.'
            ], 422);
        }

        $createdControls = [];

        foreach ($selectedUptis as $uptiValue) {
            // Auto-generate next sequence for this UPTI
            $existingControls = Control::where('application_id', $request->application_id)
                ->where('year', $request->year)
                ->where('quarter', $request->quarter)
                ->where('upti', $uptiValue)
                ->get();

            $maxSequence = 0;
            foreach ($existingControls as $c) {
                if (preg_match('/C-IT-(\d+)/', $c->it_control_id, $matches)) {
                    $num = (int)$matches[1];
                    if ($num > $maxSequence) {
                        $maxSequence = $num;
                    }
                }
            }
            $nextSequence = $maxSequence + 1;
            $nextControlId = 'C-IT-' . str_pad($nextSequence, 2, '0', STR_PAD_LEFT);

            $control = Control::create([
                'application_id'      => $request->application_id,
                'it_category_id'      => $request->it_category_id,
                'it_control_id'       => $request->it_control_id,
                'control_description' => $request->control_description,
                'status_control'      => $request->status_control ?? 'not_started',
                'status_it_category'  => 'not_completed',
                'keterangan_frekuensi'=> $request->keterangan_frekuensi,
                'upti'                => $uptiValue,
                'key_control'         => $request->key_control,
                'file_type'           => $request->file_type,
                'year'                => $request->year,
                'quarter'             => $request->quarter,
            ]);

            $createdControls[] = $control;
        }

        if (empty($createdControls)) {
            return response()->json([
                'success' => false,
                'message' => 'Control ID already exists for the selected UPTI(s) in this period. (' . implode(', ', $skipped) . ')'
            ], 422);
        }

        $control = $createdControls[0]; // For returning one reference object if needed

        if ($request->hasFile('evidences')) {
            $fileTypes = $request->input('file_types', []);
            $defaultFileType = $request->input('file_type', null);
            foreach ($request->file('evidences') as $index => $file) {
                $path = $file->store('evidence', 'public');
                $ft = (!empty($fileTypes[$index])) ? $fileTypes[$index] : $defaultFileType;
                $evidence = ControlEvidence::create([
                    'control_id'    => $control->id,
                    'file_name'     => basename($path),
                    'file_path'     => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'file_type'     => $ft,
                    'mime_type'     => $file->getClientMimeType() ?: $file->getMimeType(),
                    'size'          => $file->getSize(),
                    'uploaded_by'   => auth()->user()?->name ?? 'System',
                ]);

                // Generate PDF preview immediately for DOCX/XLSX
                $extension = strtolower($file->getClientOriginalExtension());
                if (in_array($extension, ['doc', 'docx', 'xls', 'xlsx'])) {
                    $pdfContent = \App\Services\DocumentConverter::convertToPdf(Storage::disk('public')->path($path), $extension);
                    if ($pdfContent) {
                        $previewRelativePath = 'evidence/previews/preview_' . $evidence->id . '.pdf';
                        Storage::disk('public')->put($previewRelativePath, $pdfContent);
                    }
                }
            }
        }

        $this->recalculateCategoryStatus(
            $control->application_id,
            $control->it_category_id,
            $control->year,
            $control->quarter
        );

        // load evidences to return a consistent object
        $control->load('evidences');
        $control->refresh();

        return response()->json([
            'success' => true,
            'message' => count($createdControls) . ' Control(s) created successfully.',
            'control' => $control // returns the first created control
        ]);
    }

    /**
     * Update the control record (status, description) and handle file uploads.
     */
    public function update(Request $request, Control $control)
    {
        \Illuminate\Support\Facades\Log::info('Update Control Request:', $request->all());
        
        try {
            $request->validate([
                'application_id'      => 'required|exists:applications,id',
                'it_category_id'      => 'required|exists:it_categories,id',
                'it_control_id'       => 'required|string|max:255',
                'status_control'      => 'required|in:not_started,ongoing_review,ongoing_approval,completed',
                'keterangan_frekuensi'=> 'nullable|string|max:255',
                'upti'                => 'nullable|string|max:255',
                'key_control'         => 'nullable|string|max:255',
                'control_description' => 'required|string',
                'evidences.*'         => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
                'file_type'           => 'nullable|string|max:255',
                'file_types.*'        => 'nullable|string|max:255',
                'existing_file_types.*' => 'nullable|string|max:255',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Illuminate\Support\Facades\Log::error('Validation Failed:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        }

        // Prevent duplicate Control ID within the same application/year/quarter
        $exists = Control::where('application_id', $request->application_id)
            ->where('it_control_id', $request->it_control_id)
            ->where('year', $control->year)
            ->where('quarter', $control->quarter)
            ->where('id', '!=', $control->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Control ID already exists for this application in the given period.'
            ], 422);
        }

        $control->update([
            'application_id'      => $request->application_id,
            'it_category_id'      => $request->it_category_id,
            'it_control_id'       => $request->it_control_id,
            'status_control'      => $request->status_control,
            'keterangan_frekuensi'=> $request->keterangan_frekuensi,
            'upti'                => $request->upti,
            'key_control'         => $request->key_control,
            'file_type'           => $request->file_type,
            'control_description' => $request->control_description,
        ]);

        // Update file_type for existing evidence records if provided
        if ($request->has('existing_file_types') && is_array($request->existing_file_types)) {
            foreach ($request->existing_file_types as $evidenceId => $fileType) {
                \App\Models\ControlEvidence::where('id', $evidenceId)
                    ->where('control_id', $control->id)
                    ->update(['file_type' => $fileType]);
            }
        }

        if ($request->hasFile('evidences')) {
            $fileTypes = $request->input('file_types', []);
            $defaultFileType = $request->input('file_type', null);
            foreach ($request->file('evidences') as $index => $file) {
                $path = $file->store('evidence', 'public');
                $ft = (!empty($fileTypes[$index])) ? $fileTypes[$index] : $defaultFileType;
                $evidence = ControlEvidence::create([
                    'control_id'    => $control->id,
                    'file_name'     => basename($path),
                    'file_path'     => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'file_type'     => $ft,
                    'mime_type'     => $file->getClientMimeType() ?: $file->getMimeType(),
                    'size'          => $file->getSize(),
                    'uploaded_by'   => auth()->user()?->name ?? 'System',
                ]);

                // Generate PDF preview immediately for DOCX/XLSX
                $extension = strtolower($file->getClientOriginalExtension());
                if (in_array($extension, ['doc', 'docx', 'xls', 'xlsx'])) {
                    $pdfContent = \App\Services\DocumentConverter::convertToPdf(Storage::disk('public')->path($path), $extension);
                    if ($pdfContent) {
                        $previewRelativePath = 'evidence/previews/preview_' . $evidence->id . '.pdf';
                        Storage::disk('public')->put($previewRelativePath, $pdfContent);
                    }
                }
            }
        }

        $this->recalculateCategoryStatus(
            $control->application_id,
            $control->it_category_id,
            $control->year,
            $control->quarter
        );
        
        $control->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Control updated successfully.',
            'control' => $control->load('evidences')
        ]);
    }

    /**
     * Update only the status of the control record.
     */
    public function updateStatus(Request $request, Control $control)
    {
        $request->validate([
            'status_control' => 'required|in:not_started,ongoing_review,ongoing_approval,completed',
        ]);

        $control->update([
            'status_control' => $request->status_control,
        ]);

        $this->recalculateCategoryStatus(
            $control->application_id,
            $control->it_category_id,
            $control->year,
            $control->quarter
        );

        $control->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
            'control' => $control->load('evidences')
        ]);
    }

    /**
     * Download the Berita Acara PDF for a completed control.
     */
    public function downloadBeritaAcara(Control $control)
    {
        $control->load(['evidences', 'application', 'itCategory']);

        // Resolve officer name from the first evidence uploaded_by field
        $officerEvidence = $control->evidences
            ->where('file_type', '!=', 'Berita Acara')
            ->first();
        $officerName = $officerEvidence?->uploaded_by ?? '( Officer / Creator )';

        // Resolve reviewer and approver names from User model by role
        $reviewer = \App\Models\User::where('role', 'reviewer')->first();
        $approver = \App\Models\User::where('role', 'approver')->first();

        $reviewerName = $reviewer?->name ?? '( Manager / Reviewer )';
        $approverName = $approver?->name ?? '( Senior Manager / Approver )';

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

        $filename = 'BeritaAcara_' . $control->it_control_id . '_' . strtoupper($control->quarter) . $control->year . '.pdf';

        return $pdf->stream($filename);
    }

    /**
     * Get all evidence files associated with the specified control directly from database.
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
     * Remove the specified control record from storage.
     */
    public function destroy(Control $control)
    {
        $appId   = $control->application_id;
        $catId   = $control->it_category_id;
        $year    = $control->year;
        $quarter = $control->quarter;

        // Delete associated files from storage
        foreach ($control->evidences as $evidence) {
            Storage::disk('public')->delete($evidence->file_path);
        }

        // Delete the control (evidences should cascade delete if DB configured, or eloquent will delete)
        // Since we may not have cascade in DB, let's delete evidences explicitly first
        $control->evidences()->delete();
        $control->delete();

        $this->recalculateCategoryStatus($appId, $catId, $year, $quarter);

        return response()->json([
            'success' => true,
            'message' => 'Control deleted successfully.'
        ]);
    }

    /**
     * Remove all control records for a specific category/application/year/quarter context.
     */
    public function destroyAll(Request $request)
    {
        $appId   = $request->application_id;
        $catId   = $request->it_category_id;
        $year    = $request->year;
        $quarter = $request->quarter;

        $controls = Control::where('application_id', $appId)
            ->where('it_category_id', $catId)
            ->where('year', $year)
            ->where('quarter', $quarter)
            ->get();

        foreach ($controls as $control) {
            foreach ($control->evidences as $evidence) {
                Storage::disk('public')->delete($evidence->file_path);
            }
            $control->evidences()->delete();
            $control->delete();
        }

        $this->recalculateCategoryStatus($appId, $catId, $year, $quarter);

        return response()->json([
            'success' => true,
            'message' => 'All controls deleted successfully.'
        ]);
    }

    /**
     * Recalculates the IT Category Status based on the statuses of all its controls.
     */
    private function recalculateCategoryStatus($applicationId, $categoryId, $year, $quarter)
    {
        $controls = Control::where('application_id', $applicationId)
            ->where('it_category_id', $categoryId)
            ->where('year', $year)
            ->where('quarter', $quarter)
            ->get();

        if ($controls->isEmpty()) {
            $catStatus = 'not_completed';
            $pivotStatus = 'not_complete';
        } else {
            $allCompleted = $controls->every(fn($c) => $c->status_control === 'completed');
            $allNotStarted = $controls->every(fn($c) => $c->status_control === 'not_started');

            if ($allCompleted) {
                $catStatus = 'completed';
                $pivotStatus = 'complete';
            } elseif ($allNotStarted) {
                $catStatus = 'not_completed';
                $pivotStatus = 'not_complete';
            } else {
                $catStatus = 'partial_completed';
                $pivotStatus = 'partial';
            }
        }

        // Update all controls in this group
        Control::where('application_id', $applicationId)
            ->where('it_category_id', $categoryId)
            ->where('year', $year)
            ->where('quarter', $quarter)
            ->update(['status_it_category' => $catStatus]);

        // Update the pivot table for the dashboard
        $application = \App\Models\Application::find($applicationId);
        if ($application) {
            $application->itCategories()->updateExistingPivot($categoryId, [
                'completion_status' => $pivotStatus
            ]);
        }
    }
}
