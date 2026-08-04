@extends('layouts.app')
@section('title', 'IT Risk & Control Matrix - CSA ITGC')
@section('breadcrumb')
    <a href="{{ route('dashboard') }}">CSA - ITGC</a><span class="separator">/</span><span class="current">Risk & Control Matrix</span>
@endsection

@section('content')
<div class="mb-4">
    <h1 class="page-title">IT Risk & Control Matrix</h1>
    <p class="page-subtitle mb-0">IT Risk & Control Matrix V1.0</p>
</div>

<div class="filter-bar">
    <form method="GET" action="{{ route('it-rcm.index') }}" class="d-flex align-items-end gap-3 flex-wrap w-100">
        <div class="filter-group">
            <label>IT Process</label>
            <select name="it_process_id" class="form-select">
                <option value="">All</option>
                @foreach($itProcesses as $ip)
                    <option value="{{ $ip->id }}" {{ request('it_process_id') == $ip->id ? 'selected' : '' }}>{{ $ip->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label>ITGC Category</label>
            <select name="category_id" class="form-select">
                <option value="">All</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label>Frequency</label>
            <select name="frequency_id" class="form-select">
                <option value="">All</option>
                @foreach($frequencies as $freq)
                    <option value="{{ $freq->id }}" {{ request('frequency_id') == $freq->id ? 'selected' : '' }}>{{ $freq->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label>Search</label>
            <input type="text" name="search" class="form-control" placeholder="Control ID, description..." value="{{ request('search') }}">
        </div>
        <div><button type="submit" class="btn-primary-custom"><i class="bi bi-funnel"></i> Filter</button></div>
    </form>
</div>

<div class="content-card">
    <div class="card-body p-0">
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Control ID</th>
                        <th>IT Process</th>
                        <th>Risk ID</th>
                        <th>IT Control</th>
                        <th>Type</th>
                        <th>ITGC Category</th>
                        <th>Frequency</th>
                        <th>Key</th>
                        <th>UIC</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($controls as $control)
                    <tr>
                        <td><span class="control-id" style="font-size: 11px; padding: 2px 8px;">{{ $control->control_id_code }}</span></td>
                        <td style="min-width: 160px;">{{ $control->itProcess->name ?? '-' }}</td>
                        <td>{{ $control->risk->risk_id_code ?? '-' }}</td>
                        <td style="min-width: 250px; font-size: 12.5px;">{{ Str::limit($control->description, 100) }}</td>
                        <td style="white-space: nowrap;">{{ $control->control_type ?? '-' }}</td>
                        <td style="white-space: nowrap;">{{ $control->category->name ?? '-' }}</td>
                        <td style="white-space: nowrap;">{{ $control->frequency->name ?? '-' }}</td>
                        <td>
                            @if($control->is_key_control)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </td>
                        <td style="font-size: 12px;">{{ Str::limit($control->uic, 30) }}</td>
                        <td>
                            <a href="{{ route('it-rcm.show', $control) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> View</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="10" class="text-center text-muted py-4">No controls found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-3 py-2">{{ $controls->links() }}</div>
    </div>
</div>
@endsection
