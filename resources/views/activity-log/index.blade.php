@extends('layouts.app')

@section('title', 'Activity Log — CSA - ITGC')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">CSA - ITGC</a>
    <span class="separator">/</span>
    <span class="current">Activity Log</span>
@endsection

@section('content')

{{-- Page Header --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="page-title">Activity Log</h1>
        <p class="page-subtitle mb-0">System Activity &amp; Audit Trail</p>
    </div>
    <span class="badge" style="background: var(--primary-light); color: var(--primary); font-size: 12px; padding: 8px 16px; border-radius: 20px; font-weight: 500;">
        <i class="bi bi-clock me-1"></i>Coming Soon
    </span>
</div>

{{-- Placeholder Content --}}
<div class="content-card">
    <div class="card-body" style="padding: 80px 40px;">
        <div class="placeholder-page-state">
            <div class="placeholder-icon-wrap">
                <i class="bi bi-clock-history"></i>
            </div>
            <h3>Activity Log</h3>
            <p class="placeholder-desc">
                Activity Log functionality will be available in a future development phase.<br>
                This section will provide a complete audit trail of all system activities,<br>
                user actions, assessment submissions, and administrative changes.
            </p>
            <div class="placeholder-feature-list">
                <div class="feature-item"><i class="bi bi-check2-circle text-success me-2"></i>User Activity Tracking</div>
                <div class="feature-item"><i class="bi bi-check2-circle text-success me-2"></i>Assessment Action Logs</div>
                <div class="feature-item"><i class="bi bi-check2-circle text-success me-2"></i>Administrative Audit Trail</div>
                <div class="feature-item"><i class="bi bi-check2-circle text-success me-2"></i>Export & Reporting</div>
            </div>
        </div>
    </div>
</div>

@endsection
