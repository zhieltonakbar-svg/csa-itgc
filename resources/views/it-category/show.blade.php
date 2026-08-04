@extends('layouts.app')

@section('title', $category->name . ' — ' . $application->name . ' — CSA - ITGC')

@push('styles')
<style>
/* ============================================================
   IT CATEGORY DETAIL PAGE
   Table columns: Control ID | Control Description | Status Control
   Summary:       Application | Year | Quarter
   ============================================================ */

/* ── Page Header ─────────────────────────────────────────── */
.itc-page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}
.itc-header-left {
    display: flex;
    flex-direction: column;
    gap: 8px;
    min-width: 0;
}

/* Breadcrumb */
.itc-breadcrumb {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 12.5px;
    flex-wrap: wrap;
    color: var(--text-secondary);
}
.itc-breadcrumb a {
    color: var(--text-secondary);
    text-decoration: none;
    transition: color 0.18s;
}
.itc-breadcrumb a:hover { color: var(--primary); }
.itc-breadcrumb .bc-sep { color: var(--text-muted); font-size: 9px; }
.itc-breadcrumb .bc-cur {
    color: var(--text-primary);
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 220px;
}

/* Page title */
.itc-page-title {
    font-size: 20px;
    font-weight: 800;
    color: var(--text-primary);
    letter-spacing: -0.3px;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
    line-height: 1.25;
}
.itc-title-icon {
    width: 38px;
    height: 38px;
    background: var(--primary-gradient);
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 2px 10px rgba(25,135,84,.3);
}
.itc-title-icon i { font-size: 18px; color: #fff; }

/* Back button */
.btn-back-itc {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 500;
    color: var(--text-secondary);
    text-decoration: none;
    padding: 8px 16px;
    border-radius: var(--radius-sm);
    border: 1.5px solid var(--border-color);
    background: var(--bg-white);
    transition: var(--transition);
    white-space: nowrap;
    flex-shrink: 0;
    align-self: flex-start;
}
.btn-back-itc:hover {
    border-color: var(--primary);
    color: var(--primary);
    background: var(--primary-light);
}

/* ── Summary Card — Application | Year | Quarter ─────────── */
.itc-summary-card {
    background: var(--bg-white);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-card);
    margin-bottom: 1.25rem;
    overflow: hidden;
}
.itc-summary-header {
    padding: 10px 20px;
    background: var(--bg-body);
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: var(--text-secondary);
}
.itc-summary-header i { color: var(--primary); font-size: 14px; }

.itc-summary-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
}
.itc-sum-cell {
    padding: 18px 24px;
    border-right: 1px solid var(--border-light);
}
.itc-sum-cell:last-child { border-right: none; }
.itc-sum-label {
    font-size: 10.5px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.65px;
    color: var(--text-muted);
    margin-bottom: 8px;
    display: block;
}
.itc-sum-value {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 8px;
    line-height: 1.3;
}
.itc-sum-value i { font-size: 16px; color: var(--primary); flex-shrink: 0; }

/* ── Section label ───────────────────────────────────────── */
.itc-section-label {
    display: flex;
    align-items: center;
    gap: 9px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.7px;
    color: var(--text-muted);
    margin-bottom: 10px;
}
.itc-section-label::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--border-color);
}
.itc-section-label i { color: var(--primary); font-size: 13px; }

/* ── Toolbar ─────────────────────────────────────────────── */
.itc-toolbar {
    background: var(--bg-white);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md) var(--radius-md) 0 0;
    border-bottom: 1px solid var(--border-light);
    padding: 12px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}
.itc-toolbar-left {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    flex: 1;
    min-width: 0;
}
.itc-toolbar-right {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

/* Search input */
.itc-search-wrap {
    position: relative;
    flex: 1;
    min-width: 180px;
    max-width: 280px;
}
.itc-search-wrap i {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 13px;
    pointer-events: none;
}
.itc-search-input {
    width: 100%;
    padding: 7px 12px 7px 32px;
    border: 1.5px solid var(--border-color);
    border-radius: var(--radius-sm);
    font-size: 13px;
    background: var(--bg-body);
    font-family: inherit;
    color: var(--text-primary);
    transition: var(--transition);
    outline: none;
}
.itc-search-input:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(25,135,84,.09);
    background: #fff;
}
.itc-search-input::placeholder { color: var(--text-muted); }

/* Toolbar buttons */
.itc-tb-btn {
    padding: 7px 13px;
    border-radius: var(--radius-sm);
    border: 1.5px solid var(--border-color);
    background: var(--bg-white);
    color: var(--text-primary);
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: var(--transition);
    font-family: inherit;
    white-space: nowrap;
    text-decoration: none;
}
.itc-tb-btn:hover {
    border-color: var(--primary);
    color: var(--primary);
    background: var(--primary-light);
}
.itc-tb-btn.active {
    border-color: var(--primary);
    color: var(--primary);
    background: var(--primary-light);
}
.itc-tb-btn i { font-size: 14px; }

/* Total controls pill */
.itc-total-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 20px;
    background: var(--primary-light);
    font-size: 12.5px;
    font-weight: 600;
    color: var(--text-secondary);
    white-space: nowrap;
    border: 1px solid rgba(25,135,84,.15);
}
.itc-total-pill strong { color: var(--primary); font-weight: 800; }
.itc-total-pill i { color: var(--primary); font-size: 13px; }

/* Add Data — green CTA */
.itc-btn-add {
    padding: 7px 16px;
    border-radius: var(--radius-sm);
    border: none;
    background: var(--primary-gradient);
    color: #fff;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: var(--transition);
    font-family: inherit;
    white-space: nowrap;
    box-shadow: 0 2px 8px rgba(25,135,84,.25);
}
.itc-btn-add:hover {
    background: var(--primary-gradient-hover);
    box-shadow: 0 4px 16px rgba(25,135,84,.3);
    transform: translateY(-1px);
}
.itc-btn-add:active { transform: translateY(0); box-shadow: none; }
.itc-btn-add i { font-size: 15px; }

