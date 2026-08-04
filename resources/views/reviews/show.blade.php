@extends('layouts.app')
@section('title', 'Review Assessment - CSA ITGC')
@section('breadcrumb')
    <a href="{{ route('dashboard') }}">CSA - ITGC</a><span class="separator">/</span>
    <a href="{{ route('reviews.index') }}">Review</a><span class="separator">/</span>
    <span class="current">{{ $assessment->control->control_id_code }}</span>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="page-title">Review Assessment</h1>
        <p class="page-subtitle mb-0">{{ $assessment->control->control_id_code }} — {{ $assessment->assessmentPeriod->name ?? '' }}</p>
    </div>
    <span class="status-badge badge-{{ str_replace('_', '-', $assessment->status) }}" style="font-size: 14px; padding: 6px 16px;">{{ $assessment->status_label }}</span>
</div>

{{-- Control & Assessment Info (reuse show layout) --}}
<div class="detail-section">
    <div class="section-header"><i class="bi bi-shield-check"></i> Control Information</div>
    <div class="section-body">
        <div class="detail-grid">
            <div class="detail-item"><div class="detail-label">Control ID</div><div class="detail-value"><span class="control-id">{{ $assessment->control->control_id_code }}</span></div></div>
            <div class="detail-item"><div class="detail-label">IT Process</div><div class="detail-value">{{ $assessment->control->itProcess->name ?? '-' }}</div></div>
            <div class="detail-item"><div class="detail-label">Risk ID</div><div class="detail-value">{{ $assessment->control->risk->risk_id_code ?? '-' }}</div></div>
            <div class="detail-item"><div class="detail-label">ITGC Category</div><div class="detail-value">{{ $assessment->control->category->name ?? '-' }}</div></div>
            <div class="detail-item full-width"><div class="detail-label">IT Control</div><div class="detail-value">{{ $assessment->control->description }}</div></div>
        </div>
    </div>
</div>

<div class="detail-section">
    <div class="section-header"><i class="bi bi-clipboard-check"></i> Assessment Result</div>
    <div class="section-body">
        <div class="detail-grid">
            <div class="detail-item"><div class="detail-label">Result</div><div class="detail-value">@if($assessment->result)<span class="badge bg-{{ $assessment->result_color }} fs-6">{{ $assessment->result_label }}</span>@else - @endif</div></div>
            <div class="detail-item"><div class="detail-label">PIC</div><div class="detail-value">{{ $assessment->pic->name ?? '-' }}</div></div>
            <div class="detail-item full-width"><div class="detail-label">Description</div><div class="detail-value">{{ $assessment->description ?? '-' }}</div></div>
            <div class="detail-item full-width"><div class="detail-label">Notes</div><div class="detail-value">{{ $assessment->notes ?? '-' }}</div></div>
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
                <thead><tr><th>File</th><th>Description</th><th>Size</th><th>Action</th></tr></thead>
                <tbody>
                    @foreach($assessment->evidences as $e)
                    <tr>
                        <td><i class="bi bi-file-earmark me-1"></i>{{ $e->file_name }}</td>
                        <td>{{ $e->description ?? '-' }}</td>
                        <td>{{ $e->file_size_formatted }}</td>
                        <td><a href="{{ route('evidence.download', $e) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-download"></i></a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else <p class="text-muted">No evidence uploaded.</p>
        @endif
    </div>
</div>

{{-- Review Actions --}}
<div class="detail-section">
    <div class="section-header"><i class="bi bi-check2-square"></i> Review Actions</div>
    <div class="section-body">
        <div class="row g-3">
            <div class="col-md-6">
                <form method="POST" action="{{ route('reviews.approve', $assessment) }}" onsubmit="return confirm('Approve this assessment?')">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Review Comment (Optional)</label>
                        <textarea class="form-control" name="comment" rows="3" placeholder="Add review comments..."></textarea>
                    </div>
                    <button type="submit" class="btn-primary-custom w-100"><i class="bi bi-check-circle"></i> Approve & Forward to Approver</button>
                </form>
            </div>
            <div class="col-md-6">
                <form method="POST" action="{{ route('reviews.return', $assessment) }}" onsubmit="return confirm('Return this assessment?')">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Return Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="comment" rows="3" placeholder="Explain why this assessment is being returned..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-outline-danger w-100"><i class="bi bi-arrow-return-left"></i> Return to Creator</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- History --}}
@if($assessment->histories->count() > 0)
<div class="detail-section">
    <div class="section-header"><i class="bi bi-clock-history"></i> History</div>
    <div class="section-body">
        <div class="timeline">
            @foreach($assessment->histories as $h)
            <div class="timeline-item">
                <div class="timeline-content">
                    <strong>{{ $h->action_label }}</strong> <span class="text-muted">by {{ $h->user->name ?? 'System' }}</span>
                    @if($h->comment)<p class="mb-0 mt-1" style="font-size: 13px;">{{ $h->comment }}</p>@endif
                </div>
                <div class="timeline-date">{{ $h->created_at->format('d M Y H:i') }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection
