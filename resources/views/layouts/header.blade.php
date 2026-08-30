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
                <x-logo />
            </div>
            <div class="header-brand-text">
                <span class="header-brand-app">CSA - ITGC</span>
                <span class="header-brand-company">PT Telkom Infrastruktur Indonesia</span>
            </div>
        </div>
    </div>

    {{-- Right: Notification | Divider | User + Logout --}}
    <div class="header-right">

        {{-- Notification bell (Database-based) --}}
        @php
            $unreadCount = auth()->check() ? auth()->user()->unreadNotifications->count() : 0;
            $recentNotifications = auth()->check() ? auth()->user()->notifications()->take(4)->get() : collect();
        @endphp
        <div class="dropdown">
            <button class="header-bell-btn dropdown-toggle-custom" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="Notifications" style="position: relative;">
                <i class="bi bi-bell"></i>
                @if($unreadCount > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem; padding: 0.25em 0.5em;">
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        <span class="visually-hidden">unread notifications</span>
                    </span>
                @endif
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="notificationDropdown" style="border-radius: var(--radius-md); min-width: 300px; border: 1px solid var(--border-color); padding: 0; margin-top: 10px;">
                <li class="px-3 py-2 bg-light border-bottom d-flex justify-content-between align-items-center" style="border-top-left-radius: var(--radius-md); border-top-right-radius: var(--radius-md);">
                    <span style="font-size: 13px; font-weight: 600; color: var(--text-primary);">Notification</span>
                    @if($unreadCount > 0)
                        <span class="badge bg-primary rounded-pill">{{ $unreadCount }}</span>
                    @endif
                </li>
                
                @forelse($recentNotifications as $notif)
                <li style="{{ empty($notif->read_at) ? 'background-color: #f8fafc;' : '' }}">
                    <a class="dropdown-item py-2 border-bottom" href="{{ $notif->data['url'] ?? route('notifications.index') }}" style="white-space: normal; padding-left: 15px; padding-right: 15px; background: transparent;">
                        <div class="d-flex align-items-start">
                            <div class="text-primary me-2 mt-1"><i class="bi bi-info-circle-fill"></i></div>
                            <div>
                                <div style="font-size: 12.5px; font-weight: 500; color: var(--text-primary); line-height: 1.3;">{{ $notif->data['message'] ?? 'New notification' }}</div>
                                <div style="font-size: 11px; color: var(--text-muted); margin-top: 3px;">{{ $notif->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </a>
                </li>
                @empty
                <li>
                    <div class="py-4 text-center text-muted">
                        <i class="bi bi-check-circle fs-4 d-block mb-1 text-success"></i>
                        <span style="font-size: 12px;">No notifications.</span>
                    </div>
                </li>
                @endforelse
                
                <li>
                    <a href="{{ route('notifications.index') }}" class="dropdown-item text-center py-2 bg-light" style="font-size: 12px; font-weight: 600; color: var(--primary);">
                        View All History
                    </a>
                </li>
            </ul>
        </div>

        {{-- Vertical divider --}}
        <div class="header-vr"></div>

        {{-- User Dropdown --}}
        <div class="dropdown">
            <button class="header-user-cluster border-0 bg-transparent p-0 dropdown-toggle-custom" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="outline: none;">
                <div class="header-avatar" style="overflow: hidden; padding: 0;">
                    @if($user && $user->profile_photo_path)
                        <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        {{ $user ? strtoupper(substr($user->name, 0, 2)) : 'ZA' }}
                    @endif
                </div>
                <div class="header-user-meta d-none d-lg-block text-start">
                    <span class="header-user-name">{{ $user ? $user->name : 'Zhielton Akbar' }}</span>
                    <span class="d-block" style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">{{ $user ? $user->role : 'Admin' }}</span>
                </div>
                <i class="bi bi-chevron-down ms-1" style="font-size: 12px; color: var(--text-muted);"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="userDropdown" style="border-radius: var(--radius-md); min-width: 200px; border: 1px solid var(--border-color); padding: 8px 0; margin-top: 10px;">
                <li class="px-3 py-2">
                    <span class="d-block" style="font-size: 13.5px; font-weight: 600; color: var(--text-primary); line-height: 1.2;">{{ $user ? $user->name : 'Zhielton Akbar' }}</span>
                    <span class="d-block" style="font-size: 11.5px; color: var(--text-muted); margin-top: 2px;">{{ $user ? $user->email : 'zhielton.akbar@gmail.com' }}</span>
                    <span class="badge bg-secondary mt-2" style="font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px;">{{ $user ? $user->role : 'Admin' }}</span>
                </li>
                <li><hr class="dropdown-divider my-1"></li>
                <li>
                    <a href="{{ route('settings.index') }}" class="dropdown-item d-flex align-items-center" style="font-size: 13px; font-weight: 500; padding: 6px 16px; color: var(--text-primary);">
                        <i class="bi bi-gear me-2"></i> Settings
                    </a>
                </li>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const bellBtn = document.getElementById('notificationDropdown');
        if (bellBtn) {
            bellBtn.addEventListener('show.bs.dropdown', function () {
                const badge = bellBtn.querySelector('.badge');
                if (badge) {
                    badge.style.display = 'none'; // hide immediately for UX
                    
                    // Mark as read via AJAX
                    fetch('{{ route("notifications.markRead") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(res => {
                        if(res.ok) {
                            // Optionally remove the badge from DOM completely
                            badge.remove();
                            const dropdownBadge = document.querySelector('.dropdown-menu .badge');
                            if (dropdownBadge) dropdownBadge.remove();
                        }
                    }).catch(err => console.error(err));
                }
            });
        }
    });
</script>
