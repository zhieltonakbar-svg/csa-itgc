@extends('layouts.app')

@section('title', 'Dashboard — CSA - ITGC')

@push('styles')
<style>
    /* Welcome Hero Overrides */
    .welcome-hero {
        padding: 1.5rem 2rem !important;
        height: 140px !important;
        display: flex;
        flex-direction: column;
        justify-content: center;
        margin-bottom: 1.5rem !important;
        border-radius: 12px;
    }
    .welcome-hero-label {
        font-size: 0.75rem !important;
        margin-bottom: 0.35rem !important;
        letter-spacing: 0.5px;
    }
    .welcome-hero-name {
        font-size: 1.5rem !important;
        margin-bottom: 0.35rem !important;
        font-weight: 700;
    }
    .welcome-hero-sub {
        font-size: 0.85rem !important;
        margin-bottom: 0 !important;
    }

    /* ── Assessment Filter Card ─────────────────────────────────────── */
    .assessment-filter-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 14px;
        padding: 1.5rem 2rem;
        margin-bottom: 1.75rem;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    }
    .afc-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 1.25rem;
    }
    .afc-header-icon {
        width: 32px;
        height: 32px;
        background: var(--primary-light);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .afc-header-icon i {
        font-size: 15px;
        color: var(--primary);
    }
    .afc-header h3 {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        letter-spacing: -0.1px;
    }
    .afc-header p {
        font-size: 0.78rem;
        color: var(--text-secondary);
        margin: 0;
    }

    /* Filter grid */
    .afc-fields {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr auto;
        gap: 1rem;
        align-items: end;
    }
    @media (max-width: 900px) {
        .afc-fields {
            grid-template-columns: 1fr 1fr;
        }
    }
    @media (max-width: 560px) {
        .afc-fields {
            grid-template-columns: 1fr;
        }
    }

    .afc-field label {
        display: block;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: var(--text-secondary);
        margin-bottom: 6px;
    }
    .afc-select-wrap {
        position: relative;
    }
    .afc-select {
        width: 100%;
        appearance: none;
        -webkit-appearance: none;
        background: #f9fafb;
        border: 1.5px solid var(--border-color);
        border-radius: 8px;
        padding: 9px 32px 9px 12px;
        font-size: 13px;
        font-weight: 500;
        color: var(--text-primary);
        font-family: inherit;
        cursor: pointer;
        outline: none;
        transition: border-color 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
    }
    .afc-select:focus {
        border-color: var(--primary);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.12);
    }
    .afc-select:hover:not(:focus) {
        border-color: #b5c6ba;
        background: #fff;
    }
    .afc-select-icon {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 10px;
        color: var(--text-muted);
        pointer-events: none;
    }
    .afc-hint {
        font-size: 11px;
        color: #dc2626;
        margin-top: 4px;
        font-weight: 500;
        display: none;
    }

    /* Search button */
    .afc-search-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 9px 22px;
        background: var(--primary-gradient);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        font-family: inherit;
        cursor: pointer;
        white-space: nowrap;
        transition: background 0.2s ease, box-shadow 0.2s ease, transform 0.15s ease;
        letter-spacing: 0.15px;
        height: 40px;
    }
    .afc-search-btn:hover {
        background: var(--primary-gradient-hover);
        box-shadow: 0 4px 14px rgba(25, 135, 84, 0.28);
        transform: translateY(-1px);
    }
    .afc-search-btn:active {
        transform: translateY(0);
        box-shadow: none;
    }
    .afc-search-btn i { font-size: 14px; }

    /* Active filter badge displayed after search */
    .afc-active-badge {
        display: none;
        align-items: center;
        gap: 6px;
        margin-top: 1rem;
        padding: 6px 12px;
        background: var(--primary-light);
        border: 1px solid rgba(25,135,84,0.2);
        border-radius: 999px;
        font-size: 12px;
        font-weight: 500;
        color: var(--primary);
        width: fit-content;
    }
    .afc-active-badge.visible { display: inline-flex; }
    .afc-active-badge i { font-size: 13px; }
    .afc-active-badge .afc-clear {
        background: none;
        border: none;
        padding: 0;
        color: var(--primary);
        cursor: pointer;
        font-size: 14px;
        line-height: 1;
        margin-left: 2px;
        opacity: 0.7;
        transition: opacity 0.15s;
    }
    .afc-active-badge .afc-clear:hover { opacity: 1; }

    /* IT Category Cards */
    .it-category-card {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        text-align: left;
        padding: 1.25rem;
        background-color: #ffffff;
        border: 1px solid rgba(25, 135, 84, 0.2);
        border-radius: 12px;
        text-decoration: none;
        color: #333;
        min-height: 220px;
        height: 100%;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
    }
    .it-category-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(25, 135, 84, 0.12);
        border-color: rgba(25, 135, 84, 0.4);
    }
    .it-category-card .card-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background-color: rgba(25, 135, 84, 0.1);
        color: #198754;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }
    .it-category-card:hover .card-icon {
        background-color: #198754;
        color: #ffffff;
    }
    .it-category-card .card-title {
        font-weight: 700;
        font-size: 0.95rem;
        margin: 0 0 0.5rem 0;
        color: #1f2937;
        line-height: 1.3;
    }
    .it-category-card .card-desc {
        font-size: 0.8rem;
        color: #6b7280;
        margin-bottom: 1rem;
        line-height: 1.4;
        flex-grow: 1;
    }
    .it-category-card .card-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.6rem;
        border-radius: 9999px;
        font-size: 0.7rem;
        font-weight: 700;
        margin-top: auto;
    }
    .it-category-card .card-badge i {
        font-size: 0.35rem;
        margin-right: 0.25rem;
    }
    /* Complete — green */
    .it-category-card .card-badge.badge-complete {
        background-color: rgba(25, 135, 84, 0.1);
        color: #198754;
        border: 1px solid rgba(25, 135, 84, 0.25);
    }
    /* Partial Complete — amber */
    .it-category-card .card-badge.badge-partial {
        background-color: rgba(217, 119, 6, 0.1);
        color: #b45309;
        border: 1px solid rgba(217, 119, 6, 0.25);
    }
    /* Not Complete — red */
    .it-category-card .card-badge.badge-not-complete {
        background-color: rgba(220, 38, 38, 0.1);
        color: #dc2626;
        border: 1px solid rgba(220, 38, 38, 0.25);
    }

    /* Empty / prompt state */
    .category-empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
        color: #9ca3af;
    }
    .category-empty-state .empty-icon {
        font-size: 2.5rem;
        margin-bottom: 0.75rem;
        display: block;
        opacity: 0.4;
    }
    .category-empty-state p {
        font-size: 0.875rem;
        margin: 0;
    }

    /* Card skeleton loader */
    .card-skeleton {
        min-height: 220px;
        border-radius: 12px;
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: shimmer 1.2s infinite;
    }
    @keyframes shimmer {
        0%   { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
</style>
@endpush

@section('content')

@php $user = auth()->user(); @endphp

{{-- ================================================
     WELCOME BANNER
     ================================================ --}}
<div class="welcome-hero hero-stacked">
    <div class="welcome-hero-body">
        <p class="welcome-hero-label">WELCOME BACK</p>
        <h2 class="welcome-hero-name">{{ $user ? $user->name : 'Zhielton Akbar' }}</h2>
        <p class="welcome-hero-sub">
            CSA - ITGC &nbsp;&middot;&nbsp; PT Telkom Infrastruktur Indonesia
        </p>
    </div>
    {{-- Decorative shapes --}}
    <div class="welcome-hero-deco" aria-hidden="true">
        <span class="deco-circle deco-lg"></span>
        <span class="deco-circle deco-sm"></span>
    </div>
</div>

{{-- ================================================
     ASSESSMENT FILTER CARD
     ================================================ --}}
<div class="assessment-filter-card" id="assessment-filter-card">
    <div class="afc-header">
        <div class="afc-header-icon">
            <i class="bi bi-funnel-fill"></i>
        </div>
        <div>
            <h3>Assessment Filter</h3>
            <p>Select the assessment parameters to explore IT RCM.</p>
        </div>
    </div>

    <div class="afc-fields">

        {{-- Year --}}
        <div class="afc-field">
            <label for="afc-year">Year</label>
            <div class="afc-select-wrap">
                <select class="afc-select" id="afc-year" name="afc_year">
                    <option value="2026" selected>2026</option>
                    <option value="2025">2025</option>
                    <option value="2024">2024</option>
                    <option value="2023">2023</option>
                </select>
                <i class="bi bi-chevron-down afc-select-icon"></i>
            </div>
        </div>

        {{-- Quarter --}}
        <div class="afc-field">
            <label for="afc-quarter">Quarter</label>
            <div class="afc-select-wrap">
                <select class="afc-select" id="afc-quarter" name="afc_quarter">
                    <option value="q1" selected>Q1</option>
                    <option value="q2">Q2</option>
                    <option value="q3">Q3</option>
                    <option value="q4">Q4</option>
                </select>
                <i class="bi bi-chevron-down afc-select-icon"></i>
            </div>
        </div>

        {{-- Application --}}
        <div class="afc-field">
            <label for="afc-application">Application</label>
            <div class="afc-select-wrap">
                <select class="afc-select" id="afc-application" name="afc_application">
                    @if($applications->isEmpty())
                        <option value="">No applications available</option>
                    @else
                        <option value="">— Select Application —</option>
                        @foreach($applications as $app)
                            <option value="{{ $app->id }}">{{ $app->name }}</option>
                        @endforeach
                    @endif
                </select>
                <i class="bi bi-chevron-down afc-select-icon"></i>
            </div>
            <div class="afc-hint" id="afc-app-hint">Please select an application.</div>
        </div>

        {{-- Search button --}}
        <div class="afc-field">
            <button type="button" class="afc-search-btn" id="afc-search-btn">
                <i class="bi bi-search"></i>
                <span>Search</span>
            </button>
        </div>

    </div>

    {{-- Active filter badge (shown after search) --}}
    <div class="afc-active-badge" id="afc-active-badge">
        <i class="bi bi-check-circle-fill"></i>
        <span id="afc-badge-text"></span>
        <button class="afc-clear" id="afc-clear-btn" title="Clear filter" aria-label="Clear filter">
            <i class="bi bi-x"></i>
        </button>
    </div>
</div>

{{-- ================================================
     IT CATEGORY CARDS SECTION
     Hidden until Search is clicked
     ================================================ --}}
<div class="category-cards-container" id="category-section">

    {{-- Default empty / prompt state --}}
    <div class="category-empty-state" id="category-empty-state">
        <i class="bi bi-funnel empty-icon"></i>
        <p>Select a <strong>Year</strong>, <strong>Quarter</strong>, and <strong>Application</strong> above, then click <strong>Search</strong> to view IT RCM.</p>
    </div>

    {{-- Cards grid — populated by JavaScript --}}
    <div class="row g-3" id="category-cards-grid" style="display:none!important;"></div>

</div>

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    // ── DOM refs ──────────────────────────────────────────────────────────
    const emptyState    = document.getElementById('category-empty-state');
    const cardsGrid     = document.getElementById('category-cards-grid');
    const selYear       = document.getElementById('afc-year');
    const selQtr        = document.getElementById('afc-quarter');
    const selApp        = document.getElementById('afc-application');
    const searchBtn     = document.getElementById('afc-search-btn');
    const appHint       = document.getElementById('afc-app-hint');
    const activeBadge   = document.getElementById('afc-active-badge');
    const badgeText     = document.getElementById('afc-badge-text');
    const clearBtn      = document.getElementById('afc-clear-btn');

    // ── SessionStorage helpers ────────────────────────────────────────────
    const STORAGE_KEY = 'assessmentFilter';

    function saveFilter(filter) {
        try { sessionStorage.setItem(STORAGE_KEY, JSON.stringify(filter)); } catch (e) {}
    }

    function loadFilter() {
        try {
            var stored = sessionStorage.getItem(STORAGE_KEY);
            return stored ? JSON.parse(stored) : null;
        } catch (e) { return null; }
    }

    function clearFilter() {
        try { sessionStorage.removeItem(STORAGE_KEY); } catch (e) {}
    }

    // ── Quarter label map ─────────────────────────────────────────────────
    const QUARTER_LABELS = { q1: 'Q1', q2: 'Q2', q3: 'Q3', q4: 'Q4' };

    // ── Status → badge class / label map ─────────────────────────────────
    const STATUS_MAP = {
        complete:     { cls: 'badge-complete',     label: 'Complete' },
        partial:      { cls: 'badge-partial',       label: 'Partial Complete' },
        not_complete: { cls: 'badge-not-complete',  label: 'Not Complete' },
    };

    // ── Base URL for IT Category detail pages ─────────────────────────────
    const categoryBaseUrl = '{{ url("it-category") }}';
    let currentAppId = '';
    let currentYear  = '2026';
    let currentQtr   = 'q1';

    function buildCard(cat) {
        const status  = STATUS_MAP[cat.completion_status] ?? STATUS_MAP['not_complete'];
        const href    = `${categoryBaseUrl}/${encodeURIComponent(cat.id)}/controls?application_id=${encodeURIComponent(currentAppId)}&year=${encodeURIComponent(currentYear)}&quarter=${encodeURIComponent(currentQtr)}&source=dashboard`;
        return `
        <div class="col-12 col-sm-6 col-xl-3">
            <a href="${href}" class="it-category-card">
                <div class="card-icon"><i class="bi ${cat.icon}"></i></div>
                <h3 class="card-title">${escHtml(cat.name)}</h3>
                <p class="card-desc">${escHtml(cat.description ?? '')}</p>
                <div class="card-badge ${status.cls}">
                    <i class="bi bi-circle-fill"></i>${status.label}
                </div>
            </a>
        </div>`;
    }

    function showSkeletons(count) {
        cardsGrid.innerHTML = Array.from({ length: count }, () =>
            `<div class="col-12 col-sm-6 col-xl-3"><div class="card-skeleton"></div></div>`
        ).join('');
        showGrid();
    }

    function renderCards(categories) {
        if (!categories.length) {
            cardsGrid.innerHTML = '';
            hideGrid();
            emptyState.querySelector('p').textContent =
                'No IT RCM found for the selected application.';
            emptyState.style.display = '';
            return;
        }
        cardsGrid.innerHTML = categories.map(buildCard).join('');
        showGrid();
    }

    function showGrid() {
        emptyState.style.display = 'none';
        cardsGrid.style.removeProperty('display');
        cardsGrid.style.display = 'flex';
        cardsGrid.style.flexWrap = 'wrap';
    }

    function hideGrid() {
        cardsGrid.style.display = 'none';
        emptyState.style.display = '';
    }

    function escHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function getAppName() {
        if (!selApp) return '';
        const opt = selApp.options[selApp.selectedIndex];
        return opt ? opt.text : '';
    }

    function showActiveBadge(year, quarter, appName) {
        if (!activeBadge || !badgeText) return;
        badgeText.textContent = `${year} · ${QUARTER_LABELS[quarter] || quarter} · ${appName}`;
        activeBadge.classList.add('visible');
    }

    function hideActiveBadge() {
        if (!activeBadge) return;
        activeBadge.classList.remove('visible');
    }

    // ── Restore saved filter values on page load ──────────────────────────
    (function restoreFilterState() {
        const saved = loadFilter();
        if (!saved) return;

        if (selYear  && saved.year)    selYear.value    = saved.year;
        if (selQtr   && saved.quarter) selQtr.value     = saved.quarter;
        if (selApp   && saved.appId)   selApp.value     = saved.appId;

        // If a complete filter exists, trigger the search automatically
        if (saved.appId) {
            runSearch(saved.appId, saved.year, saved.quarter);
        }
    }());

    // ── Search logic ──────────────────────────────────────────────────────
    function runSearch(appId, year, quarter) {
        if (!appId) return;

        currentAppId = appId;
        currentYear  = year  || '2026';
        currentQtr   = quarter || 'q1';

        const appName = getAppName();

        // Persist to sessionStorage so other pages can read it
        saveFilter({ appId, year: currentYear, quarter: currentQtr });

        // Show badge
        showActiveBadge(currentYear, currentQtr, appName);

        // Show skeletons immediately
        showSkeletons(4);

        // ── BACKEND FETCH ─────────────────────────────────────────────────
        const url = `{{ route('dashboard.categories') }}?application_id=${encodeURIComponent(appId)}&year=${encodeURIComponent(year)}&quarter=${encodeURIComponent(quarter)}`;
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(res => {
            if (!res.ok) throw new Error('Network response was not ok: ' + res.status);
            return res.json();
        })
        .then(data => renderCards(data.categories))
        .catch(err => {
            console.error('Dashboard categories fetch error:', err);
            cardsGrid.innerHTML = '';
            hideGrid();
            emptyState.querySelector('p').textContent = 'An error occurred while loading IT RCM. Please try again.';
            emptyState.style.display = '';
        });
    }

    // ── Search button click ───────────────────────────────────────────────
    if (searchBtn && selApp) {
        searchBtn.addEventListener('click', function () {
            if (!selApp.value) {
                if (appHint) appHint.style.display = 'block';
                selApp.focus();
                return;
            }
            if (appHint) appHint.style.display = 'none';

            runSearch(selApp.value, selYear ? selYear.value : '2026', selQtr ? selQtr.value : 'q1');
        });

        selApp.addEventListener('change', function () {
            if (this.value && appHint) appHint.style.display = 'none';
        });
    }

    // ── Clear filter button ───────────────────────────────────────────────
    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            clearFilter();
            hideActiveBadge();
            hideGrid();
            if (selApp) selApp.value = '';
            emptyState.querySelector('p').textContent =
                'Select a Year, Quarter, and Application above, then click Search to view IT RCM.';
            emptyState.style.display = '';
        });
    }

}());
</script>
@endpush
