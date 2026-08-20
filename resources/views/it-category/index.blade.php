@extends('layouts.app')

@section('title', 'IT Category Management — CSA - ITGC')

@push('styles')
<style>
/* ── IT Category Icon Picker ───────────────────────────────── */
.icon-picker-wrap {
    position: relative;
}
.icon-preview {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: var(--primary-light, #d1fae5);
    color: var(--primary, #198754);
    font-size: 18px;
    margin-right: 8px;
    flex-shrink: 0;
}
.itcat-table td { vertical-align: middle; }

/* Floating toast */
#itcat-toast {
    position: fixed;
    bottom: 28px;
    right: 28px;
    z-index: 9999;
    min-width: 260px;
    display: none;
}
</style>
@endpush

@section('content')

{{-- ======================================================
     PAGE HEADER
     ====================================================== --}}
<div class="welcome-hero" style="padding: 24px 32px; background: linear-gradient(135deg, #0f172a, #1e293b); border-radius: 16px; margin-bottom: 24px; position: relative; overflow: hidden;">
    <div style="position: relative; z-index: 2; display: flex; align-items: center; justify-content: space-between; flex-wrap:wrap; gap:16px;">
        <div>
            <h1 style="color: #fff; font-size: 24px; font-weight: 700; margin: 0 0 8px 0; letter-spacing: -0.5px;">IT Category Management</h1>
            <p style="color: #94a3b8; font-size: 14px; margin: 0; max-width: 600px; line-height: 1.5;">
                Manage the list of IT Categories (IT RCM framework). Add, edit, or delete categories here.
            </p>
        </div>
    </div>
</div>

{{-- ======================================================
     CATEGORY LIST CARD
     ====================================================== --}}
<div class="col-12">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 style="font-weight:700; color:#1e293b; margin:0;">IT Categories</h5>
        <button class="btn btn-sm btn-dark" style="border-radius:8px; font-weight:600;" onclick="openModal()">
            <i class="bi bi-plus-lg me-1"></i> Add IT Category
        </button>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 itcat-table" style="font-size: 14px;">
                    <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <tr>
                            <th style="padding: 16px 24px; color: #475569; font-weight: 600; width: 50px;">#</th>
                            <th style="padding: 16px 24px; color: #475569; font-weight: 600;">Icon</th>
                            <th style="padding: 16px 24px; color: #475569; font-weight: 600;">Name</th>
                            <th style="padding: 16px 24px; color: #475569; font-weight: 600;">Description</th>
                            <th style="padding: 16px 24px; color: #475569; font-weight: 600; width: 160px; text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="cat-tbody">
                        @forelse($categories as $index => $cat)
                        <tr id="cat-row-{{ $cat->id }}">
                            <td style="padding: 16px 24px; color: #64748b;">{{ $index + 1 }}</td>
                            <td style="padding: 16px 24px;">
                                <span class="icon-preview"><i class="bi {{ $cat->icon ?? 'bi-shield-lock' }}"></i></span>
                                <code style="font-size:11px; color:#64748b;">{{ $cat->icon }}</code>
                            </td>
                            <td style="padding: 16px 24px; font-weight: 600; color: #1e293b;">{{ $cat->name }}</td>
                            <td style="padding: 16px 24px; color: #475569; font-size:13px; max-width: 360px;">{{ $cat->description ?? '—' }}</td>
                            <td style="padding: 16px 24px; text-align: center;">
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                        style="border-radius: 8px; font-weight: 600; margin-right:5px;"
                                        onclick="openModal({{ $cat->id }}, '{{ addslashes($cat->name) }}', '{{ addslashes($cat->description ?? '') }}', '{{ $cat->icon ?? 'bi-shield-lock' }}')">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete-cat"
                                        data-id="{{ $cat->id }}" data-name="{{ $cat->name }}"
                                        style="border-radius: 8px; font-weight: 600;">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr id="cat-empty-row">
                            <td colspan="5" style="padding: 48px; text-align: center; color: #64748b;">
                                <i class="bi bi-inbox" style="font-size: 32px; color: #cbd5e1; margin-bottom: 12px; display: block;"></i>
                                No IT Categories found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ======================================================
     ADD / EDIT MODAL
     ====================================================== --}}
