<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — CSA - ITGC</title>
    <meta name="description" content="Register to CSA - ITGC Control Self Assessment System">
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
                    <x-logo />
                </div>
                <h1>CSA - ITGC</h1>
                <p>Register a New Account</p>
            </div>

            {{-- Error Message --}}
            @if(session('error'))
                <div class="alert alert-danger mb-3" role="alert" style="font-size: 13px; border-radius: var(--radius-sm);">
                    <i class="bi bi-exclamation-circle me-1"></i>{{ session('error') }}
                </div>
            @endif

            {{-- Registration Form --}}
            <form method="POST" action="{{ route('register.post') }}" class="login-form">
                @csrf

                {{-- Name --}}
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <div class="input-group">
                        <span class="input-group-text login-input-addon">
                            <i class="bi bi-person text-muted"></i>
                        </span>
                        <input
                            type="text"
                            class="form-control login-input @error('name') is-invalid @enderror"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Enter your full name"
                            required
                            autofocus
                        >
                    </div>
                    @error('name')
                        <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                    @enderror
                </div>

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
                        >
                    </div>
                    @error('email')
                        <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Request Role --}}
                <div class="mb-3">
                    <label for="role" class="form-label">Request Role</label>
                    <div class="input-group">
                        <span class="input-group-text login-input-addon">
                            <i class="bi bi-person-badge text-muted"></i>
                        </span>
                        <select
                            class="form-select login-input @error('role') is-invalid @enderror"
                            id="role"
                            name="role"
                            required
                        >
                            <option value="">-- Select Requested Role --</option>
                            <option value="creator" {{ old('role') == 'creator' ? 'selected' : '' }}>Creator</option>
                            <option value="reviewer" {{ old('role') == 'reviewer' ? 'selected' : '' }}>Reviewer</option>
                            <option value="approver" {{ old('role') == 'approver' ? 'selected' : '' }}>Approver</option>
                        </select>
                    </div>
                    @error('role')
                        <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-3">
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
                            placeholder="Enter your password (min 8 char)"
                            required
                        >
                    </div>
                    @error('password')
                        <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <span class="input-group-text login-input-addon">
                            <i class="bi bi-lock-fill text-muted"></i>
                        </span>
                        <input
                            type="password"
                            class="form-control login-input"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Re-enter your password"
                            required
                        >
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-login">
                    <i class="bi bi-person-plus me-2"></i>Register
                </button>
            </form>
            
            <div class="text-center mt-3">
                <a href="{{ route('login') }}" style="font-size: 13px; text-decoration: none;">Already have an account? Login here</a>
            </div>

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
