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
        $applications = Application::where('is_active', true)->orderBy('name')->get();

        return view('dashboard.index', compact('applications'));
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
        $request->validate(['application_id' => 'required|exists:applications,id']);

        $application = Application::with('itCategories')->findOrFail($request->application_id);

        $categories = $application->itCategories->map(function ($cat) {
            return [
                'id'                => $cat->id,
                'name'              => $cat->name,
                'icon'              => $cat->icon,
                'description'       => $cat->description,
                'completion_status' => $cat->pivot->completion_status,
            ];
        });

        return response()->json([
            'application' => $application->name,
            'categories'  => $categories,
        ]);
    }

    /**
     * Show the IT Category detail page (controls table template).
     *
     * GET /it-category/{application}/{category}?year=2026&quarter=q1
     */
    public function showCategory(Application $application, ItCategory $category, Request $request)
    {
        $year    = $request->query('year', date('Y'));
        $quarter = $request->query('quarter', 'q1');

        // Verify this category actually belongs to the application
        $pivot = $application->itCategories()->where('it_category_id', $category->id)->first();
        $completionStatus = $pivot ? $pivot->pivot->completion_status : 'not_complete';

        // Load controls from DB and eager-load evidence
        $controls = Control::with('evidences')
            ->where('application_id', $application->id)
            ->where('it_category_id', $category->id)
            ->where('year', $year)
            ->where('quarter', $quarter)
            ->orderBy('it_control_id')
            ->get();

        return view('it-category.show', compact(
            'application',
            'category',
            'year',
            'quarter',
            'completionStatus',
            'controls'
        ));
    }
}