/* Delete All Data */
.itc-btn-delete-all {
    padding: 7px 16px;
    border-radius: var(--radius-sm);
    border: none;
    background: linear-gradient(135deg, #ef4444, #b91c1c);
    color: #fff;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: var(--transition);
    font-family: inherit;
    text-transform: lowercase;
    box-shadow: 0 2px 8px rgba(220,38,38,.25);
}
.itc-btn-delete-all:hover {
    background: linear-gradient(135deg, #dc2626, #991b1b);
    box-shadow: 0 4px 16px rgba(220,38,38,.35);
    transform: translateY(-1px);
}
.itc-btn-delete-all:active { transform: translateY(0); box-shadow: none; }
.itc-btn-delete-all i { font-size: 14px; }

/* ── Data Table — 3 columns ──────────────────────────────── */
.itc-table-wrap {
    background: var(--bg-white);
    border: 1px solid var(--border-color);
    border-top: none;
    border-radius: 0 0 var(--radius-md) var(--radius-md);
    overflow: hidden;
}
.itc-table-scroll {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
.itc-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 560px;
}

/* Thead */
.itc-table thead th {
    padding: 11px 16px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.55px;
    color: var(--text-secondary);
    background: #F8FAFB;
    border-bottom: 2px solid var(--border-color);
    white-space: nowrap;
    vertical-align: middle;
}
.itc-table thead th:first-child { padding-left: 20px; }
.itc-table thead th:last-child  { padding-right: 20px; }
.itc-table thead th.th-center   { text-align: center; }
.itc-table thead th.sortable    { cursor: pointer; user-select: none; }
.itc-table thead th.sortable:hover {
    color: var(--primary);
    background: var(--primary-light);
}

/* Tbody */
.itc-table tbody td {
    padding: 12px 16px;
    font-size: 13.5px;
    border-bottom: 1px solid var(--border-light);
    vertical-align: middle;
    color: var(--text-primary);
    line-height: 1.5;
}
.itc-table tbody td:first-child { padding-left: 20px; }
.itc-table tbody td:last-child  { padding-right: 20px; }
.itc-table tbody td.td-center   { text-align: center; }
.itc-table tbody tr             { transition: background 0.14s; }
.itc-table tbody tr:hover       { background: #f5fbf7; }
.itc-table tbody tr:last-child td { border-bottom: none; }

/* Column widths */
.col-ctrlid  { width: 130px; white-space: nowrap; }
.col-desc    { /* takes remaining space */ }
.col-status  { width: 170px; text-align: center; }
.col-actions { width: 120px; text-align: center; white-space: nowrap; }

/* ── Row action buttons ──────────────────────────────────── */
.row-act-group {
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.row-act-btn {
    width: 30px;
    height: 30px;
    border-radius: 6px;
    border: 1.5px solid;
    background: transparent;
    cursor: pointer;
    font-size: 13px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition);
    font-family: inherit;
    flex-shrink: 0;
}
/* View — blue/teal outline */
.row-act-view {
    color: #0891b2;
    border-color: rgba(8,145,178,.3);
    background: rgba(8,145,178,.06);
}
.row-act-view:hover {
    background: #0891b2;
    border-color: #0891b2;
    color: #fff;
    box-shadow: 0 2px 8px rgba(8,145,178,.25);
}
/* Edit — green outline */
.row-act-edit {
    color: var(--primary);
    border-color: rgba(25,135,84,.35);
    background: var(--primary-light);
}
.row-act-edit:hover {
    background: var(--primary);
    border-color: var(--primary);
    color: #fff;
    box-shadow: 0 2px 8px rgba(25,135,84,.25);
}
/* Delete — red outline */
.row-act-delete {
    color: #dc2626;
    border-color: rgba(220,38,38,.3);
    background: rgba(220,38,38,.06);
}
.row-act-delete:hover {
    background: #dc2626;
    border-color: #dc2626;
    color: #fff;
    box-shadow: 0 2px 8px rgba(220,38,38,.25);
}

/* Control ID pill */
.ctrl-id-pill {
    display: inline-block;
    padding: 3px 11px;
    background: var(--primary-light);
    color: var(--primary);
    border-radius: 5px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.3px;
    white-space: nowrap;
    border: 1px solid rgba(25,135,84,.18);
}

/* ── Status Control badges ───────────────────────────────── */
/*
   Excel values → display labels:
   not_started      → Not Started Yet   (gray)
   ongoing_review   → On Going Review   (amber)
   ongoing_approval → On Going Approval (blue)
   completed        → Completed         (green)
*/
.sc-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 700;
    white-space: nowrap;
}
.sc-badge::before {
    content: '';
    width: 6px;
    height: 6px;
    border-radius: 50%;
    flex-shrink: 0;
}
.sc-not-started {
    background: rgba(107,114,128,.08);
    color: #4b5563;
    border: 1px solid rgba(107,114,128,.2);
}
.sc-not-started::before { background: #9ca3af; }
.sc-ongoing-review {
    background: rgba(217,119,6,.1);
    color: #b45309;
    border: 1px solid rgba(217,119,6,.22);
}
.sc-ongoing-review::before { background: #d97706; }
.sc-ongoing-approval {
    background: rgba(59,130,246,.1);
    color: #1d4ed8;
    border: 1px solid rgba(59,130,246,.18);
}
.sc-ongoing-approval::before { background: #3b82f6; }
.sc-completed {
    background: rgba(25,135,84,.1);
    color: #15803d;
    border: 1px solid rgba(25,135,84,.18);
}
.sc-completed::before { background: #198754; }

/* ── Empty State ─────────────────────────────────────────── */
.itc-empty-state {
    padding: 80px 24px 72px;
    text-align: center;
    background: var(--bg-white);
    border: 1px solid var(--border-color);
    border-top: none;
    border-radius: 0 0 var(--radius-md) var(--radius-md);
}
.itc-empty-icon-wrap {
    width: 96px;
    height: 96px;
    border-radius: 50%;
    background: linear-gradient(135deg, #EAF6EF 0%, #d1e7dd 100%);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 22px;
    position: relative;
    box-shadow: 0 4px 20px rgba(25,135,84,.12);
}
.itc-empty-icon-wrap::before {
    content: '';
    position: absolute;
    inset: -8px;
    border-radius: 50%;
    border: 2px dashed rgba(25,135,84,.2);
    animation: orbit-spin 28s linear infinite;
}
@keyframes orbit-spin {
    from { transform: rotate(0deg); }
    to   { transform: rotate(360deg); }
}
.itc-empty-icon-wrap i { font-size: 38px; color: var(--primary); opacity: .85; }
.itc-empty-state h5 {
    font-size: 16px;
    font-weight: 800;
    color: var(--text-primary);
    margin: 0 0 10px;
}
.itc-empty-state p {
    font-size: 13.5px;
    color: var(--text-secondary);
    max-width: 420px;
    display: inline-block;
    line-height: 1.7;
    margin: 0 0 22px;
}
.itc-empty-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 18px;
    border-radius: 20px;
    background: var(--primary-light);
    color: var(--primary);
    font-size: 12.5px;
    font-weight: 700;
    border: 1px solid rgba(25,135,84,.15);
}

/* ── Pagination footer ───────────────────────────────────── */
.itc-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 11px 20px;
    background: var(--bg-white);
    border: 1px solid var(--border-color);
    border-top: 1px solid var(--border-light);
    border-radius: 0 0 var(--radius-md) var(--radius-md);
}
.itc-footer-info { font-size: 12.5px; color: var(--text-secondary); }
.itc-footer-pag  { display: flex; align-items: center; gap: 4px; }
.pag-btn {
    padding: 5px 10px;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-sm);
    background: var(--bg-white);
    color: var(--text-primary);
    font-size: 12.5px;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
    font-family: inherit;
    display: inline-flex;
    align-items: center;
    gap: 3px;
}
.pag-btn:hover:not([disabled]):not(.pag-active) {
    border-color: var(--primary);
    color: var(--primary);
    background: var(--primary-light);
}
.pag-active  { background: var(--primary); border-color: var(--primary); color: #fff; font-weight: 700; }
.pag-btn[disabled] { opacity: 0.4; cursor: not-allowed; pointer-events: none; }

/* ── Add Control Modal ───────────────────────────────────── */
.itc-modal-backdrop {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(10,16,30,.52);
    z-index: 2000;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(2px);
    -webkit-backdrop-filter: blur(2px);
    padding: 16px;
}
.itc-modal-backdrop.open { display: flex; }

.itc-modal {
    background: var(--bg-white);
    border-radius: var(--radius-lg);
    box-shadow: 0 24px 64px rgba(0,0,0,.18);
    width: 100%;
    max-width: 520px;
    overflow: hidden;
    animation: modal-enter .22s cubic-bezier(.4,0,.2,1) both;
}
@keyframes modal-enter {
    from { opacity: 0; transform: scale(.96) translateY(12px); }
    to   { opacity: 1; transform: scale(1)  translateY(0); }
}

/* Modal header */
.itc-modal-head {
    padding: 18px 22px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    background: var(--bg-body);
}
.itc-modal-head-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
}
.modal-title-icon {
    width: 34px;
    height: 34px;
    background: var(--primary-gradient);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(25,135,84,.22);
}
.modal-title-icon i { font-size: 15px; color: #fff; }
.itc-modal-close-btn {
    width: 32px; height: 32px;
    border: 1.5px solid var(--border-color);
    background: var(--bg-white);
    border-radius: 7px;
    color: var(--text-muted);
    font-size: 16px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: var(--transition);
}
.itc-modal-close-btn:hover {
    border-color: var(--primary);
    color: var(--primary);
    background: var(--primary-light);
}

/* Modal body */
.itc-modal-body {
    padding: 22px 24px 8px;
}
.modal-ui-notice {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 10px 14px;
    background: #FFF7ED;
    border: 1px solid rgba(217,119,6,.2);
    border-radius: var(--radius-sm);
    margin-bottom: 18px;
    font-size: 12.5px;
    color: #92400e;
    line-height: 1.5;
}
.modal-ui-notice i { font-size: 15px; color: #d97706; flex-shrink: 0; }

/* Form fields */
.modal-form-group {
    margin-bottom: 16px;
}
.modal-form-group:last-child { margin-bottom: 0; }
.modal-label {
    display: block;
    font-size: 12.5px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 6px;
}
.modal-label span.required {
    color: #dc2626;
    margin-left: 2px;
}
.modal-input,
.modal-select,
.modal-textarea {
    width: 100%;
    padding: 8px 12px;
    border: 1.5px solid var(--border-color);
    border-radius: var(--radius-sm);
    font-size: 13px;
    font-family: inherit;
    color: var(--text-primary);
    background: var(--bg-body);
    transition: var(--transition);
    outline: none;
    box-sizing: border-box;
}
.modal-input:focus,
.modal-select:focus,
.modal-textarea:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(25,135,84,.09);
    background: #fff;
}
.modal-input::placeholder,
.modal-textarea::placeholder { color: var(--text-muted); }
.modal-select {
    appearance: none;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%239ca3af' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 32px;
    cursor: pointer;
}
.modal-textarea { resize: vertical; min-height: 80px; line-height: 1.5; }

/* Two-column row */
.modal-form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
}

/* Modal footer */
.itc-modal-foot {
    padding: 16px 24px;
    border-top: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-top: 18px;
}
.modal-foot-note {
    font-size: 11.5px;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 5px;
}
.modal-foot-note i { color: var(--primary); font-size: 13px; }
.modal-foot-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}
.modal-btn-cancel {
    padding: 8px 18px;
    border-radius: var(--radius-sm);
    border: 1.5px solid var(--border-color);
    background: var(--bg-white);
    color: var(--text-primary);
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    font-family: inherit;
    transition: var(--transition);
}
.modal-btn-cancel:hover {
    border-color: var(--primary);
    color: var(--primary);
    background: var(--primary-light);
}
.modal-btn-save {
    padding: 8px 20px;
    border-radius: var(--radius-sm);
    border: none;
    background: var(--primary-gradient);
    color: #fff;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 2px 8px rgba(25,135,84,.2);
}
.modal-btn-save i { font-size: 14px; }

/* ── Responsive ──────────────────────────────────────────── */
@media (max-width: 768px) {
    .itc-summary-grid { grid-template-columns: 1fr; }
    .itc-sum-cell { border-right: none; border-bottom: 1px solid var(--border-light); }
    .itc-sum-cell:last-child { border-bottom: none; }
    .itc-toolbar { flex-direction: column; align-items: stretch; }
    .itc-toolbar-left, .itc-toolbar-right { width: 100%; flex-wrap: wrap; }
    .itc-search-wrap { max-width: 100%; }
    .itc-page-header { flex-direction: column; }
    .modal-form-row { grid-template-columns: 1fr; }
    .itc-modal-foot { flex-direction: column; align-items: stretch; }
    .modal-foot-actions { justify-content: flex-end; }
}
</style>
@endpush

@section('content')

@php
    /* Quarter labels */
    $quarterLabels = [
        'q1' => 'Q1',
        'q2' => 'Q2',
        'q3' => 'Q3',
        'q4' => 'Q4',
    ];
    $quarterLabel = $quarterLabels[$quarter] ?? strtoupper($quarter);

    /*
     * Status Control — DB enum keys → display labels & badge classes
     * not_started      → "Not Started Yet"   (gray)
     * ongoing_review   → "On Going Review"   (amber)
     * ongoing_approval → "On Going Approval" (blue)
     * completed        → "Completed"         (green)
     */
    $scBadgeMap = [
        'not_started'      => ['label' => 'Not Started Yet',   'cls' => 'sc-not-started'],
        'ongoing_review'   => ['label' => 'On Going Review',   'cls' => 'sc-ongoing-review'],
        'ongoing_approval' => ['label' => 'On Going Approval', 'cls' => 'sc-ongoing-approval'],
        'completed'        => ['label' => 'Completed',          'cls' => 'sc-completed'],
        'complete'         => ['label' => 'Completed',          'cls' => 'sc-completed'],
    ];

    // $controls is injected by DashboardController@showCategory
    // (filtered by application + it_category + year + quarter)
    $totalControls = $controls->count();

    // All applications for the modal dropdown
    $allApplications = \App\Models\Application::where('is_active', true)->orderBy('name')->get();
    $allCategories   = \App\Models\ItCategory::orderBy('name')->get();
@endphp

{{-- ══════════════════════════════════════════════════════
     PAGE HEADER
     ══════════════════════════════════════════════════════ --}}
<div class="itc-page-header">

    <div class="itc-header-left">
        <nav class="itc-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('dashboard') }}">
                <i class="bi bi-house-fill" style="font-size:10px;"></i>&nbsp;Dashboard
            </a>
            <span class="bc-sep"><i class="bi bi-chevron-right"></i></span>
            <a href="{{ route('dashboard') }}">{{ $application->name }}</a>
            <span class="bc-sep"><i class="bi bi-chevron-right"></i></span>
            <span class="bc-cur">{{ $category->name }}</span>
        </nav>

        <h1 class="itc-page-title">
            <span class="itc-title-icon">
                <i class="bi {{ $category->icon }}"></i>
            </span>
            {{ $category->name }}
        </h1>
    </div>

    <a href="{{ route('dashboard') }}" class="btn-back-itc">
        <i class="bi bi-arrow-left"></i> Back to Dashboard
    </a>
</div>

{{-- ══════════════════════════════════════════════════════
     SUMMARY CARD — Application | Year | Quarter ONLY
     ══════════════════════════════════════════════════════ --}}
<div class="itc-summary-card">
    <div class="itc-summary-header">
        <i class="bi bi-info-circle-fill"></i>
        Assessment Overview
    </div>
    <div class="itc-summary-grid">

        <div class="itc-sum-cell">
            <span class="itc-sum-label">Application</span>
            <div class="itc-sum-value">
                <i class="bi bi-window-stack"></i>
                {{ $application->name }}
            </div>
        </div>

        <div class="itc-sum-cell">
            <span class="itc-sum-label">Year</span>
            <div class="itc-sum-value">
                <i class="bi bi-calendar3"></i>
                {{ $year }}
            </div>
        </div>

        <div class="itc-sum-cell">
            <span class="itc-sum-label">Quarter</span>
            <div class="itc-sum-value">
                <i class="bi bi-calendar-range"></i>
                {{ $quarterLabel }}
            </div>
        </div>

    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     CONTROLS TABLE
     ══════════════════════════════════════════════════════ --}}
<div class="itc-section-label">
    <i class="bi bi-table"></i>
    Assessment Controls
</div>

{{-- ── Toolbar ─────────────────────────────────────────────────────────── --}}
<div class="itc-toolbar" role="toolbar" aria-label="Controls toolbar">

    <div class="itc-toolbar-left">

        {{-- Search Controls --}}
        <div class="itc-search-wrap">
            <i class="bi bi-search"></i>
            <input type="text"
                   class="itc-search-input"
                   id="itc-search"
                   placeholder="Search controls..."
                   autocomplete="off"
                   aria-label="Search controls">
        </div>

        {{-- Filter --}}
        <button type="button" class="itc-tb-btn" id="itc-filter-btn" title="Filter">
            <i class="bi bi-funnel"></i> Filter
        </button>

        {{-- Refresh --}}
        <button type="button" class="itc-tb-btn" onclick="location.reload()" title="Refresh">
            <i class="bi bi-arrow-clockwise"></i> Refresh
        </button>

    </div>

    <div class="itc-toolbar-right">

        {{-- Total Controls indicator --}}
        <span class="itc-total-pill" aria-live="polite">
            <i class="bi bi-list-check"></i>
            Total Controls: <strong id="itc-total">{{ $totalControls }}</strong>
        </span>

        {{-- Delete All Controls --}}
        @if ($controls->isNotEmpty())
        <button type="button"
                class="itc-btn-delete-all"
                id="itc-delete-all-btn"
                onclick="document.getElementById('deleteAllControlModal').classList.add('open')">
            <i class="bi bi-trash-fill"></i> delete all
        </button>
        @endif

        {{-- Add Data → opens "Add Control" modal form --}}
        <button type="button"
                class="itc-btn-add"
                id="itc-add-btn"
                aria-haspopup="dialog"
                aria-controls="addControlModal">
            <i class="bi bi-plus-lg"></i> Add Data
        </button>

    </div>
</div>

{{-- ── Table / Empty State ─────────────────────────────────────────────── --}}
@if ($controls->isEmpty())

    {{-- Column headers always visible --}}
    <div style="overflow-x:auto; background:var(--bg-white);
                border:1px solid var(--border-color); border-top:none;">
        <table class="itc-table" aria-label="Assessment controls table">
            <thead>
                <tr>
                    <th class="col-ctrlid sortable">Control ID</th>
                    <th class="col-desc">Control Description</th>
                    <th class="col-status th-center">Status Control</th>
                    <th class="col-actions">Actions</th>
                </tr>
            </thead>
            <tbody id="itc-tbody"></tbody>
        </table>
    </div>

    {{-- Professional empty state --}}
    <div class="itc-empty-state" id="itc-empty-state">
        <div class="itc-empty-icon-wrap" aria-hidden="true">
            <i class="bi bi-clipboard2-data"></i>
        </div>
        <h5>No Assessment Data Available</h5>
        <p>
            No control records have been added yet for
            <strong>{{ $application->name }}</strong> — <strong>{{ $category->name }}</strong>.<br>
            Use <strong>Add Data</strong> to manually enter a control record,
            or wait for the Excel import feature to become available.
        </p>
        <span class="itc-empty-tag">
            <i class="bi bi-clock-history"></i>
            Excel Import — Coming Soon
        </span>
    </div>

@else

    {{-- Populated table --}}
    <div class="itc-table-wrap">
        <div class="itc-table-scroll">
            <table class="itc-table" id="itc-table" aria-label="Assessment controls table">
                <thead>
                    <tr>
                        <th class="col-ctrlid sortable">Control ID</th>
                        <th class="col-desc">Control Description</th>
                        <th class="col-frekuensi">Keterangan Frekuensi</th>
                        <th class="col-upti">UPTI</th>
                        <th class="col-keyctrl">Key Control</th>
                        <th class="col-filetype" style="width: 15%;">File Type</th>
                        <th class="col-status th-center">Status Control</th>
                        <th class="col-actions">Actions</th>
                    </tr>
                </thead>
                <tbody id="itc-tbody">
                    @foreach ($controls as $ctrl)
                    @php
                        $scKey  = $ctrl->status_control ?? 'not_started';
                        $scInfo = $scBadgeMap[$scKey] ?? $scBadgeMap['not_started'];
                        
                        $fileType = $ctrl->file_type ?? '—';
                    @endphp
                    <tr
                        data-id="{{ $ctrl->id ?? '' }}"
                        data-ctrl-id="{{ $ctrl->it_control_id ?? '' }}"
                        data-ctrl-desc="{{ addslashes($ctrl->control_description ?? '') }}"
                        data-ctrl-frek="{{ $ctrl->keterangan_frekuensi ?? '' }}"
                        data-ctrl-upti="{{ $ctrl->upti ?? '' }}"
                        data-ctrl-keyctrl="{{ $ctrl->key_control ?? '' }}"
                        data-ctrl-file-type="{{ $ctrl->file_type ?? '' }}"
                        data-ctrl-status="{{ $ctrl->status_control ?? 'not_started' }}"
                        data-cat-status="{{ $ctrl->status_it_category ?? 'not_completed' }}"
                        data-app-id="{{ $ctrl->application_id }}"
                        data-cat-id="{{ $ctrl->it_category_id }}"
                        data-evidences="{{ isset($ctrl->evidences) ? htmlspecialchars(json_encode($ctrl->evidences), ENT_QUOTES, 'UTF-8') : '[]' }}"
                    >
                        <td class="col-ctrlid">
                            <span class="ctrl-id-pill">{{ $ctrl->it_control_id ?? '—' }}</span>
                        </td>
                        <td class="col-desc">
                            <div>{{ $ctrl->control_description ?? '—' }}</div>
                            @if(isset($ctrl->evidences) && $ctrl->evidences->count() > 0)
                                <div class="evidence-pill-list" style="display:flex; flex-wrap:wrap; gap:6px; margin-top:6px;">
                                    @foreach($ctrl->evidences as $ev)
                                        @php
                                            $ext = strtolower(pathinfo($ev->original_name, PATHINFO_EXTENSION));
                                            $icon = 'bi-paperclip';
                                            $iconColor = 'var(--primary, #059669)';
                                            
                                            if ($ext === 'pdf') {
                                                $icon = 'bi-file-earmark-pdf-fill';
                                                $iconColor = '#e11d48'; // red
                                            } elseif (in_array($ext, ['doc', 'docx'])) {
                                                $icon = 'bi-file-earmark-word-fill';
                                                $iconColor = '#2563eb'; // blue
                                            } elseif (in_array($ext, ['xls', 'xlsx', 'csv'])) {
                                                $icon = 'bi-file-earmark-excel-fill';
                                                $iconColor = '#16a34a'; // green
                                            }
                                        @endphp
                                        <a href="{{ route('evidence.show', $ev->id) }}" target="_blank" class="evidence-pill" style="display:inline-flex; align-items:center; gap:5px; background:#f8fafc; border:1px solid #e2e8f0; padding:2px 8px; border-radius:4px; font-size:11px; color:#334155; text-decoration:none;">
                                            <i class="bi {{ $icon }}" style="color:{{ $iconColor }};"></i>
                                            <span style="font-weight:500;" title="{{ $ev->original_name }}">{{ Str::limit($ev->original_name, 26) }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td class="col-frekuensi">
                            {{ $ctrl->keterangan_frekuensi ?? '—' }}
                        </td>
                        <td class="col-upti">
                            {{ $ctrl->upti ?? '—' }}
                        </td>
                        <td class="col-keyctrl">
                            {{ $ctrl->key_control ?? '—' }}
                        </td>
                        <td class="col-filetype">
                            @if($fileType !== '—')
                                <span style="background:#e0e7ff; color:#3730a3; font-size:12px; font-weight:600; padding:3px 8px; border-radius:4px; border:1px solid #c7d2fe;">
                                    {{ $fileType }}
                                </span>
                            @else
                                <span style="color: #9ca3af; font-style: italic;">—</span>
                            @endif
                        </td>
                        <td class="col-status td-center">
                            <select class="status-quick-select" data-id="{{ $ctrl->id }}" style="padding: 4px 8px; font-size: 11.5px; font-weight: 600; border-radius: 6px; border: 1px solid #e2e8f0; background-color: #f8fafc; color: #475569; cursor: pointer; outline: none; width: 100%; text-align: center;">
                                <option value="not_started" {{ $scKey === 'not_started' ? 'selected' : '' }}>Not Started Yet</option>
                                <option value="ongoing_review" {{ $scKey === 'ongoing_review' ? 'selected' : '' }}>On Going Review</option>
                                <option value="ongoing_approval" {{ $scKey === 'ongoing_approval' ? 'selected' : '' }}>On Going Approval</option>
                                <option value="completed" {{ $scKey === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </td>
                        <td class="col-actions">
                            <div class="row-act-group">
                                {{-- 1. Edit Control / Upload Evidence --}}
                                <button type="button"
                                        class="row-act-btn row-act-edit btn-edit-ctrl"
                                        title="Upload Evidence"
                                        aria-label="Upload Evidence {{ $ctrl->it_control_id }}">
                                    <i class="bi bi-upload"></i>
                                </button>

                                {{-- 3. Delete Control --}}
                                <button type="button"
                                        class="row-act-btn row-act-delete btn-delete-ctrl"
                                        title="Delete Control"
                                        data-ctrl-id="{{ $ctrl->it_control_id }}"
                                        aria-label="Delete {{ $ctrl->it_control_id }}">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination footer --}}
    <div class="itc-footer">
        <div class="itc-footer-info">
            Showing <strong id="showing-count">{{ $controls->count() }}</strong>
            of <strong>{{ $controls->count() }}</strong> controls
        </div>
        <div class="itc-footer-pag">
            <button class="pag-btn" disabled><i class="bi bi-chevron-left"></i> Prev</button>
            <button class="pag-btn pag-active" aria-current="page">1</button>
            <button class="pag-btn" disabled>Next <i class="bi bi-chevron-right"></i></button>
        </div>
    </div>

@endif

{{-- ══════════════════════════════════════════════════════
     ADD CONTROL MODAL
     ══════════════════════════════════════════════════════ --}}
<div class="itc-modal-backdrop"
     id="addControlModal"
     role="dialog"
     aria-modal="true"
     aria-labelledby="addControlTitle">

    <div class="itc-modal">

        <div class="itc-modal-head">
            <h2 class="itc-modal-head-title" id="addControlTitle">
                <span class="modal-title-icon">
                    <i class="bi bi-plus-circle-fill"></i>
                </span>
                Add Control Record
            </h2>
            <button class="itc-modal-close-btn" id="addControlClose" type="button" aria-label="Close">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="itc-modal-body">

            <form id="addControlForm" autocomplete="off">
                @csrf
                <input type="hidden" name="year" value="{{ $year }}">
                <input type="hidden" name="quarter" value="{{ $quarter }}">

                <input type="hidden" id="mc-application" name="application_id" value="{{ $application->id }}">
                <input type="hidden" id="mc-category" name="it_category_id" value="{{ $category->id }}">

                <div class="modal-form-row">

                    <div class="modal-form-group">
                        <label class="modal-label" for="mc-ctrl-id">
                            Control ID <span class="required">*</span>
                        </label>
                        <input type="text"
                               class="modal-input"
                               id="mc-ctrl-id"
                               name="it_control_id"
                               placeholder="e.g. C-IT-01"
                               required>
                    </div>

                </div>

                <div class="modal-form-group">
                    <label class="modal-label" for="mc-description">
                        Control Description <span class="required">*</span>
                    </label>
                    <textarea class="modal-textarea"
                              id="mc-description"
                              name="control_description"
                              placeholder="Describe the IT control objective and activity..."
                              rows="4"
                              required></textarea>
                </div>

                <div class="modal-form-row">
                    <div class="modal-form-group">
                        <label class="modal-label" for="mc-frekuensi">Keterangan Frekuensi</label>
                        <select class="modal-input" id="mc-frekuensi" name="keterangan_frekuensi">
                            <option value="">-- Pilih --</option>
                            <option value="Per Project">Per Project</option>
                            <option value="Quarterly">Quarterly</option>
                            <option value="Twice a year">Twice a year</option>
                            <option value="Yearly">Yearly</option>
                        </select>
                    </div>
                    <div class="modal-form-group">
                        <label class="modal-label" for="mc-upti">UPTI</label>
                        <select class="modal-input" id="mc-upti" name="upti">
                            <option value="">-- Pilih --</option>
                            <option value="Unit ITSM">Unit ITSM</option>
                            <option value="Unit ESS">Unit ESS</option>
                            <option value="Unit BSS">Unit BSS</option>
                        </select>
                    </div>
                </div>

                <div class="modal-form-group">
                    <label class="modal-label" for="mc-key-control">Key Control</label>
                    <select class="modal-input" id="mc-key-control" name="key_control">
                        <option value="">-- Select --</option>
                        <option value="YES">YES</option>
                        <option value="NO">NO</option>
                    </select>
                </div>

                <div class="modal-form-group">
                    <label class="modal-label" for="mc-file-type">File Type</label>
                    <select class="modal-input" id="mc-file-type" name="file_type">
                        <option value="">-- Select File Type --</option>
                        <option value="Population Data">Population Data</option>
                        <option value="Approval Email">Approval Email</option>
                        <option value="System Screenshot">System Screenshot</option>
                        <option value="Memo/Policy">Memo/Policy</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="modal-form-group">
                    <label class="modal-label" for="mc-evidences">Upload Evidence (PDF, Word, Excel)</label>
                    <input type="file" class="modal-input" id="mc-evidences" name="evidences[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx" style="padding: 6px;">
                    <div style="font-size:11px; color:var(--text-tertiary); margin-top:4px;">Max 10MB per file. You can select multiple files.</div>
                </div>

            </form>
        </div>

        <div class="itc-modal-foot">
            <span class="modal-foot-note" id="mc-save-msg">
                <i class="bi bi-info-circle"></i>
                Ready to save
            </span>
            <div class="modal-foot-actions">
                <button type="button" class="modal-btn-cancel" id="addControlCancel">
                    Cancel
                </button>
                <button type="button" class="modal-btn-save" id="btn-save-add-control">
                    <i class="bi bi-floppy-fill"></i> Save Control
                </button>
            </div>
        </div>

    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     EDIT CONTROL RECORD MODAL
     ══════════════════════════════════════════════════════ --}}
