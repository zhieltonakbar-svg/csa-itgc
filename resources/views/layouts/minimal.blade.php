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
<body class="minimal-body">

    {{-- Minimal Top Bar: brand + logout only --}}
    <header class="minimal-topbar">
        <div class="minimal-topbar-inner">

            {{-- Brand --}}
            <div class="minimal-brand">
                <div class="minimal-brand-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
                <div class="minimal-brand-text">
                    <span class="minimal-brand-name">CSA - ITGC</span>
                    <span class="minimal-brand-sub">Control Self Assessment</span>
                </div>
            </div>

            {{-- Logout --}}
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="minimal-logout-btn" title="Logout">
                    <i class="bi bi-box-arrow-right me-1"></i>Logout
                </button>
            </form>

        </div>
    </header>

    {{-- Page Content --}}
    <main class="minimal-main">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
