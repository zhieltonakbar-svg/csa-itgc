@extends('layouts.app')

@section('title', 'Master Data — CSA - ITGC')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">CSA - ITGC</a>
    <span class="separator">/</span>
    <span class="current">Master Data</span>
@endsection

@section('content')

{{-- Page Header --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="page-title">Master Data</h1>
        <p class="page-subtitle mb-0">System Master Data Management</p>
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
                <i class="bi bi-database"></i>
            </div>
            <h3>Master Data</h3>
            <p class="placeholder-desc">
                Master Data management functionality will be available in a future development phase.<br>
                This section will allow administrators to manage users, IT processes, controls,<br>
                categories, frequencies, applications, and assessment periods.
            </p>
            <div class="placeholder-feature-list">
                <div class="feature-item"><i class="bi bi-check2-circle text-success me-2"></i>User Management</div>
                <div class="feature-item"><i class="bi bi-check2-circle text-success me-2"></i>IT Process & Control Setup</div>
                <div class="feature-item"><i class="bi bi-check2-circle text-success me-2"></i>ITGC Categories & Frequencies</div>
                <div class="feature-item"><i class="bi bi-check2-circle text-success me-2"></i>Assessment Period Management</div>
                <div class="feature-item"><i class="bi bi-check2-circle text-success me-2"></i>PIC / UIC Assignment</div>
                <div class="feature-item"><i class="bi bi-check2-circle text-success me-2"></i>Application Registry</div>
            </div>
        </div>
    </div>
</div>

@endsection
