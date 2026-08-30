@php
    $user         = auth()->user();
    $currentRoute = request()->route() ? request()->route()->getName() : '';

    // Detect if we are on an IT Category or IT RCM detail page.
    // For Admin, the "IT RCM" section should only ever
    // highlight/expand when actually on the rcm.controls route —
    // reaching the same category via the Dashboard (dashboard.controls)
    // must NOT make the sidebar look like you're inside IT RCM.
    if ($user->isAdmin()) {
        $isOnCategoryPage = $currentRoute === 'rcm.controls';
    } else {
        $isOnCategoryPage = in_array($currentRoute, ['dashboard.controls', 'it-category.show']);
    }

    $activeCategoryId = null;

    if ($isOnCategoryPage) {
        $activeCategoryId = request()->route('category') ? (is_object(request()->route('category')) ? request()->route('category')->id : request()->route('category')) : null;
    }

    // The "sticky" search: prefer whatever is in the current URL,
    // otherwise fall back to the last search stored in session by
    // DashboardController@index. If neither exists, the user hasn't
    // searched yet — category links are disabled until they do.
    $sidebarFilterParams = array_filter(
        request()->only('year', 'quarter', 'upti_id', 'application_id'),
        fn ($value) => $value !== null && $value !== ''
    );

    if (empty($sidebarFilterParams['application_id'] ?? null)) {
        $sidebarFilterParams = array_filter([
            'year' => session('itgc_filter.year'),
            'quarter' => session('itgc_filter.quarter'),
            'application_id' => session('itgc_filter.application_id'),
        ]);
    }

    $hasActiveSearch = !empty($sidebarFilterParams['application_id'] ?? null);

    // Load only the IT categories actually configured for the
    // currently active period (Application + Year + Quarter),
    // so categories Admin removed from a period disappear from
    // everyone's sidebar too — not just the global category list.
    $sidebarCategories = collect();

    if ($hasActiveSearch) {

        $period = \App\Models\ApplicationPeriod::where(
            'application_id',
            $sidebarFilterParams['application_id']
        )
            ->where('year', $sidebarFilterParams['year'] ?? null)
            ->where('quarter', $sidebarFilterParams['quarter'] ?? null)
            ->first();

        if ($period) {

            $sidebarCategories = $period->itCategories()
                ->orderBy('name')
                ->get();

        } else {

            // Fallback for periods created before the
            // ApplicationPeriod table existed — derive the
            // category list from the actual Controls instead.
            $categoryIds = \App\Models\Control::where(
                'application_id',
                $sidebarFilterParams['application_id']
            )
                ->where('year', $sidebarFilterParams['year'] ?? null)
                ->where('quarter', $sidebarFilterParams['quarter'] ?? null)
                ->distinct()
                ->pluck('it_category_id');

            $sidebarCategories = \App\Models\ItCategory::whereIn(
                'id',
                $categoryIds
            )
                ->orderBy('name')
                ->get();

        }

    }

@endphp

<aside class="sidebar" id="sidebar">

    {{-- Brand --}}
    <div class="sidebar-brand">
        <div class="brand-icon">
            <x-logo />
        </div>
        <div class="brand-text">
            <h2>CSA - ITGC</h2>
            <small>Control Self Assessment</small>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav">

        <div class="nav-section">Main</div>

        <div class="nav-item">
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ in_array($currentRoute, ['dashboard', 'dashboard.controls']) ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i>
                <span class="nav-text">Dashboard</span>
            </a>
        </div>

        <div class="nav-section">Modules</div>

        {{-- IT RCM (Admin) / IT Category (Officer, Reviewer, Approver) → submenu with categories --}}
        <div class="nav-item">
            <a href="#" class="nav-link nav-toggle"
               style="color: #ffffff !important;"
               aria-expanded="{{ $isOnCategoryPage ? 'true' : 'false' }}">
                <i class="bi bi-shield-lock"></i>
                <span class="nav-text">{{ $user->isAdmin() ? 'IT RCM' : 'IT Category' }}</span>
                <i class="bi bi-chevron-right nav-arrow"></i>
            </a>
            <ul class="nav-submenu {{ $isOnCategoryPage ? 'show' : '' }}">
                @foreach($sidebarCategories as $cat)
                    <li>
                        @if($hasActiveSearch)

                            {{--
                                Admin -> IT RCM (rcm.controls): completed
                                rows are simplified there. Everyone else -> IT Category
                                (dashboard.controls): full operational view.
                                Filter carried over from the current URL, or from the
                                last search saved in session — "sticky" until cleared.
                            --}}
                            <a href="{{ route($user->isAdmin() ? 'rcm.controls' : 'dashboard.controls', ['category' => $cat->id]) . '?' . http_build_query($sidebarFilterParams) }}"
                               class="nav-link {{ $activeCategoryId == $cat->id ? 'sub-active' : '' }}"
                               data-category-id="{{ $cat->id }}"
                               data-category-name="{{ $cat->name }}">
                                {{ $cat->name }}
                            </a>

                        @else

                            <a href="{{ route('dashboard') }}"
                               class="nav-link"
                               style="opacity:.5; cursor:not-allowed;"
                               title="Search a year, quarter, and application on the Dashboard first"
                               data-category-id="{{ $cat->id }}"
                               data-category-name="{{ $cat->name }}">
                                <i class="bi bi-lock-fill" style="font-size:11px; margin-right:4px;"></i>
                                {{ $cat->name }}
                            </a>

                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
        @if(auth()->user()->isAdmin())
        <div class="nav-item">
            <a href="{{ route('applications.index') }}"
               class="nav-link {{ $currentRoute === 'applications.index' ? 'active' : '' }}">
                <i class="bi bi-app-indicator"></i>
                <span class="nav-text">App Management</span>
            </a>
        </div>
        
        <div class="nav-item">
            <a href="{{ route('users.index') }}"
               class="nav-link {{ $currentRoute === 'users.index' ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i>
                <span class="nav-text">User Management</span>
            </a>
        </div>


        @endif

    </nav>

    {{-- User profile at sidebar bottom --}}
    <div class="sidebar-user-profile">
        <div class="sidebar-user-avatar" style="overflow: hidden; padding: 0;">
            @if($user && $user->profile_photo_path)
                <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
            @else
                {{ $user ? strtoupper(substr($user->name, 0, 2)) : 'ZA' }}
            @endif
        </div>
        <div class="sidebar-user-info">
            <span class="sidebar-user-name">{{ $user ? $user->name : 'Zhielton Akbar' }}</span>
            <span class="sidebar-user-email">{{ $user ? $user->email : 'zhielton.akbar@gmail.com' }}</span>
        </div>
    </div>

</aside>