<div class="itc-modal-backdrop"
     id="editControlModal"
     role="dialog"
     aria-modal="true"
     aria-labelledby="editControlTitle">

    <div class="itc-modal">

        <div class="itc-modal-head">
            <h2 class="itc-modal-head-title" id="editControlTitle">
                <span class="modal-title-icon" style="background:linear-gradient(135deg,#2563eb,#1d4ed8);">
                    <i class="bi bi-upload"></i>
                </span>
                Upload Evidence
            </h2>
            <button class="itc-modal-close-btn" id="editControlClose" type="button" aria-label="Close">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="itc-modal-body">

            <form id="editControlForm" autocomplete="off" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="ec-id" name="id">

                <input type="hidden" id="ec-application" name="application_id" value="{{ $application->id }}">
                <input type="hidden" id="ec-category" name="it_category_id" value="{{ $category->id }}">
                <input type="hidden" id="ec-status-control" name="status_control">

                <div class="modal-form-row">
                    <div class="modal-form-group">
                        <label class="modal-label" for="ec-ctrl-id">Control ID</label>
                        <input type="text" class="modal-input" id="ec-ctrl-id"
                               name="it_control_id" placeholder="e.g. C-IT-01" required>
                    </div>
                </div>

                <div class="modal-form-group">
                    <label class="modal-label" for="ec-description">Control Description</label>
                    <textarea class="modal-textarea" id="ec-description"
                              name="control_description" rows="4"
                              placeholder="Control description..."></textarea>
                </div>

                <div class="modal-form-row">
                    <div class="modal-form-group">
                        <label class="modal-label" for="ec-frekuensi">Keterangan Frekuensi</label>
                        <select class="modal-input" id="ec-frekuensi" name="keterangan_frekuensi">
                            <option value="">-- Pilih --</option>
                            <option value="Per Project">Per Project</option>
                            <option value="Quarterly">Quarterly</option>
                            <option value="Twice a year">Twice a year</option>
                            <option value="Yearly">Yearly</option>
                        </select>
                    </div>
                    <div class="modal-form-group">
                        <label class="modal-label" for="ec-upti">UPTI</label>
                        <select class="modal-input" id="ec-upti" name="upti">
                            <option value="">-- Pilih --</option>
                            <option value="Unit ITSM">Unit ITSM</option>
                            <option value="Unit ESS">Unit ESS</option>
                            <option value="Unit BSS">Unit BSS</option>
                        </select>
                    </div>
                </div>

                <div class="modal-form-group">
                    <label class="modal-label" for="ec-key-control">Key Control</label>
                    <select class="modal-input" id="ec-key-control" name="key_control">
                        <option value="">-- Select --</option>
                        <option value="YES">YES</option>
                        <option value="NO">NO</option>
                    </select>
                </div>

                <div class="modal-form-group">
                    <label class="modal-label" for="ec-file-type">File Type</label>
                    <select class="modal-input" id="ec-file-type" name="file_type">
                        <option value="">-- Select File Type --</option>
                        <option value="Population Data">Population Data</option>
                        <option value="Approval Email">Approval Email</option>
                        <option value="System Screenshot">System Screenshot</option>
                        <option value="Memo/Policy">Memo/Policy</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                {{-- Upload / Replace Evidence --}}
                <div class="modal-form-group">
                    <label class="modal-label" for="ec-evidences">Upload / Replace Evidence (PDF, Word, Excel)</label>
                    <input type="file" class="modal-input" id="ec-evidences" name="evidences[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx" style="padding: 6px;">
                    <div style="font-size:11px; color:var(--text-tertiary); margin-top:4px;">Max 10MB per file. You can select multiple files to upload or replace existing files.</div>
                </div>

                {{-- Currently Attached Evidence List --}}
                <div class="modal-form-group">
                    <label class="modal-label" style="font-weight:600;">Currently Attached Evidence Files &amp; File Types</label>
                    <ul id="ec-existing-files" style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:8px;">
                        <!-- JS populated from MySQL -->
                    </ul>
                </div>

            </form>
        </div>

        <div class="itc-modal-foot">
            <span class="modal-foot-note" id="ec-save-msg">
                <i class="bi bi-info-circle"></i>
                Ready to save
            </span>
            <div class="modal-foot-actions">
                <button type="button" class="modal-btn-cancel" id="editControlCancel">Cancel</button>
                <button type="button" class="modal-btn-save" id="btn-save-control"
                        style="background:linear-gradient(135deg,#2563eb,#1d4ed8);
                               box-shadow:0 2px 8px rgba(37,99,235,.25);">
                    <i class="bi bi-floppy-fill"></i> Save Changes
                </button>
            </div>
        </div>

    </div>
