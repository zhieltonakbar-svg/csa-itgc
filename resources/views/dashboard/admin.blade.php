@extends('layouts.app')

@section('title', 'Admin Dashboard — CSA - ITGC')

@push('styles')
<style>
    /* Premium Admin Dashboard Styling */
    .admin-dashboard {
        padding: 1rem 0;
    }

    /* Welcome Hero */
    .welcome-hero {
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
        border-radius: 16px;
        padding: 2.5rem 3rem;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 2rem;
        align-items: center;
    }
    .welcome-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(25, 135, 84, 0.15) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .welcome-hero-content {
        position: relative;
        z-index: 1;
        text-align: left;
    }
    .welcome-hero-label {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: #9ca3af;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }
    .welcome-hero-name {
        font-size: 2.2rem;
        font-weight: 800;
        margin-bottom: 0.25rem;
        background: linear-gradient(to right, #ffffff, #d1d5db);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .welcome-hero-sub {
        font-size: 1rem;
        color: #d1d5db;
        margin-bottom: 0;
    }
    .welcome-hero-actions {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        min-width: 200px;
    }
    .hero-btn {
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.25s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        gap: 0.5rem;
    }
    .hero-btn-primary {
        background: #198754;
        color: #ffffff;
        border: 1px solid #198754;
    }
    .hero-btn-primary:hover {
        background: #146c43;
        transform: translateY(-2px);
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3);
    }
    .hero-btn-secondary {
        background: rgba(255, 255, 255, 0.1);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(4px);
    }
    .hero-btn-secondary:hover {
        background: rgba(255, 255, 255, 0.15);
        color: #ffffff;
        transform: translateY(-2px);
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: #ffffff;
        border-radius: 14px;
        padding: 1.5rem;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        border-color: rgba(25, 135, 84, 0.2);
    }
    .stat-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #198754, #20c997);
        opacity: 0;
        transition: opacity 0.3s;
    }
    .stat-card:hover::after {
        opacity: 1;
    }
    
    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    .stat-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0;
    }
    .stat-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    
    .stat-icon.primary { background: rgba(25, 135, 84, 0.1); color: #198754; }
    .stat-icon.blue { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .stat-icon.purple { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }
    .stat-icon.amber { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        color: #111827;
        line-height: 1;
        margin-bottom: 0.5rem;
    }
    .stat-footer {
        font-size: 0.8rem;
        color: #9ca3af;
        margin-top: auto;
    }

    /* Controls Progress Section */
    .controls-overview {
        background: #ffffff;
        border-radius: 16px;
        padding: 2rem;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .controls-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .controls-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }
    
    .progress-bars-container {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }
    .progress-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    .progress-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.9rem;
        font-weight: 600;
    }
    .progress-item-header .label {
        color: #4b5563;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .progress-item-header .value {
        color: #111827;
    }
    
    .progress-bar-bg {
        height: 10px;
        background: #f3f4f6;
        border-radius: 999px;
        overflow: hidden;
    }
    .progress-bar-fill {
        height: 100%;
        border-radius: 999px;
        transition: width 1s ease-out;
    }
    .fill-success { background: linear-gradient(90deg, #10b981, #34d399); }
    .fill-warning { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .fill-danger { background: linear-gradient(90deg, #ef4444, #f87171); }

    /* Dot indicators */
    .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
    }
    .dot-success { background: #10b981; }
    .dot-warning { background: #f59e0b; }
    .dot-danger { background: #ef4444; }

    @media (max-width: 768px) {
        .welcome-hero {
            grid-template-columns: 1fr;
            padding: 2rem;
            gap: 1.5rem;
        }
    }
</style>
@endpush

@section('content')

@php 
    $user = auth()->user(); 
    $totalControls = $stats['controls'] > 0 ? $stats['controls'] : 1; // Prevent division by zero
    $pctCompleted = round(($stats['completed'] / $totalControls) * 100);
    $pctOngoing = round(($stats['ongoing'] / $totalControls) * 100);
    $pctNotStarted = round(($stats['not_started'] / $totalControls) * 100);
@endphp

<div class="admin-dashboard">

    {{-- Welcome Hero --}}
    <div class="welcome-hero">
        <div class="welcome-hero-content">
            <p class="welcome-hero-label">SYSTEM ADMINISTRATOR</p>
            <h2 class="welcome-hero-name">Hello, {{ $user ? $user->name : 'Admin' }}</h2>
            <p class="welcome-hero-sub">Welcome to the CSA - ITGC Management Dashboard.</p>
        </div>
        <div class="welcome-hero-actions">
            <a href="{{ route('applications.index') }}" class="hero-btn hero-btn-primary">
                <i class="bi bi-grid-fill"></i> Manage Applications
            </a>
            <a href="{{ route('users.index') }}" class="hero-btn hero-btn-secondary">
                <i class="bi bi-people-fill"></i> Manage Users
            </a>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <h3 class="stat-title">Total Users</h3>
                <div class="stat-icon blue"><i class="bi bi-people-fill"></i></div>
            </div>
            <div class="stat-value">{{ number_format($stats['users']) }}</div>
            <div class="stat-footer">Registered in system</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <h3 class="stat-title">UPTIs</h3>
                <div class="stat-icon purple"><i class="bi bi-diagram-3-fill"></i></div>
            </div>
            <div class="stat-value">{{ number_format($stats['uptis']) }}</div>
            <div class="stat-footer">Business Units</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <h3 class="stat-title">Applications</h3>
                <div class="stat-icon primary"><i class="bi bi-window-stack"></i></div>
            </div>
            <div class="stat-value">{{ number_format($stats['applications']) }}</div>
            <div class="stat-footer">Active Applications</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <h3 class="stat-title">IT Categories</h3>
                <div class="stat-icon amber"><i class="bi bi-tags-fill"></i></div>
            </div>
            <div class="stat-value">{{ number_format($stats['categories']) }}</div>
            <div class="stat-footer">Defined Categories</div>
        </div>
    </div>

    {{-- Controls Overview --}}
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="controls-overview h-100">
                <div class="controls-header">
                    <h3 class="controls-title">Control Status Overview</h3>
                    <span class="badge bg-light text-dark border"><i class="bi bi-check2-all text-success"></i> {{ number_format($stats['controls']) }} Total Controls</span>
                </div>
                
                <div class="progress-bars-container">
                    
                    {{-- Completed --}}
                    <div class="progress-item">
                        <div class="progress-item-header">
                            <span class="label"><span class="dot dot-success"></span> Completed</span>
                            <span class="value">{{ number_format($stats['completed']) }} <span class="text-muted fw-normal ms-1">({{ $pctCompleted }}%)</span></span>
                        </div>
                        <div class="progress-bar-bg">
                            <div class="progress-bar-fill fill-success" style="width: 0%" data-width="{{ $pctCompleted }}%"></div>
                        </div>
                    </div>

                    {{-- Ongoing --}}
                    <div class="progress-item">
                        <div class="progress-item-header">
                            <span class="label"><span class="dot dot-warning"></span> Ongoing Review / Approval</span>
                            <span class="value">{{ number_format($stats['ongoing']) }} <span class="text-muted fw-normal ms-1">({{ $pctOngoing }}%)</span></span>
                        </div>
                        <div class="progress-bar-bg">
                            <div class="progress-bar-fill fill-warning" style="width: 0%" data-width="{{ $pctOngoing }}%"></div>
                        </div>
                    </div>

                    {{-- Not Started --}}
                    <div class="progress-item">
                        <div class="progress-item-header">
                            <span class="label"><span class="dot dot-danger"></span> Not Started / Returned</span>
                            <span class="value">{{ number_format($stats['not_started']) }} <span class="text-muted fw-normal ms-1">({{ $pctNotStarted }}%)</span></span>
                        </div>
                        <div class="progress-bar-bg">
                            <div class="progress-bar-fill fill-danger" style="width: 0%" data-width="{{ $pctNotStarted }}%"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        
        <div class="col-lg-4 mb-4">
            <div class="controls-overview h-100 d-flex flex-column justify-content-center text-center">
                <div class="mb-3">
                    <i class="bi bi-shield-check text-success" style="font-size: 3rem;"></i>
                </div>
                <h4 class="mb-2" style="font-weight: 700; color: #1f2937;">System Status</h4>
                <p class="text-muted mb-4" style="font-size: 0.9rem;">The system is running smoothly. All services are fully operational.</p>
                <div class="d-grid gap-2">
                     <a href="#" class="btn btn-outline-success btn-sm fw-semibold">View System Logs</a>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Animate progress bars on load
        setTimeout(() => {
            const fills = document.querySelectorAll('.progress-bar-fill');
            fills.forEach(fill => {
                fill.style.width = fill.getAttribute('data-width');
            });
        }, 300);
    });
</script>
@endpush
