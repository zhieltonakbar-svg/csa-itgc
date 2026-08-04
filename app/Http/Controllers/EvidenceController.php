<?php

namespace App\Http\Controllers;

use App\Models\ControlEvidence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EvidenceController extends Controller
{
    /**
     * View or download the evidence file.
     */
    public function show(ControlEvidence $evidence)
    {
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
     * Stream PDF preview directly in browser (converts Word/Excel on-the-fly or uses cached PDF).
     */
    public function streamPreviewPdf(ControlEvidence $evidence)
    {
        $path = Storage::disk('public')->path($evidence->file_path);

        if (!file_exists($path)) {
            $path = storage_path('app/public/' . $evidence->file_path);
            if (!file_exists($path)) {
                abort(404, 'File not found.');
            }
        }

        $extension = strtolower(pathinfo($evidence->original_name, PATHINFO_EXTENSION));

        // If already PDF, serve directly
        if ($extension === 'pdf') {
            return response()->file($path, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . rawurlencode($evidence->original_name) . '"'
            ]);
        }

        // Check if converted preview PDF already exists in storage/app/public/evidence/previews
        $previewFilename = 'preview_' . $evidence->id . '.pdf';
        $previewRelativePath = 'evidence/previews/' . $previewFilename;
        $previewFullPath = Storage::disk('public')->path($previewRelativePath);

        if (!file_exists($previewFullPath)) {
            $pdfContent = \App\Services\DocumentConverter::convertToPdf($path, $extension);
            if ($pdfContent) {
                Storage::disk('public')->put($previewRelativePath, $pdfContent);
                $previewFullPath = Storage::disk('public')->path($previewRelativePath);
            }
        }

        if (file_exists($previewFullPath)) {
            return response()->file($previewFullPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . rawurlencode(pathinfo($evidence->original_name, PATHINFO_FILENAME) . '.pdf') . '"'
            ]);
        }

        return response()->file($path, [
            'Content-Type' => $evidence->mime_type ?: 'application/octet-stream',
            'Content-Disposition' => 'inline;'
        ]);
    }

    /**
     * Update evidence file type.
     */
    public function update(Request $request, ControlEvidence $evidence)
    {
        $request->validate([
            'file_type' => 'nullable|string|max:255',
        ]);

        $evidence->update([
            'file_type' => $request->file_type,
        ]);

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
        if (Storage::disk('public')->exists($evidence->file_path)) {
            Storage::disk('public')->delete($evidence->file_path);
        }

        $evidence->delete();

        return response()->json([
            'success' => true,
            'message' => 'Evidence deleted successfully.'
        ]);
    }
}
