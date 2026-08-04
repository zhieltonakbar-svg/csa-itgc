@extends('layouts.app')

@section('title', 'Evidence — CSA - ITGC')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">CSA - ITGC</a>
    <span class="separator">/</span>
    <span class="current">Evidence</span>
@endsection

@section('content')

{{-- Page Header --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="page-title">Evidence</h1>
        <p class="page-subtitle mb-0">Control Assessment Evidence Management</p>
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
                <i class="bi bi-folder2-open"></i>
            </div>
            <h3>Evidence</h3>
            <p class="placeholder-desc">
                Evidence management functionality will be available in a future development phase.<br>
                This section will allow users to upload, manage, and review supporting evidence<br>
                documents for IT General Control assessments.
            </p>
            <div class="placeholder-feature-list">
                <div class="feature-item"><i class="bi bi-check2-circle text-success me-2"></i>Evidence File Upload</div>
                <div class="feature-item"><i class="bi bi-check2-circle text-success me-2"></i>Document Management</div>
                <div class="feature-item"><i class="bi bi-check2-circle text-success me-2"></i>Evidence Review & Approval</div>
                <div class="feature-item"><i class="bi bi-check2-circle text-success me-2"></i>Evidence Download</div>
            </div>
        </div>
    </div>
</div>

@endsection
