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

        // Auto-dismiss alerts after 5 seconds
        document.querySelectorAll('.alert-dismissible').forEach(function(alert) {
            setTimeout(function() {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                bsAlert.close();
            }, 5000);
        });

        // ── IT CATEGORY LINKS — use filter stored in sessionStorage ────────────
        (function () {
            // Read filter values stored by the Dashboard filter form
            function getFilter() {
                try {
                    var stored = sessionStorage.getItem('assessmentFilter');
                    return stored ? JSON.parse(stored) : null;
                } catch (e) { return null; }
            }

            document.querySelectorAll('.nav-submenu .nav-link[data-category-id]').forEach(function (link) {
                link.addEventListener('click', function (e) {
                    var filter = getFilter();
                    if (!filter || !filter.appId) {
                        // No filter set — navigate to Dashboard so user can choose
                        e.preventDefault();
                        window.location.href = '{{ route("dashboard") }}';
                        return;
                    }
                    // Build the correct URL if href is a placeholder
                    if (this.getAttribute('href') === '#' || this.getAttribute('href') === '') {
                        e.preventDefault();
                        var catId = this.getAttribute('data-category-id');
                        window.location.href = '/it-category/' + encodeURIComponent(filter.appId) + '/' + encodeURIComponent(catId)
                            + '?year=' + encodeURIComponent(filter.year)
                            + '&quarter=' + encodeURIComponent(filter.quarter);
                    }
                });
            });
        }());
        // ──────────────────────────────────────────────────────────────────────
    </script>
    @stack('scripts')
</body>
</html>
