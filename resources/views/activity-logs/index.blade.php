@extends('layouts.app')
@section('title', 'Activity Log - CSA ITGC')
@section('breadcrumb')
    <a href="{{ route('dashboard') }}">CSA - ITGC</a><span class="separator">/</span><span class="current">Activity Log</span>
@endsection

@section('content')
<div class="mb-4"><h1 class="page-title">Activity Log</h1><p class="page-subtitle mb-0">System audit trail</p></div>

<div class="filter-bar">
    <form method="GET" class="d-flex align-items-end gap-3 flex-wrap w-100">
        <div class="filter-group"><label>Search</label><input type="text" name="search" class="form-control" placeholder="Search activity..." value="{{ request('search') }}"></div>
        <div class="filter-group"><label>From</label><input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}"></div>
        <div class="filter-group"><label>To</label><input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}"></div>
        <div><button type="submit" class="btn-primary-custom"><i class="bi bi-funnel"></i> Filter</button></div>
    </form>
</div>

<div class="content-card">
    <div class="card-body p-0">
        <div class="table-wrapper">
            <table class="data-table">
                <thead><tr><th>User</th><th>Activity</th><th>Object</th><th>Description</th><th>IP</th><th>Date & Time</th></tr></thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->user->name ?? 'System' }}</td>
                        <td><span class="badge bg-light text-dark">{{ $log->activity }}</span></td>
                        <td style="font-size: 12px;">{{ $log->object_type }}{{ $log->object_id ? " #{$log->object_id}" : '' }}</td>
                        <td style="font-size: 12.5px;">{{ Str::limit($log->description, 60) }}</td>
                        <td style="font-size: 12px;">{{ $log->ip_address }}</td>
                        <td style="font-size: 12px;">{{ $log->created_at->format('d M Y H:i:s') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No activity logs.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-3 py-2">{{ $logs->links() }}</div>
    </div>
</div>
@endsection
