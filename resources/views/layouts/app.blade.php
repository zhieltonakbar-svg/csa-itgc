<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CSA - ITGC')</title>
    <meta name="description" content="Control Self Assessment - IT General Control Management System">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    {{-- Sidebar --}}
    @include('layouts.sidebar')

    {{-- Sidebar Overlay (mobile) --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    {{-- Main Content --}}
    <div class="main-content" id="mainContent">
        {{-- Header --}}
        @include('layouts.header')

        {{-- Page Content --}}
        <div class="page-content">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const isMobile = window.innerWidth <= 1024;

            if (isMobile) {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            } else {
                sidebar.classList.toggle('collapsed');
            }
        }

        // Submenu toggle
        document.querySelectorAll('.nav-toggle').forEach(function(toggle) {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                const submenu = this.nextElementSibling;
                const isOpen = submenu.classList.contains('show');

                // Close all submenus
                document.querySelectorAll('.nav-submenu.show').forEach(function(menu) {
                    menu.classList.remove('show');
                });
                document.querySelectorAll('.nav-toggle[aria-expanded="true"]').forEach(function(t) {
                    t.setAttribute('aria-expanded', 'false');
                });

                if (!isOpen) {
                    submenu.classList.add('show');
                    this.setAttribute('aria-expanded', 'true');
                }
            });
        });

        // Close sidebar on window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 1024) {
                document.getElementById('sidebarOverlay').classList.remove('show');
                document.getElementById('sidebar').classList.remove('show');
            }
        });

        // Auto-dismiss disabled so users can close them manually

        // NOTE: category links now carry their correct URL (rcm.controls
        // for Admin, dashboard.controls for everyone else, with the
        // sticky year/quarter/application filter) directly in href,
        // resolved server-side in layouts/sidebar.blade.php. No client-
        // side interception needed anymore.
    </script>

    @if(auth()->check())
    <script>
        window.APP_USER_ID = {{ auth()->id() }};
    </script>
    @vite(['resources/js/app.js'])
    <script type="module">
        if (window.Echo) {
            window.Echo.private('App.Models.User.' + window.APP_USER_ID)
                .notification((notification) => {
                    // Update bell count
                    let dropdownBtn = document.getElementById('notificationDropdown');
                    if (dropdownBtn) {
                        let badge = dropdownBtn.querySelector('.badge.bg-danger');
                        if (!badge) {
                            badge = document.createElement('span');
                            badge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger';
                            badge.style.fontSize = '0.65rem';
                            badge.style.padding = '0.25em 0.5em';
                            dropdownBtn.appendChild(badge);
                        }
                        let currentCount = parseInt(badge.innerText) || 0;
                        let newCount = currentCount + 1;
                        badge.innerHTML = newCount > 9 ? '9+ <span class="visually-hidden">unread notifications</span>' : newCount + ' <span class="visually-hidden">unread notifications</span>';
                        badge.style.display = 'inline-block';
                    }
                    
                    // Show a toast
                    let toastContainer = document.getElementById('toast-container');
                    if (!toastContainer) {
                        toastContainer = document.createElement('div');
                        toastContainer.id = 'toast-container';
                        toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
                        toastContainer.style.zIndex = '1090';
                        document.body.appendChild(toastContainer);
                    }
                    
                    let toastId = 'toast-' + Date.now();
                    let toastHtml = `
                        <div id="${toastId}" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="toast-header bg-primary text-white">
                                <i class="bi bi-bell-fill me-2"></i>
                                <strong class="me-auto">New Notification</strong>
                                <small>Just now</small>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                            <div class="toast-body">
                                ${notification.message}
                            </div>
                        </div>
                    `;
                    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
                    let toastEl = document.getElementById(toastId);
                    let toast = new bootstrap.Toast(toastEl, { delay: 5000 });
                    toast.show();
                });
        }
    </script>
    @endif

    @stack('scripts')
</body>
</html>
