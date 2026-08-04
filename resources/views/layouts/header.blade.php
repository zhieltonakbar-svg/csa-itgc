@php
    $user = auth()->user();
@endphp

<header class="main-header">

    {{-- Left: Sidebar toggle + App branding --}}
    <div class="header-left">
        <button class="sidebar-toggle" onclick="toggleSidebar()" title="Toggle Sidebar">
            <i class="bi bi-list fs-5"></i>
        </button>

        <div class="header-brand-cluster">
            <div class="header-brand-logo">
                <i class="bi bi-shield-check"></i>
            </div>
            <div class="header-brand-text">
                <span class="header-brand-app">CSA - ITGC</span>
                <span class="header-brand-company">PT Telkom Infrastruktur Indonesia</span>
            </div>
        </div>
    </div>

    {{-- Right: Notification | Divider | User + Logout --}}
    <div class="header-right">

        {{-- Notification bell (visual only) --}}
        <button class="header-bell-btn" title="Notifications">
            <i class="bi bi-bell"></i>
        </button>

        {{-- Vertical divider --}}
        <div class="header-vr"></div>

        {{-- User Dropdown --}}
        <div class="dropdown">
            <button class="header-user-cluster border-0 bg-transparent p-0 dropdown-toggle-custom" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="outline: none;">
                <div class="header-avatar">
                    {{ $user ? strtoupper(substr($user->name, 0, 2)) : 'ZA' }}
                </div>
                <div class="header-user-meta d-none d-lg-block text-start">
                    <span class="header-user-name">{{ $user ? $user->name : 'Zhielton Akbar' }}</span>
                </div>
                <i class="bi bi-chevron-down ms-1" style="font-size: 12px; color: var(--text-muted);"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="userDropdown" style="border-radius: var(--radius-md); min-width: 200px; border: 1px solid var(--border-color); padding: 8px 0; margin-top: 10px;">
                <li class="px-3 py-2">
                    <span class="d-block" style="font-size: 13.5px; font-weight: 600; color: var(--text-primary); line-height: 1.2;">{{ $user ? $user->name : 'Zhielton Akbar' }}</span>
                    <span class="d-block" style="font-size: 11.5px; color: var(--text-muted); margin-top: 2px;">{{ $user ? $user->email : 'zhielton.akbar@gmail.com' }}</span>
                </li>
                <li><hr class="dropdown-divider my-1"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item d-flex align-items-center text-danger" style="font-size: 13px; font-weight: 500; padding: 6px 16px;">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>

    </div>
</header>
