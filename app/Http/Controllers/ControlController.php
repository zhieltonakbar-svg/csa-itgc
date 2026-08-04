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
            'it_control_id'       => 'required|string|max:255',
            'control_description' => 'required|string',
            'year'                => 'required|integer',
            'quarter'             => 'required|string',
            'status_control'      => 'nullable|in:not_started,ongoing_review,ongoing_approval,completed',
            'evidences.*'         => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'file_type'           => 'nullable|string|max:255',
            'file_types.*'        => 'nullable|string|max:255',
        ]);

        // Prevent duplicate Control ID within the same application
        $exists = Control::where('application_id', $request->application_id)
            ->where('it_control_id', $request->it_control_id)
            ->where('year', $request->year)
            ->where('quarter', $request->quarter)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Control ID already exists for this application in the given period.'
            ], 422);
        }

        $control = Control::create([
            'application_id'      => $request->application_id,
            'it_category_id'      => $request->it_category_id,
            'it_control_id'       => $request->it_control_id,
            'control_description' => $request->control_description,
            'status_control'      => $request->status_control ?? 'not_started',
            'status_it_category'  => 'not_completed',
            'year'                => $request->year,
            'quarter'             => $request->quarter,
        ]);

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

        // reload from DB to get the updated status_it_category
        $control->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Control created successfully.',
            'control' => $control
        ]);
    }

    /**
     * Update the control record (status, description) and handle file uploads.
     */
    public function update(Request $request, Control $control)
    {
        $request->validate([
            'application_id'      => 'required|exists:applications,id',
            'it_category_id'      => 'required|exists:it_categories,id',
            'it_control_id'       => 'required|string|max:255',
            'status_control'      => 'required|in:not_started,ongoing_review,ongoing_approval,completed',
            'control_description' => 'required|string',
            'evidences.*'         => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'file_type'           => 'nullable|string|max:255',
            'file_types.*'        => 'nullable|string|max:255',
            'existing_file_types.*' => 'nullable|string|max:255',
        ]);

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
            'control_description' => $request->control_description,
        ]);

        // Update file_type for all existing evidence records associated with this control
        $defaultFileType = $request->input('file_type', null);
        if ($defaultFileType !== null) {
            ControlEvidence::where('control_id', $control->id)
                ->update(['file_type' => $defaultFileType]);
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
