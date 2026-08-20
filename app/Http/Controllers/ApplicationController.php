<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ItCategory;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the applications for Admin management.
     */
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $applications = Application::with(['upti', 'itCategories'])->orderBy('created_at', 'asc')->get();
        $uptis = \App\Models\Upti::orderBy('created_at', 'asc')->get();
        $itRcmCount = ItCategory::count();
        return view('applications.index', compact('applications', 'uptis', 'itRcmCount'));
    }

    /**
     * Store a newly created application and attach all existing IT categories.
     * Admin only.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $application = Application::where('name', $request->name)->first();

        if ($application) {
            // Already exists, reactivate if inactive
            if (!$application->is_active) {
                $application->update(['is_active' => true]);
            }
            $isNew = false;
        } else {
            $application = Application::create([
                'name'        => $request->name,
                'is_active'   => true,
            ]);

            // Auto-attach all existing IT categories (empty data, fresh start)
            $allCategories = ItCategory::all();
            $syncData = [];
            foreach ($allCategories as $cat) {
                $syncData[$cat->id] = ['completion_status' => 'not_complete'];
            }
            $application->itCategories()->sync($syncData);
            $isNew = true;
        }

        return response()->json([
            'success' => true,
            'message' => 'Application stored successfully.',
            'application' => $application
        ]);
    }

    /**
     * Update an application (e.g., name, mapping to UPTI).
     */
    public function update(Request $request, Application $application)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'upti_id' => 'nullable|exists:uptis,id',
        ]);

        $application->update($request->only(['name', 'upti_id']));

        return response()->json([
            'success' => true,
            'message' => 'Application updated successfully.',
            'application' => $application->load('upti')
        ]);
    }

    /**
     * Soft-deactivate (hide) an application.
     * Admin only.
     */
    public function destroy(Application $application)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $application->update(['is_active' => false]);

        return response()->json([
            'success' => true,
            'message' => "Application \"{$application->name}\" has been removed.",
        ]);
    }
}
