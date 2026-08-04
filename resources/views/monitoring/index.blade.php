@extends('layouts.app')

@section('title', 'Monitoring — CSA - ITGC')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">CSA - ITGC</a>
    <span class="separator">/</span>
    <span class="current">Monitoring</span>
@endsection

@section('content')

{{-- Page Header --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="page-title">Monitoring</h1>
        <p class="page-subtitle mb-0">Control Assessment Monitoring &amp; Analytics</p>
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
                <i class="bi bi-graph-up"></i>
            </div>
            <h3>Monitoring</h3>
            <p class="placeholder-desc">
                Monitoring functionality will be available in a future development phase.<br>
                This section will provide real-time visibility into assessment completion rates,<br>
                control compliance trends, and outstanding action items.
            </p>
            <div class="placeholder-feature-list">
                <div class="feature-item"><i class="bi bi-check2-circle text-success me-2"></i>Completion Rate Tracking</div>
                <div class="feature-item"><i class="bi bi-check2-circle text-success me-2"></i>Compliance Trend Analysis</div>
                <div class="feature-item"><i class="bi bi-check2-circle text-success me-2"></i>Outstanding Action Items</div>
                <div class="feature-item"><i class="bi bi-check2-circle text-success me-2"></i>Period Comparison Reports</div>
            </div>
        </div>
    </div>
</div>

@endsection
