@extends('layouts.app')
@section('title', 'Edit Assessment - CSA ITGC')
@section('breadcrumb')
    <a href="{{ route('dashboard') }}">CSA - ITGC</a><span class="separator">/</span>
    <a href="{{ route('assessments.my') }}">Assessment</a><span class="separator">/</span>
    <span class="current">Edit {{ $assessment->control->control_id_code }}</span>
@endsection

@section('content')
<div class="mb-4">
    <h1 class="page-title">Edit Assessment</h1>
    <p class="page-subtitle mb-0">{{ $assessment->control->control_id_code }} — {{ $assessment->assessmentPeriod->name }}</p>
</div>

@if($assessment->isReturned())
<div class="alert alert-warning">
    <i class="bi bi-exclamation-triangle me-2"></i><strong>This assessment was returned.</strong>
    @php $returnHistory = $assessment->histories->where('action', 'returned_by_reviewer')->merge($assessment->histories->where('action', 'returned_by_approver'))->first(); @endphp
    @if($returnHistory)
        <br>Reason: {{ $returnHistory->comment }}
        <br><small>By {{ $returnHistory->user->name ?? 'Unknown' }} on {{ $returnHistory->created_at->format('d M Y H:i') }}</small>
    @endif
</div>
@endif

<form method="POST" action="{{ route('assessments.update', $assessment) }}" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div class="detail-section mb-4">
        <div class="section-header"><i class="bi bi-shield-check"></i> Control Information</div>
        <div class="section-body">
            <div class="detail-grid">
                <div class="detail-item"><div class="detail-label">Control ID</div><div class="detail-value"><span class="control-id">{{ $assessment->control->control_id_code }}</span></div></div>
                <div class="detail-item"><div class="detail-label">Period</div><div class="detail-value">{{ $assessment->assessmentPeriod->name }}</div></div>
                <div class="detail-item"><div class="detail-label">IT Process</div><div class="detail-value">{{ $assessment->control->itProcess->name ?? '-' }}</div></div>
                <div class="detail-item"><div class="detail-label">Risk ID</div><div class="detail-value">{{ $assessment->control->risk->risk_id_code ?? '-' }}</div></div>
                <div class="detail-item full-width"><div class="detail-label">IT Control</div><div class="detail-value">{{ $assessment->control->description }}</div></div>
            </div>
        </div>
    </div>

    <div class="form-section">
        <div class="form-section-title"><i class="bi bi-clipboard-check"></i> Assessment Result</div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Assessment Result <span class="text-danger">*</span></label>
            <div class="d-flex flex-wrap gap-3">
                @foreach(['compliant' => 'Compliant', 'partially_compliant' => 'Partially Compliant', 'non_compliant' => 'Non-Compliant', 'not_applicable' => 'Not Applicable'] as $value => $label)
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="result" id="result_{{ $value }}" value="{{ $value }}" {{ old('result', $assessment->result) == $value ? 'checked' : '' }}>
                    <label class="form-check-label" for="result_{{ $value }}">{{ $label }}</label>
                </div>
                @endforeach
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Assessment Description</label>
            <textarea class="form-control" name="description" rows="3">{{ old('description', $assessment->description) }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Assessment Notes</label>
            <textarea class="form-control" name="notes" rows="2">{{ old('notes', $assessment->notes) }}</textarea>
        </div>
        <div class="mb-0">
            <label class="form-label fw-semibold">Recommendation / Follow Up</label>
            <textarea class="form-control" name="recommendation" rows="2">{{ old('recommendation', $assessment->recommendation) }}</textarea>
        </div>
    </div>

    {{-- Existing Evidence --}}
    @if($assessment->evidences->count() > 0)
    <div class="form-section">
        <div class="form-section-title"><i class="bi bi-file-earmark-check"></i> Uploaded Evidence</div>
        <div class="table-wrapper">
            <table class="data-table">
                <thead><tr><th>File Name</th><th>Description</th><th>Size</th><th>Uploaded</th><th>Actions</th></tr></thead>
                <tbody>
                    @foreach($assessment->evidences as $evidence)
                    <tr>
                        <td><i class="bi bi-file-earmark me-1"></i>{{ $evidence->file_name }}</td>
                        <td>{{ $evidence->description ?? '-' }}</td>
                        <td>{{ $evidence->file_size_formatted }}</td>
                        <td>{{ $evidence->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('evidence.download', $evidence) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-download"></i></a>
                            <form action="{{ route('evidence.delete', $evidence) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this evidence?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Upload New Evidence --}}
    <div class="form-section">
        <div class="form-section-title"><i class="bi bi-paperclip"></i> Upload New Evidence</div>
        <div id="evidenceContainer">
            <div class="row g-3 mb-3 evidence-row">
                <div class="col-md-5"><input type="file" class="form-control" name="evidences[]" accept=".pdf,.xlsx,.xls,.docx,.doc,.png,.jpg,.jpeg"></div>
                <div class="col-md-6"><input type="text" class="form-control" name="evidence_descriptions[]" placeholder="Description..."></div>
                <div class="col-md-1 d-flex align-items-end"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.evidence-row').remove()"><i class="bi bi-trash"></i></button></div>
            </div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addEvidenceRow()"><i class="bi bi-plus-circle me-1"></i> Add More</button>
    </div>

    <div class="d-flex gap-3 mt-4">
        <button type="submit" name="action" value="draft" class="btn-outline-custom"><i class="bi bi-save"></i> Save Draft</button>
        <button type="submit" name="action" value="submit" class="btn-primary-custom" onclick="return confirm('Submit this assessment?')"><i class="bi bi-send"></i> Submit Assessment</button>
        <a href="{{ route('assessments.show', $assessment) }}" class="btn-outline-custom ms-auto">Cancel</a>
    </div>
</form>

@push('scripts')
<script>
function addEvidenceRow() {
    const c = document.getElementById('evidenceContainer');
    const r = document.createElement('div');
    r.className = 'row g-3 mb-3 evidence-row';
    r.innerHTML = '<div class="col-md-5"><input type="file" class="form-control" name="evidences[]" accept=".pdf,.xlsx,.xls,.docx,.doc,.png,.jpg,.jpeg"></div><div class="col-md-6"><input type="text" class="form-control" name="evidence_descriptions[]" placeholder="Description..."></div><div class="col-md-1 d-flex align-items-end"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest(\'.evidence-row\').remove()"><i class="bi bi-trash"></i></button></div>';
    c.appendChild(r);
}
</script>
@endpush
@endsection
