<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waiting for Approval — CSA - ITGC</title>
    <meta name="description" content="Account waiting for approval">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .pending-icon-wrapper {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, rgba(37,99,235,0.1), rgba(29,78,216,0.05));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px auto;
            border: 1px dashed rgba(37,99,235,0.3);
            animation: pulse-border 2s infinite;
        }

        .pending-icon {
            font-size: 32px;
            color: var(--primary);
        }

        @keyframes pulse-border {
            0% {
                border-color: rgba(37,99,235,0.3);
                box-shadow: 0 0 0 0 rgba(37,99,235,0.1);
            }
            50% {
                border-color: rgba(37,99,235,0.8);
                box-shadow: 0 0 0 10px rgba(37,99,235,0);
            }
            100% {
                border-color: rgba(37,99,235,0.3);
                box-shadow: 0 0 0 0 rgba(37,99,235,0);
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card" style="text-align: center; max-width: 420px; padding: 48px 32px;">

            <div class="pending-icon-wrapper">
                <i class="bi bi-hourglass-split pending-icon"></i>
            </div>
            
            <h2 style="font-weight: 700; color: var(--text-primary); font-size: 22px; margin-bottom: 12px;">Account Pending Approval</h2>
            <p style="color: var(--text-secondary); font-size: 14.5px; line-height: 1.6; margin-bottom: 32px;">
                Thank you for registering at <strong>CSA - ITGC</strong>. Your account is currently in a <em>pending</em> status and is waiting for validation and approval from an Administrator.
            </p>

            <a href="{{ route('login') }}" class="btn-login" style="display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">
                <i class="bi bi-arrow-left me-2"></i>Back to Login
            </a>

            {{-- Footer Note --}}
            <div class="text-center mt-5">
                <small style="color: var(--text-muted); font-size: 11px;">
                    &copy; {{ date('Y') }} CSA - ITGC &nbsp;&middot;&nbsp; IT Governance &amp; Compliance
                </small>
            </div>

        </div>
    </div>
</body>
</html>
