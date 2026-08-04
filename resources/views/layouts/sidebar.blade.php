@php
    $user         = auth()->user();
    $currentRoute = request()->route() ? request()->route()->getName() : '';

    // Detect if we are on an IT Category detail page
    $isOnCategoryPage = $currentRoute === 'it-category.show';
    $activeCategoryId = null;
    $activeAppId      = null;

    if ($isOnCategoryPage) {
        $activeCategoryId = request()->route('category') ? request()->route('category')->id : null;
        $activeAppId      = request()->route('application') ? request()->route('application')->id : null;
    }

    // Load all IT categories for the submenu (from DB)
    $sidebarCategories = \App\Models\ItCategory::orderBy('name')->get();

@endphp

<aside class="sidebar" id="sidebar">

    {{-- Brand --}}
    <div class="sidebar-brand">
        <div class="brand-icon">
            <i class="bi bi-shield-check"></i>
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

        <div class="nav-item">
            <a href="#" class="nav-link nav-toggle {{ $isOnCategoryPage ? '' : '' }}" 
               style="color: #ffffff !important;"
               aria-expanded="{{ $isOnCategoryPage ? 'true' : 'false' }}">
                <i class="bi bi-shield-lock"></i>
                <span class="nav-text">IT Category</span>
                <i class="bi bi-chevron-right nav-arrow"></i>
            </a>
            <ul class="nav-submenu {{ $isOnCategoryPage ? 'show' : '' }}">
                @foreach($sidebarCategories as $cat)
                    <li>
                        <a href="{{ $isOnCategoryPage && $activeAppId
                                    ? route('it-category.show', ['application' => $activeAppId, 'category' => $cat->id]) . '?' . http_build_query(request()->only('year', 'quarter'))
                                    : '#' }}"
                           class="nav-link {{ $activeCategoryId == $cat->id ? 'sub-active' : '' }}"
                           data-category-id="{{ $cat->id }}"
                           data-category-name="{{ $cat->name }}">
                            {{ $cat->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

    </nav>

    {{-- User profile at sidebar bottom --}}
    <div class="sidebar-user-profile">
        <div class="sidebar-user-avatar">
            {{ $user ? strtoupper(substr($user->name, 0, 2)) : 'ZA' }}
        </div>
        <div class="sidebar-user-info">
            <span class="sidebar-user-name">{{ $user ? $user->name : 'Zhielton Akbar' }}</span>
            <span class="sidebar-user-email">{{ $user ? $user->email : 'zhielton.akbar@gmail.com' }}</span>
        </div>
    </div>

</aside>
