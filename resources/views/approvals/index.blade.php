@extends('layouts.app')
@section('title', 'Approval - CSA ITGC')
@section('breadcrumb')
    <a href="{{ route('dashboard') }}">CSA - ITGC</a><span class="separator">/</span><span class="current">Approval</span>
@endsection

@section('content')
<div class="mb-4"><h1 class="page-title">Approval</h1><p class="page-subtitle mb-0">Assessments pending your approval</p></div>

<div class="filter-bar">
    <form method="GET" class="d-flex align-items-end gap-3 flex-wrap w-100">
        <div class="filter-group">
            <label>Period</label>
            <select name="period_id" class="form-select"><option value="">All</option>
                @foreach($periods as $p)<option value="{{ $p->id }}" {{ request('period_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>@endforeach
            </select>
        </div>
        <div class="filter-group"><label>Search</label><input type="text" name="search" class="form-control" placeholder="Control ID..." value="{{ request('search') }}"></div>
        <div><button type="submit" class="btn-primary-custom"><i class="bi bi-funnel"></i> Filter</button></div>
    </form>
</div>

<div class="content-card">
    <div class="card-body p-0">
        @if($assessments->count() > 0)
        <div class="table-wrapper">
            <table class="data-table">
                <thead><tr><th>Control ID</th><th>IT Process</th><th>Period</th><th>PIC</th><th>Reviewer</th><th>Result</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @foreach($assessments as $a)
                    <tr>
                        <td><span class="control-id" style="font-size: 11px; padding: 2px 8px;">{{ $a->control->control_id_code }}</span></td>
                        <td>{{ $a->control->itProcess->name ?? '-' }}</td>
                        <td>{{ $a->assessmentPeriod->name ?? '-' }}</td>
                        <td>{{ $a->pic->name ?? '-' }}</td>
                        <td>{{ $a->reviewer->name ?? '-' }}</td>
                        <td>@if($a->result)<span class="badge bg-{{ $a->result_color }}">{{ $a->result_label }}</span>@else - @endif</td>
                        <td><span class="status-badge badge-{{ str_replace('_', '-', $a->status) }}">{{ $a->status_label }}</span></td>
                        <td><a href="{{ route('approvals.show', $a) }}" class="btn-primary-custom" style="font-size: 12px; padding: 4px 12px;"><i class="bi bi-check2-square"></i> Approve</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-3 py-2">{{ $assessments->links() }}</div>
        @else
        <div class="empty-state"><i class="bi bi-inbox"></i><h5>No assessments pending approval</h5></div>
        @endif
    </div>
</div>
@endsection
