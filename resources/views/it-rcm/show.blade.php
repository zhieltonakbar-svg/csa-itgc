@extends('layouts.app')
@section('title', $control->control_id_code . ' - CSA ITGC')
@section('breadcrumb')
    <a href="{{ route('dashboard') }}">CSA - ITGC</a><span class="separator">/</span>
    <a href="{{ route('it-rcm.index') }}">Risk & Control Matrix</a><span class="separator">/</span>
    <span class="current">{{ $control->control_id_code }}</span>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="page-title">{{ $control->control_id_code }}</h1>
        <p class="page-subtitle mb-0">Control Detail</p>
    </div>
    <a href="{{ route('it-rcm.index') }}" class="btn-outline-custom"><i class="bi bi-arrow-left"></i> Back to Matrix</a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="detail-section">
            <div class="section-header"><i class="bi bi-shield-check"></i> Control Information</div>
            <div class="section-body">
                <div class="detail-grid">
                    <div class="detail-item"><div class="detail-label">Control ID</div><div class="detail-value"><span class="control-id" style="font-size: 16px; padding: 6px 16px;">{{ $control->control_id_code }}</span></div></div>
                    <div class="detail-item"><div class="detail-label">Mapping to Telkom</div><div class="detail-value">{{ $control->mapping_telkom ?? '-' }}</div></div>
                    <div class="detail-item"><div class="detail-label">IT Process</div><div class="detail-value">{{ $control->itProcess->name ?? '-' }}</div></div>
                    <div class="detail-item"><div class="detail-label">Procedure</div><div class="detail-value">{{ $control->procedure->name ?? '-' }}</div></div>
                    <div class="detail-item full-width"><div class="detail-label">IT Control Description</div><div class="detail-value" style="line-height: 1.7;">{{ $control->description }}</div></div>
                    <div class="detail-item"><div class="detail-label">Control Type</div><div class="detail-value"><span class="badge bg-primary">{{ $control->control_type ?? '-' }}</span></div></div>
                    <div class="detail-item"><div class="detail-label">Key Control</div><div class="detail-value">@if($control->is_key_control)<span class="badge bg-success">Yes</span>@else<span class="badge bg-secondary">No</span>@endif</div></div>
                </div>
            </div>
        </div>

        <div class="detail-section">
            <div class="section-header"><i class="bi bi-exclamation-triangle"></i> Risk Information</div>
            <div class="section-body">
                <div class="detail-grid">
                    <div class="detail-item"><div class="detail-label">Risk ID</div><div class="detail-value"><span class="badge bg-danger fs-6">{{ $control->risk->risk_id_code ?? '-' }}</span></div></div>
                    <div class="detail-item full-width"><div class="detail-label">IT Risk</div><div class="detail-value">{{ $control->risk->description ?? '-' }}</div></div>
                </div>
            </div>
        </div>

        @if($control->deliverable_document || $control->ey_cap_index)
        <div class="detail-section">
            <div class="section-header"><i class="bi bi-file-earmark-text"></i> Documents & References</div>
            <div class="section-body">
                <div class="detail-grid">
                    @if($control->deliverable_document)
                    <div class="detail-item full-width"><div class="detail-label">Deliverable Document</div><div class="detail-value" style="white-space: pre-line;">{{ $control->deliverable_document }}</div></div>
                    @endif
                    @if($control->ey_cap_index)
                    <div class="detail-item full-width"><div class="detail-label">EY CAP Index</div><div class="detail-value" style="white-space: pre-line;">{{ $control->ey_cap_index }}</div></div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="detail-section">
            <div class="section-header"><i class="bi bi-tags"></i> Classification</div>
            <div class="section-body">
                <div class="mb-3"><div class="detail-label">ITGC Category</div><div class="detail-value"><span class="badge bg-success fs-6">{{ $control->category->name ?? '-' }}</span></div></div>
                <div class="mb-3"><div class="detail-label">Control Frequency</div><div class="detail-value"><span class="badge bg-info fs-6">{{ $control->frequency->name ?? '-' }}</span></div></div>
                <div class="mb-3"><div class="detail-label">COBIT 2019 Domain</div><div class="detail-value" style="white-space: pre-line; font-size: 12.5px;">{{ $control->cobit_domain ?? '-' }}</div></div>
                <div class="mb-3"><div class="detail-label">COBIT 2019 Reference</div><div class="detail-value" style="font-size: 12.5px;">{{ $control->cobit_reference ?? '-' }}</div></div>
                <div class="mb-3"><div class="detail-label">UIC</div><div class="detail-value">{{ $control->uic ?? '-' }}</div></div>
                <div class="mb-0"><div class="detail-label">Applications</div><div class="detail-value">
                    @forelse($control->applications as $app)
                        <span class="badge bg-outline-secondary border me-1">{{ $app->name }}</span>
                    @empty - @endforelse
                </div></div>
            </div>
        </div>

        {{-- Assessment History for this control --}}
        @if($control->assessments->count() > 0)
        <div class="detail-section">
            <div class="section-header"><i class="bi bi-clock-history"></i> Assessment History</div>
            <div class="section-body p-0">
                <table class="data-table">
                    <thead><tr><th>Period</th><th>Status</th><th>PIC</th></tr></thead>
                    <tbody>
                        @foreach($control->assessments->sortByDesc('created_at') as $assessment)
                        <tr>
                            <td>{{ $assessment->assessmentPeriod->name ?? '-' }}</td>
                            <td><span class="status-badge badge-{{ str_replace('_', '-', $assessment->status) }}" style="font-size: 10px;">{{ $assessment->status_label }}</span></td>
                            <td style="font-size: 12px;">{{ $assessment->pic->name ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
