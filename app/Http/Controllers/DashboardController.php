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

        $availableYears = Control::query()
            ->whereNotNull('year')
            ->select('year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->map(fn ($year) => (int) $year)
            ->values();

        if ($availableYears->isEmpty()) {
            $availableYears = collect([
                now()->year,
                now()->year - 1,
                now()->year - 2,
                now()->year - 3,
            ]);
        }

        $year = (int) $request->input(
            'year',
            $availableYears->first()
        );

        if (!$availableYears->contains($year)) {
            $year = (int) $availableYears->first();
        }

        $quarter = strtolower(
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
        }

        $categories =
            ItCategory::query()
                ->orderBy('name')
                ->get();

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

            foreach (
                $categories
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
                'categories',
                'uptis',
                'year',
                'quarter',
                'availableYears',
                'applicationId',
                'selectedApplication'
            )
        );
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

        if (!$applicationId) {
            abort(
                400,
                'Application is required.'
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

        $allApplications =
            $allApplicationsQuery->get();

        $allCategories =
            ItCategory::query()
                ->orderBy('name')
                ->get();

        $allUptis =
            Upti::query()
                ->orderBy('name')
                ->get();

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
                'allUptis'
            )
        );
    }
}