@extends('layouts.app')
@section('title', 'New Assessment - CSA ITGC')
@section('breadcrumb')
    <a href="{{ route('dashboard') }}">CSA - ITGC</a><span class="separator">/</span>
    <a href="{{ route('assessments.my') }}">Assessment</a><span class="separator">/</span>
    <span class="current">New Assessment</span>
@endsection

@section('content')
<div class="mb-4">
    <h1 class="page-title">Control Self Assessment</h1>
    <p class="page-subtitle mb-0">{{ $control->control_id_code }} — {{ $period->name }}</p>
</div>

<form method="POST" action="{{ route('assessments.store') }}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="control_id" value="{{ $control->id }}">
    <input type="hidden" name="assessment_period_id" value="{{ $period->id }}">

    {{-- Control Information --}}
    <div class="detail-section mb-4">
        <div class="section-header"><i class="bi bi-shield-check"></i> Control Information</div>
        <div class="section-body">
            <div class="detail-grid">
                <div class="detail-item"><div class="detail-label">Control ID</div><div class="detail-value"><span class="control-id">{{ $control->control_id_code }}</span></div></div>
                <div class="detail-item"><div class="detail-label">Assessment Period</div><div class="detail-value">{{ $period->name }}</div></div>
                <div class="detail-item"><div class="detail-label">IT Process</div><div class="detail-value">{{ $control->itProcess->name ?? '-' }}</div></div>
                <div class="detail-item"><div class="detail-label">Risk ID</div><div class="detail-value">{{ $control->risk->risk_id_code ?? '-' }}</div></div>
                <div class="detail-item full-width"><div class="detail-label">IT Control</div><div class="detail-value">{{ $control->description }}</div></div>
                <div class="detail-item"><div class="detail-label">ITGC Category</div><div class="detail-value">{{ $control->category->name ?? '-' }}</div></div>
                <div class="detail-item"><div class="detail-label">Control Frequency</div><div class="detail-value">{{ $control->frequency->name ?? '-' }}</div></div>
                <div class="detail-item"><div class="detail-label">UIC</div><div class="detail-value">{{ $control->uic ?? '-' }}</div></div>
                <div class="detail-item"><div class="detail-label">PIC</div><div class="detail-value">{{ auth()->user()->name }}</div></div>
            </div>
        </div>
    </div>

    {{-- Assessment Result --}}
    <div class="form-section">
        <div class="form-section-title"><i class="bi bi-clipboard-check"></i> Assessment Result</div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Assessment Result <span class="text-danger">*</span></label>
            <div class="d-flex flex-wrap gap-3">
                @foreach(['compliant' => 'Compliant', 'partially_compliant' => 'Partially Compliant', 'non_compliant' => 'Non-Compliant', 'not_applicable' => 'Not Applicable'] as $value => $label)
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="result" id="result_{{ $value }}" value="{{ $value }}" {{ old('result') == $value ? 'checked' : '' }}>
                    <label class="form-check-label" for="result_{{ $value }}">{{ $label }}</label>
                </div>
                @endforeach
            </div>
            @error('result') <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label fw-semibold">Assessment Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Describe your assessment findings...">{{ old('description') }}</textarea>
        </div>
        <div class="mb-3">
            <label for="notes" class="form-label fw-semibold">Assessment Notes</label>
            <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Additional notes...">{{ old('notes') }}</textarea>
        </div>
        <div class="mb-0">
            <label for="recommendation" class="form-label fw-semibold">Recommendation / Follow Up</label>
            <textarea class="form-control" id="recommendation" name="recommendation" rows="2" placeholder="Recommendations or follow-up actions...">{{ old('recommendation') }}</textarea>
        </div>
    </div>

    {{-- Evidence Upload --}}
    <div class="form-section">
        <div class="form-section-title"><i class="bi bi-paperclip"></i> Evidence</div>
        <div id="evidenceContainer">
            <div class="row g-3 mb-3 evidence-row">
                <div class="col-md-5">
                    <label class="form-label">File</label>
                    <input type="file" class="form-control" name="evidences[]" accept=".pdf,.xlsx,.xls,.docx,.doc,.png,.jpg,.jpeg">
                    <div class="form-text">Max 10MB. Supported: PDF, Excel, Word, PNG, JPG</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Description</label>
                    <input type="text" class="form-control" name="evidence_descriptions[]" placeholder="Evidence description...">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.evidence-row').remove()"><i class="bi bi-trash"></i></button>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addEvidenceRow()">
            <i class="bi bi-plus-circle me-1"></i> Add More Evidence
        </button>
    </div>

    {{-- Action Buttons --}}
    <div class="d-flex gap-3 mt-4">
        <button type="submit" name="action" value="draft" class="btn-outline-custom">
            <i class="bi bi-save"></i> Save Draft
        </button>
        <button type="submit" name="action" value="submit" class="btn-primary-custom" onclick="return confirm('Submit this assessment for review?')">
            <i class="bi bi-send"></i> Submit Assessment
        </button>
        <a href="{{ route('assessments.my') }}" class="btn-outline-custom ms-auto">Cancel</a>
    </div>
</form>

@push('scripts')
<script>
function addEvidenceRow() {
    const container = document.getElementById('evidenceContainer');
    const row = document.createElement('div');
    row.className = 'row g-3 mb-3 evidence-row';
    row.innerHTML = `
        <div class="col-md-5">
            <label class="form-label">File</label>
            <input type="file" class="form-control" name="evidences[]" accept=".pdf,.xlsx,.xls,.docx,.doc,.png,.jpg,.jpeg">
        </div>
        <div class="col-md-6">
            <label class="form-label">Description</label>
            <input type="text" class="form-control" name="evidence_descriptions[]" placeholder="Evidence description...">
        </div>
        <div class="col-md-1 d-flex align-items-end">
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.evidence-row').remove()"><i class="bi bi-trash"></i></button>
        </div>
    `;
    container.appendChild(row);
}
</script>
@endpush
@endsection
