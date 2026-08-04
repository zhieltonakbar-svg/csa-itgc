@extends('layouts.app')
@section('title', 'Assessment Detail - CSA ITGC')
@section('breadcrumb')
    <a href="{{ route('dashboard') }}">CSA - ITGC</a><span class="separator">/</span>
    <a href="{{ route('assessments.my') }}">Assessment</a><span class="separator">/</span>
    <span class="current">{{ $assessment->control->control_id_code }}</span>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="page-title">Assessment Detail</h1>
        <p class="page-subtitle mb-0">{{ $assessment->control->control_id_code }} — {{ $assessment->assessmentPeriod->name ?? '' }}</p>
    </div>
    <div class="d-flex gap-2">
        <span class="status-badge badge-{{ str_replace('_', '-', $assessment->status) }}" style="font-size: 14px; padding: 6px 16px;">{{ $assessment->status_label }}</span>
        @if($assessment->canBeEdited() && $assessment->pic_user_id === auth()->id())
            <a href="{{ route('assessments.edit', $assessment) }}" class="btn-primary-custom"><i class="bi bi-pencil"></i> Edit</a>
        @endif
    </div>
</div>

{{-- Control Info --}}
<div class="detail-section">
    <div class="section-header"><i class="bi bi-shield-check"></i> Control Information</div>
    <div class="section-body">
        <div class="detail-grid">
            <div class="detail-item"><div class="detail-label">Control ID</div><div class="detail-value"><span class="control-id">{{ $assessment->control->control_id_code }}</span></div></div>
            <div class="detail-item"><div class="detail-label">Mapping to Telkom</div><div class="detail-value">{{ $assessment->control->mapping_telkom ?? '-' }}</div></div>
            <div class="detail-item"><div class="detail-label">IT Process</div><div class="detail-value">{{ $assessment->control->itProcess->name ?? '-' }}</div></div>
            <div class="detail-item"><div class="detail-label">Risk ID</div><div class="detail-value">{{ $assessment->control->risk->risk_id_code ?? '-' }}</div></div>
            <div class="detail-item full-width"><div class="detail-label">IT Control</div><div class="detail-value">{{ $assessment->control->description }}</div></div>
            <div class="detail-item"><div class="detail-label">Control Type</div><div class="detail-value">{{ $assessment->control->control_type ?? '-' }}</div></div>
            <div class="detail-item"><div class="detail-label">ITGC Category</div><div class="detail-value">{{ $assessment->control->category->name ?? '-' }}</div></div>
            <div class="detail-item"><div class="detail-label">Frequency</div><div class="detail-value">{{ $assessment->control->frequency->name ?? '-' }}</div></div>
            <div class="detail-item"><div class="detail-label">Key Control</div><div class="detail-value">{{ $assessment->control->is_key_control ? 'Yes' : 'No' }}</div></div>
            <div class="detail-item"><div class="detail-label">UIC</div><div class="detail-value">{{ $assessment->control->uic ?? '-' }}</div></div>
            <div class="detail-item"><div class="detail-label">PIC</div><div class="detail-value">{{ $assessment->pic->name ?? '-' }}</div></div>
        </div>
    </div>
</div>

{{-- Assessment Result --}}
<div class="detail-section">
    <div class="section-header"><i class="bi bi-clipboard-check"></i> Assessment Result</div>
    <div class="section-body">
        <div class="detail-grid">
            <div class="detail-item"><div class="detail-label">Result</div><div class="detail-value">@if($assessment->result)<span class="badge bg-{{ $assessment->result_color }} fs-6">{{ $assessment->result_label }}</span>@else <span class="text-muted">Not yet assessed</span> @endif</div></div>
            <div class="detail-item"><div class="detail-label">Assessment Period</div><div class="detail-value">{{ $assessment->assessmentPeriod->name ?? '-' }}</div></div>
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
                <thead><tr><th>File Name</th><th>Description</th><th>Size</th><th>Uploaded By</th><th>Date</th><th>Actions</th></tr></thead>
                <tbody>
                    @foreach($assessment->evidences as $evidence)
                    <tr>
                        <td><i class="bi bi-file-earmark me-1"></i>{{ $evidence->file_name }}</td>
                        <td>{{ $evidence->description ?? '-' }}</td>
                        <td>{{ $evidence->file_size_formatted }}</td>
                        <td>{{ $evidence->uploader->name ?? '-' }}</td>
                        <td>{{ $evidence->created_at->format('d M Y H:i') }}</td>
                        <td><a href="{{ route('evidence.download', $evidence) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-download"></i></a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-muted mb-0">No evidence uploaded.</p>
        @endif
    </div>
</div>

{{-- Assessment History --}}
<div class="detail-section">
    <div class="section-header"><i class="bi bi-clock-history"></i> Assessment History</div>
    <div class="section-body">
        @if($assessment->histories->count() > 0)
        <div class="timeline">
            @foreach($assessment->histories as $history)
            <div class="timeline-item">
                <div class="timeline-content">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <strong>{{ $history->action_label }}</strong>
                            <span class="text-muted ms-2">by {{ $history->user->name ?? 'System' }}</span>
                        </div>
                        <span class="status-badge badge-{{ str_replace('_', '-', $history->to_status) }}" style="font-size: 10px; padding: 2px 8px;">{{ ucwords(str_replace('_', ' ', $history->to_status)) }}</span>
                    </div>
                    @if($history->comment)
                    <p class="mb-0 mt-1" style="font-size: 13px;">{{ $history->comment }}</p>
                    @endif
                </div>
                <div class="timeline-date">{{ $history->created_at->format('d M Y H:i') }}</div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-muted mb-0">No history records.</p>
        @endif
    </div>
</div>
@endsection