<div class="modal fade" id="cat-modal" tabindex="-1" aria-labelledby="catModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.15);">
            <div class="modal-header" style="border-bottom: 1px solid #e2e8f0; padding: 20px 24px;">
                <h5 class="modal-title fw-700" id="catModalLabel" style="font-weight:700; color:#1e293b;">Add IT Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 24px;">
                <input type="hidden" id="cat-id" value="">

                <div class="mb-3">
                    <label class="form-label fw-600" style="font-weight:600; font-size:13px; color:#374151;">Category Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="cat-name" placeholder="e.g. Access to Programs & Data" style="border-radius:10px;">
                    <div class="invalid-feedback" id="cat-name-error">Name is required.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-600" style="font-weight:600; font-size:13px; color:#374151;">Bootstrap Icon Class</label>
                    <div class="input-group">
                        <span class="input-group-text" style="border-radius:10px 0 0 10px; background:#f8fafc;">
                            <span class="icon-preview" id="icon-preview-modal" style="margin:0; width:28px; height:28px; font-size:15px;">
                                <i class="bi bi-shield-lock" id="icon-preview-i"></i>
                            </span>
                        </span>
                        <input type="text" class="form-control" id="cat-icon" placeholder="bi-shield-lock" style="border-radius:0 10px 10px 0;"
                               oninput="updateIconPreview(this.value)">
                    </div>
                    <div class="form-text mt-1">
                        Browse icons at <a href="https://icons.getbootstrap.com/" target="_blank" rel="noopener">icons.getbootstrap.com</a>. Example: <code>bi-shield-lock</code>
                    </div>
                </div>

                <div class="mb-1">
                    <label class="form-label fw-600" style="font-weight:600; font-size:13px; color:#374151;">Description</label>
                    <textarea class="form-control" id="cat-desc" rows="3" placeholder="Short description of this IT category..." style="border-radius:10px; resize:vertical;"></textarea>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #e2e8f0; padding: 16px 24px;">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius:8px; font-weight:600;">Cancel</button>
                <button type="button" class="btn btn-dark" id="cat-save-btn" onclick="saveCategory()" style="border-radius:8px; font-weight:600; min-width:100px;">
                    <span id="cat-save-text">Save</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ======================================================
     DELETE CONFIRM MODAL
     ====================================================== --}}
<div class="modal fade" id="cat-delete-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius:16px; border:none; box-shadow:0 20px 60px rgba(0,0,0,0.15);">
            <div class="modal-body text-center" style="padding:28px 24px;">
                <div style="width:52px;height:52px;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="bi bi-trash3-fill" style="color:#dc2626;font-size:22px;"></i>
                </div>
                <h6 style="font-weight:700;color:#1e293b;margin-bottom:8px;">Delete IT Category?</h6>
                <p style="color:#64748b;font-size:13px;margin-bottom:20px;" id="cat-delete-confirm-text">This action cannot be undone.</p>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light flex-fill" data-bs-dismiss="modal" style="border-radius:8px;font-weight:600;">Cancel</button>
                    <button type="button" class="btn btn-danger flex-fill" id="cat-delete-confirm-btn" style="border-radius:8px;font-weight:600;">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Toast --}}