</div>

    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     DELETE ALL CONTROLS MODAL
     ══════════════════════════════════════════════════════ --}}
<div class="itc-modal-backdrop"
     id="deleteAllControlModal"
     role="dialog"
     aria-modal="true"
     aria-labelledby="deleteAllControlTitle">

    <div class="itc-modal" style="max-width:420px;">

        <div class="itc-modal-head">
            <h2 class="itc-modal-head-title" id="deleteAllControlTitle">
                <span class="modal-title-icon" style="background:linear-gradient(135deg,#dc2626,#b91c1c);">
                    <i class="bi bi-trash3-fill"></i>
                </span>
                Delete All Controls
            </h2>
            <button class="itc-modal-close-btn" id="deleteAllControlClose" type="button" aria-label="Close" onclick="document.getElementById('deleteAllControlModal').classList.remove('open')">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="itc-modal-body" style="padding-top:20px; padding-bottom:30px; text-align:center;">
            <i class="bi bi-exclamation-triangle-fill" style="font-size:36px; color:#dc2626; margin-bottom:12px; display:block;"></i>
            <h3 style="font-size:16px; font-weight:700; color:var(--text-primary); margin-bottom:10px;">Are you absolutely sure?</h3>
            <p style="font-size:13.5px; color:var(--text-secondary); margin:0;">
                This will permanently delete <strong>ALL</strong> controls shown on this page for this category. This action cannot be undone.
            </p>
            <input type="hidden" id="delete-all-app-id" value="{{ $application->id }}">
            <input type="hidden" id="delete-all-cat-id" value="{{ $category->id }}">
            <input type="hidden" id="delete-all-year" value="{{ $year }}">
            <input type="hidden" id="delete-all-quarter" value="{{ $quarter }}">
        </div>

        <div class="itc-modal-foot">
            <div class="modal-foot-actions" style="width:100%; justify-content:center;">
                <button type="button" class="modal-btn-cancel" id="deleteAllControlCancel" onclick="document.getElementById('deleteAllControlModal').classList.remove('open')">Cancel</button>
                <button type="button" class="modal-btn-save" id="btn-confirm-delete-all"
                        style="background:linear-gradient(135deg,#dc2626,#b91c1c);
                               box-shadow:0 2px 8px rgba(220,38,38,.25);">
                    <i class="bi bi-trash3-fill"></i> Yes, Delete All
                </button>
            </div>
        </div>

    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     DELETE CONFIRMATION MODAL (UI placeholder — confirm disabled)
     ══════════════════════════════════════════════════════ --}}
