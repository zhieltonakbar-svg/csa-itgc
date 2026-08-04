@extends('layouts.app')
@section('title', 'My Assessments - CSA ITGC')
@section('breadcrumb')
    <a href="{{ route('dashboard') }}">CSA - ITGC</a>
    <span class="separator">/</span>
    <span class="current">My Assessments</span>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="page-title">My Assessments</h1>
        <p class="page-subtitle mb-0">Control Self Assessments assigned to you</p>
    </div>
</div>

{{-- Filters --}}
<div class="filter-bar">
    <form method="GET" action="{{ route('assessments.my') }}" class="d-flex align-items-end gap-3 flex-wrap w-100">
        <div class="filter-group">
            <label>Period</label>
            <select name="period_id" class="form-select">
                <option value="">All Periods</option>
                @foreach($periods as $period)
                    <option value="{{ $period->id }}" {{ request('period_id') == $period->id ? 'selected' : '' }}>{{ $period->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label>Status</label>
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                <option value="in_review" {{ request('status') == 'in_review' ? 'selected' : '' }}>In Review</option>
                <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
                <option value="pending_approval" {{ request('status') == 'pending_approval' ? 'selected' : '' }}>Pending Approval</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
            </select>
        </div>
        <div class="filter-group">
            <label>Search</label>
            <input type="text" name="search" class="form-control" placeholder="Search Control ID..." value="{{ request('search') }}">
        </div>
        <div>
            <button type="submit" class="btn-primary-custom"><i class="bi bi-funnel"></i> Filter</button>
        </div>
        @if(request()->hasAny(['period_id', 'status', 'search']))
        <div>
            <a href="{{ route('assessments.my') }}" class="btn-outline-custom"><i class="bi bi-x-circle"></i> Clear</a>
        </div>
        @endif
    </form>
</div>

{{-- Pending Controls (need assessment) --}}
@if(request('period_id') && $pendingControls->count() > 0)
<div class="content-card mb-4">
    <div class="card-header">
        <h5><i class="bi bi-exclamation-circle text-warning me-2"></i>Pending Assessment ({{ $pendingControls->count() }})</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            @foreach($pendingControls as $control)
            <div class="col-lg-6 col-xl-4">
                <div class="control-card">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <span class="control-id">{{ $control->control_id_code }}</span>
                        <span class="status-badge badge-draft">Pending Assessment</span>
                    </div>
                    <div class="control-meta">
                        <div class="meta-item">
                            <div class="meta-label">IT Process</div>
                            <div class="meta-value">{{ $control->itProcess->name ?? '-' }}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">ITGC Category</div>
                            <div class="meta-value">{{ $control->category->name ?? '-' }}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Frequency</div>
                            <div class="meta-value">{{ $control->frequency->name ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="mt-3 d-flex gap-2">
                        <a href="{{ route('it-rcm.show', $control) }}" class="btn-outline-custom" style="font-size: 12px; padding: 5px 12px;"><i class="bi bi-eye"></i> View Control</a>
                        <a href="{{ route('assessments.create', ['control_id' => $control->id, 'period_id' => request('period_id')]) }}" class="btn-primary-custom" style="font-size: 12px; padding: 5px 12px;"><i class="bi bi-clipboard-plus"></i> Assessment</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- Assessment List --}}
<div class="content-card">
    <div class="card-header">
        <h5>Assessment History</h5>
    </div>
    <div class="card-body p-0">
        @if($assessments->count() > 0)
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Control ID</th>
                        <th>IT Process</th>
                        <th>ITGC Category</th>
                        <th>Period</th>
                        <th>Result</th>
                        <th>Status</th>
                        <th>Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assessments as $assessment)
                    <tr>
                        <td><span class="control-id" style="font-size: 11px; padding: 2px 8px;">{{ $assessment->control->control_id_code }}</span></td>
                        <td>{{ $assessment->control->itProcess->name ?? '-' }}</td>
                        <td>{{ $assessment->control->category->name ?? '-' }}</td>
                        <td>{{ $assessment->assessmentPeriod->name ?? '-' }}</td>
                        <td>
                            @if($assessment->result)
                                <span class="badge bg-{{ $assessment->result_color }}">{{ $assessment->result_label }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td><span class="status-badge badge-{{ str_replace('_', '-', $assessment->status) }}">{{ $assessment->status_label }}</span></td>
                        <td style="font-size: 12px; color: var(--text-muted);">{{ $assessment->updated_at->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('assessments.show', $assessment) }}" class="btn btn-sm btn-outline-secondary" title="View"><i class="bi bi-eye"></i></a>
                                @if($assessment->canBeEdited())
                                <a href="{{ route('assessments.edit', $assessment) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-3 py-2">
            {{ $assessments->links() }}
        </div>
        @else
        <div class="empty-state">
            <i class="bi bi-clipboard-x"></i>
            <h5>No assessments found</h5>
            <p>Select a period and start your control self assessment.</p>
        </div>
        @endif
    </div>
</div>
@endsection
