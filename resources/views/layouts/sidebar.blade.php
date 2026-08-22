@php
    $user         = auth()->user();
    $currentRoute = request()->route() ? request()->route()->getName() : '';

    // Detect if we are on an IT Category or IT RCM detail page
    $isOnCategoryPage = in_array($currentRoute, ['dashboard.controls', 'it-category.show']);
    $isOnRcmPage      = in_array($currentRoute, ['rcm.controls', 'rcm.index']);
    $activeCategoryId = null;

    if ($isOnCategoryPage || $isOnRcmPage) {
        $activeCategoryId = request()->route('category') ? (is_object(request()->route('category')) ? request()->route('category')->id : request()->route('category')) : null;
    }

    // Load all IT categories for the submenu (from DB)
    $sidebarCategories = \App\Models\ItCategory::orderBy('name')->get();

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
               class="nav-link {{ $currentRoute === 'dashboard' ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i>
                <span class="nav-text">Dashboard</span>
            </a>
        </div>

        <div class="nav-section">Modules</div>

        {{-- IT RCM (Admin) / IT Category (Officer, Reviewer, Approver) → submenu with categories --}}
        <div class="nav-item">
            <a href="#" class="nav-link nav-toggle"
               style="color: #ffffff !important;"
               aria-expanded="{{ $isOnCategoryPage || $isOnRcmPage ? 'true' : 'false' }}">
                <i class="bi bi-shield-lock"></i>
                <span class="nav-text">{{ $user->isAdmin() ? 'IT RCM Management' : 'IT Category' }}</span>
                <i class="bi bi-chevron-right nav-arrow"></i>
            </a>
            <ul class="nav-submenu {{ $isOnCategoryPage || $isOnRcmPage ? 'show' : '' }}">
                @foreach($sidebarCategories as $cat)
                    <li>
                        @if($user->isAdmin())
                            {{-- Admin: full IT RCM management, no period filter, all applications --}}
                            <a href="{{ route('rcm.controls', ['category' => $cat->id]) }}"
                               class="nav-link {{ $activeCategoryId == $cat->id ? 'sub-active' : '' }}"
                               data-category-id="{{ $cat->id }}"
                               data-category-name="{{ $cat->name }}">
                                {{ $cat->name }}
                            </a>
                        @else
                            {{-- Officer / Reviewer / Approver: filtered by year, quarter, application (via Dashboard filter) --}}
                            <a href="{{ route('dashboard.controls', ['category' => $cat->id]) . '?' . http_build_query(request()->only('year', 'quarter', 'upti_id', 'application_id')) }}"
                               class="nav-link {{ $activeCategoryId == $cat->id ? 'sub-active' : '' }}"
                               data-category-id="{{ $cat->id }}"
                               data-category-name="{{ $cat->name }}">
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
                <span class="nav-text">Application Mgt</span>
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