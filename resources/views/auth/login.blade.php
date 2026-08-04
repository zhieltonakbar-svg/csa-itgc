<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — CSA - ITGC</title>
    <meta name="description" content="Login to CSA - ITGC Control Self Assessment System">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">

            {{-- Logo & Title --}}
            <div class="login-logo">
                <div class="logo-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
                <h1>CSA - ITGC</h1>
                <p>Control Self Assessment<br>IT General Control</p>
            </div>

            {{-- Error Message --}}
            @if(session('error'))
                <div class="alert alert-danger mb-3" role="alert" style="font-size: 13px; border-radius: var(--radius-sm);">
                    <i class="bi bi-exclamation-circle me-1"></i>{{ session('error') }}
                </div>
            @endif

            {{-- Login Form --}}
            <form method="POST" action="{{ route('login.post') }}" class="login-form">
                @csrf

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text login-input-addon">
                            <i class="bi bi-envelope text-muted"></i>
                        </span>
                        <input
                            type="email"
                            class="form-control login-input @error('email') is-invalid @enderror"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Enter your email address"
                            required
                            autofocus
                        >
                    </div>
                    @error('email')
                        <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text login-input-addon">
                            <i class="bi bi-lock text-muted"></i>
                        </span>
                        <input
                            type="password"
                            class="form-control login-input @error('password') is-invalid @enderror"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            required
                        >
                    </div>
                    @error('password')
                        <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="mb-4">
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="remember"
                            id="remember"
                            {{ old('remember') ? 'checked' : '' }}
                        >
                        <label class="form-check-label" for="remember" style="font-size: 13px; color: var(--text-secondary);">
                            Remember me
                        </label>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-login">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login
                </button>
            </form>

            {{-- Footer Note --}}
            <div class="text-center mt-4">
                <small style="color: var(--text-muted); font-size: 11px;">
                    &copy; {{ date('Y') }} CSA - ITGC &nbsp;&middot;&nbsp; IT Governance &amp; Compliance
                </small>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
