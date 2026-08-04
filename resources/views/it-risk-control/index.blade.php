@extends('layouts.app')

@section('title', 'IT Risk & Control — CSA - ITGC')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">CSA - ITGC</a>
    <span class="separator">/</span>
    <span class="current">IT Risk &amp; Control</span>
@endsection

@section('content')

{{-- Page Header --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="page-title">IT Category</h1>
        <p class="page-subtitle mb-0">IT Category — ITGC Framework</p>
    </div>
    <span class="badge" style="background: var(--primary-light); color: var(--primary); font-size: 12px; padding: 8px 16px; border-radius: 20px; font-weight: 500;">
        <i class="bi bi-clock me-1"></i>Coming Soon
    </span>
</div>

{{-- Placeholder Content --}}
<div class="content-card">
    <div class="card-body" style="padding: 80px 40px;">
        <div class="placeholder-page-state">

            <p class="placeholder-desc">
                IT Category functionality will be available in a future development phase.<br>
                This section will display the full ITGC control framework, risk categories,<br>
                and control ownership across IT processes and applications.
            </p>
            <div class="placeholder-feature-list">
                <div class="feature-item"><i class="bi bi-check2-circle text-success me-2"></i>IT Category</div>
                <div class="feature-item"><i class="bi bi-check2-circle text-success me-2"></i>ITGC Category Management</div>
                <div class="feature-item"><i class="bi bi-check2-circle text-success me-2"></i>Control Frequency Tracking</div>
                <div class="feature-item"><i class="bi bi-check2-circle text-success me-2"></i>IT Process Mapping</div>
            </div>
        </div>
    </div>
</div>

@endsection
