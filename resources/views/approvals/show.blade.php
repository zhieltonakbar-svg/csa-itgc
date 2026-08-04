@extends('layouts.app')
@section('title', 'Approve Assessment - CSA ITGC')
@section('breadcrumb')
    <a href="{{ route('dashboard') }}">CSA - ITGC</a><span class="separator">/</span>
    <a href="{{ route('approvals.index') }}">Approval</a><span class="separator">/</span>
    <span class="current">{{ $assessment->control->control_id_code }}</span>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="page-title">Approve Assessment</h1>
        <p class="page-subtitle mb-0">{{ $assessment->control->control_id_code }} — {{ $assessment->assessmentPeriod->name ?? '' }}</p>
    </div>
    <span class="status-badge badge-{{ str_replace('_', '-', $assessment->status) }}" style="font-size: 14px; padding: 6px 16px;">{{ $assessment->status_label }}</span>
</div>

<div class="detail-section">
    <div class="section-header"><i class="bi bi-shield-check"></i> Control & Assessment</div>
    <div class="section-body">
        <div class="detail-grid">
            <div class="detail-item"><div class="detail-label">Control ID</div><div class="detail-value"><span class="control-id">{{ $assessment->control->control_id_code }}</span></div></div>
            <div class="detail-item"><div class="detail-label">IT Process</div><div class="detail-value">{{ $assessment->control->itProcess->name ?? '-' }}</div></div>
            <div class="detail-item"><div class="detail-label">Risk</div><div class="detail-value">{{ $assessment->control->risk->risk_id_code ?? '-' }} — {{ Str::limit($assessment->control->risk->description ?? '', 80) }}</div></div>
            <div class="detail-item"><div class="detail-label">Result</div><div class="detail-value">@if($assessment->result)<span class="badge bg-{{ $assessment->result_color }} fs-6">{{ $assessment->result_label }}</span>@else - @endif</div></div>
            <div class="detail-item full-width"><div class="detail-label">IT Control</div><div class="detail-value">{{ $assessment->control->description }}</div></div>
            <div class="detail-item"><div class="detail-label">Creator</div><div class="detail-value">{{ $assessment->pic->name ?? '-' }}</div></div>
            <div class="detail-item"><div class="detail-label">Reviewer</div><div class="detail-value">{{ $assessment->reviewer->name ?? '-' }}</div></div>
            <div class="detail-item full-width"><div class="detail-label">Description</div><div class="detail-value">{{ $assessment->description ?? '-' }}</div></div>
            <div class="detail-item full-width"><div class="detail-label">Recommendation</div><div class="detail-value">{{ $assessment->recommendation ?? '-' }}</div></div>
        </div>
    </div>
</div>

{{-- Evidence --}}
<div class="detail-section">
    <div class="section-header"><i class="bi bi-paperclip"></i> Evidence ({{ $assessment->evidences->count() }})</div>
    <div class="section-body">
        @if($assessment->evidences->count() > 0)
        <div class="table-wrapper">
            <table class="data-table">
                <thead><tr><th>File</th><th>Description</th><th>Action</th></tr></thead>
                <tbody>
                    @foreach($assessment->evidences as $e)
                    <tr><td><i class="bi bi-file-earmark me-1"></i>{{ $e->file_name }}</td><td>{{ $e->description ?? '-' }}</td><td><a href="{{ route('evidence.download', $e) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-download"></i></a></td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else <p class="text-muted">No evidence.</p>
        @endif
    </div>
</div>

{{-- Reviewer Comments (from history) --}}
@php $reviewComment = $assessment->histories->where('action', 'reviewed')->first(); @endphp
@if($reviewComment)
<div class="detail-section">
    <div class="section-header"><i class="bi bi-chat-dots"></i> Reviewer Comment</div>
    <div class="section-body">
        <p class="mb-1"><strong>{{ $reviewComment->user->name ?? 'Reviewer' }}</strong> <span class="text-muted">— {{ $reviewComment->created_at->format('d M Y H:i') }}</span></p>
        <p class="mb-0">{{ $reviewComment->comment }}</p>
    </div>
</div>
@endif

{{-- Approval Actions --}}
<div class="detail-section">
    <div class="section-header"><i class="bi bi-check2-square"></i> Approval Actions</div>
    <div class="section-body">
        <div class="row g-3">
            <div class="col-md-6">
                <form method="POST" action="{{ route('approvals.approve', $assessment) }}" onsubmit="return confirm('Approve this assessment?')">
                    @csrf
                    <div class="mb-3"><label class="form-label fw-semibold">Approval Comment</label><textarea class="form-control" name="comment" rows="3" placeholder="Add comment..."></textarea></div>
                    <button type="submit" class="btn-primary-custom w-100"><i class="bi bi-check-circle"></i> Approve Assessment</button>
                </form>
            </div>
            <div class="col-md-6">
                <form method="POST" action="{{ route('approvals.return', $assessment) }}" onsubmit="return confirm('Return this assessment?')">
                    @csrf
                    <div class="mb-3"><label class="form-label fw-semibold">Return Reason <span class="text-danger">*</span></label><textarea class="form-control" name="comment" rows="3" required placeholder="Reason..."></textarea></div>
                    <button type="submit" class="btn btn-outline-danger w-100"><i class="bi bi-arrow-return-left"></i> Return to Creator</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
