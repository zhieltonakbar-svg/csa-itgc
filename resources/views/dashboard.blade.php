@extends('layouts.app')

@section('title', 'Dashboard — CSA - ITGC')

@php
    $roleLabels = [
        'admin' => 'ADMIN',
        'creator' => 'OFFICER',
        'officer' => 'OFFICER',
        'reviewer' => 'MANAGER',
        'approver' => 'SENIOR MANAGER',
    ];

    $roleLabel = $roleLabels[$user->role ?? '']
        ?? strtoupper($user->role ?? 'USER');

    $statusLabels = [
        'complete' => 'Complete',
        'partial' => 'Partial Complete',
        'not_complete' => 'Not Complete',
    ];

    $statusClasses = [
        'complete' => 'dashboard-status-complete',
        'partial' => 'dashboard-status-partial',
        'not_complete' => 'dashboard-status-not-complete',
    ];

    $userUptiName = optional($user->upti)->name;

    $applicationUptiName = optional(
        optional($selectedApplication)->upti
    )->name;
@endphp

@push('styles')
<style>
.dashboard-page {
    width:100%;
}

.dashboard-welcome {
    position:relative;
    overflow:hidden;
    min-height:255px;
    background:linear-gradient(135deg,#198754 0%,#157347 100%);
    border-radius:16px;
    padding:42px 36px;
    margin-bottom:24px;
    box-shadow:0 12px 30px rgba(25,135,84,.18);
    display:flex;
    align-items:center;
}

.dashboard-welcome::before,
.dashboard-welcome::after {
    content:'';
    position:absolute;
    border-radius:50%;
    background:rgba(255,255,255,.055);
    pointer-events:none;
}

.dashboard-welcome::before {
    width:290px;
    height:290px;
    right:-80px;
    top:-150px;
}

.dashboard-welcome::after {
    width:170px;
    height:170px;
    right:90px;
    bottom:-110px;
}

.dashboard-welcome-content {
    position:relative;
    z-index:2;
}

.dashboard-welcome-eyebrow {
    color:rgba(255,255,255,.84);
    font-size:13px;
    font-weight:800;
    letter-spacing:.8px;
    margin-bottom:8px;
}

.dashboard-welcome-name-row {
    display:flex;
    align-items:center;
    flex-wrap:wrap;
    gap:12px;
}

.dashboard-welcome-name {
    margin:0;
    color:#fff;
    font-size:31px;
    line-height:1.15;
    font-weight:800;
}

.dashboard-role {
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:6px 11px;
    background:#fff;
    color:#198754;
    border-radius:7px;
    font-size:11px;
    font-weight:800;
}

.dashboard-welcome-org {
    margin-top:11px;
    color:rgba(255,255,255,.94);
    font-size:15px;
    font-weight:600;
}

.dashboard-welcome-upti {
    margin-top:16px;
    display:inline-flex;
    align-items:center;
    gap:7px;
    padding:7px 12px;
    border-radius:20px;
    background:rgba(255,255,255,.12);
    border:1px solid rgba(255,255,255,.2);
    color:#fff;
    font-size:12px;
    font-weight:700;
}

.dashboard-filter-card {
    background:#fff;
    border:1px solid #dbe3e8;
    border-radius:16px;
    padding:27px 30px 24px;
    margin-bottom:30px;
    box-shadow:0 8px 24px rgba(15,23,42,.05);
}

.dashboard-filter-header {
    display:flex;
    align-items:center;
    gap:14px;
    margin-bottom:22px;
}

.dashboard-filter-icon {
    width:38px;
    height:38px;
    flex-shrink:0;
    border-radius:9px;
    background:#eaf6ef;
    color:#198754;
    display:flex;
    align-items:center;
    justify-content:center;
}

.dashboard-filter-header h3 {
    margin:0;
    color:#152238;
    font-size:17px;
    font-weight:800;
}

.dashboard-filter-header p {
    margin:3px 0 0;
    color:#64748b;
    font-size:13px;
}

.dashboard-filter-form {
    display:grid;
    grid-template-columns:1fr 1fr 1.7fr auto;
    gap:12px;
    align-items:end;
}

.dashboard-field label {
    display:block;
    margin-bottom:7px;
    color:#64748b;
    font-size:11px;
    font-weight:800;
    letter-spacing:.55px;
}

.dashboard-field select {
    width:100%;
    height:46px;
    padding:0 14px;
    border:1px solid #dbe3e8;
    border-radius:9px;
    outline:none;
    background:#f8fafb;
    color:#152238;
    font-size:14px;
    cursor:pointer;
}

.dashboard-field select:focus {
    border-color:#198754;
    background:#fff;
    box-shadow:0 0 0 3px rgba(25,135,84,.08);
}

.dashboard-search-btn {
    height:46px;
    padding:0 21px;
    border:0;
    border-radius:9px;
    background:linear-gradient(135deg,#198754,#157347);
    color:#fff;
    font-size:13px;
    font-weight:800;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:7px;
    cursor:pointer;
    white-space:nowrap;
}

.dashboard-selected-filter {
    display:inline-flex;
    align-items:center;
    gap:7px;
    margin-top:17px;
    padding:8px 13px;
    border-radius:20px;
    background:#eaf6ef;
    border:1px solid rgba(25,135,84,.18);
    color:#198754;
    font-size:12px;
    font-weight:700;
}

.dashboard-section-title {
    display:flex;
    align-items:center;
    gap:9px;
    margin-bottom:12px;
    color:#94a3b8;
    font-size:12px;
    font-weight:800;
    letter-spacing:.65px;
}

.dashboard-section-title i {
    color:#198754;
}

.dashboard-section-line {
    flex:1;
    height:1px;
    background:#dbe3e8;
}

.dashboard-application-box {
    width:100%;
    background:#fff;
    border:1px solid #dbe3e8;
    border-radius:16px;
    padding:20px;
    margin-bottom:30px;
    box-shadow:0 8px 24px rgba(15,23,42,.05);
}

.dashboard-application-head {
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap:18px;
    margin-bottom:18px;
}

.dashboard-application-title {
    margin:0;
    color:#152238;
    font-size:22px;
    font-weight:800;
}

.dashboard-application-subtitle {
    margin-top:7px;
}

.dashboard-upti-badge,
.dashboard-admin-all-badge {
    display:inline-flex;
    align-items:center;
    gap:6px;
    padding:6px 11px;
    border-radius:20px;
    font-size:11.5px;
    font-weight:800;
}

.dashboard-upti-badge {
    background:#eaf6ef;
    border:1px solid #cfe5d7;
    color:#198754;
}

.dashboard-admin-all-badge {
    background:#eef3f7;
    border:1px solid #dbe3e8;
    color:#64748b;
}

.dashboard-admin-all-badge i {
    color:#198754;
}

.dashboard-category-grid {
    display:grid;
    grid-template-columns:repeat(4,minmax(0,1fr));
    gap:16px;
}

.dashboard-category-card {
    position:relative;
    min-height:225px;
    display:flex;
    flex-direction:column;
    padding:20px;
    border:1px solid #dce5ea;
    border-radius:13px;
    background:#fff;
    text-decoration:none;
    transition:.18s;
}

.dashboard-category-card:hover {
    border-color:#b8d8c3;
    background:#fbfefd;
    transform:translateY(-2px);
    box-shadow:0 8px 20px rgba(25,135,84,.08);
}

.dashboard-category-card-top {
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap:12px;
}

.dashboard-category-icon {
    width:43px;
    height:43px;
    flex-shrink:0;
    border-radius:10px;
    background:#eaf6ef;
    color:#198754;
    display:flex;
    align-items:center;
    justify-content:center;
}

.dashboard-category-count {
    display:inline-flex;
    align-items:center;
    gap:6px;
    padding:6px 9px;
    border-radius:20px;
    background:#f1f5f9;
    border:1px solid #dde6eb;
    color:#64748b;
    font-size:11px;
    font-weight:800;
    white-space:nowrap;
}

.dashboard-category-name {
    margin-top:17px;
    color:#152238;
    font-size:16px;
    font-weight:800;
    line-height:1.3;
}

.dashboard-category-description {
    margin-top:8px;
    color:#64748b;
    font-size:12.5px;
    line-height:1.6;
}

.dashboard-category-bottom {
    margin-top:auto;
    padding-top:17px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
}

.dashboard-category-status {
    display:inline-flex;
    align-items:center;
    gap:6px;
    padding:6px 10px;
    border-radius:20px;
    font-size:11px;
    font-weight:800;
    white-space:nowrap;
}

.dashboard-status-dot {
    width:6px;
    height:6px;
    border-radius:50%;
}

.dashboard-status-complete {
    background:#eaf6ef;
    border:1px solid #cce5d4;
    color:#15803d;
}

.dashboard-status-complete .dashboard-status-dot {
    background:#198754;
}

.dashboard-status-partial {
    background:#fff7ed;
    border:1px solid #fed7aa;
    color:#b45309;
}

.dashboard-status-partial .dashboard-status-dot {
    background:#d97706;
}

.dashboard-status-not-complete {
    background:#f1f5f9;
    border:1px solid #dbe3e8;
    color:#64748b;
}

.dashboard-status-not-complete .dashboard-status-dot {
    background:#94a3b8;
}

.dashboard-category-arrow {
    color:#198754;
}

.dashboard-empty {
    width:100%;
    background:#fff;
    border:1px solid #dbe3e8;
    border-radius:16px;
    padding:70px 30px;
    text-align:center;
}

.dashboard-empty-icon {
    width:78px;
    height:78px;
    margin:0 auto 16px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:50%;
    background:#eaf6ef;
    color:#198754;
}

.dashboard-empty-icon i {
    font-size:32px;
}

.dashboard-empty h3 {
    margin:0 0 7px;
    color:#152238;
    font-size:18px;
    font-weight:800;
}

.dashboard-empty p {
    margin:0;
    color:#64748b;
    font-size:13px;
}

@media (max-width:1100px) {
    .dashboard-filter-form {
        grid-template-columns:1fr 1fr;
    }

    .dashboard-field-application {
        grid-column:span 2;
    }

    .dashboard-search-btn {
        width:100%;
    }

    .dashboard-category-grid {
        grid-template-columns:repeat(2,minmax(0,1fr));
    }
}

@media (max-width:700px) {
    .dashboard-welcome {
        min-height:220px;
        padding:30px 24px;
    }

    .dashboard-welcome-name {
        font-size:25px;
    }

    .dashboard-filter-card {
        padding:22px 18px;
    }

    .dashboard-filter-form {
        grid-template-columns:1fr;
    }

    .dashboard-field-application {
        grid-column:span 1;
    }

    .dashboard-category-grid {
        grid-template-columns:1fr;
    }
}
</style>
@endpush

@section('content')

<div class="dashboard-page">

    <div class="dashboard-welcome">

        <div class="dashboard-welcome-content">

            <div class="dashboard-welcome-eyebrow">
                WELCOME BACK
            </div>

            <div class="dashboard-welcome-name-row">

                <h1 class="dashboard-welcome-name">
                    {{ $user->name }}
                </h1>

                <span class="dashboard-role">
                    {{ $roleLabel }}
                </span>

            </div>

            <div class="dashboard-welcome-org">
                CSA - ITGC · PT Telkom Infrastruktur Indonesia
            </div>

            @if($user->isAdmin())

                <div class="dashboard-welcome-upti">
                    <i class="bi bi-diagram-3-fill"></i>
                    All UPTI Access
                </div>

            @elseif($userUptiName)

                <div class="dashboard-welcome-upti">
                    <i class="bi bi-diagram-3-fill"></i>
                    UPTI: {{ $userUptiName }}
                </div>

            @endif

        </div>

    </div>

    <div class="dashboard-filter-card">

        <div class="dashboard-filter-header">

            <div class="dashboard-filter-icon">
                <i class="bi bi-funnel-fill"></i>
            </div>

            <div>
                <h3>Assessment Filter</h3>
                <p>
                    Select the assessment parameters to explore IT Categories.
                </p>
            </div>

        </div>

        <form
            method="GET"
            action="{{ route('dashboard') }}"
            class="dashboard-filter-form"
        >

            <div class="dashboard-field">

                <label for="dashboard-year">
                    YEAR
                </label>

                <select
                    id="dashboard-year"
                    name="year"
                >

                    @foreach($availableYears as $availableYear)

                        <option
                            value="{{ $availableYear }}"
                            {{ (int) $year === (int) $availableYear ? 'selected' : '' }}
                        >
                            {{ $availableYear }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div class="dashboard-field">

                <label for="dashboard-quarter">
                    QUARTER
                </label>

                <select
                    id="dashboard-quarter"
                    name="quarter"
                >

                    @foreach($availableQuartersForYear as $q)

                        <option
                            value="{{ $q }}"
                            {{ $quarter === $q ? 'selected' : '' }}
                        >
                            {{ strtoupper($q) }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div class="dashboard-field dashboard-field-application">

                <label for="dashboard-application">
                    APPLICATION
                </label>

                <select
                    id="dashboard-application"
                    name="application_id"
                >

                    <option value="">
                        Select Application
                    </option>

                    @foreach($applications as $application)

                        <option
                            value="{{ $application->id }}"
                            {{ (int) $applicationId === (int) $application->id ? 'selected' : '' }}
                        >
                            {{ $application->name }}
                        </option>

                    @endforeach

                </select>

            </div>

            <button
                type="submit"
                class="dashboard-search-btn"
            >
                <i class="bi bi-search"></i>
                Search
            </button>

        </form>

        @if($selectedApplication)

            <div class="dashboard-selected-filter">

                <i class="bi bi-check-circle-fill"></i>

                {{ $year }}
                ·
                {{ strtoupper($quarter) }}
                ·
                {{ $selectedApplication->name }}

                <a
                    href="{{ route('dashboard.clearFilter') }}"
                    title="Clear search"
                    style="
                        margin-left:10px;
                        color:inherit;
                        opacity:.7;
                        text-decoration:none;
                    "
                >
                    <i class="bi bi-x-circle"></i>
                    Change
                </a>

            </div>

        @endif

    </div>

    <div class="dashboard-section-title">

        <i class="bi bi-grid-fill"></i>

        IT CATEGORIES

        <span class="dashboard-section-line"></span>

    </div>

    @if($selectedApplication)

        <div class="dashboard-application-box">

            <div class="dashboard-application-head" style="display:flex; justify-content:space-between; align-items:flex-start;">

                <div>

                    <h2 class="dashboard-application-title">
                        {{ $selectedApplication->name }}
                    </h2>

                    <div class="dashboard-application-subtitle">

                        @if($user->isAdmin())

                            <span class="dashboard-admin-all-badge">
                                <i class="bi bi-globe2"></i>
                                All UPTI Data
                            </span>

                        @else

                            <span class="dashboard-upti-badge">
                                <i class="bi bi-diagram-3-fill"></i>
                                {{ $applicationUptiName ?: $userUptiName ?: 'UPTI' }}
                            </span>

                        @endif

                    </div>

                </div>

            </div>

            <div class="dashboard-category-grid">

                @forelse(
                    $selectedApplication->dashboard_categories
                    as $category
                )

                    @php
                        $categoryStatus =
                            $category->dashboard_status
                            ?? 'not_complete';

                        $categoryCount =
                            (int) (
                                $category->total_controls
                                ?? 0
                            );

                        $statusLabel =
                            $statusLabels[$categoryStatus]
                            ?? 'Not Complete';

                        $statusClass =
                            $statusClasses[$categoryStatus]
                            ?? 'dashboard-status-not-complete';

                        $categoryDescription =
                            $category->description
                            ?? 'Assess and evaluate controls related to '
                            . strtolower($category->name)
                            . '.';
                    @endphp

                    <a
                        href="{{ route('dashboard.controls', [
                            'category' => $category->id,
                            'application_id' => $selectedApplication->id,
                            'year' => $year,
                            'quarter' => $quarter,
                            'source' => 'dashboard'
                        ]) }}"
                        class="dashboard-category-card"
                    >

                        <div class="dashboard-category-card-top">

                            <div class="dashboard-category-icon">
                                <i class="bi {{ $category->icon }}"></i>
                            </div>

                            <span class="dashboard-category-count">
                                <i class="bi bi-stack"></i>
                                {{ $categoryCount }} Controls
                            </span>

                        </div>

                        <div class="dashboard-category-name">
                            {{ $category->name }}
                        </div>

                        <div class="dashboard-category-description">
                            {{ $categoryDescription }}
                        </div>

                        <div class="dashboard-category-bottom">

                            <span
                                class="dashboard-category-status {{ $statusClass }}"
                            >
                                <span class="dashboard-status-dot"></span>
                                {{ $statusLabel }}
                            </span>

                            <i class="bi bi-chevron-right dashboard-category-arrow"></i>

                        </div>

                    </a>

                @empty

                    <div
                        class="dashboard-empty"
                        style="grid-column:1/-1;"
                    >

                        <div class="dashboard-empty-icon">
                            <i class="bi bi-grid"></i>
                        </div>

                        <h3>
                            No IT Category Available
                        </h3>

                        <p>
                            No IT Category has been configured yet.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    @else

        <div class="dashboard-empty">

            <div class="dashboard-empty-icon">
                <i class="bi bi-search"></i>
            </div>

            <h3>
                Select an Application
            </h3>

            <p>
                Select an application and click Search to view IT Categories.
            </p>

        </div>

    @endif

