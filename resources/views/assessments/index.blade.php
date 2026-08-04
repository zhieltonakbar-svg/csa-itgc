@extends('layouts.app')
@section('title', 'All Assessments - CSA ITGC')
@section('breadcrumb')
    <a href="{{ route('dashboard') }}">CSA - ITGC</a><span class="separator">/</span><span class="current">All Assessments</span>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="page-title">All Assessments</h1>
        <p class="page-subtitle mb-0">View all control self assessments</p>
    </div>
</div>

<div class="filter-bar">
    <form method="GET" action="{{ route('assessments.index') }}" class="d-flex align-items-end gap-3 flex-wrap w-100">
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
                <option value="">All</option>
                @foreach(['draft','submitted','in_review','returned','pending_approval','approved'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label>Search</label>
            <input type="text" name="search" class="form-control" placeholder="Control ID..." value="{{ request('search') }}">
        </div>
        <div><button type="submit" class="btn-primary-custom"><i class="bi bi-funnel"></i> Filter</button></div>
    </form>
</div>

<div class="content-card">
    <div class="card-body p-0">
        @if($assessments->count() > 0)
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Control ID</th>
                        <th>IT Process</th>
                        <th>Period</th>
                        <th>PIC</th>
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
                        <td>{{ $assessment->assessmentPeriod->name ?? '-' }}</td>
                        <td>{{ $assessment->pic->name ?? '-' }}</td>
                        <td>
                            @if($assessment->result)
                                <span class="badge bg-{{ $assessment->result_color }}">{{ $assessment->result_label }}</span>
                            @else <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td><span class="status-badge badge-{{ str_replace('_', '-', $assessment->status) }}">{{ $assessment->status_label }}</span></td>
                        <td style="font-size: 12px;">{{ $assessment->updated_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('assessments.show', $assessment) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-3 py-2">{{ $assessments->links() }}</div>
        @else
        <div class="empty-state">
            <i class="bi bi-clipboard-x"></i>
            <h5>No assessments found</h5>
        </div>
        @endif
    </div>
</div>
@endsection