<div id="itcat-toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast show align-items-center text-white border-0" id="itcat-toast-inner" style="border-radius:12px; min-width:260px; box-shadow:0 8px 24px rgba(0,0,0,0.18);">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center gap-2" id="itcat-toast-body">
                <i class="bi bi-check-circle-fill" id="itcat-toast-icon"></i>
                <span id="itcat-toast-msg">Done!</span>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    const STORE_URL  = '{{ route('it-categories.store') }}';
    const UPDATE_URL = '{{ url('it-categories') }}';
    const DELETE_URL = '{{ url('it-categories') }}';
    const CSRF       = '{{ csrf_token() }}';

    const catModal       = new bootstrap.Modal(document.getElementById('cat-modal'));
    const deleteModal    = new bootstrap.Modal(document.getElementById('cat-delete-modal'));
    let pendingDeleteId  = null;
    let pendingDeleteName = null;

    // ── Open add / edit modal ──────────────────────────────────────────────
    window.openModal = function (id = null, name = '', description = '', icon = 'bi-shield-lock') {
        document.getElementById('cat-id').value = id ?? '';
        document.getElementById('cat-name').value = name;
        document.getElementById('cat-desc').value = description;
        document.getElementById('cat-icon').value = icon;
        document.getElementById('catModalLabel').textContent = id ? 'Edit IT Category' : 'Add IT Category';
        document.getElementById('cat-save-text').textContent = id ? 'Update' : 'Save';
        document.getElementById('cat-name').classList.remove('is-invalid');
        updateIconPreview(icon);
        catModal.show();
    };

    // ── Icon preview ──────────────────────────────────────────────────────
    window.updateIconPreview = function (val) {
        const icon = val.trim() || 'bi-shield-lock';
        const el = document.getElementById('icon-preview-i');
        el.className = 'bi ' + icon;
    };

    // ── Save (store or update) ────────────────────────────────────────────
    window.saveCategory = async function () {
        const id   = document.getElementById('cat-id').value;
        const name = document.getElementById('cat-name').value.trim();
        const desc = document.getElementById('cat-desc').value.trim();
        const icon = document.getElementById('cat-icon').value.trim() || 'bi-shield-lock';

        if (!name) {
            document.getElementById('cat-name').classList.add('is-invalid');
            return;
        }
        document.getElementById('cat-name').classList.remove('is-invalid');

        const btn = document.getElementById('cat-save-btn');
        btn.disabled = true;
        document.getElementById('cat-save-text').textContent = 'Saving…';

        try {
            const isEdit = !!id;
            const url    = isEdit ? `${UPDATE_URL}/${id}` : STORE_URL;
            const method = isEdit ? 'PUT' : 'POST';

            const res  = await fetch(url, {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ name, description: desc, icon }),
            });
            const data = await res.json();

            if (!res.ok || !data.success) {
                throw new Error(data.message || 'Failed to save.');
            }

            catModal.hide();
            showToast(data.message, 'success');

            // Reload to reflect table changes cleanly
            setTimeout(() => location.reload(), 800);
        } catch (err) {
            showToast(err.message, 'danger');
        } finally {
            btn.disabled = false;
            document.getElementById('cat-save-text').textContent = id ? 'Update' : 'Save';
        }
    };

    // ── Delete ───────────────────────────────────────────────────────────
    document.querySelectorAll('.btn-delete-cat').forEach(btn => {
        btn.addEventListener('click', () => {
            pendingDeleteId   = btn.dataset.id;
            pendingDeleteName = btn.dataset.name;
            document.getElementById('cat-delete-confirm-text').textContent =
                `"${pendingDeleteName}" will be permanently removed. This cannot be undone.`;
            deleteModal.show();
        });
    });

    document.getElementById('cat-delete-confirm-btn').addEventListener('click', async () => {
        if (!pendingDeleteId) return;

        const btn = document.getElementById('cat-delete-confirm-btn');
        btn.disabled = true;
        btn.textContent = 'Deleting…';

        try {
            const res  = await fetch(`${DELETE_URL}/${pendingDeleteId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                },
            });
            const data = await res.json();

            if (!res.ok || !data.success) {
                throw new Error(data.message || 'Failed to delete.');
            }

            deleteModal.hide();
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 800);
        } catch (err) {
            showToast(err.message, 'danger');
        } finally {
            btn.disabled = false;
            btn.textContent = 'Delete';
        }
    });

    // ── Toast helper ─────────────────────────────────────────────────────
    function showToast(msg, type = 'success') {
        const wrap  = document.getElementById('itcat-toast');
        const inner = document.getElementById('itcat-toast-inner');
        const msgEl = document.getElementById('itcat-toast-msg');
        const icon  = document.getElementById('itcat-toast-icon');

        inner.style.background = type === 'success' ? '#198754' : '#dc2626';
        icon.className = type === 'success' ? 'bi bi-check-circle-fill' : 'bi bi-x-circle-fill';
        msgEl.textContent = msg;
        wrap.style.display = 'block';

        setTimeout(() => { wrap.style.display = 'none'; }, 3500);
    }

}());
</script>
@endpush