</div>

@endsection

@push('scripts')
<script>
    var currentAppId = '{{ $applicationId ?? "" }}';
    var currentYear = '{{ $year ?? "" }}';
    var currentQuarter = '{{ $quarter ?? "" }}';

    document.addEventListener('DOMContentLoaded', function () {
        var existingPeriods = @json($existingPeriods);
        var appSelect = document.getElementById('dashboard-application');
        var yearSelect = document.getElementById('dashboard-year');
        var quarterSelect = document.getElementById('dashboard-quarter');
        
        var initialYear = '{{ $year }}';
        var initialQuarter = '{{ $quarter }}';

        function updateDropdowns() {
            var selectedApp = appSelect.value ? parseInt(appSelect.value) : null;
            var selectedYear = yearSelect.value ? parseInt(yearSelect.value) : parseInt(initialYear);
            var selectedQuarter = quarterSelect.value || initialQuarter;

            // 1. Filter Years based on Application
            var availableYears = [];
            existingPeriods.forEach(function (period) {
                if (!selectedApp || period.application_id === selectedApp) {
                    if (!availableYears.includes(period.year)) {
                        availableYears.push(period.year);
                    }
                }
            });

            availableYears.sort(function(a, b){return b-a});
            if (availableYears.length === 0) {
                availableYears = [new Date().getFullYear()];
            }

            // Rebuild Year options
            yearSelect.innerHTML = '';
            var yearStillExists = false;
            availableYears.forEach(function (y) {
                var option = document.createElement('option');
                option.value = y;
                option.text = y;
                if (y === selectedYear) {
                    option.selected = true;
                    yearStillExists = true;
                }
                yearSelect.appendChild(option);
            });

            if (!yearStillExists && yearSelect.options.length > 0) {
                yearSelect.options[0].selected = true;
                selectedYear = parseInt(yearSelect.value);
            }

            // 2. Filter Quarters based on Application AND Year
            var availableQuarters = [];
            existingPeriods.forEach(function (period) {
                var appMatch = !selectedApp || period.application_id === selectedApp;
                var yearMatch = period.year === selectedYear;
                if (appMatch && yearMatch) {
                    var q = period.quarter.toLowerCase();
                    if (!availableQuarters.includes(q)) {
                        availableQuarters.push(q);
                    }
                }
            });

            availableQuarters.sort();
            if (availableQuarters.length === 0) {
                availableQuarters = ['q1', 'q2', 'q3', 'q4'];
            }

            // Rebuild Quarter options
            quarterSelect.innerHTML = '';
            var quarterStillExists = false;
            availableQuarters.forEach(function (q) {
                var option = document.createElement('option');
                option.value = q;
                option.text = q.toUpperCase();
                if (q === selectedQuarter) {
                    option.selected = true;
                    quarterStillExists = true;
                }
                quarterSelect.appendChild(option);
            });

            if (!quarterStillExists && quarterSelect.options.length > 0) {
                quarterSelect.options[0].selected = true;
            }
        }

        appSelect.addEventListener('change', updateDropdowns);
        yearSelect.addEventListener('change', updateDropdowns);
        
        // Run once on load to ensure correctness based on potentially auto-selected application
        // But only if there is an application selected, so it filters correctly from the start.
        if (appSelect.value) {
            updateDropdowns();
        }
    });
</script>
@endpush