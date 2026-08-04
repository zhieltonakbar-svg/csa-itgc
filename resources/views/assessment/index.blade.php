@extends('layouts.app')

@section('title', 'Assessment — CSA - ITGC')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">CSA - ITGC</a>
    <span class="separator">/</span>
    <span class="current">Assessment</span>
@endsection

@section('content')

{{-- Page Header --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="page-title">Assessment</h1>
        <p class="page-subtitle mb-0">Control Self Assessment — ITGC Evaluation</p>
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
                <i class="bi bi-clipboard-check"></i>
            </div>
            <h3>Assessment</h3>
            <p class="placeholder-desc">
                Assessment functionality will be available in a future development phase.<br>
                This section will allow officers to submit control self assessments,<br>
                attach evidence, and track assessment status through the workflow.
            </p>
            <div class="placeholder-feature-list">
                <div class="feature-item"><i class="bi bi-check2-circle text-success me-2"></i>Self Assessment Submission</div>
                <div class="feature-item"><i class="bi bi-check2-circle text-success me-2"></i>Evidence Upload</div>
                <div class="feature-item"><i class="bi bi-check2-circle text-success me-2"></i>Assessment Workflow</div>
                <div class="feature-item"><i class="bi bi-check2-circle text-success me-2"></i>Status Tracking</div>
            </div>
        </div>
    </div>
</div>

@endsection