<div class="itc-modal-backdrop"
     id="deleteControlModal"
     role="dialog"
     aria-modal="true"
     aria-labelledby="deleteControlTitle">

    <div class="itc-modal" style="max-width:420px;">

        <div class="itc-modal-head">
            <h2 class="itc-modal-head-title" id="deleteControlTitle">
                <span class="modal-title-icon" style="background:linear-gradient(135deg,#dc2626,#b91c1c);">
                    <i class="bi bi-trash3-fill"></i>
                </span>
                Delete Control
            </h2>
            <button class="itc-modal-close-btn" id="deleteControlClose" type="button" aria-label="Close">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="itc-modal-body" style="padding:24px;">

            {{-- Confirm illustration --}}
            <div style="text-align:center; margin-bottom:20px;">
                <div style="width:64px;height:64px;border-radius:50%;
                            background:rgba(220,38,38,.08);border:2px solid rgba(220,38,38,.2);
                            display:inline-flex;align-items:center;justify-content:center;
                            margin-bottom:14px;">
                    <i class="bi bi-exclamation-triangle-fill" style="font-size:26px;color:#dc2626;"></i>
                </div>
                <h6 style="font-size:15px;font-weight:700;color:var(--text-primary);margin:0 0 8px;">
                    Delete this control?
                </h6>
                <p style="font-size:13px;color:var(--text-secondary);margin:0 0 10px;line-height:1.6;">
                    You are about to delete control
                    <strong id="delete-ctrl-id-label" style="color:#dc2626;">—</strong>.<br>
                    This action cannot be undone.
                </p>
                <input type="hidden" id="delete-ctrl-db-id">
            </div>

        </div>

        <div class="itc-modal-foot">
            <span class="modal-foot-note">
                <i class="bi bi-info-circle"></i>
                Confirm is disabled — UI placeholder
            </span>
            <div class="modal-foot-actions">
                <button type="button" class="modal-btn-cancel" id="deleteControlCancel">Cancel</button>
                <button type="button"
                        class="modal-btn-save"
                        id="btn-confirm-delete"
                        style="background:linear-gradient(135deg,#dc2626,#b91c1c);box-shadow:0 2px 8px rgba(220,38,38,.25);">
                    <i class="bi bi-trash3-fill"></i> Confirm Delete
                </button>
            </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     VIEW EVIDENCE DOCUMENT MODAL
     ══════════════════════════════════════════════════════ --}}
