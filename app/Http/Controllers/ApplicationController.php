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

        $applications = Application::with(['upti', 'itCategories', 'periods.itCategories'])->orderBy('created_at', 'asc')->get();
        $uptis = \App\Models\Upti::orderBy('created_at', 'asc')->get();
        $itRcmCount = ItCategory::count();

        // Build a map: app_id → distinct [year, quarter] combinations that exist
        $activeQuarters = $applications->mapWithKeys(function ($app) {
            $quarters = $app->periods
                ->map(fn($c) => $c->year . '-' . strtoupper($c->quarter))
                ->unique()
                ->values();
            return [$app->id => $quarters];
        });

        // Real-time IT Category count per application, broken down
        // PER PERIOD (since different Year/Quarter periods can have
        // different categories attached) — not one lump aggregate.
        $categoryCountsByPeriod = $applications->mapWithKeys(function ($app) {
            $rows = $app->periods
                ->sortByDesc(fn ($p) => $p->year . $p->quarter)
                ->map(function ($period) {
                    return [
                        'label' => $period->year . ' · ' . strtoupper($period->quarter),
                        'count' => $period->itCategories->count(),
                    ];
                })
                ->values();

            return [$app->id => $rows];
        });

        return view('applications.index', compact('applications', 'uptis', 'itRcmCount', 'activeQuarters', 'categoryCountsByPeriod'));
    }

    /**
     * Return year/quarter combinations that already have periods
     * for the given application (used by "Add Period" to disable
     * quarters that already exist for the chosen year).
     */
    public function existingPeriods(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $applicationId = $request->query('application_id');

        if (!$applicationId) {
            return response()->json(['success' => true, 'periods' => []]);
        }

        $periods = \App\Models\ApplicationPeriod::query()
            ->where('application_id', $applicationId)
            ->select('year', 'quarter')
            ->distinct()
            ->get()
            ->map(function ($row) {
                return [
                    'year' => (int) $row->year,
                    'quarter' => strtolower($row->quarter),
                ];
            });

        return response()->json([
            'success' => true,
            'periods' => $periods,
        ]);
    }

    /**
     * Return distinct year/quarter combinations that actually have periods
     * for the given application_id(s). Used by "Delete Period" to show only
     * periods that exist (and the years available).
     *
     * Query params:
     *   application_ids[]  – one or more application IDs (required)
     */
    public function existingPeriodsForDelete(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $applicationIds = $request->query('application_ids', []);

        if (empty($applicationIds)) {
            return response()->json(['success' => true, 'years' => [], 'periods' => []]);
        }

        $rows = \App\Models\ApplicationPeriod::query()
            ->whereIn('application_id', (array) $applicationIds)
            ->select('year', 'quarter')
            ->distinct()
            ->orderBy('year', 'desc')
            ->orderBy('quarter', 'asc')
            ->get();

        $years = $rows->pluck('year')->unique()->values()->map(fn($y) => (int) $y);
        $periods = $rows->map(fn($r) => [
            'year'    => (int) $r->year,
            'quarter' => strtolower($r->quarter),
        ]);

        return response()->json([
            'success' => true,
            'years'   => $years,
            'periods' => $periods,
        ]);
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
            'is_active' => 'nullable',
        ]);

        $isActive = $request->has('is_active')
            ? filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN)
            : true;

        $application = Application::where('name', $request->name)->first();

        if ($application) {
            // Already exists, reactivate if inactive
            if (!$application->is_active) {
                $application->update(['is_active' => true]);
                if ($request->filled('upti_id')) {
                    $application->update(['upti_id' => $request->upti_id]);
                }
                return response()->json(['success' => true, 'message' => 'Application reactivated.']);
            }
            return response()->json(['success' => false, 'message' => 'Application already exists.'], 400);
        }

        $application = Application::create([
            'name' => $request->name,
            'description' => $request->name,
            'is_active' => $isActive,
            'upti_id' => $request->upti_id,
        ]);

        $itCategories = ItCategory::all();
        foreach ($itCategories as $category) {
            $application->itCategories()->attach($category->id, [
                'completion_status' => 'not_complete',
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Add a period to an application (store in application_periods)
     */
    public function storePeriod(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $validated = $request->validate([
            'application_id' => 'required|exists:applications,id',
            'year' => 'required|integer',
            'quarter' => 'required|string|in:q1,q2,q3,q4',
        ]);

        $period = \App\Models\ApplicationPeriod::firstOrCreate([
            'application_id' => $validated['application_id'],
            'year' => $validated['year'],
            'quarter' => $validated['quarter'],
        ]);

        /*
         * Auto-copy IT Categories from the most recent existing
         * period of this same Application, so a new period isn't
         * born empty — Admin no longer manually curates categories
         * per period (that feature was removed).
         */
        if ($period->itCategories()->count() === 0) {
            $previousPeriod = \App\Models\ApplicationPeriod::where(
                'application_id',
                $validated['application_id']
            )
                ->where('id', '!=', $period->id)
                ->orderByDesc('year')
                ->orderByDesc('quarter')
                ->first();

            $categoryIds = $previousPeriod
                ? $previousPeriod->itCategories()->pluck('it_categories.id')
                : \App\Models\ItCategory::pluck('id');

            if ($categoryIds->isNotEmpty()) {
                $period->itCategories()->syncWithoutDetaching(
                    $categoryIds
                );
            }
        }

        return response()->json(['success' => true]);
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
            'is_active' => 'nullable',
        ]);

        $updateData = $request->only(['name', 'upti_id']);

        if ($request->has('is_active')) {
            $updateData['is_active'] = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN);
        }

        $application->update($updateData);

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
            'message' => "Application \"{$application->name}\" has been deactivated.",
        ]);
    }

    /**
     * Permanently delete an application.
     * Admin only.
     */
    public function forceDestroy(Application $application)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($application) {
            $controls = \App\Models\Control::where('application_id', $application->id)->get();
            foreach ($controls as $control) {
                foreach ($control->evidences as $evidence) {
                    if ($evidence->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($evidence->file_path)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($evidence->file_path);
                    }
                }
            }
            $application->delete();
        });

        return response()->json([
            'success' => true,
            'message' => "Application \"{$application->name}\" has been permanently deleted.",
        ]);
    }
}
