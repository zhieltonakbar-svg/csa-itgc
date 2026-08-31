<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Control;
use App\Models\ItCategory;
use App\Models\Upti;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $existingPeriods = \App\Models\ApplicationPeriod::select('application_id', 'year', 'quarter')->distinct()->get();

        $availableYears = $existingPeriods
            ->pluck('year')
            ->unique()
            ->sortDesc()
            ->values();

        if ($availableYears->isEmpty()) {
            $availableYears = collect([now()->year]);
        }

        $year = (int) $request->input(
            'year',
            $availableYears->first()
        );

        if (!$availableYears->contains($year)) {
            $year = (int) $availableYears->first();
        }

        $availableQuartersForYear = $existingPeriods
            ->where('year', $year)
            ->pluck('quarter')
            ->map(fn($q) => strtolower($q))
            ->unique()
            ->sort()
            ->values();

        if ($availableQuartersForYear->isEmpty()) {
            $availableQuartersForYear = collect(['q1', 'q2', 'q3', 'q4']);
        }

        $quarter = strtolower(
            (string) $request->input(
                'quarter',
                $availableQuartersForYear->first()
            )
        );

        if (!$availableQuartersForYear->contains($quarter)) {
            $quarter = $availableQuartersForYear->first();
        }

        /*
         * If the URL has no filter params at all (e.g. plain visit
         * to /dashboard, or arriving from the sidebar), fall back
         * to whatever was last searched — kept in session until the
         * user explicitly clears it (dashboard.clearFilter).
         */
        if (
            !$request->has('application_id') &&
            session()->has('itgc_filter.application_id')
        ) {
            $year = (int) session('itgc_filter.year', $year);
            $quarter = session('itgc_filter.quarter', $quarter);
        }

        $userUptiName =
            trim(
                (string) optional(
                    $user->upti
                )->name
            );

        $applicationsQuery =
            Application::query()
                ->where(
                    'is_active',
                    true
                )
                ->with('upti')
                ->orderBy('name');

        if (!$user->isAdmin()) {
            if ($user->upti_id) {
                $applicationsQuery->where(
                    function ($query) use (
                        $user
                    ) {
                        $query
                            ->where(
                                'upti_id',
                                $user->upti_id
                            )
                            ->orWhereExists(
                                function ($subQuery) use (
                                    $user
                                ) {
                                    $subQuery
                                        ->selectRaw('1')
                                        ->from('controls')
                                        ->whereColumn(
                                            'controls.application_id',
                                            'applications.id'
                                        )
                                        ->whereRaw(
                                            'LOWER(TRIM(controls.upti)) = LOWER(TRIM(?))',
                                            [
                                                optional(
                                                    $user->upti
                                                )->name
                                            ]
                                        );
                                }
                            );
                    }
                );
            } else {
                $applicationsQuery->whereRaw(
                    '1 = 0'
                );
            }
        }

        $applications =
            $applicationsQuery->get();

        $applicationId =
            $request->input(
                'application_id'
            );

        if (
            $applicationId === null &&
            session()->has('itgc_filter.application_id')
        ) {
            $applicationId =
                session('itgc_filter.application_id');
        }

        if (
            $applicationId !== null &&
            $applications->contains(
                'id',
                (int) $applicationId
            )
        ) {
            $applicationId =
                (int) $applicationId;
        } else {
            $applicationId = null;
        }

        $selectedApplication = null;

        if ($applicationId !== null) {
            $selectedApplication =
                $applications->firstWhere(
                    'id',
                    $applicationId
                );

            // Keep this search "sticky" until the user clears it.
            session([
                'itgc_filter' => [
                    'year' => $year,
                    'quarter' => $quarter,
                    'application_id' => $applicationId,
                ],
            ]);
        }

        $uptis =
            Upti::query()
                ->orderBy('name')
                ->get();

        foreach (
            $applications
            as $application
        ) {
            $dashboardCategories =
                collect();

            // Find the specific ApplicationPeriod
            $applicationPeriod = \App\Models\ApplicationPeriod::where('application_id', $application->id)
                ->where('year', $year)
                ->where('quarter', $quarter)
                ->first();

            $categoriesForPeriod = $applicationPeriod ? $applicationPeriod->itCategories : collect();

            foreach (
                $categoriesForPeriod
                as $category
            ) {
                $controlsQuery =
                    Control::query()
                        ->where(
                            'application_id',
                            $application->id
                        )
                        ->where(
                            'it_category_id',
                            $category->id
                        )
                        ->where(
                            'year',
                            $year
                        )
                        ->where(
                            'quarter',
                            $quarter
                        );

                if (
                    !$user->isAdmin()
                ) {
                    if (
                        $userUptiName !== ''
                    ) {
                        $controlsQuery
                            ->whereRaw(
                                'LOWER(TRIM(upti)) = LOWER(TRIM(?))',
                                [
                                    $userUptiName
                                ]
                            );
                    } else {
                        $controlsQuery
                            ->whereRaw(
                                '1 = 0'
                            );
                    }
                }

                $controls =
                    $controlsQuery
                        ->get();

                $total =
                    $controls->count();

                $completed =
                    $controls
                        ->where(
                            'status_control',
                            'completed'
                        )
                        ->count();

                $ongoing =
                    $controls
                        ->whereIn(
                            'status_control',
                            [
                                'drafting',
                                'ongoing_review',
                                'ongoing_approval',
                                'return_to_officer',
                                'return_to_reviewer',
                            ]
                        )
                        ->count();

                if ($total === 0) {
                    $status =
                        'not_complete';
                } elseif (
                    $completed === $total
                ) {
                    $status =
                        'complete';
                } elseif (
                    $completed > 0 ||
                    $ongoing > 0
                ) {
                    $status =
                        'partial';
                } else {
                    $status =
                        'not_complete';
                }

                $dashboardCategory =
                    clone $category;

                $dashboardCategory->setAttribute(
                    'dashboard_status',
                    $status
                );

                $dashboardCategory->setAttribute(
                    'completion_status',
                    $status
                );

                $dashboardCategory->setAttribute(
                    'dashboard_count',
                    $total
                );

                $dashboardCategory->setAttribute(
                    'total_controls',
                    $total
                );

                $dashboardCategory->setAttribute(
                    'completed_controls',
                    $completed
                );

                $dashboardCategory->setAttribute(
                    'ongoing_controls',
                    $ongoing
                );

                $dashboardCategories->push(
                    $dashboardCategory
                );
            }

            $application->setRelation(
                'dashboard_categories',
                $dashboardCategories
            );
        }

        return view(
            'dashboard',
            compact(
                'user',
                'applications',
                'uptis',
                'year',
                'quarter',
                'availableYears',
                'availableQuartersForYear',
                'existingPeriods',
                'applicationId',
                'selectedApplication'
            )
        );
    }

    /**
     * Clear the sticky Dashboard search (year/quarter/application),
     * so both Dashboard and the sidebar go back to requiring a
     * fresh search before IT Category / IT RCM can be opened.
     */
    public function clearFilter()
    {
        session()->forget('itgc_filter');

        return redirect()->route('dashboard');
    }

    public function getCategories(
        Request $request
    ) {
        $user =
            auth()->user();

        $applicationId =
            $request->input(
                'application_id'
            );

        $year =
            (int) $request->input(
                'year',
                now()->year
            );

        $quarter =
            strtolower(
                (string) $request->input(
                    'quarter',
                    'q1'
                )
            );

        if (!in_array(
            $quarter,
            ['q1', 'q2', 'q3', 'q4'],
            true
        )) {
            $quarter = 'q1';
        }

        if (!$applicationId) {
            return response()->json([
                'success' =>
                    false,
                'message' =>
                    'Application is required.',
                'categories' => [],
            ], 422);
        }

        $application =
            Application::query()
                ->where(
                    'is_active',
                    true
                )
                ->with('upti')
                ->find(
                    $applicationId
                );

        if (!$application) {
            return response()->json([
                'success' =>
                    false,
                'message' =>
                    'Application not found.',
                'categories' => [],
            ], 404);
        }

        if (!$user->isAdmin()) {
            $userUptiName =
                trim(
                    (string) optional(
                        $user->upti
                    )->name
                );

            $hasAccess =
                (
                    $user->upti_id &&
                    (int) $application->upti_id ===
                    (int) $user->upti_id
                ) ||
                (
                    $userUptiName !== '' &&
                    Control::query()
                        ->where(
                            'application_id',
                            $application->id
                        )
                        ->whereRaw(
                            'LOWER(TRIM(upti)) = LOWER(TRIM(?))',
                            [
                                $userUptiName
                            ]
                        )
                        ->exists()
                );

            if (!$hasAccess) {
                return response()->json([
                    'success' =>
                        false,
                    'message' =>
                        'You do not have access to this application.',
                    'categories' => [],
                ], 403);
            }
        }

        $categories =
            ItCategory::query()
                ->orderBy('name')
                ->get();

        $userUptiName =
            trim(
                (string) optional(
                    $user->upti
                )->name
            );

        $result =
            $categories->map(
                function (
                    $category
                ) use (
                    $applicationId,
                    $year,
                    $quarter,
                    $user,
                    $userUptiName
                ) {
                    $controlsQuery =
                        Control::query()
                            ->where(
                                'application_id',
                                $applicationId
                            )
                            ->where(
                                'it_category_id',
                                $category->id
                            )
                            ->where(
                                'year',
                                $year
                            )
                            ->where(
                                'quarter',
                                $quarter
                            );

                    if (
                        !$user->isAdmin()
                    ) {
                        if (
                            $userUptiName !== ''
                        ) {
                            $controlsQuery
                                ->whereRaw(
                                    'LOWER(TRIM(upti)) = LOWER(TRIM(?))',
                                    [
                                        $userUptiName
                                    ]
                                );
                        } else {
                            $controlsQuery
                                ->whereRaw(
                                    '1 = 0'
                                );
                        }
                    }

                    $controls =
                        $controlsQuery
                            ->get();

                    $total =
                        $controls->count();

                    $completed =
                        $controls
                            ->where(
                                'status_control',
                                'completed'
                            )
                            ->count();

                    $ongoing =
                        $controls
                            ->whereIn(
                                'status_control',
                                [
                                    'drafting',
                                    'ongoing_review',
                                    'ongoing_approval',
                                    'return_to_officer',
                                    'return_to_reviewer',
                                ]
                            )
                            ->count();

                    $status =
                        $total === 0
                            ? 'not_complete'
                            : (
                                $completed === $total
                                    ? 'complete'
                                    : (
                                        $completed > 0 ||
                                        $ongoing > 0
                                            ? 'partial'
                                            : 'not_complete'
                                    )
                            );

                    return [
                        'id' =>
                            $category->id,
                        'name' =>
                            $category->name,
                        'icon' =>
                            $category->icon,
                        'count' =>
                            $total,
                        'total_controls' =>
                            $total,
                        'completed_controls' =>
                            $completed,
                        'ongoing_controls' =>
                            $ongoing,
                        'status' =>
                            $status,
                        'completion_status' =>
                            $status,
                        'dashboard_status' =>
                            $status,
                    ];
                }
            );

        return response()->json([
            'success' =>
                true,
            'categories' =>
                $result->values(),
        ]);
    }

    public function showControls(
        Request $request,
        ItCategory $category
    ) {
        $user =
            auth()->user();

        $applicationId =
            $request->input(
                'application_id'
            );

        $year =
            (int) $request->input(
                'year',
                now()->year
            );

        $quarter =
            strtolower(
                (string) $request->input(
                    'quarter',
                    'q1'
                )
            );

        $source =
            $request->input(
                'source',
                'dashboard'
            );

        if (!in_array(
            $quarter,
            ['q1', 'q2', 'q3', 'q4'],
            true
        )) {
            $quarter = 'q1';
        }

        // Fall back to the sticky Dashboard search if this page was
        // reached without explicit filter params (e.g. an old
        // bookmark or a stale sidebar link).
        if (
            !$applicationId &&
            session()->has('itgc_filter.application_id')
        ) {
            $applicationId =
                session('itgc_filter.application_id');
            $year =
                (int) session('itgc_filter.year', $year);
            $quarter =
                session('itgc_filter.quarter', $quarter);
        }

        if (!$applicationId) {
            return redirect()
                ->route('dashboard')
                ->with(
                    'error',
                    'Please select a Year, Quarter, and Application on the Dashboard first.'
                );
        }

        $application =
            Application::query()
                ->where(
                    'is_active',
                    true
                )
                ->with('upti')
                ->findOrFail(
                    $applicationId
                );

        // Year/Quarter options are scoped to the CURRENTLY selected
        // Application — sourced from ApplicationPeriod (what Admin
        // actually opened), falling back to Controls for legacy data.
        // Computed here (before the Controls query below) so the
        // clamped $year/$quarter actually match what gets displayed.
        $periodsForThisApp = \App\Models\ApplicationPeriod::where(
            'application_id',
            $application->id
        )->get(['year', 'quarter']);

        if ($periodsForThisApp->isEmpty()) {
            $periodsForThisApp = Control::where(
                'application_id',
                $application->id
            )
                ->whereNotNull('year')
                ->whereNotNull('quarter')
                ->select('year', 'quarter')
                ->distinct()
                ->get();
        }

        $availableYears = $periodsForThisApp
            ->pluck('year')
            ->map(fn ($y) => (int) $y)
            ->unique()
            ->sortDesc()
            ->values();

        if ($availableYears->isEmpty()) {
            $availableYears = collect([now()->year]);
        }

        if (!$availableYears->contains($year)) {
            $year = (int) $availableYears->first();
        }

        $availableQuartersForYear = $periodsForThisApp
            ->where('year', $year)
            ->pluck('quarter')
            ->map(fn ($q) => strtolower($q))
            ->unique()
            ->sort()
            ->values();

        if ($availableQuartersForYear->isEmpty()) {
            $availableQuartersForYear = collect(['q1', 'q2', 'q3', 'q4']);
        }

        if (!$availableQuartersForYear->contains($quarter)) {
            $quarter = $availableQuartersForYear->first();
        }

        // Keep the sticky Dashboard search in sync when Admin
        // switches Application/Year/Quarter directly from this
        // page's Assessment Overview dropdowns.
        session([
            'itgc_filter' => [
                'year' => $year,
                'quarter' => $quarter,
                'application_id' => $application->id,
            ],
        ]);

        if (!$user->isAdmin()) {
            $userUptiName =
                trim(
                    (string) optional(
                        $user->upti
                    )->name
                );

            $hasAccess =
                (
                    $user->upti_id &&
                    (int) $application->upti_id ===
                    (int) $user->upti_id
                ) ||
                (
                    $userUptiName !== '' &&
                    Control::query()
                        ->where(
                            'application_id',
                            $application->id
                        )
                        ->whereRaw(
                            'LOWER(TRIM(upti)) = LOWER(TRIM(?))',
                            [
                                $userUptiName
                            ]
                        )
                        ->exists()
                );

            if (!$hasAccess) {
                abort(
                    403,
                    'You do not have access to this application.'
                );
            }
        }

        $controlsQuery =
            Control::query()
                ->with([
                    'application.upti',
                    'itCategory',
                    'evidences',
                ])
                ->where(
                    'application_id',
                    $application->id
                )
                ->where(
                    'it_category_id',
                    $category->id
                )
                ->where(
                    'year',
                    $year
                )
                ->where(
                    'quarter',
                    $quarter
                );

        if (!$user->isAdmin()) {
            $userUptiName =
                trim(
                    (string) optional(
                        $user->upti
                    )->name
                );

            if (
                $userUptiName !== ''
            ) {
                $controlsQuery
                    ->whereRaw(
                        'LOWER(TRIM(upti)) = LOWER(TRIM(?))',
                        [
                            $userUptiName
                        ]
                    );
            } else {
                $controlsQuery
                    ->whereRaw(
                        '1 = 0'
                    );
            }
        }

        $controls =
            $controlsQuery
                ->orderBy('upti')
                ->orderByRaw(
                    "
                    CASE
                        WHEN it_control_id REGEXP '^C-IT-[0-9]+$'
                        THEN CAST(
                            SUBSTRING_INDEX(
                                it_control_id,
                                '-',
                                -1
                            ) AS UNSIGNED
                        )
                        ELSE 999999
                    END
                    "
                )
                ->orderBy('id')
                ->get();

        $allApplicationsQuery =
            Application::query()
                ->where(
                    'is_active',
                    true
                )
                ->with('upti')
                ->orderBy('name');

        if (!$user->isAdmin()) {
            $allApplicationsQuery
                ->where(
                    function ($query) use (
                        $user
                    ) {
                        $query
                            ->where(
                                'upti_id',
                                $user->upti_id
                            )
                            ->orWhereExists(
                                function (
                                    $subQuery
                                ) use (
                                    $user
                                ) {
                                    $subQuery
                                        ->selectRaw(
                                            '1'
                                        )
                                        ->from(
                                            'controls'
                                        )
                                        ->whereColumn(
                                            'controls.application_id',
                                            'applications.id'
                                        )
                                        ->whereRaw(
                                            'LOWER(TRIM(controls.upti)) = LOWER(TRIM(?))',
                                            [
                                                optional(
                                                    $user->upti
                                                )->name
                                            ]
                                        );
                                }
                            );
                    }
                );
        }

        // Only offer applications that Admin has actually opened a
        // period for (or, as a fallback for legacy data created
        // before ApplicationPeriod existed, that already have
        // Controls) — never an app with nothing configured yet.
        $appIdsWithPeriods = \App\Models\ApplicationPeriod::query()
            ->distinct()
            ->pluck('application_id');

        $appIdsWithControls = \App\Models\Control::query()
            ->whereNotNull('application_id')
            ->distinct()
            ->pluck('application_id');

        $validAppIds = $appIdsWithPeriods
            ->merge($appIdsWithControls)
            ->unique();

        $allApplications =
            $allApplicationsQuery
                ->whereIn('id', $validAppIds)
                ->get();

        $allCategories =
            ItCategory::query()
                ->orderBy('name')
                ->get();

        $allUptis =
            Upti::query()
                ->orderBy('name')
                ->get();

        /*
         * IT RCM (Admin sidebar) and IT Category (Dashboard) share
         * this exact same controller method and Blade view, so the
         * "not yet completed" rows are always guaranteed 100%
         * identical between the two. Only rows already Completed
         * render differently, and only under the rcm.controls route.
         */
        $isRcmView =
            optional($request->route())->getName() === 'rcm.controls';

        return view(
            'it-category.show',
            compact(
                'category',
                'application',
                'controls',
                'year',
                'quarter',
                'source',
                'allApplications',
                'allCategories',
                'allUptis',
                'isRcmView',
                'availableYears',
                'availableQuartersForYear'
            )
        );
    }

    public function addCategoryToPeriod(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $validated = $request->validate([
            'application_id' => 'required|exists:applications,id',
            'year' => 'required|integer',
            'quarter' => 'required|string',
            'category_name' => 'required|string|max:255',
            'category_description' => 'nullable|string',
        ]);

        $category = \App\Models\ItCategory::where('name', $validated['category_name'])->first();
        
        if ($category) {
            if (!empty($validated['category_description'])) {
                $category->description = $validated['category_description'];
                $category->save();
            }
        } else {
            $category = \App\Models\ItCategory::create([
                'name' => $validated['category_name'],
                'icon' => 'bi-shield-check',
                'description' => $validated['category_description'] ?: 'Assess and evaluate controls related to ' . strtolower($validated['category_name']) . '.'
            ]);
        }

        $period = \App\Models\ApplicationPeriod::firstOrCreate([
            'application_id' => $validated['application_id'],
            'year' => $validated['year'],
            'quarter' => $validated['quarter'],
        ]);

        $period->itCategories()->syncWithoutDetaching([$category->id]);

        // Also add to global application_it_category if not exists
        $application = \App\Models\Application::find($validated['application_id']);
        $application->itCategories()->syncWithoutDetaching([
            $category->id => ['completion_status' => 'not_complete']
        ]);

        return response()->json(['success' => true]);
    }

    public function removeCategoryFromPeriod(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }
        
        $validated = $request->validate([
            'application_id' => 'required|exists:applications,id',
            'year' => 'required|integer',
            'quarter' => 'required|string',
            'it_category_ids' => 'required|array',
            'it_category_ids.*' => 'exists:it_categories,id',
        ]);

        $period = \App\Models\ApplicationPeriod::where('application_id', $validated['application_id'])
            ->where('year', $validated['year'])
            ->where('quarter', $validated['quarter'])
            ->first();

        if ($period) {
            $period->itCategories()->detach($validated['it_category_ids']);
        }

        // Delete all controls for these categories in this period
        $controls = \App\Models\Control::where('application_id', $validated['application_id'])
            ->where('year', $validated['year'])
            ->where('quarter', $validated['quarter'])
            ->whereIn('it_category_id', $validated['it_category_ids'])
            ->get();

        foreach ($controls as $control) {
            foreach ($control->evidences as $evidence) {
                if ($evidence->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($evidence->file_path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($evidence->file_path);
                }
            }
            $control->delete();
        }

        return response()->json(['success' => true]);
    }
}