<div class="modal fade" id="viewEvidenceModal" tabindex="-1"
     aria-labelledby="viewEvidenceTitle" aria-hidden="true"
     data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg"
             style="height:90vh; display:flex; flex-direction:column;">

            {{-- Header --}}
            <div class="modal-header bg-dark text-white border-0 py-3" style="flex-shrink:0;">
                <h5 class="modal-title d-flex align-items-center mb-0"
                    id="viewEvidenceTitle" style="font-size:16px;">
                    <span class="d-flex align-items-center justify-content-center bg-white bg-opacity-10 rounded p-1 me-3">
                        <i class="bi bi-file-earmark-pdf-fill fs-5"></i>
                    </span>
                    <span id="ve-modal-title-text" class="fw-semibold">Document Preview</span>
                </h5>
                <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Body: flex grows to fill height --}}
            <div style="flex:1; display:flex; flex-direction:column; overflow:hidden; background:#e9ecef; position:relative;">

                {{-- Tab strip for multiple files (hidden when single) --}}
                <div id="ve-files-list-container"
                     style="display:none; padding:10px 16px; border-bottom:1px solid #dee2e6;
                            background:#fff; flex-shrink:0;">
                    <div style="font-size:11px; font-weight:700; text-transform:uppercase;
                                letter-spacing:.6px; color:#6c757d; margin-bottom:8px;">
                        Select Evidence File
                    </div>
                    <ul id="ve-files-ul"
                        style="list-style:none; padding:0; margin:0;
                               display:flex; flex-wrap:wrap; gap:8px;"></ul>
                </div>

                {{-- Spinner (shown while fetching evidence list) --}}
                <div id="ve-loading-container"
                     style="display:flex; flex:1; align-items:center; justify-content:center;
                            flex-direction:column; gap:14px;">
                    <div id="ve-spinner"
                         style="width:44px; height:44px;
                                border:4px solid #dee2e6;
                                border-top-color:#198754;
                                border-radius:50%;
                                animation:ve-spin .75s linear infinite;"></div>
                    <p style="margin:0; color:#6c757d; font-weight:600; font-size:14px;">
                        Loading document&hellip;
                    </p>
                </div>

                {{-- Error banner (shown when no evidence / load fail) --}}
                <div id="ve-error-container"
                     style="display:none; flex:1; align-items:center; justify-content:center;
                            flex-direction:column; gap:16px; padding:32px;">
                    <div style="width:64px; height:64px; border-radius:50%;
                                background:rgba(220,38,38,.08); border:2px solid rgba(220,38,38,.2);
                                display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-exclamation-triangle-fill"
                           style="font-size:26px; color:#dc2626;"></i>
                    </div>
                    <p id="ve-error-msg"
                       style="margin:0; color:#6c757d; font-size:14px; text-align:center;
                              max-width:360px; line-height:1.6;">Could not load the document.</p>
                    <a id="ve-download-fallback" href="#"
                       class="btn btn-success btn-sm d-inline-flex align-items-center gap-2">
                        <i class="bi bi-download"></i> Download Original
                    </a>
                </div>

                {{-- iFrame PDF Viewer (shown when preview is ready) --}}
                <iframe id="ve-pdf-iframe"
                        src="about:blank"
                        style="display:none; flex:1; width:100%; border:none;"
                        title="Document Preview"></iframe>

            </div>

            {{-- Footer --}}
            <div class="modal-footer bg-white border-top py-2" style="flex-shrink:0;">
                <span class="text-secondary me-auto small d-flex align-items-center gap-2">
                    <i class="bi bi-shield-lock-fill text-success"></i> Read-only preview
                </span>
                <a id="ve-download-btn" href="#" download
                   class="btn btn-success d-inline-flex align-items-center gap-2 fw-semibold px-3">
                    <i class="bi bi-download"></i> Download
                </a>
                <button type="button"
                        class="btn btn-light border px-4 fw-semibold text-secondary"
                        data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<style>
