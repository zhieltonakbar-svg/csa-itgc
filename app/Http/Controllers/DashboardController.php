<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Control;
use App\Models\ItCategory;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the dashboard with the application dropdown populated from the DB.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user && !$user->isAdmin() && !$user->upti_id) {
            $uptis = collect();
            $applications = collect();
        } else {
            $uptis = \App\Models\Upti::orderBy('name')->get();
            $applications = \App\Models\Application::where('is_active', true);
            if ($user && !$user->isAdmin()) {
                $applications->where('upti_id', $user->upti_id);
            }
            $applications = $applications->with('upti')->orderBy('name')->get();
        }

        return view('dashboard.index', compact('uptis', 'applications'));
    }

    /**
     * JSON endpoint: return IT categories for a given application.
     *
     * GET /dashboard/categories?application_id=1
     *
     * Response shape:
     * {
     *   "application": "SAP S/4HANA",
     *   "categories": [
     *     { "id": 1, "name": "Access to Programs & Data", "icon": "bi-shield-lock",
     *       "description": "...", "completion_status": "partial" },
     *     ...
     *   ]
     * }
     */
    public function getCategories(Request $request)
    {
        $request->validate([
            'application_id' => 'required|exists:applications,id',
            'year'           => 'nullable|string',
            'quarter'        => 'nullable|string',
        ]);

        $appId   = $request->application_id;
        $year    = $request->query('year', date('Y'));
        $quarter = $request->query('quarter', 'q1');
        
        $user = auth()->user();
        
        if ($user && !$user->isAdmin() && !$user->upti_id) {
            return response()->json(['application' => '', 'categories' => []]);
        }
        
        $query = Application::with('itCategories')->where('id', $appId);
        if ($user && !$user->isAdmin()) {
            $query->where('upti_id', $user->upti_id);
        }
        $applications = $query->get();
        if ($applications->isEmpty()) {
            return response()->json(['application' => '', 'categories' => []]);
        }
        $applicationName = $applications->first()->name;
        $appIds = $applications->pluck('id');
        $user = auth()->user();

        $uniqueCategories = collect();
        foreach ($applications as $app) {
            foreach ($app->itCategories as $cat) {
                if (!$uniqueCategories->has($cat->id)) {
                    $uniqueCategories->put($cat->id, $cat);
                }
            }
        }

        $categories = $uniqueCategories->values()->map(function ($cat) use ($appIds, $year, $quarter, $user) {
            $query = Control::whereIn('application_id', $appIds)
                ->where('it_category_id', $cat->id)
                ->where('year', $year)
                ->where('quarter', $quarter);
                
            if ($user && !$user->isAdmin() && $user->upti) {
                $query->where(function($q) use ($user) {
                    $q->where('upti', 'LIKE', '%' . $user->upti->name . '%')
                      ->orWhere('upti', 'Multi UPTI');
                });
            }

            $controls = $query->get();

            $statusInfo = Control::calculateStatus($controls);

            $totalCount = $controls->count();
            $completedCount = $controls->where('status_control', 'completed')->count();
            $notCompletedCount = $totalCount - $completedCount;

            return [
                'id'                  => $cat->id,
                'name'                => $cat->name,
                'icon'                => $cat->icon,
                'description'         => $cat->description,
                'completion_status'   => $statusInfo['pivot_status'],
                'completed_count'     => $completedCount,
                'not_completed_count' => $notCompletedCount,
                'total_count'         => $totalCount,
            ];
        });

        return response()->json([
            'application' => $applicationName,
            'categories'  => $categories,
        ]);
    }

    /**
     * Show the IT Category detail page (controls table template) for an UPTI/Application.
     *
     * GET /it-category/{category}/controls?upti_id=1&application_id=1&year=2026&quarter=q1
     */
    /**
     * Dashboard IT Category: read-only for Admin.
     * GET /it-category/{category}/controls?application_id=1&year=2026&quarter=q1
     */
    public function showControls(ItCategory $category, Request $request)
    {
        $year    = $request->query('year', date('Y'));
        $quarter = $request->query('quarter', 'q1');
        $appId   = $request->query('application_id');

        if (!$appId) {
            return redirect()->route('dashboard')->with('error', 'Application is required.');
        }

        $application = Application::with('upti')->findOrFail($appId);
        $upti = $application->upti;

        $user = auth()->user();
        $appIds = [$appId];

        $completionStatus = 'partial_completed';

        $query = Control::with(['evidences', 'application.upti'])
            ->whereIn('application_id', $appIds)
            ->where('it_category_id', $category->id)
            ->where('year', $year)
            ->where('quarter', $quarter);

        if ($user && !$user->isAdmin() && $user->upti) {
            $query->where(function ($q) use ($user) {
                $q->where('upti', 'LIKE', '%' . $user->upti->name . '%')
                  ->orWhere('upti', 'Multi UPTI');
            });
        }

        $controls = $query->orderBy('application_id')
            ->orderBy('it_control_id')
            ->get();

        $allUptis = \App\Models\Upti::orderBy('name')->get();

        // Dashboard entry: always view-only for Admin
        $source = 'dashboard';

        return view('it-category.show', compact(
            'upti',
            'application',
            'category',
            'year',
            'quarter',
            'completionStatus',
            'controls',
            'allUptis',
            'source'
        ));
    }

    /**
     * IT RCM Management: full admin capabilities.
     * GET /rcm/{category}/controls
     * Shows ALL controls for this category across all applications & UPTIs.
     */
    public function showRcmControls(ItCategory $category, Request $request)
    {
        // Only admin can access this route
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $completionStatus = 'partial_completed';

        // Load ALL controls for this category — no application/upti/period filter
        $controls = Control::with(['evidences', 'application.upti'])
            ->where('it_category_id', $category->id)
            ->orderBy('year', 'desc')
            ->orderBy('quarter', 'desc')
            ->orderBy('application_id')
            ->orderBy('it_control_id')
            ->get();

        $allUptis        = \App\Models\Upti::orderBy('name')->get();
        $allApplications = \App\Models\Application::where('is_active', true)
            ->with('upti')
            ->orderBy('name')
            ->get();

        // Dummy values — not used in IT RCM view (no period filter)
        $year        = null;
        $quarter     = null;
        $application = null;
        $upti        = null;

        // IT RCM: full capabilities
        $source = 'rcm';

        return view('it-rcm.show', compact(
            'upti',
            'application',
            'category',
            'year',
            'quarter',
            'completionStatus',
            'controls',
            'allUptis',
            'allApplications',
            'source'
        ));
    }

    /**
     * IT RCM landing page — shows all IT categories for admin.
     * GET /rcm
     */
    public function rcmIndex()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $categories = \App\Models\ItCategory::orderBy('name')->get();

        return view('it-rcm.index', compact('categories'));
    }
}
