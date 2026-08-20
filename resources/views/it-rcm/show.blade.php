@extends('layouts.app')

@section('title', $category->name . ' — IT RCM Management')

@push('styles')
<style>
/* ============================================================
   IT RCM MANAGEMENT PAGE (PREMIUM ADMIN THEME)
   ============================================================ */

/* ── Premium Aesthetic Overrides ── */
.rcm-premium-wrapper {
    --primary: #3730a3; /* Indigo/Navy */
    --primary-light: #e0e7ff;
    --primary-gradient: linear-gradient(135deg, #1e1b4b, #4f46e5);
    --border-color: #c7d2fe;
    --bg-white: #ffffff;
    --shadow-card: 0 8px 30px rgba(49, 46, 129, 0.08);
    
    padding: 24px;
    background: #f8fafc;
    border-radius: 16px;
    box-shadow: inset 0 0 0 1px rgba(0,0,0,0.05);
}

.rcm-premium-wrapper .itc-page-header { margin-bottom: 2rem; border-bottom: 2px solid var(--primary-light); padding-bottom: 1.5rem; }
.rcm-premium-wrapper .itc-page-title { font-size: 24px; color: #1e1b4b; }
.rcm-premium-wrapper .itc-title-icon { box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3); border-radius: 12px; }
.rcm-premium-wrapper .itc-table th { background: #1e1b4b; color: #fff; border-bottom: none; }
.rcm-premium-wrapper .itc-table tbody tr:hover { background-color: #f1f5f9; }
.rcm-premium-wrapper .itc-btn-add { background: var(--primary-gradient); box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3); }
.rcm-premium-wrapper .itc-btn-add:hover { background: linear-gradient(135deg, #312e81, #4338ca); }

/* Category Switcher Dropdown */
.rcm-cat-switcher {
    padding: 8px 36px 8px 16px;
    font-size: 14px;
    font-weight: 600;
    color: #1e1b4b;
    background-color: #e0e7ff;
    border: 1px solid #c7d2fe;
    border-radius: 8px;
    appearance: none;
    cursor: pointer;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%233730a3' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: calc(100% - 12px) center;
    transition: all 0.2s;
}
.rcm-cat-switcher:hover { border-color: #818cf8; background-color: #c7d2fe; }
.rcm-cat-switcher:focus { outline: none; box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2); }
/* ============================================================
   IT CATEGORY DETAIL PAGE
   Table columns: Control ID | Control Description | Status Control
   Summary:       UPTI | Year | Quarter
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
/* PDF Download — red/rose outline */
.row-act-pdf {
    color: #e11d48;
    border-color: rgba(225,29,72,.3);
    background: rgba(225,29,72,.06);
}
.row-act-pdf:hover {
    background: #e11d48;
    border-color: #e11d48;
    color: #fff;
    box-shadow: 0 2px 8px rgba(225,29,72,.25);
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
.sc-drafting {
    background: rgba(139,92,246,.1);
    color: #6d28d9;
    border: 1px solid rgba(139,92,246,.2);
}
.sc-drafting::before { background: #8b5cf6; }
.sc-return-to-officer {
    background: rgba(220,38,38,.1);
    color: #dc2626;
    border: 1px solid rgba(220,38,38,.2);
}
.sc-return-to-officer::before { background: #ef4444; }
.sc-return-to-reviewer {
    background: rgba(217,119,6,.12);
    color: #c2410c;
    border: 1px solid rgba(217,119,6,.25);
}
.sc-return-to-reviewer::before { background: #ea580c; }

/* ── Workflow Action Buttons ─────────────────────────────── */
.wf-btn {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11.5px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: opacity 0.18s, transform 0.12s;
    white-space: nowrap;
}
.wf-btn:active { transform: scale(0.96); }
.wf-btn:disabled { opacity: 0.5; cursor: not-allowed; }
.btn-workflow-start {
    background: rgba(139,92,246,.12);
    color: #6d28d9;
    border: 1px solid rgba(139,92,246,.3);
}
.btn-workflow-start:hover:not(:disabled) { background: rgba(139,92,246,.22); }
.btn-workflow-submit, .btn-workflow-resubmit {
    background: rgba(217,119,6,.12);
    color: #b45309;
    border: 1px solid rgba(217,119,6,.3);
}
.btn-workflow-submit:hover:not(:disabled),
.btn-workflow-resubmit:hover:not(:disabled) { background: rgba(217,119,6,.22); }
.btn-workflow-approve {
    background: rgba(25,135,84,.1);
    color: #15803d;
    border: 1px solid rgba(25,135,84,.25);
}
.btn-workflow-approve:hover:not(:disabled) { background: rgba(25,135,84,.2); }
.btn-workflow-reject {
    background: rgba(220,38,38,.08);
    color: #dc2626;
    border: 1px solid rgba(220,38,38,.2);
}
.btn-workflow-reject:hover:not(:disabled) { background: rgba(220,38,38,.16); }
.wf-action-group {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    justify-content: center;
    margin-top: 5px;
}

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
    max-height: calc(100vh - 40px);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    animation: modal-enter .22s cubic-bezier(.4,0,.2,1) both;
}
@keyframes modal-enter {
    from { opacity: 0; transform: scale(.96) translateY(12px); }
    to   { opacity: 1; transform: scale(1)  translateY(0); }
}

/* Modal header */
.itc-modal-head {
    padding: 14px 20px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    background: var(--bg-body);
    flex-shrink: 0;
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
    width: 32px;
    height: 32px;
    background: var(--primary-gradient);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(25,135,84,.22);
}
.modal-title-icon i { font-size: 14px; color: #fff; }
.itc-modal-close-btn {
    width: 30px; height: 30px;
    border: 1.5px solid var(--border-color);
    background: var(--bg-white);
    border-radius: 7px;
    color: var(--text-muted);
    font-size: 15px;
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
    padding: 16px 20px 10px;
    overflow-y: auto;
    flex: 1;
}
.modal-ui-notice {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 9px 12px;
    background: #FFF7ED;
    border: 1px solid rgba(217,119,6,.2);
    border-radius: var(--radius-sm);
    margin-bottom: 14px;
    font-size: 12px;
    color: #92400e;
    line-height: 1.4;
}
.modal-ui-notice i { font-size: 14px; color: #d97706; flex-shrink: 0; }

/* Form fields */
.modal-form-group {
    margin-bottom: 12px;
}
.modal-form-group:last-child { margin-bottom: 0; }
.modal-label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 4px;
}
.modal-label span.required {
    color: #dc2626;
    margin-left: 2px;
}
.modal-input,
.modal-select,
.modal-textarea {
    width: 100%;
    padding: 6.5px 11px;
    border: 1.5px solid var(--border-color);
    border-radius: var(--radius-sm);
    font-size: 12.5px;
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
.modal-textarea { resize: vertical; min-height: 60px; height: 75px; line-height: 1.45; }

/* Two-column row */
.modal-form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

/* Modal footer */
.itc-modal-foot {
    padding: 12px 20px;
    border-top: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-top: 0;
    background: var(--bg-white);
    flex-shrink: 0;
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
    $quarterLabel = $quarter ? ($quarterLabels[$quarter] ?? strtoupper($quarter)) : '—';

    /*
     * Status Control — DB enum keys → display labels & badge classes
     * not_started      → "Not Started Yet"   (gray)
     * ongoing_review   → "On Going Review"   (amber)
     * ongoing_approval → "On Going Approval" (blue)
     * completed        → "Completed"         (green)
     */
    $scBadgeMap = [
        'not_started'        => ['label' => 'Not Started Yet',   'cls' => 'sc-not-started'],
        'drafting'           => ['label' => 'Drafting',          'cls' => 'sc-drafting'],
        'ongoing_review'     => ['label' => 'On Going Review',   'cls' => 'sc-ongoing-review'],
        'ongoing_approval'   => ['label' => 'On Going Approval', 'cls' => 'sc-ongoing-approval'],
        'return_to_officer'  => ['label' => 'Return to Officer', 'cls' => 'sc-return-to-officer'],
        'return_to_reviewer' => ['label' => 'Return to Reviewer','cls' => 'sc-return-to-reviewer'],
        'completed'          => ['label' => 'Completed',         'cls' => 'sc-completed'],
        'complete'           => ['label' => 'Completed',         'cls' => 'sc-completed'],
    ];

    // $controls is injected by controller
    $totalControls = $controls->count();

    // Applications for the modal dropdown
    // For IT RCM ($upti is null), use $allApplications from controller
    // For Dashboard view, use upti's applications
    if (isset($allApplications)) {
        $uptiApplications = $allApplications;
    } elseif ($upti) {
        $uptiApplications = $upti->applications()->orderBy('name')->get();
    } else {
        $uptiApplications = collect();
    }

    $allCategories   = \App\Models\ItCategory::orderBy('name')->get();

    // Safe quarter label (null for IT RCM)
    $quarterLabels = ['q1' => 'Q1', 'q2' => 'Q2', 'q3' => 'Q3', 'q4' => 'Q4'];
    $quarterLabel  = $quarter ? ($quarterLabels[$quarter] ?? strtoupper($quarter)) : '—';

    // Current authenticated user role
    $authUser = auth()->user();
    $authRole = $authUser->role ?? 'creator';

    // Extract the numeric suffix from existing control IDs (e.g. "C-IT-03" -> 3)
    $usedControlNumbers = $controls->map(function($c) {
        if (preg_match('/C-IT-(\d+)/i', $c->it_control_id ?? '', $m)) {
            return (int) $m[1];
        }
        return null;
    })->filter()->values()->toArray();
@endphp

<div class="rcm-premium-wrapper">

{{-- ══════════════════════════════════════════════════════
     PAGE HEADER (PREMIUM RCM)
     ══════════════════════════════════════════════════════ --}}
<div class="itc-page-header">

    <div class="itc-header-left">
        <nav class="itc-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('dashboard') }}">
                <i class="bi bi-house-fill" style="font-size:10px;"></i>&nbsp;Dashboard
            </a>
            @if($source === 'rcm')
                <span class="bc-sep"><i class="bi bi-chevron-right"></i></span>
                <span>IT RCM</span>
            @endif
            @if($upti)
                <span class="bc-sep"><i class="bi bi-chevron-right"></i></span>
                <span>{{ $upti->name }}</span>
            @endif
            @if($application)
                <span class="bc-sep"><i class="bi bi-chevron-right"></i></span>
                <span>{{ $application->name }}</span>
            @endif
            <span class="bc-sep"><i class="bi bi-chevron-right"></i></span>
            <span class="bc-cur">{{ $category->name }}</span>
        </nav>

        <h1 class="itc-page-title">
            <span class="itc-title-icon">
                <i class="bi {{ $category->icon }}"></i>
            </span>
            @if($source === 'rcm')
                <span style="font-size:0.65em; font-weight:500; color:var(--text-secondary); margin-right:6px;">[IT RCM]</span>
            @endif
            {{ $category->name }}
        </h1>
    </div>

    <a href="{{ route('rcm.index') }}" class="btn-back-itc">
        <i class="bi bi-arrow-left"></i> Back to IT RCM
    </a>
</div>

{{-- ── IT RCM Read-Only Notice ────────────────────────────────────── --}}
<div style="display:flex; align-items:center; gap:10px; padding:12px 18px; margin-bottom:1.25rem; background: linear-gradient(135deg, #eff6ff, #dbeafe); border:1px solid #bfdbfe; border-radius:10px; font-size:13px; color:#1d4ed8;">
    <i class="bi bi-info-circle-fill" style="font-size:18px; flex-shrink:0;"></i>
    <div>
        <strong>IT RCM — Overview Mode</strong> &nbsp;|&nbsp;
        Halaman ini menampilkan <strong>semua data control</strong> lintas UPTI, Aplikasi, dan Periode untuk keperluan review.
        Untuk menambah, mengedit, atau menghapus control, gunakan menu <strong>IT Category</strong> di Dashboard.
    </div>
</div>

@if(!auth()->user()->isAdmin())
{{-- ══════════════════════════════════════════════════════
     SUMMARY CARD — Application | Year | Quarter ONLY
     ══════════════════════════════════════════════════════ --}}
<div class="itc-summary-card">
    <div class="itc-summary-header">
        <i class="bi bi-info-circle-fill"></i>
        Assessment Overview
    </div>
    <div class="itc-summary-grid" style="grid-template-columns: repeat(4, 1fr);">

        <div class="itc-sum-cell">
            <span class="itc-sum-label">UPTI</span>
            <div class="itc-sum-value">
                <i class="bi bi-diagram-3"></i>
                {{ $upti->name }}
            </div>
        </div>

        <div class="itc-sum-cell">
            <span class="itc-sum-label">Application</span>
            <div class="itc-sum-value">
                <i class="bi bi-window-stack"></i>
                {{ $application ? $application->name : 'All Applications' }}
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
@endif


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

        {{-- No CRUD buttons in IT RCM — this is a read-only overview --}}
        {{-- Add Data and Delete All are available in IT Category (Dashboard) --}}

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
                    @if(auth()->user()->isAdmin())
                    <th class="col-upti">UPTI</th>
                    @endif
                    <th class="col-app">Application</th>
                    @if(!auth()->user()->isAdmin())
                    <th class="col-status th-center">Status Control</th>
                    @endif
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
            <strong>{{ $upti ? $upti->name : 'All UPTI' }}</strong> — <strong>{{ $category->name }}</strong>.<br>
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
                        <th style="width:42px; text-align:center; color:#94a3b8; font-size:12px;">#</th>
                        <th class="col-ctrlid sortable">Control ID</th>
                        <th class="col-desc">Control Description</th>
                        @if(auth()->user()->isAdmin())
                        <th class="col-upti">UPTI</th>
                        @endif
                        <th class="col-frekuensi">Frequency Description</th>
                        <th class="col-app">Application</th>
                        <th class="col-keyctrl">Key Control</th>
                        @if(!auth()->user()->isAdmin())
                        <th class="col-status th-center">Status Control</th>
                        @endif
                        <th class="col-actions">Actions</th>
                    </tr>
                </thead>
                <tbody id="itc-tbody">
                    @foreach ($controls as $ctrl)
                    @php
                        $scKey  = $ctrl->status_control ?? 'not_started';
                        $scInfo = $scBadgeMap[$scKey] ?? $scBadgeMap['not_started'];
                    @endphp
                    <tr
                        data-id="{{ $ctrl->id ?? '' }}"
                        data-ctrl-id="{{ $ctrl->it_control_id ?? '' }}"
                        data-ctrl-desc="{{ addslashes($ctrl->control_description ?? '') }}"
                        data-ctrl-frek="{{ $ctrl->keterangan_frekuensi ?? '' }}"
                        data-ctrl-upti="{{ $ctrl->application && $ctrl->application->upti ? $ctrl->application->upti->name : ($ctrl->upti ?? '') }}"
                        data-ctrl-app="{{ $ctrl->application ? $ctrl->application->name : '' }}"
                        data-ctrl-keyctrl="{{ $ctrl->key_control ?? '' }}"
                        data-ctrl-file-type="{{ $ctrl->file_type ?? '' }}"
                        data-ctrl-status="{{ $ctrl->status_control ?? 'not_started' }}"
                        data-cat-status="{{ $ctrl->status_it_category ?? 'not_completed' }}"
                        data-app-id="{{ $ctrl->application_id }}"
                        data-cat-id="{{ $ctrl->it_category_id }}"
                        data-evidences="{{ isset($ctrl->evidences) ? htmlspecialchars(json_encode($ctrl->evidences->filter(fn($e) => $e->file_type !== 'Berita Acara')->values()), ENT_QUOTES, 'UTF-8') : '[]' }}"
                    >
                        <td style="text-align:center; color:#94a3b8; font-size:12px; font-weight:600; width:42px;">{{ $loop->iteration }}</td>
                        <td class="col-ctrlid">
                            <span class="ctrl-id-pill">{{ $ctrl->it_control_id ?? '—' }}</span>
                        </td>
                        <td class="col-desc">
                            <div>{{ $ctrl->control_description ?? '—' }}</div>
                            @if(isset($ctrl->evidences) && $ctrl->evidences->count() > 0)
                                <div class="evidence-pill-list" style="display:flex; flex-wrap:wrap; gap:6px; margin-top:6px;">
                                    @foreach($ctrl->evidences as $ev)
                                        @if($ev->file_type === 'Berita Acara') @continue @endif
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
                                            @if(!empty($ev->file_type))
                                                <span style="background:#e0e7ff; color:#3730a3; font-size:10px; font-weight:700; padding:1px 5px; border-radius:3px; border:1px solid #c7d2fe; white-space:nowrap; margin-left:2px;">{{ $ev->file_type }}</span>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                            
                            @if($ctrl->reviewer_notes || $ctrl->approver_notes)
                                <div class="ctrl-notes-container" style="margin-top:8px; border-top:1px dashed #e2e8f0; padding-top:6px; font-size:11px;">
                                    @if($ctrl->reviewer_notes)
                                        <div style="background:#fef3c7; color:#92400e; padding:4px 8px; border-radius:4px; margin-bottom:4px; border-left:3px solid #f59e0b;">
                                            <strong>Manager Notes:</strong> 
                                            <span style="font-style:italic;">"{{ $ctrl->reviewer_notes }}"</span>
                                        </div>
                                    @endif
                                    @if($ctrl->approver_notes)
                                        <div style="background:#e0e7ff; color:#3730a3; padding:4px 8px; border-radius:4px; border-left:3px solid #6366f1;">
                                            <strong>Senior Manager Notes:</strong> 
                                            <span style="font-style:italic;">"{{ $ctrl->approver_notes }}"</span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </td>
                        @if(auth()->user()->isAdmin())
                        <td class="col-upti">
                            {{ $ctrl->application && $ctrl->application->upti ? $ctrl->application->upti->name : '—' }}
                        </td>
                        @endif
                        <td class="col-frekuensi">
                            {{ $ctrl->keterangan_frekuensi ?? '—' }}
                        </td>
                        <td class="col-app">
                            {{ $ctrl->application ? $ctrl->application->name : '—' }}
                        </td>
                        <td class="col-keyctrl">
                            {{ $ctrl->key_control === null ? '—' : ($ctrl->key_control ? 'Yes' : 'No') }}
                        </td>

                        @if(!auth()->user()->isAdmin())
                        <td class="col-status td-center">
                            {{-- Read-only status badge --}}
                            <span class="sc-badge {{ $scInfo['cls'] }}">{{ $scInfo['label'] }}</span>

                            {{-- Workflow action buttons — role-based --}}
                            @php
                                $wfActions = $ctrl->availableActions($authUser);
                            @endphp
                            @if(count($wfActions) > 0)
                                <div class="wf-action-group">
                                    @foreach($wfActions as $wfAct)
                                        <button type="button"
                                                class="wf-btn {{ $wfAct['class'] }} btn-wf-action"
                                                data-ctrl-id="{{ $ctrl->id }}"
                                                data-to-status="{{ $wfAct['to'] }}"
                                                data-label="{{ $wfAct['label'] }}"
                                                title="{{ $wfAct['label'] }}">
                                            @if($wfAct['action'] === 'approve')<i class="bi bi-check-lg"></i>@elseif($wfAct['action'] === 'reject')<i class="bi bi-x-lg"></i>@elseif($wfAct['action'] === 'start')<i class="bi bi-play-fill"></i>@else<i class="bi bi-send-fill"></i>@endif
                                            {{ $wfAct['label'] }}
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        @endif
                        <td class="col-actions">
                            <div class="row-act-group">
                                {{-- 1. Edit Control (admin-rcm) / View (admin-dashboard) / Add File (creator) --}}
                                @if(auth()->user()->isAdmin())
                                    @if($source === 'rcm')
                                    {{-- Admin via IT RCM: show full Edit button --}}
                                    <button type="button"
                                            class="row-act-btn btn-edit-ctrl row-act-edit"
                                            title="Edit Control"
                                            aria-label="Action Control {{ $ctrl->it_control_id }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    @else
                                    {{-- Admin via Dashboard: view only --}}
                                    <button type="button"
                                            class="row-act-btn btn-edit-ctrl row-act-view"
                                            title="View Control"
                                            aria-label="View Control {{ $ctrl->it_control_id }}">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                    @endif
                                @elseif(auth()->user()->isCreator())
                                    {{-- Always show View Details (Eye icon) --}}
                                    <button type="button"
                                            class="row-act-btn btn-edit-ctrl row-act-view"
                                            title="View Details"
                                            aria-label="View Control {{ $ctrl->it_control_id }}">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>

                                    {{-- If editable, also show Tambah/Edit File button --}}
                                    @if(in_array($ctrl->status_control, ['not_started', 'drafting', 'return_to_officer', 'ongoing_review', 'return_to_reviewer']))
                                    @php
                                        $hasUploadedFiles = isset($ctrl->evidences) && $ctrl->evidences->filter(fn($e) => $e->file_type !== 'Berita Acara')->count() > 0;
                                    @endphp
                                    <button type="button"
                                            class="row-act-btn btn-edit-ctrl row-act-edit"
                                            title="{{ $hasUploadedFiles ? 'Edit File' : 'Tambah File' }}"
                                            aria-label="Edit File {{ $ctrl->it_control_id }}">
                                        @if($hasUploadedFiles)
                                            <i class="bi bi-pencil-square"></i>
                                        @else
                                            <i class="bi bi-file-earmark-plus-fill"></i>
                                        @endif
                                    </button>
                                    @endif
                                @else
                                <button type="button"
                                        class="row-act-btn btn-edit-ctrl row-act-view"
                                        title="View Control"
                                        aria-label="Action Control {{ $ctrl->it_control_id }}">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                                @endif

                                @if($ctrl->status_control === 'completed')
                                <a href="{{ route('controls.beritaAcara', $ctrl->id) }}"
                                   class="row-act-btn row-act-pdf"
                                   title="Download Berita Acara"
                                   target="_blank">
                                    <i class="bi bi-file-earmark-pdf-fill"></i>
                                </a>
                                @endif

                                {{-- Delete button: Admin only when coming via IT RCM --}}
                                @if(auth()->user()->role === 'admin' && $source === 'rcm')
                                <button type="button"
                                        class="row-act-btn row-act-delete btn-delete-ctrl"
                                        title="Delete Control"
                                        data-ctrl-id="{{ $ctrl->it_control_id }}"
                                        aria-label="Delete {{ $ctrl->it_control_id }}">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                                @endif
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

                @if($source === 'rcm')
                {{-- ─── IT RCM: Admin can choose Application(s), UPTI, Year, Quarter ─── --}}

                <div class="modal-form-group">
                    <label class="modal-label">
                        Application <span class="required">*</span>
                        <span style="font-size:11px; font-weight:400; color:#6b7280;">(pilih satu atau lebih)</span>
                    </label>
                    <div id="mc-apps-list" style="display:flex; flex-direction:column; gap:6px; max-height:160px; overflow-y:auto;
                                                  border:1px solid var(--border-color,#d1d5db); border-radius:8px; padding:10px;
                                                  background:var(--input-bg,#f9fafb);">
                        @foreach($allApplications ?? [] as $app)
                        <label style="display:flex; align-items:center; gap:8px; font-size:13px; cursor:pointer; font-weight:400;">
                            <input type="checkbox" name="application_ids[]" value="{{ $app->id }}"
                                   style="width:15px; height:15px; cursor:pointer;">
                            <span>{{ $app->name }}</span>
                            @if($app->upti)
                                <span style="font-size:11px; color:#6b7280;">({{ $app->upti->name }})</span>
                            @endif
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="modal-form-row">
                    <div class="modal-form-group">
                        <label class="modal-label" for="mc-year">Year <span class="required">*</span></label>
                        <select class="modal-input" id="mc-year" name="year" required>
                            <option value="2026" selected>2026</option>
                            <option value="2025">2025</option>
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                        </select>
                    </div>
                    <div class="modal-form-group">
                        <label class="modal-label" for="mc-quarter">Quarter <span class="required">*</span></label>
                        <select class="modal-input" id="mc-quarter" name="quarter" required>
                            <option value="q1">Q1</option>
                            <option value="q2">Q2</option>
                            <option value="q3">Q3</option>
                            <option value="q4">Q4</option>
                        </select>
                    </div>
                </div>

                @else
                {{-- ─── Dashboard mode: application & period fixed ─── --}}
                <input type="hidden" name="year" value="{{ $year }}">
                <input type="hidden" name="quarter" value="{{ $quarter }}">
                <input type="hidden" name="application_id" value="{{ $application?->id }}">
                @endif

                <input type="hidden" id="mc-category" name="it_category_id" value="{{ $category->id }}">

                <div class="modal-form-row">

                    <div class="modal-form-group">
                        <label class="modal-label" for="mc-ctrl-num">
                            Control ID <span class="required">*</span>
                        </label>
                        {{-- Hidden field that stores the final C-IT-XX value --}}
                        <input type="hidden" id="mc-ctrl-id" name="it_control_id">
                        <div style="display:flex; align-items:center; gap:0;">
                            <span style="background:#f1f5f9; border:1px solid #cbd5e1; border-right:none; padding:6px 12px; border-radius:6px 0 0 6px; font-size:13px; font-weight:700; color:#475569; white-space:nowrap;">C-IT-</span>
                            <input type="number"
                                   class="modal-input"
                                   id="mc-ctrl-num"
                                   placeholder="e.g. 01"
                                   min="1"
                                   max="999"
                                   step="1"
                                   style="border-radius:0 6px 6px 0; border-left:none;"
                                   required>
                        </div>
                        <div id="mc-ctrl-id-feedback" style="font-size:11.5px; margin-top:4px; min-height:16px;"></div>
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
                              rows="3"
                              required></textarea>
                </div>

                <div class="modal-form-group">
                    <label class="modal-label" for="mc-frekuensi">Frequency Description</label>
                    <select class="modal-input" id="mc-frekuensi" name="keterangan_frekuensi">
                        <option value="">-- Select --</option>
                        <option value="Per Project">Per Project</option>
                        <option value="Quarterly">Quarterly</option>
                        <option value="Twice a year">Twice a year</option>
                        <option value="Yearly">Yearly</option>
                    </select>
                </div>
                <div class="modal-form-group">
                    <label class="modal-label">
                        UPTI <span class="required">*</span>
                        <span style="font-size:11px; font-weight:400; color:#6b7280;">(pilih satu atau lebih)</span>
                    </label>
                    <div id="mc-uptis-list" style="display:flex; flex-direction:column; gap:6px; max-height:160px; overflow-y:auto;
                                                  border:1px solid var(--border-color,#d1d5db); border-radius:8px; padding:10px;
                                                  background:var(--input-bg,#f9fafb);">
                        @foreach($allUptis as $u)
                        <label style="display:flex; align-items:center; gap:8px; font-size:13px; cursor:pointer; font-weight:400;">
                            <input type="checkbox" name="uptis[]" value="{{ $u->name }}" class="mc-upti-chk"
                                   style="width:15px; height:15px; cursor:pointer;"
                                   {{ ($application && $application->upti && $application->upti->name == $u->name) ? 'checked' : '' }}>
                            <span>{{ $u->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div class="modal-form-group">
                    <label class="modal-label" for="mc-key-control">Key Control</label>
                    <select class="modal-input" id="mc-key-control" name="key_control">
                        <option value="">-- Select --</option>
                        <option value="1">YES</option>
                        <option value="0">NO</option>
                    </select>
                </div>



                {{-- Upload Evidence removed for Admin --}}            </form>
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
                <span class="modal-title-icon" style="background:linear-gradient(135deg,{{ auth()->user()->isAdmin() ? '#2563eb,#1d4ed8' : (auth()->user()->isCreator() ? '#059669,#047857' : '#0891b2,#0e7490') }});">
                    @if(auth()->user()->isAdmin())
                        <i class="bi bi-pencil-square"></i>
                    @elseif(auth()->user()->isCreator())
                        <i class="bi bi-cloud-arrow-up-fill"></i>
                    @else
                        <i class="bi bi-eye-fill"></i>
                    @endif
                </span>
                @if(auth()->user()->isAdmin()) Edit Control @elseif(auth()->user()->isCreator()) Upload Evidence @else View Control @endif
            </h2>
            <button class="itc-modal-close-btn" id="editControlClose" type="button" aria-label="Close">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="itc-modal-body">

            <form id="editControlForm" autocomplete="off" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="ec-id" name="id">

                @php
                    $isRA = in_array(auth()->user()->role, ['reviewer', 'approver']);
                @endphp

                <input type="hidden" id="ec-application" name="application_id" value="{{ $application?->id }}">
                <input type="hidden" id="ec-category" name="it_category_id" value="{{ $category->id }}">
                <input type="hidden" id="ec-status-control" name="status_control">

                <style>
                    .shrink-details .modal-form-group { margin-bottom: 8px !important; }
                    .shrink-details .modal-label { font-size: 11px !important; margin-bottom: 2px !important; }
                    .shrink-details .modal-input, .shrink-details .modal-textarea { font-size: 11px !important; padding: 4px 8px !important; min-height: 28px !important; }
                    .shrink-details textarea.modal-textarea { height: 40px !important; }
                </style>

                <div class="{{ $isRA ? 'shrink-details' : '' }}">

                <div class="modal-form-row">
                    <div class="modal-form-group">
                        <label class="modal-label" for="ec-ctrl-id">Control ID</label>
                        <input type="text" class="modal-input" id="ec-ctrl-id"
                               name="it_control_id" placeholder="e.g. C-IT-01" required
                               {{ auth()->user()->isAdmin() ? '' : 'readonly' }}>
                    </div>
                </div>

                <div class="modal-form-group">
                    <label class="modal-label" for="ec-description">Control Description</label>
                    <textarea class="modal-textarea" id="ec-description"
                              name="control_description" rows="3"
                              placeholder="Control description..."
                              {{ auth()->user()->isAdmin() ? '' : 'readonly' }}></textarea>
                </div>

                <div class="modal-form-group">
                    <label class="modal-label" for="ec-frekuensi">Frequency Description</label>
                    <select class="modal-input" id="ec-frekuensi" name="keterangan_frekuensi"
                            {{ auth()->user()->isAdmin() ? '' : 'disabled' }}>
                        <option value="">-- Select --</option>
                        <option value="Per Project">Per Project</option>
                        <option value="Quarterly">Quarterly</option>
                        <option value="Twice a year">Twice a year</option>
                        <option value="Yearly">Yearly</option>
                    </select>
                </div>
                <div class="modal-form-group">
                    <label class="modal-label" for="ec-upti">UPTI</label>
                    <select class="modal-input" id="ec-upti" name="upti"
                            {{ auth()->user()->isAdmin() ? '' : 'disabled' }}>
                        <option value="">-- Select UPTI --</option>
                        <option value="Multi UPTI">Multi UPTI</option>
                        @foreach($allUptis as $u)
                            <option value="{{ $u->name }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>

                    <div class="modal-form-group">
                        <label class="modal-label" for="ec-key-control">Key Control</label>
                        <select class="modal-input" id="ec-key-control" name="key_control"
                                {{ auth()->user()->isAdmin() ? '' : 'disabled' }}>
                            <option value="">-- Select --</option>
                            <option value="1">YES</option>
                            <option value="0">NO</option>
                        </select>
                    </div>
                </div> <!-- End shrink-details wrapper -->

                {{-- Officer only: file_type is set per-file at upload time (hidden field, no UI for non-creator) --}}
                <input type="hidden" id="ec-file-type" name="file_type" value="">

                @if(auth()->user()->role === 'creator')
                {{-- Upload / Replace Evidence — per-file type selection --}}
                <div class="modal-form-group" id="ec-upload-section" style="background-color: #f0fdf4; border: 2px dashed #34d399; padding: 15px; border-radius: 8px; margin-top: 10px; margin-bottom: 20px;">
                    <label class="modal-label" for="ec-evidences" style="color: #065f46; font-size: 14px; font-weight: 700; margin-bottom: 8px;"><i class="bi bi-cloud-arrow-up-fill me-1"></i> Upload Evidence (PDF, Word, Excel)</label>
                    <div style="font-size:12px; color:#047857; margin-bottom:10px;">Select files, then specify the <strong>File Type</strong> for each file you upload.</div>
                    <input type="file" class="modal-input" id="ec-evidences" name="evidences[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx" style="padding: 10px; background: #fff; border: 1px solid #6ee7b7; cursor: pointer;">
                    <div style="font-size:11px; color:#059669; margin-top:6px;"><i class="bi bi-info-circle"></i> Max 2MB per file. You can upload up to 10 files at once.</div>
                    <div id="ec-selected-files-list" style="margin-top:12px; display:flex; flex-direction:column; gap:8px;"></div>
                </div>
                @endif

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
            @if((auth()->user()->isAdmin() && $source === 'rcm') || auth()->user()->isCreator())
            <span class="modal-foot-note" id="ec-save-msg">
                <i class="bi bi-info-circle"></i> Ready to save
            </span>
            @else
            <span class="modal-foot-note">
                <i class="bi bi-eye"></i> View only
            </span>
            @endif
            <div class="modal-foot-actions">
                <button type="button" class="modal-btn-cancel" id="editControlCancel">Close</button>
                @if(auth()->user()->isAdmin() && $source === 'rcm')
                <button type="button" class="modal-btn-save" id="btn-save-control"
                        style="background:linear-gradient(135deg,#2563eb,#1d4ed8);
                               box-shadow:0 2px 8px rgba(37,99,235,.25);">
                    <i class="bi bi-send-fill me-1"></i> Save Changes
                </button>
                @elseif(auth()->user()->isCreator())
                <button type="button" class="modal-btn-save" id="btn-save-control"
                        style="background:linear-gradient(135deg,#059669,#047857);
                               box-shadow:0 2px 8px rgba(5,150,105,.25);">
                    <i class="bi bi-cloud-arrow-up-fill me-1"></i> Upload & Save
                </button>
                @endif
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
            <input type="hidden" id="delete-all-upti-id" value="{{ $upti?->id }}">
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

</div> <!-- End rcm-premium-wrapper -->

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        if (ecDbId)   ecDbId.value   = id;
        if (ecApp)    ecApp.value    = appId;
        if (ecCat)    ecCat.value    = catId;
        if (ecId)     ecId.value     = ctrlId;
        if (ecDesc)   ecDesc.value   = ctrlDesc.replace(/\\/g, '');
        if (ecFrek)   ecFrek.value   = ctrlFrek;
        if (ecUpti)   ecUpti.value   = ctrlUpti;
        if (ecKeyCtrl) ecKeyCtrl.value = ctrlKeyCtrl;
        if (ecSt)     ecSt.value     = ctrlSt;

        var ecFiles  = document.getElementById('ec-existing-files');
        var ecEvInput = document.getElementById('ec-evidences');
        if (ecEvInput) ecEvInput.value = '';

        // Handle visibility of upload section and save button for creator / admin-rcm
        var isCreator = @json(auth()->user()->role === 'creator');
        var isAdmin   = @json(auth()->user()->isAdmin());
        var pageSource = @json($source);
        var ecUploadSection = document.getElementById('ec-upload-section');
        var btnSaveControl = document.getElementById('btn-save-control');
        var saveMsg = document.getElementById('ec-save-msg');

        if (isCreator) {
            var editableStatuses = ['not_started', 'drafting', 'return_to_officer', 'ongoing_review', 'return_to_reviewer'];
            var canEdit = editableStatuses.includes(ctrlSt);

            if (ecUploadSection) {
                ecUploadSection.style.display = canEdit ? 'block' : 'none';
            }
            if (btnSaveControl) {
                btnSaveControl.style.display = canEdit ? 'inline-flex' : 'none';
            }
            if (saveMsg) {
                saveMsg.innerHTML = canEdit ? '<i class="bi bi-info-circle"></i> Ready to save' : '<i class="bi bi-eye"></i> View only';
            }
        }

        var ecSelectedList = document.getElementById('ec-selected-files-list');
        if (ecSelectedList) ecSelectedList.innerHTML = '';

        if (ecFiles && id) {
            ecFiles.innerHTML = '<li style="font-size:12px; color:#9ca3af; font-style:italic;"><i class="bi bi-hourglass-split"></i> Loading files...</li>';
            loadAndRenderEvidences(id, function(evidences) {
                ecFiles.innerHTML = '';
                if (evidences.length === 0) {
                    ecFiles.innerHTML = '<li style="font-size:12px; color:#9ca3af; font-style:italic;">No evidence files currently attached.</li>';
                } else {
                    evidences.forEach(function(ev) {
                        var li = document.createElement('li');
                        var isCreator = @json(auth()->user()->role === 'creator');
                        var ftOptions = [
                            { value: '',                               label: '-- File Type --' },
                            { value: 'Population Data',                label: 'Population Data' },
                            { value: 'Information provided by Entity', label: 'Information provided by Entity' },
                            { value: 'Supporting Document',            label: 'Supporting Document' },
                            { value: 'Others',                         label: 'Others' },
                        ];
                        var optHtml = ftOptions.map(function(o) {
                            var sel = (o.value === (ev.file_type || '')) ? ' selected' : '';
                            return '<option value="' + o.value + '"' + sel + '>' + o.label + '</option>';
                        }).join('');

                        // File type display: editable for creator, badge for admin
                        var ftHtml = '';
                        var isEditableByCreator = isCreator && ['not_started', 'drafting', 'return_to_officer', 'ongoing_review', 'return_to_reviewer'].includes(ctrlSt);
                        var isEditableByAdmin = @json(auth()->user()->isAdmin());
                        if (isEditableByCreator || isEditableByAdmin) {
                            ftHtml = `<select name="existing_file_types[${ev.id}]" class="existing-file-type-select" data-original="${ev.file_type || ''}"
                                style="font-size:11.5px; padding:3px 7px; border:1px solid #d1d5db; border-radius:5px; background:#fff; color:#111827; cursor:pointer; min-width:160px; max-width:200px;"
                                title="Edit file type for this file">${optHtml}</select>`;
                        } else {
                            var ftLabel = ev.file_type
                                ? `<span style="background:#e0e7ff; color:#3730a3; font-size:11px; font-weight:600; padding:2px 8px; border-radius:4px; border:1px solid #c7d2fe; white-space:nowrap;">${ev.file_type}</span>`
                                : `<span style="color:#9ca3af; font-size:11px; font-style:italic;">No type</span>`;
                            ftHtml = ftLabel;
                        }

                        var isRA = @json(in_array(auth()->user()->role, ['reviewer', 'approver']));
                        var fileUrl = '/evidence/' + ev.id;
                        var fileLinkHtml = isRA ?
                            `<a href="${fileUrl}" target="_blank" style="font-weight:700; font-size:14px; color:#1d4ed8; text-decoration:none; display:flex; align-items:center; gap:8px; padding:8px 12px; background:#eff6ff; border-radius:6px; border:1px solid #bfdbfe; flex:1; overflow:hidden;">
                                <i class="bi bi-box-arrow-up-right" style="font-size:16px;"></i> <span style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">Open File: ${ev.original_name}</span>
                            </a>` :
                            `<a href="${fileUrl}" target="_blank" style="display:flex; align-items:center; gap:8px; font-size:12.5px; overflow:hidden; flex:1; min-width:0; color:#1d4ed8; text-decoration:none; font-weight:600;">
                                <i class="bi bi-file-earmark-text" style="font-size:15px; flex-shrink:0;"></i>
                                <span style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:180px;" title="${ev.original_name}">${ev.original_name}</span>
                            </a>`;

                        var canDeleteEv = isEditableByAdmin || (isCreator && ['not_started', 'drafting', 'return_to_officer', 'ongoing_review', 'return_to_reviewer'].includes(ctrlSt));

                        li.style.cssText = 'background:#f8fafc; padding:8px 12px; border-radius:6px; border:1px solid #e2e8f0; display:flex; align-items:center; gap:8px;';
                        li.innerHTML = `
                            ${fileLinkHtml}
                            ${!isRA ? `<span style="flex-shrink:0;">${ftHtml}</span>` : `<span style="flex-shrink:0; margin-left:auto;">${ftHtml}</span>`}
                            ${ canDeleteEv ? 
                            `<button type="button" class="btn-delete-ev btn-sm" data-id="${ev.id}" style="color:#dc2626; background:#fef2f2; border:none; padding:4px 8px; border-radius:4px; cursor:pointer; font-size:11.5px; flex-shrink:0;" title="Delete file"><i class="bi bi-trash3-fill"></i> Delete</button>`
                            : '' }
                        `;
                        ecFiles.appendChild(li);

                        // If admin is editing and the control is submitted, attach confirmation
                        if (isEditableByAdmin && !['not_started', 'drafting', 'return_to_officer'].includes(ctrlSt)) {
                            var selectEl = li.querySelector('.existing-file-type-select');
                            if (selectEl) {
                                selectEl.addEventListener('change', function(e) {
                                    if (!confirm('Changing the File Type will return this Control to the review and approval workflow (Return to Reviewer status). Are you sure you want to proceed?')) {
                                        // Revert the select change
                                        this.value = this.dataset.original;
                                    } else {
                                        this.dataset.original = this.value;
                                    }
                                });
                            }
                        } else if (isCreator && ['ongoing_review', 'return_to_reviewer'].includes(ctrlSt)) {
                            var selectEl = li.querySelector('.existing-file-type-select');
                            if (selectEl) {
                                selectEl.addEventListener('change', function(e) {
                                    if (!confirm('Changing the File Type will revert this Control to Drafting. You will need to click "Send to Manager" again to let the manager see it. Are you sure you want to proceed?')) {
                                        // Revert the select change
                                        this.value = this.dataset.original;
                                    } else {
                                        this.dataset.original = this.value;
                                    }
                                });
                            }
                        }
                    });
                }
            });
        }


        openModal(editModal);
    });

    // Dynamic Multi-File Selection Preview Helper
    // showFileType: if true, each file row includes a File Type dropdown (file_types[])
    function setupFilePickerPreview(inputId, previewListId, showFileType) {
        var input = document.getElementById(inputId);
        var container = document.getElementById(previewListId);
        if (!input || !container) return;

        var FILE_TYPE_OPTIONS = [
            { value: '',                               label: '-- Select File Type *' },
            { value: 'Population Data',                label: 'Population Data' },
            { value: 'Information provided by Entity', label: 'Information provided by Entity' },
            { value: 'Supporting Document',            label: 'Supporting Document' },
            { value: 'Others',                         label: 'Others' },
        ];

        input.addEventListener('change', function () {
            container.innerHTML = '';
            if (!this.files || this.files.length === 0) return;

            if (this.files.length > 10) {
                alert('You can only upload a maximum of 10 files at once.');
                input.value = '';
                return;
            }

            Array.from(this.files).forEach(function (file, idx) {
                if (file.size > 2 * 1024 * 1024) {
                    alert('File "' + file.name + '" is too large. Maximum allowed size is 2MB.');
                    input.value = '';
                    container.innerHTML = '';
                    return;
                }
                var sizeMb = (file.size / (1024 * 1024)).toFixed(2);
                var div = document.createElement('div');

                if (showFileType) {
                    // Per-file row: icon + name + size + file type dropdown
                    div.style.cssText = 'background:#f0fdf4; border:1px solid #bbf7d0; padding:8px 12px; border-radius:8px; display:flex; flex-direction:column; gap:6px; font-size:12px;';

                    // Build select options HTML
                    var optionsHtml = FILE_TYPE_OPTIONS.map(function(o) {
                        return '<option value="' + o.value + '">' + o.label + '</option>';
                    }).join('');

                    div.innerHTML = `
                        <div style="display:flex; align-items:center; gap:8px;">
                            <i class="bi bi-file-earmark-arrow-up-fill text-success" style="font-size:15px; flex-shrink:0;"></i>
                            <span style="font-weight:600; text-overflow:ellipsis; overflow:hidden; white-space:nowrap; max-width:220px;" title="${file.name}">${file.name}</span>
                            <span style="font-size:11px; color:#6b7280; margin-left:auto; white-space:nowrap;">(${sizeMb} MB)</span>
                        </div>
                        <div style="display:flex; align-items:center; gap:8px;">
                            <label style="font-size:11.5px; font-weight:600; color:#374151; white-space:nowrap; min-width:70px;">File Type <span style="color:#dc2626;">*</span></label>
                            <select name="file_types[]" required
                                style="flex:1; font-size:12px; padding:4px 8px; border:1px solid #d1d5db; border-radius:6px; background:#fff; color:#111827; min-width:0; cursor:pointer;">
                                ${optionsHtml}
                            </select>
                        </div>
                    `;
                } else {
                    // Simple row: icon + name + size
                    div.style.cssText = 'background:#f0fdf4; border:1px solid #bbf7d0; padding:6px 10px; border-radius:6px; display:flex; align-items:center; gap:8px; font-size:12px;';
                    div.innerHTML = `
                        <i class="bi bi-file-earmark-arrow-up-fill text-success" style="font-size:15px; flex-shrink:0;"></i>
                        <span style="font-weight:600; text-overflow:ellipsis; overflow:hidden; white-space:nowrap; max-width:260px;" title="${file.name}">${file.name}</span>
                        <span style="font-size:11px; color:#6b7280; margin-left:auto;">(${sizeMb} MB)</span>
                    `;
                }

                container.appendChild(div);
            });
        });
    }

    setupFilePickerPreview('mc-evidences', 'mc-selected-files-list', false);
    // Creator (officer): show per-file File Type dropdown
    setupFilePickerPreview('ec-evidences', 'ec-selected-files-list', {{ auth()->user()->role === 'creator' ? 'true' : 'false' }});

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

    // ── Control ID prefix input: validate number and build hidden field ──────────
    (function() {
        var usedNums = @json($usedControlNumbers); // array of already-used numbers
        var numInput  = document.getElementById('mc-ctrl-num');
        var hiddenId  = document.getElementById('mc-ctrl-id');
        var feedback  = document.getElementById('mc-ctrl-id-feedback');

        if (!numInput) return;

        function padNum(n) {
            return n < 10 ? '0' + n : '' + n;
        }

        function validate() {
            var raw = numInput.value.trim();
            var num = parseInt(raw, 10);

            if (!raw || isNaN(num) || num < 1) {
                hiddenId.value = '';
                hiddenId.removeAttribute('value');
                feedback.textContent = '';
                feedback.style.color = '';
                numInput.setCustomValidity('Please enter a valid number.');
                return;
            }

            if (usedNums.includes(num)) {
                hiddenId.value = '';
                feedback.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i> C-IT-' + padNum(num) + ' is already in use. Please choose a different number.';
                feedback.style.color = '#dc2626';
                numInput.setCustomValidity('This number is already in use.');
                return;
            }

            var fullId = 'C-IT-' + padNum(num);
            hiddenId.value = fullId;
            feedback.innerHTML = '<i class="bi bi-check-circle-fill"></i> Will be saved as: <strong>' + fullId + '</strong>';
            feedback.style.color = '#059669';
            numInput.setCustomValidity('');
        }

        numInput.addEventListener('input', validate);
        numInput.addEventListener('change', validate);
    })();

    // Add Control logic
    var btnSaveAddControl = document.getElementById('btn-save-add-control');
    if (btnSaveAddControl) {
        btnSaveAddControl.addEventListener('click', function() {
            var form = document.getElementById('addControlForm');

            // Build final ID before submitting
            var numInput = document.getElementById('mc-ctrl-num');
            var hiddenId = document.getElementById('mc-ctrl-id');
            var usedNums = @json($usedControlNumbers);
            var num = parseInt((numInput ? numInput.value : ''), 10);

            if (!num || num < 1) {
                showNotification('Please enter a valid number for the Control ID.', 'danger');
                if (numInput) numInput.focus();
                return;
            }
            if (usedNums.includes(num)) {
                showNotification('Control ID C-IT-' + (num < 10 ? '0' + num : num) + ' is already in use. Please choose a different number.', 'danger');
                if (numInput) numInput.focus();
                return;
            }

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
                 showNotification(data.message || 'Evidence deleted', 'success');
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
                // If there are required dropdowns that aren't filled, alert the user explicitly
                var invalidFields = form.querySelectorAll(':invalid');
                if (invalidFields.length > 0) {
                    alert('Please fill out all required fields, including selecting a "File Type" for each uploaded file.');
                }
                form.reportValidity();
                return;
            }
            
            var id = document.getElementById('ec-id').value;
            if (!id) return;
            
            var msg = document.getElementById('ec-save-msg');
            msg.innerHTML = '<i class="bi bi-hourglass-split"></i> Sending to approval...';
            btnSaveControl.disabled = true;

            var formData = new FormData(form);
            formData.append('_method', 'PUT');

            // If no files are selected, do not send the empty evidences[] array
            var fileInput = document.getElementById('ec-evidences');
            if (fileInput && fileInput.files.length === 0) {
                formData.delete('evidences[]');
            }

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
                showNotification(err.message || 'An error occurred while sending.', 'danger');
            })
            .finally(() => {
                msg.innerHTML = '<i class="bi bi-info-circle"></i> Ready to send';
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
            var uptiId = document.getElementById('delete-all-upti-id').value;
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
                    upti_id: uptiId,
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

    /* ── Workflow Action Buttons ─────────────────────────── */
    document.addEventListener('click', function(e) {
        var btn = e.target.closest('.btn-wf-action');
        if (!btn) return;

        var controlId = btn.dataset.ctrlId;
        var toStatus  = btn.dataset.toStatus;
        var label     = btn.dataset.label;

        if (!controlId || !toStatus) return;

        // Is it an action that requires notes (Approve/Reject)?
        var requiresNotes = ['ongoing_approval', 'completed', 'return_to_officer', 'return_to_reviewer'].includes(toStatus);
        
        if (requiresNotes) {
            Swal.fire({
                title: label + ' Control',
                input: 'textarea',
                inputLabel: 'Please enter your notes (minimum 3 words)',
                inputPlaceholder: 'Type your notes here...',
                inputAttributes: {
                    'aria-label': 'Type your notes here'
                },
                showCancelButton: true,
                confirmButtonText: 'Submit ' + label,
                confirmButtonColor: toStatus.includes('return') ? '#dc3545' : '#198754',
                preConfirm: (notes) => {
                    if (!notes || notes.trim() === '') {
                        Swal.showValidationMessage('Notes cannot be empty');
                        return false;
                    }
                    var wordCount = notes.trim().split(/\s+/).length;
                    if (wordCount < 3) {
                        Swal.showValidationMessage('Notes must be at least 3 words long');
                        return false;
                    }
                    return notes;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    processWorkflowAction(btn, controlId, toStatus, result.value);
                }
            });
        } else {
            // For simple actions (like Send to Manager)
            processWorkflowAction(btn, controlId, toStatus, null);
        }
    });

    function processWorkflowAction(btn, controlId, toStatus, notes) {
        // Disable all buttons in this row while request is in flight
        var row = btn.closest('tr');
        var btns = row ? row.querySelectorAll('.btn-wf-action') : [btn];
        btns.forEach(function(b) { b.disabled = true; });

        var payload = { to_status: toStatus };
        if (notes) {
            payload.notes = notes;
        }

        fetch('/controls/' + controlId + '/transition', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(function(response) {
            return response.json().then(function(data) {
                data._httpStatus = response.status;
                return data;
            });
        })
        .then(function(data) {
            if (data.success) {
                showNotification(data.message || 'Status updated successfully.', 'success');
                setTimeout(function() { window.location.reload(); }, 700);
            } else {
                if (data.errors && data.errors.notes) {
                    showNotification(data.errors.notes[0], 'danger');
                } else {
                    showNotification(data.message || 'Action failed.', 'danger');
                }
                btns.forEach(function(b) { b.disabled = false; });
            }
        })
        .catch(function(err) {
            showNotification(err.message || 'An error occurred.', 'danger');
            btns.forEach(function(b) { b.disabled = false; });
        });
    }

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