@keyframes ve-spin { to { transform: rotate(360deg); } }
</style>

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    /* ── Live search filter ──────────────────────────────── */
    const searchEl    = document.getElementById('itc-search');
    const tbody       = document.getElementById('itc-tbody');
    const totalEl     = document.getElementById('itc-total');
    const showingEl   = document.getElementById('showing-count');

    if (searchEl && tbody) {
        searchEl.addEventListener('input', function () {
            const term = this.value.trim().toLowerCase();
            const rows = tbody.querySelectorAll('tr');
            let count = 0;

            rows.forEach(function (row) {
                const text = row.textContent.toLowerCase();
                const show = text.includes(term);
                row.style.display = show ? '' : 'none';
                if (show) count++;
            });

            if (totalEl)   totalEl.textContent   = count;
            if (showingEl) showingEl.textContent  = count;
        });
    }

    /* ── Filter button toggle (visual placeholder) ───────── */
    const filterBtn = document.getElementById('itc-filter-btn');
    if (filterBtn) {
        filterBtn.addEventListener('click', function () {
            this.classList.toggle('active');
        });
    }

    /* ─────────────────────────────────────────────────────
       Generic modal helpers
    ───────────────────────────────────────────────────── */
    function openModal(el) {
        if (!el) return;
        document.querySelectorAll('.itc-modal-backdrop.open').forEach(function (m) {
            if (m !== el) m.classList.remove('open');
        });
        el.classList.add('open');
        document.body.style.overflow = 'hidden';
        setTimeout(function () {
            const first = el.querySelector('select:not([disabled]), input:not([disabled]), textarea:not([disabled]), button:not([disabled])');
            if (first) first.focus();
        }, 80);
    }

    function closeModal(el) {
        if (!el) return;
        el.classList.remove('open');
        document.body.style.overflow = '';
    }

    function wireClose(backdropId, closeBtnId, cancelBtnId) {
        var backdrop  = document.getElementById(backdropId);
        var closeBtn  = document.getElementById(closeBtnId);
        var cancelBtn = document.getElementById(cancelBtnId);
        if (closeBtn)  closeBtn.addEventListener('click',  function () { closeModal(backdrop); });
        if (cancelBtn) cancelBtn.addEventListener('click', function () { closeModal(backdrop); });
        if (backdrop)  backdrop.addEventListener('click',  function (e) { if (e.target === backdrop) closeModal(backdrop); });
    }

    /* ── Add Control Modal ───────────────────────────────── */
    var addModal = document.getElementById('addControlModal');
    var addBtn   = document.getElementById('itc-add-btn');
    if (addBtn) addBtn.addEventListener('click', function () { openModal(addModal); });
    wireClose('addControlModal', 'addControlClose', 'addControlCancel');

    /* ── Edit Control Modal ──────────────────────────────── */
    var editModal = document.getElementById('editControlModal');
    wireClose('editControlModal', 'editControlClose', 'editControlCancel');

    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.btn-edit-ctrl');
        if (!btn) return;

        var row      = btn.closest('tr');
        if (!row) return;
        var id       = row.dataset.id       || '';
        var ctrlId   = row.dataset.ctrlId   || '';
        var ctrlDesc = row.dataset.ctrlDesc  || '';
        var ctrlFrek = row.dataset.ctrlFrek  || '';
        var ctrlUpti = row.dataset.ctrlUpti  || '';
        var ctrlKeyCtrl = row.dataset.ctrlKeyctrl || '';
        var ctrlSt   = row.dataset.ctrlStatus || 'not_started';
        var catSt    = row.dataset.catStatus  || 'not_completed';
        var appId    = row.dataset.appId     || '';
        var catId    = row.dataset.catId     || '';

        var ecDbId   = document.getElementById('ec-id');
        var ecApp    = document.getElementById('ec-application');
        var ecCat    = document.getElementById('ec-category');
        var ecId     = document.getElementById('ec-ctrl-id');
        var ecDesc   = document.getElementById('ec-description');
        var ecFrek   = document.getElementById('ec-frekuensi');
        var ecUpti   = document.getElementById('ec-upti');
        var ecKeyCtrl = document.getElementById('ec-key-control');
        var ecSt     = document.getElementById('ec-status-control');

        var ctrlFileType = row.dataset.ctrlFileType || '';
        
        if (ecDbId)   ecDbId.value   = id;
        if (ecApp)    ecApp.value    = appId;
        if (ecCat)    ecCat.value    = catId;
        if (ecId)     ecId.value     = ctrlId;
        if (ecDesc)   ecDesc.value   = ctrlDesc.replace(/\\/g, '');
        if (ecFrek)   ecFrek.value   = ctrlFrek;
        if (ecUpti)   ecUpti.value   = ctrlUpti;
        if (ecKeyCtrl) ecKeyCtrl.value = ctrlKeyCtrl;
        if (ecSt)     ecSt.value     = ctrlSt;

        var ecFileType = document.getElementById('ec-file-type');
        if (ecFileType) ecFileType.value = ctrlFileType;

        var ecFiles  = document.getElementById('ec-existing-files');
        var ecEvInput = document.getElementById('ec-evidences');
        if (ecEvInput) ecEvInput.value = '';

        if (ecFiles && id) {
            ecFiles.innerHTML = '<li style="font-size:12px; color:#9ca3af; font-style:italic;"><i class="bi bi-hourglass-split"></i> Loading files...</li>';
            loadAndRenderEvidences(id, function(evidences) {
                ecFiles.innerHTML = '';
                if (evidences.length === 0) {
                    ecFiles.innerHTML = '<li style="font-size:12px; color:#9ca3af; font-style:italic;">No evidence files currently attached.</li>';
                } else {
                    // Update fallback fileType if it was empty on the control but exists in evidence
                    if (!ecFileType.value && evidences[0].file_type) {
                        ecFileType.value = evidences[0].file_type;
                    }

                    evidences.forEach(function(ev) {
                        var li = document.createElement('li');
                        li.style.cssText = 'background:#f8fafc; padding:10px 12px; border-radius:6px; border:1px solid #e2e8f0; display:flex; flex-direction:column; gap:8px;';
                        var ftVal = (ev.file_type || '').replace(/"/g, '&quot;');
                        li.innerHTML = `
                            <div style="display:flex; align-items:center; justify-content:space-between;">
                                <div style="display:flex; align-items:center; gap:8px; font-size:12.5px; overflow:hidden;">
                                    <i class="bi bi-file-earmark-text text-primary" style="font-size:16px;"></i>
                                    <span style="font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:240px;" title="${ev.original_name}">${ev.original_name}</span>
                                </div>
                                <div style="display:flex; gap:6px;">
                                    <button type="button" class="btn-delete-ev btn-sm" data-id="${ev.id}" style="color:#dc2626; background:#fef2f2; border:none; padding:4px 8px; border-radius:4px; cursor:pointer; font-size:11.5px;"><i class="bi bi-trash3-fill"></i> Delete</button>
                                </div>
                            </div>
                        `;
                        ecFiles.appendChild(li);
                    });
                }
            });
        }


        openModal(editModal);
    });

    // Custom Notification helper
    function showNotification(message, type = 'success') {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert" style="margin-top: 15px;">
                <i class="bi bi-${type === 'success' ? 'check' : 'exclamation'}-circle me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        const container = document.querySelector('.page-content');
        if (container) {
            container.insertAdjacentHTML('afterbegin', alertHtml);
            setTimeout(() => {
                const alerts = container.querySelectorAll('.alert');
                if (alerts.length > 0) {
                    const lastAlert = alerts[0];
                    if (window.bootstrap && bootstrap.Alert) {
                        bootstrap.Alert.getOrCreateInstance(lastAlert).close();
                    }
                }
            }, 5000);
        } else {
            alert(message);
        }
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    async function parseJsonResponse(res) {
        const contentType = res.headers.get('content-type') || '';
        let data = {};
        if (contentType.includes('application/json')) {
            data = await res.json();
        } else {
            const text = await res.text();
            throw new Error('Server returned non-JSON response (' + res.status + '). Please try again.');
        }

        if (!res.ok) {
            let msg = data.message || 'Error processing request.';
            if (data.errors) {
                const errs = Object.values(data.errors).flat();
                if (errs.length > 0) msg = errs.join(' ');
            }
            throw new Error(msg);
        }
        return data;
    }

    // Helper: Load evidence records directly from MySQL database by Control ID
    function loadAndRenderEvidences(controlId, callback) {
        fetch('/controls/' + controlId + '/evidence', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(parseJsonResponse)
        .then(data => {
            callback(data.evidences || []);
        })
        .catch(err => {
            callback([]);
        });
    }

    // Add Control logic
    var btnSaveAddControl = document.getElementById('btn-save-add-control');
    if (btnSaveAddControl) {
        btnSaveAddControl.addEventListener('click', function() {
            var form = document.getElementById('addControlForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            var msg = document.getElementById('mc-save-msg');
            if (msg) msg.innerHTML = '<i class="bi bi-hourglass-split"></i> Saving...';
            btnSaveAddControl.disabled = true;

            var formData = new FormData(form);

            fetch('{{ route("controls.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(parseJsonResponse)
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    closeModal(document.getElementById('addControlModal'));
                    // Reload to reflect changes immediately from the database
                    location.reload();
                } else {
                    showNotification(data.message || 'Error occurred', 'danger');
                }
            })
            .catch(err => {
                showNotification(err.message || 'An error occurred while saving.', 'danger');
            })
            .finally(() => {
                if (msg) msg.innerHTML = '<i class="bi bi-info-circle"></i> Ready to save';
                btnSaveAddControl.disabled = false;
            });
        });
    }

    // Delete Evidence logic
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.btn-delete-ev');
        if (!btn) return;
        if (!confirm('Delete this file permanently?')) return;
        
        var evId = btn.dataset.id;
        btn.disabled = true;

        fetch('/evidence/' + evId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(parseJsonResponse)
        .then(data => {
             if (data.success) {
                 showNotification('Evidence deleted', 'success');
                 location.reload();
             }
        })
        .catch(err => { 
            showNotification(err.message || 'Error deleting evidence', 'danger');
            btn.disabled = false;
        });
    });

    // Save Control logic (Edit)
    var btnSaveControl = document.getElementById('btn-save-control');
    if (btnSaveControl) {
        btnSaveControl.addEventListener('click', function() {
            var form = document.getElementById('editControlForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            var id = document.getElementById('ec-id').value;
            if (!id) return;
            
            var msg = document.getElementById('ec-save-msg');
            msg.innerHTML = '<i class="bi bi-hourglass-split"></i> Saving...';
            btnSaveControl.disabled = true;

            var formData = new FormData(form);
            formData.append('_method', 'PUT');

            fetch('/controls/' + id, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(parseJsonResponse)
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    location.reload();
                } else {
                    showNotification(data.message || 'Error occurred', 'danger');
                }
            })
            .catch(err => {
                showNotification(err.message || 'An error occurred while saving.', 'danger');
            })
            .finally(() => {
                msg.innerHTML = '<i class="bi bi-info-circle"></i> Ready to save';
                btnSaveControl.disabled = false;
            });
        });
    }

    /* ── View Evidence Modal Logic (iframe approach) ──────── */
    var viewEvidenceModalEl = document.getElementById('viewEvidenceModal');
    var viewEvidenceModal   = new bootstrap.Modal(viewEvidenceModalEl);

    // Reset state when modal closes
    viewEvidenceModalEl.addEventListener('hidden.bs.modal', function () {
        var iframe = document.getElementById('ve-pdf-iframe');
        if (iframe) iframe.src = 'about:blank';
        veShowPanel('loading');
        var listCont = document.getElementById('ve-files-list-container');
        if (listCont) listCont.style.display = 'none';
    });

    /* Show one panel at a time: 'loading' | 'iframe' | 'error' */
    function veShowPanel(state) {
        var loading = document.getElementById('ve-loading-container');
        var iframe  = document.getElementById('ve-pdf-iframe');
        var errCont = document.getElementById('ve-error-container');
        if (loading) loading.style.display = state === 'loading' ? 'flex'  : 'none';
        if (iframe)  iframe.style.display  = state === 'iframe'  ? 'flex'  : 'none';
        if (errCont) errCont.style.display = state === 'error'   ? 'flex'  : 'none';
    }

    function veSetError(msg, evidenceId) {
        var errMsg  = document.getElementById('ve-error-msg');
        var dlFallback = document.getElementById('ve-download-fallback');
        if (errMsg) errMsg.textContent = msg || 'Could not load the document.';
        if (dlFallback && evidenceId) dlFallback.href = '/evidence/' + evidenceId;
        veShowPanel('error');
    }

    /* Load a file into the iframe */
    function veLoadInFrame(evidenceId, evidenceName, evidenceType) {
        var iframe = document.getElementById('ve-pdf-iframe');
        var dlBtn  = document.getElementById('ve-download-btn');
        var modalTitle = document.getElementById('ve-modal-title-text');
        if (dlBtn) dlBtn.href = '/evidence/' + evidenceId;

        if (modalTitle && evidenceName) {
            var typeBadge = evidenceType ? ' \u2014 [' + evidenceType + ']' : '';
            modalTitle.textContent = 'Evidence Preview: ' + evidenceName + typeBadge;
        }

        // Show spinner while iframe loads
        veShowPanel('loading');
        iframe.src = 'about:blank';

        var url = '/evidence/' + evidenceId + '/preview-pdf';
        console.log('[Preview] Loading iframe src:', url);

        // When iframe finishes loading, show it (or catch an error)
        iframe.onload = function () {
            // If the src is still about:blank, skip
            if (iframe.src === 'about:blank' || iframe.src === window.location.origin + '/blank') return;
            try {
                // If the server redirected to login, the iframe content-type won't be PDF
                // We detect this by trying to access the iframe document
                var iDoc = iframe.contentDocument || iframe.contentWindow.document;
                var bodyText = iDoc && iDoc.body ? iDoc.body.innerText : '';
                if (bodyText.includes('Login') || bodyText.includes('Unauthorized')) {
                    veSetError('Session expired. Please refresh the page and log in again.', evidenceId);
                    return;
                }
            } catch (e) {
                // Cross-origin or PDF content — this is actually GOOD (PDF renders)
            }
            veShowPanel('iframe');
        };

        iframe.onerror = function () {
            veSetError('Failed to load the document. Please try downloading instead.', evidenceId);
        };

        // Small delay to let the modal finish animating before setting src
        setTimeout(function () {
            iframe.src = url;
        }, 200);
    }

    /* Called when the eye button is clicked */
    document.addEventListener('click', function (e) {
        var btnView = e.target.closest('.btn-view-files');
        if (!btnView) return;
        e.preventDefault();
        e.stopPropagation();

        var row = btnView.closest('tr');
        if (!row) return;

        var controlDbId = row.dataset.id     || '';
        var ctrlId      = row.dataset.ctrlId || '';
        if (!controlDbId) {
            showNotification('Cannot identify control record.', 'danger');
            return;
        }

        // Set modal title
        var modalTitle = document.getElementById('ve-modal-title-text');
        if (modalTitle) modalTitle.textContent = 'Evidence Preview \u2014 ' + ctrlId;

        // Reset and open immediately showing spinner
        document.getElementById('ve-files-list-container').style.display = 'none';
        veShowPanel('loading');
        viewEvidenceModal.show();

        // Fetch evidence list from server
        loadAndRenderEvidences(controlDbId, function (evidences) {
            if (evidences.length === 0) {
                veSetError('No evidence files attached to this control.\nUpload evidence using the Edit button.', null);
                return;
            }

            var listCont = document.getElementById('ve-files-list-container');
            var listUl   = document.getElementById('ve-files-ul');

            // Build tab strip if multiple files
            if (evidences.length > 1 && listCont && listUl) {
                listCont.style.display = 'block';
                listUl.innerHTML = '';

                evidences.forEach(function (ev, idx) {
                    var li       = document.createElement('li');
                    var fileName = ev.original_name || ev.file_name || 'File ' + (idx + 1);
                    var ext      = fileName.split('.').pop().toLowerCase();
                    var iconCls  = ext === 'pdf'  ? 'bi-file-earmark-pdf-fill text-danger' :
                                  (ext === 'xlsx' || ext === 'xls') ? 'bi-file-earmark-excel-fill text-success' :
                                  'bi-file-earmark-word-fill text-primary';
                    var isFirst  = idx === 0;
                    var btnStyle = isFirst
                        ? 'background:#198754; color:#fff; border:1px solid #198754;'
                        : 'background:#f8f9fa; color:#495057; border:1px solid #dee2e6;';
                    var ftBadge  = ev.file_type ? ' <span style="font-size:10px; font-weight:600; opacity:0.9; margin-left:4px;">(' + ev.file_type + ')</span>' : '';
                    li.innerHTML = '<button type="button" class="ve-tab-btn btn btn-sm d-flex align-items-center gap-1" '
                        + 'data-ev-id="' + ev.id + '" '
                        + 'data-ev-name="' + encodeURIComponent(fileName) + '" '
                        + 'data-ev-type="' + encodeURIComponent(ev.file_type || '') + '" '
                        + 'style="' + btnStyle + ' padding:5px 12px; border-radius:6px; font-size:12.5px;">'
                        + '<i class="bi ' + iconCls + '"></i>'
                        + '<span style="max-width:140px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="' + fileName + '">' + fileName + '</span>'
                        + ftBadge
                        + '</button>';
                    listUl.appendChild(li);
                });

                listUl.querySelectorAll('.ve-tab-btn').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        listUl.querySelectorAll('.ve-tab-btn').forEach(function (b) {
                            b.style.background  = '#f8f9fa';
                            b.style.color       = '#495057';
                            b.style.borderColor = '#dee2e6';
                        });
                        this.style.background  = '#198754';
                        this.style.color       = '#fff';
                        this.style.borderColor = '#198754';
                        veLoadInFrame(this.dataset.evId,
                                      decodeURIComponent(this.dataset.evName),
                                      decodeURIComponent(this.dataset.evType));
                    });
                });
            }

            // Load the first (or only) file
            veLoadInFrame(evidences[0].id,
                          evidences[0].original_name || evidences[0].file_name,
                          evidences[0].file_type || '');
        });
    });

    /* ── Delete Confirmation Modal ───────────────────────── */
    var deleteModal  = document.getElementById('deleteControlModal');
    var deleteLabel  = document.getElementById('delete-ctrl-id-label');
    var btnConfirmDelete = document.getElementById('btn-confirm-delete');
    var deleteDbId   = document.getElementById('delete-ctrl-db-id');

    wireClose('deleteControlModal', 'deleteControlClose', 'deleteControlCancel');

    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.btn-delete-ctrl');
        if (!btn) return;

        var row = btn.closest('tr');
        var id = row.dataset.id;
        var ctrlId = btn.dataset.ctrlId || '—';
        
        if (deleteLabel) deleteLabel.textContent = ctrlId;
        if (deleteDbId) deleteDbId.value = id;

        openModal(deleteModal);
    });
    
    if (btnConfirmDelete) {
        btnConfirmDelete.addEventListener('click', function() {
            var id = document.getElementById('delete-ctrl-db-id').value;
            if (!id) return;
            
            btnConfirmDelete.disabled = true;
            btnConfirmDelete.innerHTML = '<i class="bi bi-hourglass-split"></i> Deleting...';

            fetch('/controls/' + id, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(parseJsonResponse)
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    location.reload();
                } else {
                    showNotification(data.message || 'Error occurred', 'danger');
                }
            })
            .catch(err => {
                showNotification(err.message || 'An error occurred while deleting.', 'danger');
            })
            .finally(() => {
                btnConfirmDelete.disabled = false;
                btnConfirmDelete.innerHTML = '<i class="bi bi-trash3-fill"></i> Confirm Delete';
            });
        });
    }

    /* ── Delete All Controls ─────────────────────────────── */
    var btnConfirmDeleteAll = document.getElementById('btn-confirm-delete-all');
    if (btnConfirmDeleteAll) {
        btnConfirmDeleteAll.addEventListener('click', function() {
            var appId = document.getElementById('delete-all-app-id').value;
            var catId = document.getElementById('delete-all-cat-id').value;
            var year = document.getElementById('delete-all-year').value;
            var quarter = document.getElementById('delete-all-quarter').value;
            
            btnConfirmDeleteAll.disabled = true;
            btnConfirmDeleteAll.innerHTML = '<i class="bi bi-hourglass-split"></i> Deleting...';

            fetch('/controls/delete-all', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    application_id: appId,
                    it_category_id: catId,
                    year: year,
                    quarter: quarter
                })
            })
            .then(parseJsonResponse)
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    location.reload();
                } else {
                    showNotification(data.message || 'Error occurred', 'danger');
                }
            })
            .catch(err => {
                showNotification(err.message || 'An error occurred while deleting.', 'danger');
            })
            .finally(() => {
                btnConfirmDeleteAll.disabled = false;
                btnConfirmDeleteAll.innerHTML = '<i class="bi bi-trash3-fill"></i> Yes, Delete All';
            });
        });
    }

    /* ── Quick Status Update from Table ──────────────────── */
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('status-quick-select')) {
            var select = e.target;
            var controlId = select.dataset.id;
            var newStatus = select.value;
            var originalValue = select.getAttribute('data-original-value') || select.querySelector('option[selected]')?.value;

            select.disabled = true;

            fetch(`/controls/${controlId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status_control: newStatus })
            })
            .then(response => {
                if (!response.ok) throw new Error('Network error');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showNotification(data.message || 'Status updated successfully.', 'success');
                    select.setAttribute('data-original-value', newStatus);
                    // Update category status UI if needed (simple refresh is easiest)
                    setTimeout(() => window.location.reload(), 800);
                } else {
                    throw new Error(data.message || 'Failed to update status.');
                }
            })
            .catch(err => {
                showNotification(err.message || 'An error occurred.', 'danger');
                select.value = originalValue; // Revert change
            })
            .finally(() => {
                select.disabled = false;
            });
        }
    });

    // Store original values on load
    document.querySelectorAll('.status-quick-select').forEach(function(select) {
        select.setAttribute('data-original-value', select.value);
    });

    /* ── Global Escape to close any open modal ───────────── */
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.itc-modal-backdrop.open').forEach(function (m) {
                closeModal(m);
            });
        }
    });

}());
</script>
@endpush
