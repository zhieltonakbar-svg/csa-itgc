@extends('layouts.app')

@section('title', $category->name . ' — ' . $application->name . ' — CSA - ITGC')

@push('styles')
<style>
/* ============================================================
   CSA - ITGC
   IT CATEGORY DETAIL PAGE
   GREEN THEME
   ============================================================ */

.itc-page-header {
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap:16px;
    margin-bottom:1.5rem;
    flex-wrap:wrap;
}

.itc-header-left {
    display:flex;
    flex-direction:column;
    gap:8px;
    min-width:0;
}

.itc-breadcrumb {
    display:flex;
    align-items:center;
    gap:5px;
    font-size:12.5px;
    flex-wrap:wrap;
    color:#64748b;
}

.itc-breadcrumb a {
    color:#64748b;
    text-decoration:none;
    transition:.18s;
}

.itc-breadcrumb a:hover {
    color:#198754;
}

.itc-breadcrumb .bc-sep {
    color:#94a3b8;
    font-size:9px;
}

.itc-breadcrumb .bc-cur {
    color:#152238;
    font-weight:600;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
    max-width:220px;
}

.itc-page-title {
    font-size:20px;
    font-weight:800;
    color:#152238;
    letter-spacing:-.3px;
    margin:0;
    display:flex;
    align-items:center;
    gap:10px;
    line-height:1.25;
}

.itc-title-icon {
    width:38px;
    height:38px;
    background:linear-gradient(135deg,#198754,#157347);
    border-radius:9px;
    display:flex;
    align-items:center;
    justify-content:center;
    flex-shrink:0;
    box-shadow:0 3px 12px rgba(25,135,84,.25);
}

.itc-title-icon i {
    font-size:18px;
    color:#fff;
}

.btn-back-itc {
    display:inline-flex;
    align-items:center;
    gap:6px;
    font-size:13px;
    font-weight:500;
    color:#64748b;
    text-decoration:none;
    padding:8px 16px;
    border-radius:8px;
    border:1.5px solid #dbe3e8;
    background:#fff;
    transition:.18s;
    white-space:nowrap;
    flex-shrink:0;
    align-self:flex-start;
}

.btn-back-itc:hover {
    border-color:#198754;
    color:#198754;
    background:#eaf6ef;
}

/* ============================================================
   SUMMARY
   ============================================================ */

.itc-summary-card {
    background:#fff;
    border:1px solid #dbe3e8;
    border-radius:12px;
    box-shadow:0 8px 25px rgba(25,135,84,.06);
    margin-bottom:1.25rem;
    overflow:hidden;
}

.itc-summary-header {
    padding:10px 20px;
    background:#f8fafb;
    border-bottom:1px solid #dbe3e8;
    display:flex;
    align-items:center;
    gap:7px;
    font-size:11.5px;
    font-weight:700;
    text-transform:uppercase;
    letter-spacing:.6px;
    color:#64748b;
}

.itc-summary-header i {
    color:#198754;
    font-size:14px;
}

.itc-summary-grid {
    display:grid;
    grid-template-columns:repeat(3,1fr);
}

.itc-sum-cell {
    padding:18px 24px;
    border-right:1px solid #edf1f3;
}

.itc-sum-cell:last-child {
    border-right:none;
}

.itc-sum-label {
    font-size:10.5px;
    font-weight:600;
    text-transform:uppercase;
    letter-spacing:.65px;
    color:#94a3b8;
    margin-bottom:8px;
    display:block;
}

.itc-sum-value {
    font-size:14px;
    font-weight:600;
    color:#152238;
    display:flex;
    align-items:center;
    gap:8px;
    line-height:1.3;
}

.itc-sum-value i {
    font-size:16px;
    color:#198754;
    flex-shrink:0;
}

/* ============================================================
   SECTION
   ============================================================ */

.itc-section-label {
    display:flex;
    align-items:center;
    gap:9px;
    font-size:11px;
    font-weight:700;
    text-transform:uppercase;
    letter-spacing:.7px;
    color:#94a3b8;
    margin-bottom:10px;
}

.itc-section-label::after {
    content:'';
    flex:1;
    height:1px;
    background:#dbe3e8;
}

.itc-section-label i {
    color:#198754;
    font-size:13px;
}

/* ============================================================
   TOOLBAR
   ============================================================ */

.itc-toolbar {
    background:#fff;
    border:1px solid #dbe3e8;
    border-radius:12px 12px 0 0;
    border-bottom:1px solid #edf1f3;
    padding:12px 18px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    flex-wrap:wrap;
}

.itc-toolbar-left {
    display:flex;
    align-items:center;
    gap:8px;
    flex-wrap:wrap;
    flex:1;
    min-width:0;
}

.itc-toolbar-right {
    display:flex;
    align-items:center;
    gap:8px;
    flex-shrink:0;
}

.itc-search-wrap {
    position:relative;
    flex:1;
    min-width:180px;
    max-width:280px;
}

.itc-search-wrap i {
    position:absolute;
    left:10px;
    top:50%;
    transform:translateY(-50%);
    color:#94a3b8;
    font-size:13px;
    pointer-events:none;
}

.itc-search-input {
    width:100%;
    padding:7px 12px 7px 32px;
    border:1.5px solid #dbe3e8;
    border-radius:8px;
    font-size:13px;
    background:#f8fafb;
    font-family:inherit;
    color:#152238;
    transition:.18s;
    outline:none;
}

.itc-search-input:focus {
    border-color:#198754;
    box-shadow:0 0 0 3px rgba(25,135,84,.09);
    background:#fff;
}

.itc-search-input::placeholder {
    color:#94a3b8;
}

.itc-tb-btn {
    padding:7px 13px;
    border-radius:8px;
    border:1.5px solid #dbe3e8;
    background:#fff;
    color:#152238;
    font-size:13px;
    font-weight:500;
    cursor:pointer;
    display:inline-flex;
    align-items:center;
    gap:5px;
    transition:.18s;
    font-family:inherit;
    white-space:nowrap;
    text-decoration:none;
}

.itc-tb-btn:hover,
.itc-tb-btn.active {
    border-color:#198754;
    color:#198754;
    background:#eaf6ef;
}

.itc-tb-btn i {
    font-size:14px;
}

.itc-total-pill {
    display:inline-flex;
    align-items:center;
    gap:6px;
    padding:6px 14px;
    border-radius:20px;
    background:#eaf6ef;
    font-size:12.5px;
    font-weight:600;
    color:#64748b;
    white-space:nowrap;
    border:1px solid rgba(25,135,84,.15);
}

.itc-total-pill strong {
    color:#198754;
    font-weight:800;
}

.itc-total-pill i {
    color:#198754;
    font-size:13px;
}

.itc-btn-add {
    padding:7px 16px;
    border-radius:8px;
    border:none;
    background:linear-gradient(135deg,#198754,#157347);
    color:#fff;
    font-size:13px;
    font-weight:700;
    cursor:pointer;
    display:inline-flex;
    align-items:center;
    gap:6px;
    transition:.18s;
    font-family:inherit;
    white-space:nowrap;
    box-shadow:0 2px 8px rgba(25,135,84,.22);
}

.itc-btn-add:hover {
    background:linear-gradient(135deg,#157347,#146c43);
    box-shadow:0 4px 16px rgba(25,135,84,.3);
    transform:translateY(-1px);
}

.itc-btn-delete-all {
    padding:7px 16px;
    border-radius:8px;
    border:none;
    background:linear-gradient(135deg,#dc3545,#bb2d3b);
    color:#fff;
    font-size:13px;
    font-weight:700;
    cursor:pointer;
    display:inline-flex;
    align-items:center;
    gap:6px;
    transition:.18s;
    font-family:inherit;
    white-space:nowrap;
    box-shadow:0 2px 8px rgba(220,53,69,.2);
}

.itc-btn-delete-all:hover {
    background:linear-gradient(135deg,#c82333,#a71d2a);
    box-shadow:0 4px 16px rgba(220,53,69,.3);
    transform:translateY(-1px);
}

/* ============================================================
   TABLE
   ============================================================ */

.itc-table-wrap {
    background:#fff;
    border:1px solid #dbe3e8;
    border-top:none;
    border-radius:0 0 12px 12px;
    overflow:hidden;
}

.itc-table-scroll {
    overflow-x:auto;
    -webkit-overflow-scrolling:touch;
}

.itc-table {
    width:100%;
    border-collapse:collapse;
    min-width:850px;
}

.itc-table thead th {
    padding:11px 16px;
    font-size:11px;
    font-weight:700;
    text-transform:uppercase;
    letter-spacing:.55px;
    color:#64748b;
    background:#f8fafb;
    border-bottom:2px solid #dbe3e8;
    white-space:nowrap;
    vertical-align:middle;
}

.itc-table thead th:first-child {
    padding-left:20px;
}

.itc-table thead th:last-child {
    padding-right:20px;
}

.itc-table thead th.th-center {
    text-align:center;
}

.itc-table tbody td {
    padding:12px 16px;
    font-size:13.5px;
    border-bottom:1px solid #edf1f3;
    vertical-align:middle;
    color:#152238;
    line-height:1.5;
}

.itc-table tbody td:first-child {
    padding-left:20px;
}

.itc-table tbody td:last-child {
    padding-right:20px;
}

.itc-table tbody td.td-center {
    text-align:center;
}

.itc-table tbody tr {
    transition:background .14s;
}

.itc-table tbody tr:hover {
    background:#f5fbf7;
}

.itc-table tbody tr:last-child td {
    border-bottom:none;
}

.col-ctrlid {
    width:130px;
    white-space:nowrap;
}

.col-status {
    width:170px;
    text-align:center;
}

.col-status-wide {
    width:340px !important;
}

.col-handled-by {
    width:280px;
}

.rcm-handled-by {
    display:flex;
    flex-direction:column;
    gap:3px;
    font-size:11.5px;
    color:#475569;
}

.col-actions {
    width:120px;
    text-align:center;
    white-space:nowrap;
}

/* ============================================================
   CONTROL ID
   ============================================================ */

.ctrl-id-pill {
    display:inline-block;
    padding:4px 11px;
    background:#eaf6ef;
    color:#198754;
    border-radius:6px;
    font-size:12px;
    font-weight:700;
    letter-spacing:.3px;
    white-space:nowrap;
    border:1px solid rgba(25,135,84,.18);
}

/* ============================================================
   STATUS
   ============================================================ */

.status-badge {
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:5px;
    padding:5px 12px;
    border-radius:20px;
    font-size:11.5px;
    font-weight:700;
    white-space:nowrap;
}

.status-badge::before {
    content:'';
    width:6px;
    height:6px;
    border-radius:50%;
    flex-shrink:0;
}

.sc-not-started {
    background:rgba(107,114,128,.08);
    color:#4b5563;
    border:1px solid rgba(107,114,128,.2);
}

.sc-not-started::before {
    background:#9ca3af;
}

.sc-ongoing-review {
    background:rgba(217,119,6,.10);
    color:#b45309;
    border:1px solid rgba(217,119,6,.22);
}

.sc-ongoing-review::before {
    background:#d97706;
}

.sc-ongoing-approval {
    background:rgba(59,130,246,.10);
    color:#1d4ed8;
    border:1px solid rgba(59,130,246,.18);
}

.sc-ongoing-approval::before {
    background:#3b82f6;
}

.sc-completed {
    background:rgba(25,135,84,.10);
    color:#15803d;
    border:1px solid rgba(25,135,84,.18);
}

.sc-completed::before {
    background:#198754;
}

.rcm-completed-row {
    background:rgba(25,135,84,.05);
}

.rcm-completed-row:hover {
    background:rgba(25,135,84,.09);
}

/* ============================================================
   ACTION BUTTONS
   ============================================================ */

.row-act-group {
    display:inline-flex;
    align-items:center;
    gap:5px;
}

.row-act-stack {
    display:inline-flex;
    flex-direction:column;
    align-items:center;
    gap:5px;
}

.row-act-btn {
    width:32px;
    height:32px;
    border-radius:7px;
    border:1.5px solid;
    background:transparent;
    cursor:pointer;
    font-size:13px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    transition:.18s;
    font-family:inherit;
    flex-shrink:0;
}

.row-act-send-btn {
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:5px;
    padding:6px 10px;
    border-radius:7px;
    border:1.5px solid #ddd6fe;
    background:#f5f3ff;
    color:#7c3aed;
    font-size:11px;
    font-weight:700;
    cursor:pointer;
    white-space:nowrap;
    transition:.18s;
    font-family:inherit;
}

.row-act-send-btn:hover {
    background:#7c3aed;
    border-color:#7c3aed;
    color:#fff;
}

.row-act-send-btn .row-act-send-text {
    line-height:1;
}

.row-act-view {
    color:#0891b2;
    border-color:rgba(8,145,178,.3);
    background:rgba(8,145,178,.06);
}

.row-act-view:hover {
    background:#0891b2;
    border-color:#0891b2;
    color:#fff;
}

.row-act-edit {
    color:#198754;
    border-color:rgba(25,135,84,.35);
    background:#eaf6ef;
}

.row-act-edit:hover {
    background:#198754;
    border-color:#198754;
    color:#fff;
}

.row-act-delete {
    color:#dc3545;
    border-color:rgba(220,53,69,.3);
    background:rgba(220,53,69,.06);
}

.row-act-delete:hover {
    background:#dc3545;
    border-color:#dc3545;
    color:#fff;
}

.row-act-approve {
    color:#198754;
    border-color:rgba(25,135,84,.35);
    background:rgba(25,135,84,.08);
}

.row-act-approve:hover {
    background:#198754;
    border-color:#198754;
    color:#fff;
}

.row-act-reject {
    color:#dc3545;
    border-color:rgba(220,53,69,.35);
    background:rgba(220,53,69,.08);
}

.row-act-reject:hover {
    background:#dc3545;
    border-color:#dc3545;
    color:#fff;
}

.row-act-pdf {
    color:#e11d48;
    border-color:rgba(225,29,72,.3);
    background:rgba(225,29,72,.06);
    text-decoration:none;
}

.row-act-pdf:hover {
    background:#e11d48;
    border-color:#e11d48;
    color:#fff;
}

/* ============================================================
   EVIDENCE
   ============================================================ */

.evidence-pill {
    display:inline-flex;
    align-items:center;
    gap:5px;
    background:#f8fafc;
    border:1px solid #dbe3e8;
    padding:3px 8px;
    border-radius:5px;
    font-size:11px;
    color:#334155;
    text-decoration:none;
}

.evidence-pill:hover {
    border-color:#b8c8d0;
    background:#fff;
}

.ctrl-notes-container {
    margin-top:8px;
    border-top:1px dashed #e2e8f0;
    padding-top:6px;
    font-size:11px;
}

/* ============================================================
   EMPTY STATE
   ============================================================ */

.itc-empty-state {
    padding:80px 24px 72px;
    text-align:center;
    background:#fff;
    border:1px solid #dbe3e8;
    border-top:none;
    border-radius:0 0 12px 12px;
}

.itc-empty-icon-wrap {
    width:96px;
    height:96px;
    border-radius:50%;
    background:linear-gradient(135deg,#eaf6ef 0%,#d1e7dd 100%);
    display:inline-flex;
    align-items:center;
    justify-content:center;
    margin-bottom:22px;
    position:relative;
    box-shadow:0 4px 20px rgba(25,135,84,.12);
}

.itc-empty-icon-wrap i {
    font-size:38px;
    color:#198754;
}

.itc-empty-icon-wrap::before {
    content:'';
    position:absolute;
    inset:-8px;
    border-radius:50%;
    border:2px dashed rgba(25,135,84,.2);
}

.itc-empty-state h5 {
    font-size:16px;
    font-weight:800;
    color:#152238;
    margin:0 0 10px;
}

.itc-empty-state p {
    font-size:13.5px;
    color:#64748b;
    max-width:420px;
    display:inline-block;
    line-height:1.7;
    margin:0 0 22px;
}

.itc-empty-tag {
    display:inline-flex;
    align-items:center;
    gap:6px;
    padding:6px 18px;
    border-radius:20px;
    background:#eaf6ef;
    color:#198754;
    font-size:12.5px;
    font-weight:700;
    border:1px solid rgba(25,135,84,.15);
}

/* ============================================================
   FOOTER
   ============================================================ */

.itc-footer {
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:11px 20px;
    background:#fff;
    border:1px solid #dbe3e8;
    border-top:1px solid #edf1f3;
    border-radius:0 0 12px 12px;
}

.itc-footer-info {
    font-size:12.5px;
    color:#64748b;
}

.itc-footer-pag {
    display:flex;
    align-items:center;
    gap:4px;
}

.pag-btn {
    padding:5px 10px;
    border:1px solid #dbe3e8;
    border-radius:8px;
    background:#fff;
    color:#152238;
    font-size:12.5px;
    font-weight:500;
    cursor:pointer;
    transition:.18s;
    font-family:inherit;
    display:inline-flex;
    align-items:center;
    gap:3px;
}

.pag-btn:hover:not([disabled]):not(.pag-active) {
    border-color:#198754;
    color:#198754;
    background:#eaf6ef;
}

.pag-active {
    background:#198754;
    border-color:#198754;
    color:#fff;
    font-weight:700;
}

.pag-btn[disabled] {
    opacity:.4;
    cursor:not-allowed;
    pointer-events:none;
}

/* ============================================================
   MODAL
   ============================================================ */

.itc-modal-backdrop {
    display:none;
    position:fixed;
    inset:0;
    background:rgba(10,16,30,.52);
    z-index:2000;
    align-items:center;
    justify-content:center;
    backdrop-filter:blur(2px);
    padding:16px;
}

.itc-modal-backdrop.open {
    display:flex;
}

.itc-modal {
    background:#fff;
    border-radius:14px;
    box-shadow:0 24px 64px rgba(0,0,0,.18);
    width:100%;
    max-width:520px;
    max-height:90vh;
    display:flex;
    flex-direction:column;
    overflow:hidden;
}

.itc-modal-head {
    padding:18px 22px;
    border-bottom:1px solid #dbe3e8;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    background:#f8fafb;
}

.itc-modal-head-title {
    font-size:15px;
    font-weight:700;
    color:#152238;
    display:flex;
    align-items:center;
    gap:10px;
    margin:0;
}

.modal-title-icon {
    width:34px;
    height:34px;
    background:linear-gradient(135deg,#198754,#157347);
    border-radius:8px;
    display:flex;
    align-items:center;
    justify-content:center;
    flex-shrink:0;
    box-shadow:0 2px 8px rgba(25,135,84,.22);
}

.modal-title-icon i {
    font-size:15px;
    color:#fff;
}

.itc-modal-close-btn {
    width:32px;
    height:32px;
    border:1.5px solid #dbe3e8;
    background:#fff;
    border-radius:7px;
    color:#94a3b8;
    font-size:16px;
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    transition:.18s;
}

.itc-modal-close-btn:hover {
    border-color:#198754;
    color:#198754;
    background:#eaf6ef;
}

.itc-modal-body {
    padding:22px 24px 8px;
    overflow-y:auto;
    flex:1;
}

.modal-form-group {
    margin-bottom:16px;
}

.modal-label {
    display:block;
    font-size:12.5px;
    font-weight:600;
    color:#152238;
    margin-bottom:6px;
}

.modal-label span.required {
    color:#dc3545;
    margin-left:2px;
}

.modal-input,
.modal-select,
.modal-textarea {
    width:100%;
    padding:8px 12px;
    border:1.5px solid #dbe3e8;
    border-radius:8px;
    font-size:13px;
    font-family:inherit;
    color:#152238;
    background:#f8fafb;
    transition:.18s;
    outline:none;
    box-sizing:border-box;
}

.modal-input:focus,
.modal-select:focus,
.modal-textarea:focus {
    border-color:#198754;
    box-shadow:0 0 0 3px rgba(25,135,84,.09);
    background:#fff;
}

.modal-select {
    cursor:pointer;
}

.modal-textarea {
    resize:vertical;
    min-height:80px;
    line-height:1.5;
}

.modal-form-row {
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:14px;
}

.itc-modal-foot {
    padding:16px 24px;
    border-top:1px solid #dbe3e8;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
    background:#fff;
}

.modal-foot-note {
    font-size:11.5px;
    color:#94a3b8;
    display:flex;
    align-items:center;
    gap:5px;
}

.modal-foot-note i {
    color:#198754;
    font-size:13px;
}

.modal-foot-actions {
    display:flex;
    align-items:center;
    gap:8px;
}

.modal-btn-cancel {
    padding:8px 18px;
    border-radius:8px;
    border:1.5px solid #dbe3e8;
    background:#fff;
    color:#152238;
    font-size:13px;
    font-weight:500;
    cursor:pointer;
    font-family:inherit;
    transition:.18s;
}

.modal-btn-cancel:hover {
    border-color:#198754;
    color:#198754;
    background:#eaf6ef;
}

.modal-btn-save {
    padding:8px 20px;
    border-radius:8px;
    border:none;
    background:linear-gradient(135deg,#198754,#157347);
    color:#fff;
    font-size:13px;
    font-weight:700;
    cursor:pointer;
    font-family:inherit;
    transition:.18s;
    display:inline-flex;
    align-items:center;
    gap:6px;
    box-shadow:0 2px 8px rgba(25,135,84,.20);
}

.modal-btn-save:hover {
    background:linear-gradient(135deg,#157347,#146c43);
}

/* ============================================================
   MAPPING
   ============================================================ */

.mc-list-box {
    display:flex;
    flex-direction:column;
    gap:6px;
    max-height:160px;
    overflow-y:auto;
    border:1px solid #dbe3e8;
    border-radius:8px;
    padding:10px;
    background:#f8fafb;
}

.mc-item {
    display:flex;
    align-items:center;
    gap:8px;
    font-size:13px;
    cursor:pointer;
    font-weight:400;
    padding:7px 8px;
    border:1px solid transparent;
    border-radius:7px;
    background:#fff;
}

.mc-item:hover {
    border-color:#bbf7d0;
    background:#f0fdf4;
}

.mc-item-static {
    cursor:default;
}

.mc-item-static:hover {
    border-color:transparent;
    background:#fff;
}

.mc-item-locked {
    background:#f0fdf4;
    border-color:#86efac;
    cursor:not-allowed;
}

.mc-item-locked:hover {
    background:#f0fdf4;
    border-color:#86efac;
}

.mc-item-locked .mc-item-text::after {
    content: ' (required for this Application)';
    color:#16a34a;
    font-size:10px;
    font-weight:600;
    font-style:italic;
}

.mc-item input {
    width:15px;
    height:15px;
    cursor:pointer;
    flex-shrink:0;
}

.mc-item-text {
    flex:1;
    min-width:0;
}

.mc-item-badge {
    background:#eaf6ef;
    color:#198754;
    border:1px solid #bbf7d0;
    border-radius:4px;
    padding:2px 6px;
    font-size:10px;
    font-weight:700;
    white-space:nowrap;
}

.mc-control-id-box {
    display:flex;
    flex-direction:column;
    gap:8px;
    border:1px solid #dbe3e8;
    border-radius:8px;
    padding:10px;
    background:#f8fafb;
}

.mc-control-id-row {
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
    padding:8px 10px;
    background:#fff;
    border:1px solid #e2e8f0;
    border-radius:7px;
}

.mc-control-id-upti {
    font-size:12.5px;
    font-weight:600;
    color:#334155;
}

.mc-control-id-value {
    display:inline-flex;
    align-items:center;
    padding:4px 10px;
    border-radius:5px;
    background:#eaf6ef;
    color:#198754;
    border:1px solid #bbf7d0;
    font-size:12px;
    font-weight:700;
}

@media (max-width:768px) {
    .itc-summary-grid {
        grid-template-columns:1fr;
    }

    .itc-sum-cell {
        border-right:none;
        border-bottom:1px solid #edf1f3;
    }

    .itc-sum-cell:last-child {
        border-bottom:none;
    }

    .itc-toolbar {
        flex-direction:column;
        align-items:stretch;
    }

    .itc-toolbar-left,
    .itc-toolbar-right {
        width:100%;
        flex-wrap:wrap;
    }

    .itc-search-wrap {
        max-width:100%;
    }

    .itc-page-header {
        flex-direction:column;
    }

    .modal-form-row {
        grid-template-columns:1fr;
    }

    .itc-modal-foot {
        flex-direction:column;
        align-items:stretch;
    }

    .modal-foot-actions {
        justify-content:flex-end;
    }
}
</style>
@endpush

@section('content')

@php
    $quarterLabels = [
        'q1' => 'Q1',
        'q2' => 'Q2',
        'q3' => 'Q3',
        'q4' => 'Q4',
    ];

    $quarterLabel =
        $quarterLabels[$quarter]
        ?? strtoupper($quarter);

    $scBadgeMap = [
        'not_started' => [
            'label' => 'Not Started Yet',
            'cls' => 'sc-not-started',
        ],

        'drafting' => [
            'label' => 'Drafting',
            'cls' => 'sc-not-started',
        ],

        'ongoing_review' => [
            'label' => 'On Going Review',
            'cls' => 'sc-ongoing-review',
        ],

        'ongoing_approval' => [
            'label' => 'On Going Approval',
            'cls' => 'sc-ongoing-approval',
        ],

        'return_to_officer' => [
            'label' => 'Return to Officer',
            'cls' => 'sc-ongoing-review',
        ],

        'return_to_reviewer' => [
            'label' => 'Return to Reviewer',
            'cls' => 'sc-ongoing-review',
        ],

        'completed' => [
            'label' => 'Completed',
            'cls' => 'sc-completed',
        ],

        'complete' => [
            'label' => 'Completed',
            'cls' => 'sc-completed',
        ],
    ];

    $totalControls =
        $controls->count();

    $allUptis =
        \App\Models\Upti::query()
            ->orderBy('name')
            ->get();

    $authUser =
        auth()->user();

    $authRole =
        $authUser->role ?? '';

    $isAdmin =
        $authUser->isAdmin();

    $isOfficer =
        in_array(
            $authRole,
            ['creator', 'officer'],
            true
        );

    $isReviewer =
        $authRole === 'reviewer';

    $isApprover =
        $authRole === 'approver';
@endphp

<div class="itc-page-header">

    <div class="itc-header-left">

        <nav
            class="itc-breadcrumb"
            aria-label="Breadcrumb"
        >

            <a href="{{ route('dashboard') }}">

                <i
                    class="bi bi-house-fill"
                    style="font-size:10px;"
                ></i>

                &nbsp;Dashboard

            </a>

            <span class="bc-sep">

                <i class="bi bi-chevron-right"></i>

            </span>

            <a href="{{ route('dashboard') }}">

                {{ $application->name }}

            </a>

            <span class="bc-sep">

                <i class="bi bi-chevron-right"></i>

            </span>

            @if($isRcmView ?? false)

                <span class="bc-sep" style="color:#7c3aed; font-weight:700;">
                    IT RCM
                </span>

                <span class="bc-sep">
                    <i class="bi bi-chevron-right"></i>
                </span>

            @endif

            <span class="bc-cur">

                {{ $category->name }}

            </span>

        </nav>

        <h1 class="itc-page-title">

            <span class="itc-title-icon">

                <i class="bi {{ $category->icon }}"></i>

            </span>

            {{ $category->name }}

            @if($isRcmView ?? false)
                <span style="font-size:12px; font-weight:700; color:#7c3aed; background:#f5f3ff; border:1px solid #ddd6fe; padding:3px 10px; border-radius:9999px; margin-left:10px; vertical-align:middle;">
                    IT RCM
                </span>
            @endif

        </h1>

    </div>

    <a
        href="{{ route('dashboard') }}"
        class="btn-back-itc"
    >

        <i class="bi bi-arrow-left"></i>

        Back to Dashboard

    </a>

</div>

<div class="itc-summary-card">

    <div class="itc-summary-header">

        <i class="bi bi-info-circle-fill"></i>

        Assessment Overview

    </div>

    <div class="itc-summary-grid">

        <div class="itc-sum-cell">

            <span class="itc-sum-label">
                Application
            </span>

            <div class="itc-sum-value">

                <i class="bi bi-window-stack"></i>

                {{ $application->name }}

            </div>

        </div>

        <div class="itc-sum-cell">
            <span class="itc-sum-label">Year</span>
            <div class="itc-sum-value">
                <i class="bi bi-calendar3"></i>
                @if($isAdmin)
                    <form method="GET" action="{{ url()->current() }}" style="margin: 0; display: inline-block;">
                        <input type="hidden" name="application_id" value="{{ $application->id }}">
                        <input type="hidden" name="quarter" value="{{ $quarter }}">
                        @if($isRcmView ?? false)
                            <input type="hidden" name="source" value="rcm">
                        @else
                            <input type="hidden" name="source" value="{{ $source ?? 'dashboard' }}">
                        @endif
                        <select name="year" onchange="this.form.submit()" style="border: 1px solid #dbe3e8; border-radius: 6px; padding: 2px 8px; font-size: 13px; font-weight: 600; color: #152238; background: #f8fafb; outline: none; cursor: pointer;">
                            @foreach($availableYears as $availYear)
                                <option value="{{ $availYear }}" {{ (int) $year === (int) $availYear ? 'selected' : '' }}>{{ $availYear }}</option>
                            @endforeach
                        </select>
                    </form>
                @else
                    {{ $year }}
                @endif
            </div>
        </div>

        <div class="itc-sum-cell">
            <span class="itc-sum-label">Quarter</span>
            <div class="itc-sum-value">
                <i class="bi bi-calendar-range"></i>
                @if($isAdmin)
                    <form method="GET" action="{{ url()->current() }}" style="margin: 0; display: inline-block;">
                        <input type="hidden" name="application_id" value="{{ $application->id }}">
                        <input type="hidden" name="year" value="{{ $year }}">
                        @if($isRcmView ?? false)
                            <input type="hidden" name="source" value="rcm">
                        @else
                            <input type="hidden" name="source" value="{{ $source ?? 'dashboard' }}">
                        @endif
                        <select name="quarter" onchange="this.form.submit()" style="border: 1px solid #dbe3e8; border-radius: 6px; padding: 2px 8px; font-size: 13px; font-weight: 600; color: #152238; background: #f8fafb; outline: none; cursor: pointer;">
                            @foreach(['q1' => 'Q1', 'q2' => 'Q2', 'q3' => 'Q3', 'q4' => 'Q4'] as $qVal => $qLabel)
                                <option value="{{ $qVal }}" {{ $quarter === $qVal ? 'selected' : '' }}>{{ $qLabel }}</option>
                            @endforeach
                        </select>
                    </form>
                @else
                    {{ $quarterLabel }}
                @endif
            </div>
        </div>

    </div>

</div>

<div class="itc-section-label">

    <i class="bi bi-table"></i>

    Assessment Controls

</div>

<div
    class="itc-toolbar"
    role="toolbar"
    aria-label="Controls toolbar"
>

    <div class="itc-toolbar-left">

        <div class="itc-search-wrap">

            <i class="bi bi-search"></i>

            <input
                type="text"
                class="itc-search-input"
                id="itc-search"
                placeholder="Search controls..."
                autocomplete="off"
                aria-label="Search controls"
            >

        </div>

        <button
            type="button"
            class="itc-tb-btn"
            id="itc-filter-btn"
            title="Filter"
        >

            <i class="bi bi-funnel"></i>

            Filter

        </button>

        <button
            type="button"
            class="itc-tb-btn"
            onclick="location.reload()"
            title="Refresh"
        >

            <i class="bi bi-arrow-clockwise"></i>

            Refresh

        </button>

    </div>

    <div class="itc-toolbar-right">

        <span
            class="itc-total-pill"
            aria-live="polite"
        >

            <i class="bi bi-list-check"></i>

            Total Controls:

            <strong id="itc-total">
                {{ $totalControls }}
            </strong>

        </span>

        @if($isAdmin && $controls->isNotEmpty() && !($isRcmView ?? false))

            <button
                type="button"
                class="itc-btn-delete-all"
                id="itc-delete-all-btn"
            >

                <i class="bi bi-trash-fill"></i>

                Delete All

            </button>

        @endif

        @if($isAdmin && !($isRcmView ?? false))

            <button
                type="button"
                class="itc-btn-add"
                id="itc-add-btn"
                aria-haspopup="dialog"
                aria-controls="addControlModal"
                data-locked-upti="{{ optional($application->upti)->name ?? '' }}"
            >

                <i class="bi bi-plus-lg"></i>

                Add Data

            </button>

        @endif

    </div>

</div>

@if($controls->isEmpty())

    <div
        style="
            overflow-x:auto;
            background:#fff;
            border:1px solid #dbe3e8;
            border-top:none;
        "
    >

        <table
            class="itc-table"
            aria-label="Assessment controls table"
        >

            <thead>

                <tr>

                    <th class="col-ctrlid">
                        Control ID
                    </th>

                    <th>
                        Control Description
                    </th>

                    <th class="col-status th-center">
                        Status Control
                    </th>

                    <th class="col-actions">
                        Actions
                    </th>

                </tr>

            </thead>

            <tbody id="itc-tbody"></tbody>

        </table>

    </div>

    <div
        class="itc-empty-state"
        id="itc-empty-state"
    >

        <div class="itc-empty-icon-wrap">

            <i class="bi bi-clipboard2-data"></i>

        </div>

        <h5>
            No Assessment Data Available
        </h5>

        <p>

            No control records have been added yet for

            <strong>
                {{ $application->name }}
            </strong>

            —

            <strong>
                {{ $category->name }}
            </strong>.

        </p>

        <span class="itc-empty-tag">

            <i class="bi bi-clock-history"></i>

            No Control Data

        </span>

    </div>

@else

    <div class="itc-table-wrap">

        <div class="itc-table-scroll">

            <table
                class="itc-table"
                id="itc-table"
                aria-label="Assessment controls table"
            >

                <thead>

                    <tr>

                        <th class="col-ctrlid">
                            Control ID
                        </th>

                        <th>
                            Control Description
                        </th>

                        @unless($isRcmView ?? false)

                            <th>
                                Frequency Description
                            </th>

                        @endunless

                        <th>
                            UPTI
                        </th>

                        @unless($isRcmView ?? false)

                            <th>
                                Key Control
                            </th>

                        @endunless

                        @if($isRcmView ?? false)

                            <th class="col-handled-by">
                                Handled By
                            </th>

                        @endif

                        <th class="col-status {{ ($isRcmView ?? false) ? '' : 'th-center' }}">
                            Status Control
                        </th>

                        @unless($isRcmView ?? false)

                            <th class="col-actions">
                                Actions
                            </th>

                        @endunless

                    </tr>

                </thead>

                <tbody id="itc-tbody">

                    @php
                        $prevUpti = null;
                    @endphp

                    @foreach($controls as $ctrl)

                        @php
                            if (
                                $prevUpti !== null &&
                                $prevUpti !== $ctrl->upti
                            ) {
                                $dividerColspan =
                                    ($isRcmView ?? false) ? 5 : 7;

                                echo '<tr aria-hidden="true">
                                    <td
                                        colspan="' . $dividerColspan . '"
                                        style="
                                            padding:0;
                                            height:8px;
                                            border-top:1px dashed #cbd5e1;
                                        "
                                    ></td>
                                </tr>';
                            }

                            $prevUpti =
                                $ctrl->upti;
                        @endphp

                        @php
                            $scKey =
                                $ctrl->status_control
                                ?? 'not_started';

                            $scInfo =
                                $scBadgeMap[$scKey]
                                ?? $scBadgeMap['not_started'];

                            if ($isRcmView ?? false) {

                                $officerEvidence =
                                    $ctrl->evidences
                                        ->where('file_type', '!=', 'Berita Acara')
                                        ->first();

                                $officerName =
                                    $officerEvidence?->uploaded_by
                                    ?? '—';

                                $rowUptiModel =
                                    $allUptis->firstWhere('name', $ctrl->upti);

                                $rowReviewer =
                                    \App\Models\User::query()
                                        ->where('role', 'reviewer')
                                        ->when(
                                            $rowUptiModel,
                                            fn ($q) => $q->where('upti_id', $rowUptiModel->id)
                                        )
                                        ->first();

                                $rowApprover =
                                    \App\Models\User::query()
                                        ->where('role', 'approver')
                                        ->when(
                                            $rowUptiModel,
                                            fn ($q) => $q->where('upti_id', $rowUptiModel->id)
                                        )
                                        ->first();

                            }
                        @endphp

                        @if(($isRcmView ?? false) && $scKey === 'completed')

                            <tr
                                data-id="{{ $ctrl->id }}"
                                data-ctrl-status="completed"
                                class="rcm-completed-row"
                            >

                                <td class="col-ctrlid">
                                    <span class="ctrl-id-pill">
                                        {{ $ctrl->it_control_id ?? '—' }}
                                    </span>
                                </td>

                                <td class="col-desc">
                                    {{ $ctrl->control_description ?? '—' }}
                                </td>

                                <td>
                                    {{ $ctrl->upti ?? '—' }}
                                </td>

                                <td class="col-handled-by">
                                    <div class="rcm-handled-by">
                                        <span>
                                            <i class="bi bi-person-fill" style="color:#0f766e;"></i>
                                            Officer: {{ $officerName }}
                                        </span>
                                        <span>
                                            <i class="bi bi-person-check-fill" style="color:#b45309;"></i>
                                            Manager: {{ $rowReviewer?->name ?? '—' }}
                                        </span>
                                        <span>
                                            <i class="bi bi-person-badge-fill" style="color:#1d4ed8;"></i>
                                            Senior Manager: {{ $rowApprover?->name ?? '—' }}
                                        </span>
                                    </div>
                                </td>

                                <td class="col-status td-center">
                                    <span
                                        style="
                                            color:#15803d;
                                            font-weight:600;
                                            font-size:12.5px;
                                            display:inline-flex;
                                            align-items:center;
                                            gap:6px;
                                        "
                                    >
                                        <i class="bi bi-check-circle-fill"></i>
                                        Completed
                                        @if($ctrl->approved_at)
                                            &middot; {{ $ctrl->approved_at->format('d M Y') }}
                                        @endif
                                    </span>
                                </td>

                            </tr>

                            @continue

                        @endif

                        <tr
                            data-id="{{ $ctrl->id }}"
                            data-ctrl-id="{{ $ctrl->it_control_id ?? '' }}"
                            data-ctrl-desc="{{ addslashes($ctrl->control_description ?? '') }}"
                            data-ctrl-frek="{{ $ctrl->keterangan_frekuensi ?? '' }}"
                            data-ctrl-upti="{{ $ctrl->upti ?? '' }}"
                            data-ctrl-keyctrl="{{ $ctrl->key_control ?? '' }}"
                            data-ctrl-file-type="{{ $ctrl->file_type ?? '' }}"
                            data-ctrl-status="{{ $ctrl->status_control ?? 'not_started' }}"
                            data-app-id="{{ $ctrl->application_id }}"
                            data-cat-id="{{ $ctrl->it_category_id }}"
                        >

                            <td class="col-ctrlid">

                                <span class="ctrl-id-pill">

                                    {{ $ctrl->it_control_id ?? '—' }}

                                </span>

                            </td>

                            <td class="col-desc">

                                <div>

                                    {{ $ctrl->control_description ?? '—' }}

                                </div>

                                @if(isset($ctrl->evidences))

                                    <div
                                        class="row-evidence-pills"
                                        style="
                                            display:flex;
                                            flex-wrap:wrap;
                                            gap:6px;
                                            margin-top:6px;
                                        "
                                    >

                                        @foreach(
                                            $ctrl->evidences
                                            as $ev
                                        )

                                            @if(
                                                $ev->file_type ===
                                                'Berita Acara'
                                            )

                                                @continue

                                            @endif

                                            @php
                                                $ext =
                                                    strtolower(
                                                        pathinfo(
                                                            $ev->original_name,
                                                            PATHINFO_EXTENSION
                                                        )
                                                    );

                                                $icon =
                                                    'bi-paperclip';

                                                $iconColor =
                                                    '#198754';

                                                if(
                                                    $ext ===
                                                    'pdf'
                                                ) {

                                                    $icon =
                                                        'bi-file-earmark-pdf-fill';

                                                    $iconColor =
                                                        '#e11d48';

                                                } elseif(
                                                    in_array(
                                                        $ext,
                                                        ['doc','docx'],
                                                        true
                                                    )
                                                ) {

                                                    $icon =
                                                        'bi-file-earmark-word-fill';

                                                    $iconColor =
                                                        '#2563eb';

                                                } elseif(
                                                    in_array(
                                                        $ext,
                                                        ['xls','xlsx','csv'],
                                                        true
                                                    )
                                                ) {

                                                    $icon =
                                                        'bi-file-earmark-excel-fill';

                                                    $iconColor =
                                                        '#16a34a';

                                                }
                                            @endphp

                                            <a
                                                href="{{ route('evidence.show', $ev->id) }}"
                                                target="_blank"
                                                class="evidence-pill"
                                            >

                                                <i
                                                    class="bi {{ $icon }}"
                                                    style="color:{{ $iconColor }};"
                                                ></i>

                                                <span
                                                    title="{{ $ev->original_name }}"
                                                >

                                                    {{
                                                        Str::limit(
                                                            $ev->original_name,
                                                            26
                                                        )
                                                    }}

                                                </span>

                                                @if(
                                                    !empty(
                                                        $ev->file_type
                                                    )
                                                )

                                                    <span
                                                        style="
                                                            background:#e0e7ff;
                                                            color:#3730a3;
                                                            font-size:10px;
                                                            font-weight:700;
                                                            padding:1px 5px;
                                                            border-radius:3px;
                                                            border:1px solid #c7d2fe;
                                                            white-space:nowrap;
                                                        "
                                                    >

                                                        {{ $ev->file_type }}

                                                    </span>

                                                @endif

                                            </a>

                                        @endforeach

                                    </div>

                                @endif

                                @if(
                                    $ctrl->reviewer_notes ||
                                    $ctrl->approver_notes
                                )

                                    <div class="ctrl-notes-container">

                                        @if(
                                            $ctrl->reviewer_notes
                                        )

                                            <div
                                                style="
                                                    background:#fef3c7;
                                                    color:#92400e;
                                                    padding:4px 8px;
                                                    border-radius:4px;
                                                    margin-bottom:4px;
                                                    border-left:3px solid #f59e0b;
                                                "
                                            >

                                                <strong>
                                                    Manager Notes:
                                                </strong>

                                                <span
                                                    style="font-style:italic;"
                                                >

                                                    "{{ $ctrl->reviewer_notes }}"

                                                </span>

                                            </div>

                                        @endif

                                        @if(
                                            $ctrl->approver_notes
                                        )

                                            <div
                                                style="
                                                    background:#e0e7ff;
                                                    color:#3730a3;
                                                    padding:4px 8px;
                                                    border-radius:4px;
                                                    border-left:3px solid #6366f1;
                                                "
                                            >

                                                <strong>
                                                    Senior Manager Notes:
                                                </strong>

                                                <span
                                                    style="font-style:italic;"
                                                >

                                                    "{{ $ctrl->approver_notes }}"

                                                </span>

                                            </div>

                                        @endif

                                    </div>

                                @endif

                            </td>

                            @unless($isRcmView ?? false)

                                <td class="col-frekuensi">

                                    {{ $ctrl->keterangan_frekuensi ?? '—' }}

                                </td>

                            @endunless

                            <td class="col-upti">

                                {{ $ctrl->upti ?? '—' }}

                            </td>

                            @unless($isRcmView ?? false)

                                <td class="col-keyctrl">

                                    {{
                                        $ctrl->key_control === null
                                        ? '—'
                                        : (
                                            in_array(
                                                strtoupper(
                                                    (string)
                                                    $ctrl->key_control
                                                ),
                                                [
                                                    'YES',
                                                    '1',
                                                    'TRUE',
                                                ],
                                                true
                                            )
                                            ? 'Yes'
                                            : 'No'
                                        )
                                    }}

                                </td>

                            @endunless

                            @if($isRcmView ?? false)

                                <td class="col-handled-by">
                                    <div class="rcm-handled-by">
                                        <span>
                                            <i class="bi bi-person-fill" style="color:#0f766e;"></i>
                                            Officer: {{ $officerName }}
                                        </span>
                                        <span>
                                            <i class="bi bi-person-check-fill" style="color:#b45309;"></i>
                                            Manager: {{ $rowReviewer?->name ?? '—' }}
                                        </span>
                                        <span>
                                            <i class="bi bi-person-badge-fill" style="color:#1d4ed8;"></i>
                                            Senior Manager: {{ $rowApprover?->name ?? '—' }}
                                        </span>
                                    </div>
                                </td>

                            @endif

                            <td class="col-status td-center">

                                <span
                                    class="status-badge {{ $scInfo['cls'] }}"
                                >

                                    {{ $scInfo['label'] }}

                                </span>

                            </td>

                            @unless($isRcmView ?? false)

                            <td class="col-actions">

                                <div class="row-act-group">

                                    @if($isAdmin)

                                        <button
                                            type="button"
                                            class="row-act-btn row-act-edit btn-edit-ctrl"
                                            title="Edit Control"
                                            aria-label="Edit {{ $ctrl->it_control_id }}"
                                        >

                                            <i class="bi bi-pencil-fill"></i>

                                        </button>

                                    @elseif($isOfficer)

                                        @php
                                            $officerCanEdit = in_array(
                                                $ctrl->status_control,
                                                ['not_started', 'drafting', 'return_to_officer'],
                                                true
                                            );
                                        @endphp

                                        <div class="row-act-stack">

                                            @if($officerCanEdit)

                                                <button
                                                    type="button"
                                                    class="row-act-btn row-act-edit btn-edit-ctrl"
                                                    title="Upload Evidence"
                                                    aria-label="Upload Evidence {{ $ctrl->it_control_id }}"
                                                >

                                                    <i class="bi bi-cloud-arrow-up-fill"></i>

                                                </button>

                                            @else

                                                <button
                                                    type="button"
                                                    class="row-act-btn row-act-view btn-edit-ctrl"
                                                    title="View Control"
                                                    aria-label="View {{ $ctrl->it_control_id }}"
                                                >

                                                    <i class="bi bi-eye-fill"></i>

                                                </button>

                                            @endif

                                            @if(
                                                in_array(
                                                    $ctrl->status_control,
                                                    ['drafting', 'return_to_officer'],
                                                    true
                                                )
                                            )

                                                <button
                                                    type="button"
                                                    class="row-act-send-btn btn-send-ctrl"
                                                    title="{{ $ctrl->status_control === 'return_to_officer' ? 'Resubmit to Manager' : 'Send to Manager' }}"
                                                    aria-label="Send to Manager {{ $ctrl->it_control_id }}"
                                                    data-ctrl-db-id="{{ $ctrl->id }}"
                                                    data-send-label="{{ $ctrl->status_control === 'return_to_officer' ? 'Resubmit to Manager' : 'Send to Manager' }}"
                                                >

                                                    <i class="bi bi-send-fill"></i>

                                                    <span class="row-act-send-text">
                                                        {{ $ctrl->status_control === 'return_to_officer' ? 'Resubmit to Manager' : 'Send to Manager' }}
                                                    </span>

                                                </button>

                                            @endif

                                        </div>

                                    @elseif($isReviewer)

                                        @if(
                                            in_array(
                                                $ctrl->status_control,
                                                ['ongoing_review', 'return_to_reviewer'],
                                                true
                                            )
                                        )

                                            <button
                                                type="button"
                                                class="row-act-btn row-act-approve btn-approve-ctrl"
                                                title="Approve"
                                                aria-label="Approve {{ $ctrl->it_control_id }}"
                                                data-ctrl-db-id="{{ $ctrl->id }}"
                                                data-to-status="ongoing_approval"
                                                data-notes-field="reviewer_notes"
                                            >

                                                <i class="bi bi-check-circle-fill"></i>

                                            </button>

                                            <button
                                                type="button"
                                                class="row-act-btn row-act-reject btn-reject-ctrl"
                                                title="Reject"
                                                aria-label="Reject {{ $ctrl->it_control_id }}"
                                                data-ctrl-db-id="{{ $ctrl->id }}"
                                                data-to-status="return_to_officer"
                                                data-notes-field="reviewer_notes"
                                            >

                                                <i class="bi bi-x-circle-fill"></i>

                                            </button>

                                        @else

                                            <button
                                                type="button"
                                                class="row-act-btn row-act-view btn-edit-ctrl"
                                                title="View Control"
                                                aria-label="View {{ $ctrl->it_control_id }}"
                                            >

                                                <i class="bi bi-eye-fill"></i>

                                            </button>

                                        @endif

                                    @elseif($isApprover)

                                        @if(
                                            $ctrl->status_control ===
                                            'ongoing_approval'
                                        )

                                            <button
                                                type="button"
                                                class="row-act-btn row-act-approve btn-approve-ctrl"
                                                title="Approve"
                                                aria-label="Approve {{ $ctrl->it_control_id }}"
                                                data-ctrl-db-id="{{ $ctrl->id }}"
                                                data-to-status="completed"
                                                data-notes-field="approver_notes"
                                            >

                                                <i class="bi bi-check-circle-fill"></i>

                                            </button>

                                            <button
                                                type="button"
                                                class="row-act-btn row-act-reject btn-reject-ctrl"
                                                title="Reject"
                                                aria-label="Reject {{ $ctrl->it_control_id }}"
                                                data-ctrl-db-id="{{ $ctrl->id }}"
                                                data-to-status="return_to_officer"
                                                data-notes-field="approver_notes"
                                            >

                                                <i class="bi bi-x-circle-fill"></i>

                                            </button>

                                        @else

                                            <button
                                                type="button"
                                                class="row-act-btn row-act-view btn-edit-ctrl"
                                                title="View Control"
                                                aria-label="View {{ $ctrl->it_control_id }}"
                                            >

                                                <i class="bi bi-eye-fill"></i>

                                            </button>

                                        @endif

                                    @else

                                        <button
                                            type="button"
                                            class="row-act-btn row-act-view btn-edit-ctrl"
                                            title="View Control"
                                            aria-label="View {{ $ctrl->it_control_id }}"
                                        >

                                            <i class="bi bi-eye-fill"></i>

                                        </button>

                                    @endif

                                    @if(
                                        $ctrl->status_control ===
                                        'completed'
                                    )

                                        <a
                                            href="{{ route('controls.beritaAcara', $ctrl->id) }}"
                                            class="row-act-btn row-act-pdf"
                                            title="Download Berita Acara"
                                            target="_blank"
                                        >

                                            <i class="bi bi-file-earmark-pdf-fill"></i>

                                        </a>

                                    @endif

                                    @if($isAdmin)

                                        <button
                                            type="button"
                                            class="row-act-btn row-act-delete btn-delete-ctrl"
                                            title="Delete Control"
                                            data-ctrl-id="{{ $ctrl->it_control_id }}"
                                        >

                                            <i class="bi bi-trash3-fill"></i>

                                        </button>

                                    @endif

                                </div>

                            </td>

                            @endunless

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

    <div class="itc-footer">

        <div class="itc-footer-info">

            Showing

            <strong id="showing-count">
                {{ $controls->count() }}
            </strong>

            of

            <strong>
                {{ $controls->count() }}
            </strong>

            controls

        </div>

        <div class="itc-footer-pag">

            <button
                class="pag-btn"
                disabled
            >

                <i class="bi bi-chevron-left"></i>

                Prev

            </button>

            <button
                class="pag-btn pag-active"
                aria-current="page"
            >
                1
            </button>

            <button
                class="pag-btn"
                disabled
            >

                Next

                <i class="bi bi-chevron-right"></i>

            </button>

        </div>

    </div>

@endif


{{-- ============================================================
     ADD CONTROL MODAL
     ============================================================ --}}

@if($isAdmin)

<div
    class="itc-modal-backdrop"
    id="addControlModal"
    role="dialog"
    aria-modal="true"
    aria-labelledby="addControlTitle"
>

    <div class="itc-modal">

        <div class="itc-modal-head">

            <h2
                class="itc-modal-head-title"
                id="addControlTitle"
            >

                <span class="modal-title-icon">

                    <i class="bi bi-plus-circle-fill"></i>

                </span>

                Add Control Record

            </h2>

            <button
                class="itc-modal-close-btn"
                id="addControlClose"
                type="button"
            >

                <i class="bi bi-x-lg"></i>

            </button>

        </div>

        <div class="itc-modal-body">

            <form
                id="addControlForm"
                autocomplete="off"
            >

                @csrf

                <input
                    type="hidden"
                    name="year"
                    value="{{ $year }}"
                >

                <input
                    type="hidden"
                    name="quarter"
                    value="{{ $quarter }}"
                >

                <input
                    type="hidden"
                    name="it_category_id"
                    value="{{ $category->id }}"
                >

                <div class="modal-form-group">

                    <label class="modal-label">

                        UPTI

                        <span class="required">
                            *
                        </span>

                    </label>

                    <div
                        id="mc-uptis-list"
                        class="mc-list-box"
                    >

                        @foreach($allUptis as $u)

                            <label class="mc-item">

                                <input
                                    type="checkbox"
                                    name="uptis[]"
                                    value="{{ $u->name }}"
                                >

                                <span class="mc-item-text">
                                    {{ $u->name }}
                                </span>

                            </label>

                        @endforeach

                    </div>

                    <div
                        style="
                            font-size:11.5px;
                            color:#64748b;
                            margin-top:6px;
                        "
                    >

                        Select one or more UPTIs.

                    </div>

                </div>

                <div class="modal-form-group">

                    <label class="modal-label">

                        Application

                    </label>

                    <div
                        id="mc-applications-list"
                        class="mc-list-box"
                    >

                        <div
                            style="
                                font-size:12.5px;
                                color:#64748b;
                            "
                        >

                            Select UPTI first.

                        </div>

                    </div>

                    <div
                        style="
                            font-size:11.5px;
                            color:#64748b;
                            margin-top:6px;
                        "
                    >

                        Application is mapped automatically based on the selected UPTI(s).

                    </div>

                </div>

                <div class="modal-form-group">

                    <label class="modal-label">

                        Control ID

                    </label>

                    <div
                        id="mc-generated-control-ids"
                        class="mc-control-id-box"
                    >

                        <div
                            style="
                                font-size:12.5px;
                                color:#64748b;
                            "
                        >

                            Select UPTI to generate Control ID automatically.

                        </div>

                    </div>

                </div>

                <div class="modal-form-group">

                    <label
                        class="modal-label"
                        for="mc-description"
                    >

                        Control Description

                        <span class="required">
                            *
                        </span>

                    </label>

                    <textarea
                        class="modal-textarea"
                        id="mc-description"
                        name="control_description"
                        rows="4"
                        required
                    ></textarea>

                </div>

                <div class="modal-form-group">

                    <label
                        class="modal-label"
                        for="mc-frekuensi"
                    >

                        Keterangan Frekuensi

                    </label>

                    <select
                        class="modal-input"
                        id="mc-frekuensi"
                        name="keterangan_frekuensi"
                    >

                        <option value="">
                            -- Pilih --
                        </option>

                        <option value="Per Project">
                            Per Project
                        </option>

                        <option value="Quarterly">
                            Quarterly
                        </option>

                        <option value="Twice a year">
                            Twice a year
                        </option>

                        <option value="Yearly">
                            Yearly
                        </option>

                    </select>

                </div>

                <div class="modal-form-group">

                    <label
                        class="modal-label"
                        for="mc-key-control"
                    >

                        Key Control

                    </label>

                    <select
                        class="modal-input"
                        id="mc-key-control"
                        name="key_control"
                    >

                        <option value="">
                            -- Select --
                        </option>

                        <option value="YES">
                            YES
                        </option>

                        <option value="NO">
                            NO
                        </option>

                    </select>

                </div>

            </form>

        </div>

        <div class="itc-modal-foot">

            <span
                class="modal-foot-note"
                id="mc-save-msg"
            >

                <i class="bi bi-info-circle"></i>

                Ready to save

            </span>

            <div class="modal-foot-actions">

                <button
                    type="button"
                    class="modal-btn-cancel"
                    id="addControlCancel"
                >
                    Cancel
                </button>

                <button
                    type="button"
                    class="modal-btn-save"
                    id="btn-save-add-control"
                >

                    <i class="bi bi-floppy-fill"></i>

                    Save Control

                </button>

            </div>

        </div>

    </div>

</div>

@endif


{{-- ============================================================
     EDIT / UPLOAD / VIEW MODAL
     ============================================================ --}}

<div
    class="itc-modal-backdrop"
    id="editControlModal"
    role="dialog"
    aria-modal="true"
    aria-labelledby="editControlTitle"
>

    <div class="itc-modal">

        <div class="itc-modal-head">

            <h2
                class="itc-modal-head-title"
                id="editControlTitle"
            >

                <span class="modal-title-icon">

                    @if($isAdmin)
                        <i class="bi bi-pencil-fill"></i>
                    @elseif($isOfficer)
                        <i class="bi bi-cloud-arrow-up-fill"></i>
                    @else
                        <i class="bi bi-eye-fill"></i>
                    @endif

                </span>

                @if($isAdmin)
                    Edit Control
                @elseif($isOfficer)
                    Upload Evidence
                @else
                    View Control
                @endif

            </h2>

            <button
                class="itc-modal-close-btn"
                id="editControlClose"
                type="button"
            >

                <i class="bi bi-x-lg"></i>

            </button>

        </div>

        <div class="itc-modal-body">

            <form
                id="editControlForm"
                autocomplete="off"
                enctype="multipart/form-data"
            >

                @csrf

                <input
                    type="hidden"
                    id="ec-id"
                    name="id"
                >

                <input
                    type="hidden"
                    id="ec-application"
                    name="application_id"
                    value="{{ $application->id }}"
                >

                <input
                    type="hidden"
                    id="ec-category"
                    name="it_category_id"
                    value="{{ $category->id }}"
                >

                <input
                    type="hidden"
                    id="ec-status-control"
                    name="status_control"
                >

                <div class="modal-form-group">

                    <label
                        class="modal-label"
                        for="ec-ctrl-id"
                    >
                        Control ID
                    </label>

                    <input
                        type="text"
                        class="modal-input"
                        id="ec-ctrl-id"
                        name="it_control_id"
                        readonly
                    >

                </div>

                <div class="modal-form-group">

                    <label
                        class="modal-label"
                        for="ec-description"
                    >
                        Control Description
                    </label>

                    <textarea
                        class="modal-textarea"
                        id="ec-description"
                        name="control_description"
                        rows="4"
                        {{ !$isAdmin ? 'readonly' : '' }}
                    ></textarea>

                </div>

                <div class="modal-form-group">

                    <label
                        class="modal-label"
                        for="ec-frekuensi"
                    >
                        Keterangan Frekuensi
                    </label>

                    <select
                        class="modal-input"
                        id="ec-frekuensi"
                        name="keterangan_frekuensi"
                        {{ !$isAdmin ? 'disabled' : '' }}
                    >

                        <option value="">
                            -- Pilih --
                        </option>

                        <option value="Per Project">
                            Per Project
                        </option>

                        <option value="Quarterly">
                            Quarterly
                        </option>

                        <option value="Twice a year">
                            Twice a year
                        </option>

                        <option value="Yearly">
                            Yearly
                        </option>

                    </select>

                </div>

                <div class="modal-form-group">

                    <label
                        class="modal-label"
                        for="ec-upti"
                    >
                        UPTI
                    </label>

                    <select
                        class="modal-input"
                        id="ec-upti"
                        name="upti"
                        {{ !$isAdmin ? 'disabled' : '' }}
                    >

                        <option value="">
                            -- Pilih --
                        </option>

                        @foreach($allUptis as $u)

                            <option
                                value="{{ $u->name }}"
                            >

                                {{ $u->name }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="modal-form-group">

                    <label
                        class="modal-label"
                        for="ec-key-control"
                    >
                        Key Control
                    </label>

                    <select
                        class="modal-input"
                        id="ec-key-control"
                        name="key_control"
                        {{ !$isAdmin ? 'disabled' : '' }}
                    >

                        <option value="">
                            -- Select --
                        </option>

                        <option value="1">
                            YES
                        </option>

                        <option value="0">
                            NO
                        </option>

                    </select>

                </div>

                @if($isOfficer)

                    <div
                        class="modal-form-group"
                        id="ec-upload-section"
                        style="
                            background:#f0fdf4;
                            border:2px dashed #34d399;
                            padding:15px;
                            border-radius:8px;
                            margin-top:10px;
                            margin-bottom:20px;
                        "
                    >

                        <label
                            class="modal-label"
                            for="ec-evidences"
                            style="
                                color:#065f46;
                                font-size:14px;
                                font-weight:700;
                                margin-bottom:8px;
                            "
                        >

                            <i
                                class="bi bi-cloud-arrow-up-fill me-1"
                            ></i>

                            Upload Evidence

                            <span
                                style="
                                    display:inline-block;
                                    background:#d1fae5;
                                    color:#065f46;
                                    font-size:10px;
                                    font-weight:700;
                                    padding:2px 8px;
                                    border-radius:9999px;
                                    margin-left:6px;
                                    vertical-align:middle;
                                "
                            >
                                Multiple files allowed
                            </span>

                        </label>

                        <div
                            style="
                                font-size:12px;
                                color:#047857;
                                margin-bottom:10px;
                            "
                        >

                            You can select <strong>more than one file at once</strong>:
                            click "Choose Files", then hold
                            <strong>Ctrl</strong> (Windows) or <strong>Cmd</strong> (Mac)
                            while clicking each file — or hold <strong>Shift</strong>
                            to select a range. Then set the
                            <strong>File Type</strong> for each file below.

                        </div>

                        <input
                            type="file"
                            class="modal-input"
                            id="ec-evidences"
                            name="evidences[]"
                            multiple
                            accept=".pdf,.doc,.docx,.xls,.xlsx"
                            style="
                                padding:10px;
                                background:#fff;
                                border:1px solid #6ee7b7;
                            "
                        >

                        <div
                            style="
                                font-size:11px;
                                color:#059669;
                                margin-top:6px;
                            "
                        >

                            <i class="bi bi-info-circle"></i>

                            Max 10MB per file.

                        </div>

                        <div
                            id="ec-selected-files-list"
                            style="
                                margin-top:12px;
                                display:flex;
                                flex-direction:column;
                                gap:8px;
                            "
                        ></div>

                    </div>

                @endif

                <div class="modal-form-group">

                    <label
                        class="modal-label"
                        style="font-weight:600;"
                    >

                        Currently Attached Evidence Files &amp; File Types

                    </label>

                    <ul
                        id="ec-existing-files"
                        style="
                            list-style:none;
                            padding:0;
                            margin:0;
                            display:flex;
                            flex-direction:column;
                            gap:8px;
                        "
                    ></ul>

                </div>

            </form>

        </div>

        <div class="itc-modal-foot">

            <span
                class="modal-foot-note"
                id="ec-save-msg"
            >

                <i class="bi bi-info-circle"></i>

                Ready

            </span>

            <div class="modal-foot-actions">

                <button
                    type="button"
                    class="modal-btn-cancel"
                    id="editControlCancel"
                >
                    Close
                </button>

                @if($isAdmin)

                    <button
                        type="button"
                        class="modal-btn-save"
                        id="btn-save-control"
                    >

                        <i class="bi bi-floppy-fill"></i>

                        Save Changes

                    </button>

                @elseif($isOfficer)

                    <button
                        type="button"
                        class="modal-btn-save"
                        id="btn-save-control"
                    >

                        <i class="bi bi-cloud-arrow-up-fill"></i>

                        Upload & Save

                    </button>

                    <button
                        type="button"
                        class="modal-btn-save"
                        id="btn-send-to-manager"
                        style="
                            background:#8b5cf6;
                            display:none;
                        "
                    >

                        <i class="bi bi-send-fill"></i>

                        <span id="btn-send-to-manager-label">
                            Send to Manager
                        </span>

                    </button>

                @endif

            </div>

        </div>

    </div>

</div>


{{-- ============================================================
     DELETE ALL
     ============================================================ --}}

@if($isAdmin)

<div
    class="itc-modal-backdrop"
    id="deleteAllControlModal"
    role="dialog"
    aria-modal="true"
>

    <div
        class="itc-modal"
        style="max-width:420px;"
    >

        <div class="itc-modal-head">

            <h2 class="itc-modal-head-title">

                <span
                    class="modal-title-icon"
                    style="
                        background:linear-gradient(135deg,#dc3545,#bb2d3b);
                    "
                >

                    <i class="bi bi-trash3-fill"></i>

                </span>

                Delete All Controls

            </h2>

            <button
                class="itc-modal-close-btn"
                id="deleteAllControlClose"
                type="button"
            >

                <i class="bi bi-x-lg"></i>

            </button>

        </div>

        <div
            class="itc-modal-body"
            style="
                padding-top:20px;
                padding-bottom:30px;
                text-align:center;
            "
        >

            <i
                class="bi bi-exclamation-triangle-fill"
                style="
                    font-size:36px;
                    color:#dc3545;
                    margin-bottom:12px;
                    display:block;
                "
            ></i>

            <h3
                style="
                    font-size:16px;
                    font-weight:700;
                    color:#152238;
                    margin-bottom:10px;
                "
            >

                Are you absolutely sure?

            </h3>

            <p
                style="
                    font-size:13.5px;
                    color:#64748b;
                    margin:0;
                "
            >

                This will permanently delete

                <strong>
                    ALL
                </strong>

                controls shown on this page.

            </p>

            <input
                type="hidden"
                id="delete-all-app-id"
                value="{{ $application->id }}"
            >

            <input
                type="hidden"
                id="delete-all-cat-id"
                value="{{ $category->id }}"
            >

            <input
                type="hidden"
                id="delete-all-year"
                value="{{ $year }}"
            >

            <input
                type="hidden"
                id="delete-all-quarter"
                value="{{ $quarter }}"
            >

        </div>

        <div class="itc-modal-foot">

            <div
                class="modal-foot-actions"
                style="
                    width:100%;
                    justify-content:center;
                "
            >

                <button
                    type="button"
                    class="modal-btn-cancel"
                    id="deleteAllControlCancel"
                >
                    Cancel
                </button>

                <button
                    type="button"
                    class="modal-btn-save"
                    id="btn-confirm-delete-all"
                    style="
                        background:linear-gradient(135deg,#dc3545,#bb2d3b);
                    "
                >

                    <i class="bi bi-trash3-fill"></i>

                    Yes, Delete All

                </button>

            </div>

        </div>

    </div>

</div>


{{-- ============================================================
     DELETE CONTROL
     ============================================================ --}}

<div
    class="itc-modal-backdrop"
    id="deleteControlModal"
    role="dialog"
    aria-modal="true"
>

    <div
        class="itc-modal"
        style="max-width:420px;"
    >

        <div class="itc-modal-head">

            <h2 class="itc-modal-head-title">

                <span
                    class="modal-title-icon"
                    style="
                        background:linear-gradient(135deg,#dc3545,#bb2d3b);
                    "
                >

                    <i class="bi bi-trash3-fill"></i>

                </span>

                Delete Control

            </h2>

            <button
                class="itc-modal-close-btn"
                id="deleteControlClose"
                type="button"
            >

                <i class="bi bi-x-lg"></i>

            </button>

        </div>

        <div
            class="itc-modal-body"
            style="padding:24px;"
        >

            <div style="text-align:center;">

                <div
                    style="
                        width:64px;
                        height:64px;
                        border-radius:50%;
                        background:rgba(220,53,69,.08);
                        border:2px solid rgba(220,53,69,.2);
                        display:inline-flex;
                        align-items:center;
                        justify-content:center;
                        margin-bottom:14px;
                    "
                >

                    <i
                        class="bi bi-exclamation-triangle-fill"
                        style="
                            font-size:26px;
                            color:#dc3545;
                        "
                    ></i>

                </div>

                <h6
                    style="
                        font-size:15px;
                        font-weight:700;
                        color:#152238;
                        margin:0 0 8px;
                    "
                >

                    Delete this control?

                </h6>

                <p
                    style="
                        font-size:13px;
                        color:#64748b;
                        margin:0;
                        line-height:1.6;
                    "
                >

                    You are about to delete control

                    <strong
                        id="delete-ctrl-id-label"
                        style="color:#dc3545;"
                    >
                        —
                    </strong>.

                </p>

                <input
                    type="hidden"
                    id="delete-ctrl-db-id"
                >

            </div>

        </div>

        <div class="itc-modal-foot">

            <span class="modal-foot-note">

                <i class="bi bi-info-circle"></i>

                This action cannot be undone.

            </span>

            <div class="modal-foot-actions">

                <button
                    type="button"
                    class="modal-btn-cancel"
                    id="deleteControlCancel"
                >
                    Cancel
                </button>

                <button
                    type="button"
                    class="modal-btn-save"
                    id="btn-confirm-delete"
                    style="
                        background:linear-gradient(135deg,#dc3545,#bb2d3b);
                    "
                >

                    <i class="bi bi-trash3-fill"></i>

                    Confirm Delete

                </button>

            </div>

        </div>

    </div>

</div>

@endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
(function () {

    'use strict';

    const csrfToken =
        document
            .querySelector(
                'meta[name="csrf-token"]'
            )
            ?.getAttribute('content')
            || '';

    const isAdmin =
        @json($isAdmin);

    const isOfficer =
        @json($isOfficer);

    const addModal =
        document.getElementById(
            'addControlModal'
        );

    const addBtn =
        document.getElementById(
            'itc-add-btn'
        );

    const addForm =
        document.getElementById(
            'addControlForm'
        );

    const addSaveBtn =
        document.getElementById(
            'btn-save-add-control'
        );

    const addSaveMsg =
        document.getElementById(
            'mc-save-msg'
        );

    const uptiList =
        document.getElementById(
            'mc-uptis-list'
        );

    const applicationList =
        document.getElementById(
            'mc-applications-list'
        );

    const generatedIdsBox =
        document.getElementById(
            'mc-generated-control-ids'
        );

    // Application IDs auto-resolved from the selected UPTI(s).
    // No manual selection needed — filled by refreshApplications().
    let currentApplicationIds = [];

    // UPTI that must stay checked in the Add Control modal because
    // it matches the Application currently being viewed.
    let lockedUptiName = '';

    // Status of the Control currently open in the Edit modal.
    // Kept in sync after in-place actions (e.g. deleting evidence)
    // so the modal/table can update without a full page reload.
    let currentEditingControlId = null;
    let currentEditingControlStatus = 'not_started';

    const scBadgeMapJs = {
        not_started: { label: 'Not Started Yet', cls: 'sc-not-started' },
        drafting: { label: 'Drafting', cls: 'sc-not-started' },
        ongoing_review: { label: 'On Going Review', cls: 'sc-ongoing-review' },
        ongoing_approval: { label: 'On Going Approval', cls: 'sc-ongoing-approval' },
        return_to_officer: { label: 'Return to Officer', cls: 'sc-ongoing-review' },
        return_to_reviewer: { label: 'Return to Reviewer', cls: 'sc-ongoing-review' },
        completed: { label: 'Completed', cls: 'sc-completed' },
    };

    function evidenceIconFor(
        originalName
    ) {

        const ext =
            (
                originalName
                || ''
            )
                .split('.')
                .pop()
                .toLowerCase();

        if (ext === 'pdf') {
            return {
                icon: 'bi-file-earmark-pdf-fill',
                color: '#e11d48',
            };
        }

        if (
            ext === 'doc' ||
            ext === 'docx'
        ) {
            return {
                icon: 'bi-file-earmark-word-fill',
                color: '#2563eb',
            };
        }

        if (
            ext === 'xls' ||
            ext === 'xlsx' ||
            ext === 'csv'
        ) {
            return {
                icon: 'bi-file-earmark-excel-fill',
                color: '#16a34a',
            };
        }

        return {
            icon: 'bi-paperclip',
            color: '#198754',
        };

    }

    function refreshRowEvidencePills(
        controlId,
        evidences
    ) {

        const row =
            document.querySelector(
                `tr[data-id="${controlId}"]`
            );

        if (!row) {
            return;
        }

        const container =
            row.querySelector(
                '.row-evidence-pills'
            );

        if (!container) {
            return;
        }

        const visibleEvidences =
            (evidences || []).filter(
                function (ev) {
                    return ev.file_type !== 'Berita Acara';
                }
            );

        if (!visibleEvidences.length) {

            container.innerHTML =
                '';

            return;

        }

        container.innerHTML =
            visibleEvidences
                .map(
                    function (ev) {

                        const name =
                            ev.original_name
                            || ev.file_name
                            || '';

                        const iconInfo =
                            evidenceIconFor(
                                name
                            );

                        const shortName =
                            name.length > 26
                                ? name.slice(0, 26) + '…'
                                : name;

                        const typeBadge =
                            ev.file_type
                                ? `<span style="background:#e0e7ff;color:#3730a3;font-size:10px;font-weight:700;padding:1px 5px;border-radius:3px;border:1px solid #c7d2fe;white-space:nowrap;">${escapeHtml(ev.file_type)}</span>`
                                : '';

                        return `
                            <a
                                href="/evidence/${ev.id}"
                                target="_blank"
                                class="evidence-pill"
                            >
                                <i class="bi ${iconInfo.icon}" style="color:${iconInfo.color};"></i>
                                <span title="${escapeHtml(name)}">${escapeHtml(shortName)}</span>
                                ${typeBadge}
                            </a>
                        `;

                    }
                )
                .join('');

    }

    function applyControlStatusToUI(
        controlId,
        newStatus
    ) {

        currentEditingControlStatus =
            newStatus;

        const editable =
            [
                'not_started',
                'drafting',
                'return_to_officer',
            ].includes(
                newStatus
            );

        const uploadSectionEl =
            document.getElementById(
                'ec-upload-section'
            );

        const saveButtonEl =
            document.getElementById(
                'btn-save-control'
            );

        if (
            isOfficer &&
            uploadSectionEl
        ) {
            uploadSectionEl.style.display =
                editable
                    ? 'block'
                    : 'none';
        }

        if (
            isOfficer &&
            saveButtonEl
        ) {
            saveButtonEl.style.display =
                editable
                    ? 'inline-flex'
                    : 'none';
        }

        const modalSendBtn =
            document.getElementById(
                'btn-send-to-manager'
            );

        const modalSendLabel =
            document.getElementById(
                'btn-send-to-manager-label'
            );

        if (
            isOfficer &&
            modalSendBtn
        ) {

            modalSendBtn.dataset.controlId =
                controlId;

            if (newStatus === 'drafting') {

                modalSendBtn.style.display =
                    'inline-flex';

                if (modalSendLabel) {
                    modalSendLabel.textContent =
                        'Send to Manager';
                }

            } else if (newStatus === 'return_to_officer') {

                modalSendBtn.style.display =
                    'inline-flex';

                if (modalSendLabel) {
                    modalSendLabel.textContent =
                        'Resubmit to Manager';
                }

            } else {

                modalSendBtn.style.display =
                    'none';

            }

        }

        // Keep the table row (behind the modal) in sync too,
        // so no page reload is needed.
        const row =
            document.querySelector(
                `tr[data-id="${controlId}"]`
            );

        if (row) {

            row.dataset.ctrlStatus =
                newStatus;

            const badge =
                row.querySelector(
                    '.status-badge'
                );

            const info =
                scBadgeMapJs[newStatus]
                || scBadgeMapJs.not_started;

            if (badge) {

                badge.className =
                    `status-badge ${info.cls}`;

                badge.textContent =
                    info.label;

            }

            if (isOfficer) {

                const editIconBtn =
                    row.querySelector(
                        '.row-act-stack .btn-edit-ctrl'
                    );

                const canEdit =
                    [
                        'not_started',
                        'drafting',
                        'return_to_officer',
                    ].includes(
                        newStatus
                    );

                if (editIconBtn) {

                    if (canEdit) {

                        editIconBtn.className =
                            'row-act-btn row-act-edit btn-edit-ctrl';

                        editIconBtn.title =
                            'Upload Evidence';

                        editIconBtn.innerHTML =
                            '<i class="bi bi-cloud-arrow-up-fill"></i>';

                    } else {

                        editIconBtn.className =
                            'row-act-btn row-act-view btn-edit-ctrl';

                        editIconBtn.title =
                            'View Control';

                        editIconBtn.innerHTML =
                            '<i class="bi bi-eye-fill"></i>';

                    }

                }

            }

            const rowSendBtn =
                row.querySelector(
                    '.btn-send-ctrl'
                );

            const showRowSendBtn =
                newStatus === 'drafting' ||
                newStatus === 'return_to_officer';

            const rowSendLabel =
                newStatus === 'return_to_officer'
                    ? 'Resubmit to Manager'
                    : 'Send to Manager';

            if (rowSendBtn) {

                rowSendBtn.style.display =
                    showRowSendBtn
                        ? ''
                        : 'none';

                rowSendBtn.dataset.sendLabel =
                    rowSendLabel;

                rowSendBtn.title =
                    rowSendLabel;

                const textEl =
                    rowSendBtn.querySelector(
                        '.row-act-send-text'
                    );

                if (textEl) {
                    textEl.textContent =
                        rowSendLabel;
                }

            } else if (
                showRowSendBtn
            ) {

                const stack =
                    row.querySelector(
                        '.row-act-stack'
                    );

                if (stack) {

                    const newBtn =
                        document.createElement(
                            'button'
                        );

                    newBtn.type =
                        'button';

                    newBtn.className =
                        'row-act-send-btn btn-send-ctrl';

                    newBtn.title =
                        rowSendLabel;

                    newBtn.dataset.ctrlDbId =
                        controlId;

                    newBtn.dataset.sendLabel =
                        rowSendLabel;

                    newBtn.innerHTML =
                        `<i class="bi bi-send-fill"></i><span class="row-act-send-text">${rowSendLabel}</span>`;

                    stack.appendChild(
                        newBtn
                    );

                }

            }

        }

    }

    const searchEl =
        document.getElementById(
            'itc-search'
        );

    const tbody =
        document.getElementById(
            'itc-tbody'
        );

    const totalEl =
        document.getElementById(
            'itc-total'
        );

    const showingEl =
        document.getElementById(
            'showing-count'
        );

    function escapeHtml(value) {

        return String(value)
            .replace(
                /&/g,
                '&amp;'
            )
            .replace(
                /</g,
                '&lt;'
            )
            .replace(
                />/g,
                '&gt;'
            )
            .replace(
                /"/g,
                '&quot;'
            )
            .replace(
                /'/g,
                '&#039;'
            );

    }

    function openModal(
        element
    ) {

        if (!element) {
            return;
        }

        document
            .querySelectorAll(
                '.itc-modal-backdrop.open'
            )
            .forEach(function (modal) {

                modal.classList.remove(
                    'open'
                );

            });

        element.classList.add(
            'open'
        );

        document.body.style.overflow =
            'hidden';

    }

    function closeModal(
        element
    ) {

        if (!element) {
            return;
        }

        element.classList.remove(
            'open'
        );

        document.body.style.overflow =
            '';

    }

    function wireClose(
        backdropId,
        closeBtnId,
        cancelBtnId
    ) {

        const backdrop =
            document.getElementById(
                backdropId
            );

        const closeBtn =
            document.getElementById(
                closeBtnId
            );

        const cancelBtn =
            document.getElementById(
                cancelBtnId
            );

        if (closeBtn) {

            closeBtn.addEventListener(
                'click',
                function () {

                    closeModal(
                        backdrop
                    );

                }
            );

        }

        if (cancelBtn) {

            cancelBtn.addEventListener(
                'click',
                function () {

                    closeModal(
                        backdrop
                    );

                }
            );

        }

        if (backdrop) {

            backdrop.addEventListener(
                'click',
                function (event) {

                    if (
                        event.target ===
                        backdrop
                    ) {

                        closeModal(
                            backdrop
                        );

                    }

                }
            );

        }

    }

    async function parseJsonResponse(
        response
    ) {

        const contentType =
            response.headers.get(
                'content-type'
            )
            || '';

        if (
            !contentType.includes(
                'application/json'
            )
        ) {

            const text =
                await response.text();

            throw new Error(
                text
                || 'Server returned a non-JSON response.'
            );

        }

        const data =
            await response.json();

        if (!response.ok) {

            let message =
                data.message
                || 'Error processing request.';

            if (data.errors) {

                const errors =
                    Object
                        .values(
                            data.errors
                        )
                        .flat();

                if (errors.length) {

                    message =
                        errors.join(' ');

                }

            }

            throw new Error(
                message
            );

        }

        return data;

    }

    function showNotification(
        message,
        type = 'success'
    ) {

        if (
            window.Swal
        ) {

            Swal.fire({
                icon:
                    type === 'danger'
                        ? 'error'
                        : type,
                text:
                    message,
                timer:
                    2200,
                showConfirmButton:
                    false
            });

            return;

        }

        alert(
            message
        );

    }


    /* ============================================================
       SEARCH
       ============================================================ */

    if (
        searchEl &&
        tbody
    ) {

        searchEl.addEventListener(
            'input',
            function () {

                const term =
                    this.value
                        .trim()
                        .toLowerCase();

                const rows =
                    tbody.querySelectorAll(
                        'tr[data-id]'
                    );

                let count =
                    0;

                rows.forEach(
                    function (row) {

                        const text =
                            row.textContent
                                .toLowerCase();

                        const visible =
                            text.includes(
                                term
                            );

                        row.style.display =
                            visible
                                ? ''
                                : 'none';

                        if (visible) {
                            count++;
                        }

                    }
                );

                if (totalEl) {
                    totalEl.textContent =
                        count;
                }

                if (showingEl) {
                    showingEl.textContent =
                        count;
                }

            }
        );

    }


    /* ============================================================
       FILTER
       ============================================================ */

    const filterBtn =
        document.getElementById(
            'itc-filter-btn'
        );

    if (filterBtn) {

        filterBtn.addEventListener(
            'click',
            function () {

                this.classList.toggle(
                    'active'
                );

            }
        );

    }


    /* ============================================================
       ADD CONTROL
       ============================================================ */

    if (
        isAdmin &&
        addModal &&
        addBtn &&
        addForm
    ) {

        wireClose(
            'addControlModal',
            'addControlClose',
            'addControlCancel'
        );

        async function refreshApplications() {

            if (
                !uptiList ||
                !applicationList
            ) {
                return;
            }

            const selectedUptis =
                Array.from(
                    uptiList.querySelectorAll(
                        'input[name="uptis[]"]:checked'
                    )
                ).map(
                    function (input) {
                        return input.value;
                    }
                );

            applicationList.innerHTML =
                '';

            if (
                generatedIdsBox
            ) {

                generatedIdsBox.innerHTML = `
                    <div
                        style="
                            font-size:12.5px;
                            color:#64748b;
                        "
                    >
                        Select UPTI to generate Control ID automatically.
                    </div>
                `;

            }

            if (
                !selectedUptis.length
            ) {

                currentApplicationIds = [];

                applicationList.innerHTML = `
                    <div
                        style="
                            font-size:12.5px;
                            color:#64748b;
                        "
                    >
                        Select UPTI first.
                    </div>
                `;

                return;

            }

            applicationList.innerHTML = `
                <div
                    style="
                        font-size:12.5px;
                        color:#64748b;
                    "
                >

                    <span
                        class="spinner-border spinner-border-sm"
                        role="status"
                    ></span>

                    Loading applications...

                </div>
            `;

            const params =
                new URLSearchParams();

            selectedUptis.forEach(
                function (upti) {

                    params.append(
                        'uptis[]',
                        upti
                    );

                }
            );

            try {

                const response =
                    await fetch(
                        `{{ route('controls.applicationsByUptis') }}?${params.toString()}`,
                        {
                            method:
                                'GET',

                            headers:{
                                'Accept':
                                    'application/json',

                                'X-Requested-With':
                                    'XMLHttpRequest'
                            }
                        }
                    );

                const data =
                    await parseJsonResponse(
                        response
                    );

                if (
                    !data.success
                ) {

                    throw new Error(
                        data.message
                        || 'Failed to load applications.'
                    );

                }

                const applications =
                    data.applications
                    || [];

                currentApplicationIds =
                    applications.map(
                        function (app) {
                            return app.id;
                        }
                    );

                if (
                    !applications.length
                ) {

                    applicationList.innerHTML = `
                        <div
                            style="
                                font-size:12.5px;
                                color:#dc3545;
                            "
                        >
                            No Application is mapped to the selected UPTI. Please contact Admin to map an Application first.
                        </div>
                    `;

                    return;

                }

                applicationList.innerHTML =
                    applications
                        .map(
                            function (app) {

                                return `
                                    <div
                                        class="mc-item mc-item-static"
                                        style="
                                            display:flex;
                                            align-items:center;
                                            justify-content:space-between;
                                        "
                                    >

                                        <span class="mc-item-text">

                                            ${escapeHtml(app.name)}

                                        </span>

                                        <span class="mc-item-badge">

                                            ${escapeHtml(
                                                app.upti_name
                                                || ''
                                            )}

                                        </span>

                                    </div>
                                `;

                            }
                        )
                        .join('');

                await refreshControlIds();

            } catch (error) {

                currentApplicationIds = [];

                applicationList.innerHTML = `
                    <div
                        style="
                            color:#dc3545;
                            font-size:12.5px;
                        "
                    >

                        ${escapeHtml(
                            error.message
                        )}

                    </div>
                `;

            }

        }

        async function refreshControlIds() {

            if (
                !uptiList ||
                !generatedIdsBox
            ) {
                return;
            }

            const selectedUptis =
                Array.from(
                    uptiList.querySelectorAll(
                        'input[name="uptis[]"]:checked'
                    )
                ).map(
                    function (input) {
                        return input.value;
                    }
                );

            if (
                !selectedUptis.length
            ) {

                generatedIdsBox.innerHTML = `
                    <div
                        style="
                            font-size:12.5px;
                            color:#64748b;
                        "
                    >
                        Select UPTI to generate Control ID automatically.
                    </div>
                `;

                return;

            }

            generatedIdsBox.innerHTML = `
                <div
                    style="
                        font-size:12.5px;
                        color:#64748b;
                        display:flex;
                        align-items:center;
                        gap:7px;
                    "
                >

                    <span
                        class="spinner-border spinner-border-sm"
                        role="status"
                    ></span>

                    Generating Control IDs...

                </div>
            `;

            const params =
                new URLSearchParams();

            selectedUptis.forEach(
                function (upti) {

                    params.append(
                        'uptis[]',
                        upti
                    );

                }
            );

            try {

                const response =
                    await fetch(
                        `{{ route('controls.nextIds') }}?${params.toString()}`,
                        {
                            method:
                                'GET',

                            headers:{
                                'Accept':
                                    'application/json',

                                'X-Requested-With':
                                    'XMLHttpRequest'
                            }
                        }
                    );

                const data =
                    await parseJsonResponse(
                        response
                    );

                if (
                    !data.success
                ) {

                    throw new Error(
                        data.message
                        || 'Failed to generate Control IDs.'
                    );

                }

                const controlIds =
                    data.control_ids
                    || {};

                generatedIdsBox.innerHTML =
                    Object
                        .entries(
                            controlIds
                        )
                        .map(
                            function (
                                [upti, controlId]
                            ) {

                                return `
                                    <div class="mc-control-id-row">

                                        <span class="mc-control-id-upti">

                                            ${escapeHtml(
                                                upti
                                            )}

                                        </span>

                                        <span class="mc-control-id-value">

                                            ${escapeHtml(
                                                controlId
                                            )}

                                        </span>

                                    </div>
                                `;

                            }
                        )
                        .join('');

            } catch (error) {

                generatedIdsBox.innerHTML = `
                    <div
                        style="
                            color:#dc3545;
                            font-size:12.5px;
                        "
                    >

                        ${escapeHtml(
                            error.message
                        )}

                    </div>
                `;

            }

        }

        uptiList.addEventListener(
            'click',
            function (event) {

                const checkbox =
                    event.target.closest(
                        'input[name="uptis[]"]'
                    );

                if (
                    checkbox &&
                    lockedUptiName &&
                    checkbox.value === lockedUptiName
                ) {

                    event.preventDefault();

                    showNotification(
                        `"${lockedUptiName}" is required for this Application and can't be removed here. You can still check other UPTI(s) to add this control there too.`,
                        'info'
                    );

                }

            }
        );

        uptiList.addEventListener(
            'change',
            async function (event) {

                if (
                    event.target &&
                    event.target.matches(
                        'input[name="uptis[]"]'
                    )
                ) {

                    await refreshApplications();

                }

            }
        );

        addBtn.addEventListener(
            'click',
            function () {

                addForm.reset();

                currentApplicationIds = [];

                lockedUptiName =
                    addBtn.dataset.lockedUpti
                    || '';

                uptiList
                    .querySelectorAll(
                        '.mc-item-locked'
                    )
                    .forEach(
                        function (el) {

                            el.classList.remove(
                                'mc-item-locked'
                            );

                        }
                    );

                applicationList.innerHTML = `
                    <div
                        style="
                            font-size:12.5px;
                            color:#64748b;
                        "
                    >
                        Select UPTI first.
                    </div>
                `;

                generatedIdsBox.innerHTML = `
                    <div
                        style="
                            font-size:12.5px;
                            color:#64748b;
                        "
                    >
                        Select UPTI to generate Control ID automatically.
                    </div>
                `;

                openModal(
                    addModal
                );

                if (lockedUptiName) {

                    const lockedCheckbox =
                        uptiList.querySelector(
                            `input[name="uptis[]"][value="${CSS.escape(lockedUptiName)}"]`
                        );

                    if (lockedCheckbox) {

                        lockedCheckbox.checked =
                            true;

                        const lockedLabel =
                            lockedCheckbox.closest(
                                'label'
                            );

                        if (lockedLabel) {

                            lockedLabel.classList.add(
                                'mc-item-locked'
                            );

                        }

                        refreshApplications();

                    }

                }

            }
        );

        addSaveBtn.addEventListener(
            'click',
            async function () {

                if (
                    !addForm.checkValidity()
                ) {

                    addForm.reportValidity();

                    return;

                }

                const selectedUptis =
                    Array.from(
                        document.querySelectorAll(
                            '#mc-uptis-list input[name="uptis[]"]:checked'
                        )
                    );

                if (
                    !selectedUptis.length
                ) {

                    showNotification(
                        'Please select at least one UPTI.',
                        'danger'
                    );

                    return;

                }

                if (
                    !currentApplicationIds.length
                ) {

                    showNotification(
                        'The selected UPTI(s) have no mapped Application. Please contact Admin to map an Application first.',
                        'danger'
                    );

                    return;

                }

                const formData =
                    new FormData(
                        addForm
                    );

                currentApplicationIds.forEach(
                    function (id) {

                        formData.append(
                            'application_ids[]',
                            id
                        );

                    }
                );

                addSaveBtn.disabled =
                    true;

                if (addSaveMsg) {

                    addSaveMsg.innerHTML =
                        '<i class="bi bi-hourglass-split"></i> Saving...';

                }

                try {

                    const response =
                        await fetch(
                            '{{ route('controls.store') }}',
                            {
                                method:
                                    'POST',

                                headers:{
                                    'X-CSRF-TOKEN':
                                        csrfToken,

                                    'Accept':
                                        'application/json',

                                    'X-Requested-With':
                                        'XMLHttpRequest'
                                },

                                body:
                                    formData
                            }
                        );

                    const data =
                        await parseJsonResponse(
                            response
                        );

                    showNotification(
                        data.message
                        || 'Control created successfully.',
                        'success'
                    );

                    closeModal(
                        addModal
                    );

                    setTimeout(
                        function () {

                            window.location.reload();

                        },
                        500
                    );

                } catch (error) {

                    showNotification(
                        error.message
                        || 'Failed to create control.',
                        'danger'
                    );

                } finally {

                    addSaveBtn.disabled =
                        false;

                    if (addSaveMsg) {

                        addSaveMsg.innerHTML =
                            '<i class="bi bi-info-circle"></i> Ready to save';

                    }

                }

            }
        );

    }


    /* ============================================================
       EDIT / UPLOAD / VIEW
       ============================================================ */

    wireClose(
        'editControlModal',
        'editControlClose',
        'editControlCancel'
    );

    document.addEventListener(
        'click',
        function (event) {

            const btn =
                event.target.closest(
                    '.btn-edit-ctrl'
                );

            if (!btn) {
                return;
            }

            const row =
                btn.closest(
                    'tr[data-id]'
                );

            if (!row) {
                return;
            }

            const id =
                row.dataset.id
                || '';

            currentEditingControlId =
                id;

            const ctrlId =
                row.dataset.ctrlId
                || '';

            const ctrlDesc =
                row.dataset.ctrlDesc
                || '';

            const ctrlFrek =
                row.dataset.ctrlFrek
                || '';

            const ctrlUpti =
                row.dataset.ctrlUpti
                || '';

            const ctrlKey =
                row.dataset.ctrlKeyctrl
                || '';

            const ctrlStatus =
                row.dataset.ctrlStatus
                || 'not_started';

            currentEditingControlStatus =
                ctrlStatus;

            const appId =
                row.dataset.appId
                || '';

            const catId =
                row.dataset.catId
                || '';

            const ecDbId =
                document.getElementById(
                    'ec-id'
                );

            const ecApp =
                document.getElementById(
                    'ec-application'
                );

            const ecCat =
                document.getElementById(
                    'ec-category'
                );

            const ecId =
                document.getElementById(
                    'ec-ctrl-id'
                );

            const ecDesc =
                document.getElementById(
                    'ec-description'
                );

            const ecFrek =
                document.getElementById(
                    'ec-frekuensi'
                );

            const ecUpti =
                document.getElementById(
                    'ec-upti'
                );

            const ecKey =
                document.getElementById(
                    'ec-key-control'
                );

            const ecStatus =
                document.getElementById(
                    'ec-status-control'
                );

            if (ecDbId) {
                ecDbId.value =
                    id;
            }

            if (ecApp) {
                ecApp.value =
                    appId;
            }

            if (ecCat) {
                ecCat.value =
                    catId;
            }

            if (ecId) {
                ecId.value =
                    ctrlId;
            }

            if (ecDesc) {
                ecDesc.value =
                    ctrlDesc.replace(
                        /\\/g,
                        ''
                    );
            }

            if (ecFrek) {
                ecFrek.value =
                    ctrlFrek;
            }

            if (ecUpti) {
                ecUpti.value =
                    ctrlUpti;
            }

            if (ecKey) {
                ecKey.value =
                    ctrlKey;
            }

            if (ecStatus) {
                ecStatus.value =
                    ctrlStatus;
            }

            const saveButton =
                document.getElementById(
                    'btn-save-control'
                );

            const uploadSection =
                document.getElementById(
                    'ec-upload-section'
                );

            if (
                isOfficer
            ) {

                const editable =
                    [
                        'not_started',
                        'drafting',
                        'return_to_officer',
                    ].includes(
                        ctrlStatus
                    );

                if (uploadSection) {

                    uploadSection.style.display =
                        editable
                            ? 'block'
                            : 'none';

                }

                if (saveButton) {

                    saveButton.style.display =
                        editable
                            ? 'inline-flex'
                            : 'none';

                }

            }

            if (
                !isAdmin &&
                !isOfficer &&
                saveButton
            ) {

                saveButton.style.display =
                    'none';

            }

            const sendToManagerBtn =
                document.getElementById(
                    'btn-send-to-manager'
                );

            const sendToManagerLabel =
                document.getElementById(
                    'btn-send-to-manager-label'
                );

            if (
                isOfficer &&
                sendToManagerBtn
            ) {

                sendToManagerBtn.dataset.controlId =
                    id;

                if (
                    ctrlStatus === 'drafting'
                ) {

                    sendToManagerBtn.style.display =
                        'inline-flex';

                    if (sendToManagerLabel) {
                        sendToManagerLabel.textContent =
                            'Send to Manager';
                    }

                } else if (
                    ctrlStatus === 'return_to_officer'
                ) {

                    sendToManagerBtn.style.display =
                        'inline-flex';

                    if (sendToManagerLabel) {
                        sendToManagerLabel.textContent =
                            'Resubmit to Manager';
                    }

                } else {

                    sendToManagerBtn.style.display =
                        'none';

                }

            }

            const evidenceList =
                document.getElementById(
                    'ec-existing-files'
                );

            if (evidenceList) {

                evidenceList.innerHTML = `
                    <li
                        style="
                            font-size:12px;
                            color:#94a3b8;
                            font-style:italic;
                        "
                    >

                        <i class="bi bi-hourglass-split"></i>

                        Loading files...

                    </li>
                `;

            }

            fetch(
                `/controls/${id}/evidence`,
                {
                    headers:{
                        'Accept':
                            'application/json',
                        'X-CSRF-TOKEN':
                            csrfToken
                    }
                }
            )
            .then(
                parseJsonResponse
            )
            .then(
                function (data) {

                    renderExistingEvidences(
                        data.evidences
                        || [],
                        ctrlStatus
                    );

                }
            )
            .catch(
                function () {

                    renderExistingEvidences(
                        [],
                        ctrlStatus
                    );

                }
            );

            openModal(
                document.getElementById(
                    'editControlModal'
                )
            );

        }
    );


    function renderExistingEvidences(
        evidences,
        controlStatus
    ) {

        const list =
            document.getElementById(
                'ec-existing-files'
            );

        if (!list) {
            return;
        }

        list.innerHTML =
            '';

        if (
            !evidences.length
        ) {

            list.innerHTML = `
                <li
                    style="
                        font-size:12px;
                        color:#94a3b8;
                        font-style:italic;
                    "
                >

                    No evidence files currently attached.

                </li>
            `;

            return;

        }

        const fileTypeOptions = [
            {
                value:'',
                label:'-- File Type --'
            },
            {
                value:'Population Data',
                label:'Population Data'
            },
            {
                value:'Information provided by Entity',
                label:'Information provided by Entity'
            },
            {
                value:'Supporting Document',
                label:'Supporting Document'
            },
            {
                value:'Others',
                label:'Others'
            }
        ];

        evidences.forEach(
            function (ev) {

                if (
                    ev.file_type ===
                    'Berita Acara'
                ) {

                    return;

                }

                const li =
                    document.createElement(
                        'li'
                    );

                li.style.cssText = `
                    background:#f8fafc;
                    padding:10px 12px;
                    border-radius:6px;
                    border:1px solid #e2e8f0;
                    display:flex;
                    align-items:center;
                    gap:8px;
                `;

                let fileTypeHtml =
                    '';

                if (
                    isAdmin
                ) {

                    const options =
                        fileTypeOptions
                            .map(
                                function (
                                    option
                                ) {

                                    return `
                                        <option
                                            value="${escapeHtml(option.value)}"
                                            ${
                                                option.value ===
                                                (
                                                    ev.file_type
                                                    || ''
                                                )
                                                    ? 'selected'
                                                    : ''
                                            }
                                        >

                                            ${escapeHtml(
                                                option.label
                                            )}

                                        </option>
                                    `;

                                }
                            )
                            .join('');

                    fileTypeHtml = `
                        <select
                            class="existing-file-type-select"
                            data-id="${ev.id}"
                            data-original="${escapeHtml(ev.file_type || '')}"
                            style="
                                font-size:11.5px;
                                padding:3px 7px;
                                border:1px solid #d1d5db;
                                border-radius:5px;
                                background:#fff;
                                color:#111827;
                                min-width:160px;
                            "
                        >

                            ${options}

                        </select>
                    `;

                } else {

                    fileTypeHtml = `
                        <span
                            style="
                                background:#e0e7ff;
                                color:#3730a3;
                                font-size:11px;
                                font-weight:600;
                                padding:2px 8px;
                                border-radius:4px;
                                white-space:nowrap;
                            "
                        >

                            ${escapeHtml(
                                ev.file_type
                                || 'No type'
                            )}

                        </span>
                    `;

                }

                let deleteHtml =
                    '';

                if (
                    isAdmin ||
                    (
                        isOfficer &&
                        [
                            'not_started',
                            'drafting',
                            'return_to_officer'
                        ].includes(
                            controlStatus
                        )
                    )
                ) {

                    deleteHtml = `
                        <button
                            type="button"
                            class="btn-delete-ev"
                            data-id="${ev.id}"
                            style="
                                color:#dc3545;
                                background:#fef2f2;
                                border:none;
                                padding:4px 8px;
                                border-radius:4px;
                                cursor:pointer;
                                font-size:11.5px;
                            "
                        >

                            <i class="bi bi-trash3-fill"></i>

                        </button>
                    `;

                }

                li.innerHTML = `
                    <a
                        href="{{ url('/evidence') }}/${ev.id}"
                        target="_blank"
                        style="
                            display:flex;
                            align-items:center;
                            gap:8px;
                            flex:1;
                            min-width:0;
                            color:#198754;
                            text-decoration:none;
                            font-weight:600;
                            font-size:12px;
                        "
                    >

                        <i class="bi bi-file-earmark-text"></i>

                        <span
                            style="
                                white-space:nowrap;
                                overflow:hidden;
                                text-overflow:ellipsis;
                                max-width:220px;
                            "
                        >

                            ${escapeHtml(
                                ev.original_name
                                || ev.file_name
                            )}

                        </span>

                    </a>

                    ${fileTypeHtml}

                    ${deleteHtml}
                `;

                list.appendChild(
                    li
                );

                const select =
                    li.querySelector(
                        '.existing-file-type-select'
                    );

                if (
                    select &&
                    isAdmin
                ) {

                    select.addEventListener(
                        'change',
                        async function () {

                            const newValue =
                                this.value;

                            const originalValue =
                                this.dataset.original
                                || '';

                            if (
                                newValue ===
                                originalValue
                            ) {

                                return;

                            }

                            const confirmed =
                                await Swal.fire({
                                    title:
                                        'Change File Type?',

                                    text:
                                        'Changing the File Type may affect the control workflow.',

                                    icon:
                                        'warning',

                                    showCancelButton:
                                        true,

                                    confirmButtonText:
                                        'Yes, change it',

                                    cancelButtonText:
                                        'Cancel',

                                    confirmButtonColor:
                                        '#198754'
                                });

                            if (
                                !confirmed.isConfirmed
                            ) {

                                this.value =
                                    originalValue;

                                return;

                            }

                            try {

                                const response =
                                    await fetch(
                                        `/evidence/${ev.id}`,
                                        {
                                            method:
                                                'PUT',

                                            headers:{
                                                'X-CSRF-TOKEN':
                                                    csrfToken,

                                                'Accept':
                                                    'application/json',

                                                'Content-Type':
                                                    'application/json'
                                            },

                                            body:
                                                JSON.stringify({
                                                    file_type:
                                                        newValue
                                                })
                                        }
                                    );

                                const data =
                                    await parseJsonResponse(
                                        response
                                    );

                                this.dataset.original =
                                    newValue;

                                showNotification(
                                    data.message
                                    || 'File Type updated.',
                                    'success'
                                );

                            } catch (error) {

                                this.value =
                                    originalValue;

                                showNotification(
                                    error.message
                                    || 'Failed to update File Type.',
                                    'danger'
                                );

                            }

                        }
                    );

                }

            }
        );

    }


    /* ============================================================
       FILE PICKER
       ============================================================ */

    const fileInput =
        document.getElementById(
            'ec-evidences'
        );

    const selectedFilesContainer =
        document.getElementById(
            'ec-selected-files-list'
        );

    if (
        fileInput &&
        selectedFilesContainer
    ) {

        fileInput.addEventListener(
            'change',
            function () {

                selectedFilesContainer.innerHTML =
                    '';

                if (
                    !this.files ||
                    !this.files.length
                ) {

                    return;

                }

                if (
                    this.files.length > 10
                ) {

                    showNotification(
                        'You can upload a maximum of 10 files at once.',
                        'danger'
                    );

                    this.value =
                        '';

                    return;

                }

                Array.from(
                    this.files
                ).forEach(
                    function (file, index) {

                        if (
                            file.size >
                            10 * 1024 * 1024
                        ) {

                            showNotification(
                                `File "${file.name}" is larger than 10MB.`,
                                'danger'
                            );

                            return;

                        }

                        const div =
                            document.createElement(
                                'div'
                            );

                        div.style.cssText = `
                            background:#f0fdf4;
                            border:1px solid #bbf7d0;
                            padding:8px 12px;
                            border-radius:8px;
                            display:flex;
                            flex-direction:column;
                            gap:6px;
                            font-size:12px;
                        `;

                        const options =
                            [
                                '<option value="">-- Select File Type * --</option>'
                            ]
                            .concat(
                                [
                                    'Population Data',
                                    'Information provided by Entity',
                                    'Supporting Document',
                                    'Others'
                                ].map(
                                    function (
                                        type
                                    ) {

                                        return `
                                            <option value="${escapeHtml(type)}">

                                                ${escapeHtml(type)}

                                            </option>
                                        `;

                                    }
                                )
                            )
                            .join('');

                        div.innerHTML = `
                            <div
                                style="
                                    display:flex;
                                    align-items:center;
                                    gap:8px;
                                "
                            >

                                <i
                                    class="bi bi-file-earmark-arrow-up-fill"
                                    style="
                                        color:#198754;
                                        font-size:15px;
                                    "
                                ></i>

                                <span
                                    style="
                                        font-weight:600;
                                        overflow:hidden;
                                        white-space:nowrap;
                                        text-overflow:ellipsis;
                                        max-width:220px;
                                    "
                                >

                                    ${escapeHtml(file.name)}

                                </span>

                            </div>

                            <div
                                style="
                                    display:flex;
                                    align-items:center;
                                    gap:8px;
                                "
                            >

                                <label
                                    style="
                                        font-size:11.5px;
                                        font-weight:600;
                                        color:#374151;
                                    "
                                >

                                    File Type *

                                </label>

                                <select
                                    name="file_types[]"
                                    required
                                    style="
                                        flex:1;
                                        font-size:12px;
                                        padding:4px 8px;
                                        border:1px solid #d1d5db;
                                        border-radius:6px;
                                        background:#fff;
                                    "
                                >

                                    ${options}

                                </select>

                            </div>
                        `;

                        selectedFilesContainer.appendChild(
                            div
                        );

                    }
                );

            }
        );

    }


    /* ============================================================
       SAVE EDIT / UPLOAD
       ============================================================ */

    const saveControlBtn =
        document.getElementById(
            'btn-save-control'
        );

    if (saveControlBtn) {

        saveControlBtn.addEventListener(
            'click',
            async function () {

                const form =
                    document.getElementById(
                        'editControlForm'
                    );

                const id =
                    document
                        .getElementById(
                            'ec-id'
                        )
                        ?.value;

                if (!id) {
                    return;
                }

                if (
                    isOfficer
                ) {

                    const input =
                        document.getElementById(
                            'ec-evidences'
                        );

                    if (
                        !input ||
                        !input.files.length
                    ) {

                        showNotification(
                            'Please select at least one evidence file.',
                            'danger'
                        );

                        return;

                    }

                    const typeSelects =
                        form.querySelectorAll(
                            'select[name="file_types[]"]'
                        );

                    for (
                        const select
                        of typeSelects
                    ) {

                        if (
                            !select.value
                        ) {

                            showNotification(
                                'Please select a File Type for each uploaded file.',
                                'danger'
                            );

                            return;

                        }

                    }

                }

                saveControlBtn.disabled =
                    true;

                try {

                    const formData =
                        new FormData(
                            form
                        );

                    formData.append(
                        '_method',
                        'PUT'
                    );

                    const response =
                        await fetch(
                            `/controls/${id}`,
                            {
                                method:
                                    'POST',

                                headers:{
                                    'X-CSRF-TOKEN':
                                        csrfToken,

                                    'Accept':
                                        'application/json',

                                    'X-Requested-With':
                                        'XMLHttpRequest'
                                },

                                body:
                                    formData
                            }
                        );

                    const data =
                        await parseJsonResponse(
                            response
                        );

                    showNotification(
                        data.message
                        || 'Saved successfully.',
                        'success'
                    );

                    closeModal(
                        document.getElementById(
                            'editControlModal'
                        )
                    );

                    setTimeout(
                        function () {

                            location.reload();

                        },
                        500
                    );

                } catch (error) {

                    showNotification(
                        error.message
                        || 'Failed to save.',
                        'danger'
                    );

                } finally {

                    saveControlBtn.disabled =
                        false;

                }

            }
        );

    }


    /* ============================================================
       APPROVE / REJECT (Reviewer / Approver)
       ============================================================ */

    function countWords(text) {

        return (text || '')
            .trim()
            .split(/\s+/)
            .filter(Boolean)
            .length;

    }

    async function performControlAction(
        controlId,
        toStatus,
        actionLabel
    ) {

        const isReject =
            toStatus === 'return_to_officer';

        const result =
            await Swal.fire({
                title:
                    `${actionLabel} this control?`,

                input:
                    'textarea',

                inputLabel:
                    'Notes (minimum 3 words, required)',

                inputPlaceholder:
                    'Write your notes here...',

                inputAttributes:{
                    'aria-label':
                        'Notes'
                },

                showCancelButton:
                    true,

                confirmButtonText:
                    actionLabel,

                confirmButtonColor:
                    isReject
                        ? '#dc3545'
                        : '#198754',

                cancelButtonText:
                    'Cancel',

                inputValidator:
                    function (value) {

                        if (
                            countWords(value) < 3
                        ) {

                            return 'Notes must contain at least 3 words.';

                        }

                    }
            });

        if (!result.isConfirmed) {
            return;
        }

        const notes =
            result.value;

        try {

            const response =
                await fetch(
                    `/controls/${controlId}/transition`,
                    {
                        method:
                            'POST',

                        headers:{
                            'Content-Type':
                                'application/json',

                            'X-CSRF-TOKEN':
                                csrfToken,

                            'Accept':
                                'application/json',

                            'X-Requested-With':
                                'XMLHttpRequest'
                        },

                        body:
                            JSON.stringify({
                                to_status:
                                    toStatus,

                                notes:
                                    notes,
                            })
                    }
                );

            const data =
                await parseJsonResponse(
                    response
                );

            if (
                !data.success
            ) {

                throw new Error(
                    data.message
                    || `Failed to ${actionLabel.toLowerCase()} control.`
                );

            }

            showNotification(
                data.message
                || `Control ${actionLabel.toLowerCase()}d successfully.`,
                'success'
            );

            setTimeout(
                function () {

                    location.reload();

                },
                500
            );

        } catch (error) {

            showNotification(
                error.message
                || `Failed to ${actionLabel.toLowerCase()} control.`,
                'danger'
            );

        }

    }

    document.addEventListener(
        'click',
        function (event) {

            const approveBtn =
                event.target.closest(
                    '.btn-approve-ctrl'
                );

            if (approveBtn) {

                performControlAction(
                    approveBtn.dataset.ctrlDbId,
                    approveBtn.dataset.toStatus,
                    'Approve'
                );

                return;

            }

            const rejectBtn =
                event.target.closest(
                    '.btn-reject-ctrl'
                );

            if (rejectBtn) {

                performControlAction(
                    rejectBtn.dataset.ctrlDbId,
                    rejectBtn.dataset.toStatus,
                    'Reject'
                );

            }

        }
    );


    /* ============================================================
       SEND TO MANAGER (Officer → Reviewer)
       ============================================================ */

    async function sendControlToManager(
        controlId,
        label,
        triggerBtn
    ) {

        label =
            label
            || 'Send to Manager';

        const confirmed =
            await Swal.fire({
                title:
                    `${label}?`,

                text:
                    'This control will move to On Going Review and the Manager will be notified.',

                icon:
                    'question',

                showCancelButton:
                    true,

                confirmButtonText:
                    label,

                confirmButtonColor:
                    '#8b5cf6',
            });

        if (!confirmed.isConfirmed) {
            return;
        }

        if (triggerBtn) {
            triggerBtn.disabled =
                true;
        }

        try {

            const response =
                await fetch(
                    `/controls/${controlId}/transition`,
                    {
                        method:
                            'POST',

                        headers:{
                            'Content-Type':
                                'application/json',

                            'X-CSRF-TOKEN':
                                csrfToken,

                            'Accept':
                                'application/json',

                            'X-Requested-With':
                                'XMLHttpRequest'
                        },

                        body:
                            JSON.stringify({
                                to_status:
                                    'ongoing_review',
                            })
                    }
                );

            const data =
                await parseJsonResponse(
                    response
                );

            if (
                !data.success
            ) {

                throw new Error(
                    data.message
                    || 'Failed to send to manager.'
                );

            }

            showNotification(
                data.message
                || 'Control sent to Manager successfully.',
                'success'
            );

            closeModal(
                document.getElementById(
                    'editControlModal'
                )
            );

            setTimeout(
                function () {

                    location.reload();

                },
                500
            );

        } catch (error) {

            showNotification(
                error.message
                || 'Failed to send to manager.',
                'danger'
            );

        } finally {

            if (triggerBtn) {
                triggerBtn.disabled =
                    false;
            }

        }

    }

    const sendToManagerBtnEl =
        document.getElementById(
            'btn-send-to-manager'
        );

    if (sendToManagerBtnEl) {

        sendToManagerBtnEl.addEventListener(
            'click',
            function () {

                const id =
                    sendToManagerBtnEl.dataset.controlId;

                if (!id) {
                    return;
                }

                const label =
                    document.getElementById(
                        'btn-send-to-manager-label'
                    )?.textContent.trim()
                    || 'Send to Manager';

                sendControlToManager(
                    id,
                    label,
                    sendToManagerBtnEl
                );

            }
        );

    }

    // Row-level "Send to Manager" icon button (Officer, table row).
    document.addEventListener(
        'click',
        function (event) {

            const btn =
                event.target.closest(
                    '.btn-send-ctrl'
                );

            if (!btn) {
                return;
            }

            const id =
                btn.dataset.ctrlDbId;

            if (!id) {
                return;
            }

            const label =
                btn.dataset.sendLabel
                || 'Send to Manager';

            sendControlToManager(
                id,
                label,
                btn
            );

        }
    );


    /* ============================================================
       DELETE EVIDENCE
       ============================================================ */

    document.addEventListener(
        'click',
        async function (event) {

            const btn =
                event.target.closest(
                    '.btn-delete-ev'
                );

            if (!btn) {
                return;
            }

            const confirmed =
                await Swal.fire({
                    title:
                        'Are you sure you want to delete this file?',

                    text:
                        'This file will be permanently deleted and cannot be undone.',

                    icon:
                        'warning',

                    showCancelButton:
                        true,

                    confirmButtonText:
                        'Delete',

                    cancelButtonText:
                        'Cancel',

                    confirmButtonColor:
                        '#dc3545'
                });

            if (
                !confirmed.isConfirmed
            ) {

                return;

            }

            const evId =
                btn.dataset.id;

            btn.disabled =
                true;

            try {

                const response =
                    await fetch(
                        `/evidence/${evId}`,
                        {
                            method:
                                'DELETE',

                            headers:{
                                'X-CSRF-TOKEN':
                                    csrfToken,

                                'Accept':
                                    'application/json'
                            }
                        }
                    );

                const data =
                    await parseJsonResponse(
                        response
                    );

                showNotification(
                    data.message
                    || 'Evidence deleted.',
                    'success'
                );

                const newStatus =
                    data.new_status
                    || currentEditingControlStatus;

                const controlIdForRefresh =
                    currentEditingControlId
                    || document.getElementById(
                        'ec-id'
                    )?.value;

                if (controlIdForRefresh) {

                    try {

                        applyControlStatusToUI(
                            controlIdForRefresh,
                            newStatus
                        );

                    } catch (uiError) {

                        // Non-fatal — don't block modal close.

                    }

                }

                closeModal(
                    document.getElementById(
                        'editControlModal'
                    )
                );

                if (controlIdForRefresh) {

                    fetch(
                        `/controls/${controlIdForRefresh}/evidence`,
                        {
                            headers:{
                                'Accept':
                                    'application/json',
                                'X-CSRF-TOKEN':
                                    csrfToken
                            }
                        }
                    )
                    .then(
                        parseJsonResponse
                    )
                    .then(
                        function (refreshData) {

                            refreshRowEvidencePills(
                                controlIdForRefresh,
                                refreshData.evidences
                                || []
                            );

                        }
                    )
                    .catch(
                        function () {
                            // Non-fatal: file already deleted.
                        }
                    );

                }

            } catch (error) {

                showNotification(
                    error.message
                    || 'Failed to delete evidence.',
                    'danger'
                );

                btn.disabled =
                    false;

            }

        }
    );


    /* ============================================================
       DELETE CONTROL
       ============================================================ */

    if (
        isAdmin
    ) {

        wireClose(
            'deleteControlModal',
            'deleteControlClose',
            'deleteControlCancel'
        );

        const deleteDbId =
            document.getElementById(
                'delete-ctrl-db-id'
            );

        const deleteLabel =
            document.getElementById(
                'delete-ctrl-id-label'
            );

        document.addEventListener(
            'click',
            function (event) {

                const btn =
                    event.target.closest(
                        '.btn-delete-ctrl'
                    );

                if (!btn) {
                    return;
                }

                const row =
                    btn.closest(
                        'tr[data-id]'
                    );

                if (!row) {
                    return;
                }

                const id =
                    row.dataset.id;

                const ctrlId =
                    btn.dataset.ctrlId
                    || '—';

                if (deleteDbId) {
                    deleteDbId.value =
                        id;
                }

                if (deleteLabel) {
                    deleteLabel.textContent =
                        ctrlId;
                }

                openModal(
                    document.getElementById(
                        'deleteControlModal'
                    )
                );

            }
        );

        const confirmDelete =
            document.getElementById(
                'btn-confirm-delete'
            );

        if (
            confirmDelete
        ) {

            confirmDelete.addEventListener(
                'click',
                async function () {

                    const id =
                        deleteDbId?.value;

                    if (!id) {
                        return;
                    }

                    confirmDelete.disabled =
                        true;

                    try {

                        const response =
                            await fetch(
                                `/controls/${id}`,
                                {
                                    method:
                                        'DELETE',

                                    headers:{
                                        'X-CSRF-TOKEN':
                                            csrfToken,

                                        'Accept':
                                            'application/json'
                                    }
                                }
                            );

                        const data =
                            await parseJsonResponse(
                                response
                            );

                        showNotification(
                            data.message
                            || 'Control deleted.',
                            'success'
                        );

                        setTimeout(
                            function () {
                                location.reload();
                            },
                            500
                        );

                    } catch (error) {

                        showNotification(
                            error.message
                            || 'Failed to delete Control.',
                            'danger'
                        );

                    } finally {

                        confirmDelete.disabled =
                            false;

                    }

                }
            );

        }


        /* ============================================================
           DELETE ALL
           ============================================================ */

        wireClose(
            'deleteAllControlModal',
            'deleteAllControlClose',
            'deleteAllControlCancel'
        );

        const deleteAllBtn =
            document.getElementById(
                'itc-delete-all-btn'
            );

        if (
            deleteAllBtn
        ) {

            deleteAllBtn.addEventListener(
                'click',
                function () {

                    openModal(
                        document.getElementById(
                            'deleteAllControlModal'
                        )
                    );

                }
            );

        }

        const confirmDeleteAll =
            document.getElementById(
                'btn-confirm-delete-all'
            );

        if (
            confirmDeleteAll
        ) {

            confirmDeleteAll.addEventListener(
                'click',
                async function () {

                    const appId =
                        document.getElementById(
                            'delete-all-app-id'
                        )?.value;

                    const catId =
                        document.getElementById(
                            'delete-all-cat-id'
                        )?.value;

                    const year =
                        document.getElementById(
                            'delete-all-year'
                        )?.value;

                    const quarter =
                        document.getElementById(
                            'delete-all-quarter'
                        )?.value;

                    confirmDeleteAll.disabled =
                        true;

                    try {

                        const response =
                            await fetch(
                                '/controls/delete-all',
                                {
                                    method:
                                        'DELETE',

                                    headers:{
                                        'Content-Type':
                                            'application/json',

                                        'X-CSRF-TOKEN':
                                            csrfToken,

                                        'Accept':
                                            'application/json'
                                    },

                                    body:
                                        JSON.stringify({
                                            application_id:
                                                appId,

                                            it_category_id:
                                                catId,

                                            year:
                                                year,

                                            quarter:
                                                quarter
                                        })
                                }
                            );

                        const data =
                            await parseJsonResponse(
                                response
                            );

                        showNotification(
                            data.message
                            || 'All controls deleted.',
                            'success'
                        );

                        setTimeout(
                            function () {

                                location.reload();

                            },
                            500
                        );

                    } catch (error) {

                        showNotification(
                            error.message
                            || 'Failed to delete controls.',
                            'danger'
                        );

                    } finally {

                        confirmDeleteAll.disabled =
                            false;

                    }

                }
            );

        }

    }


    /* ============================================================
       ESCAPE
       ============================================================ */

    document.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key !==
                'Escape'
            ) {
                return;
            }

            document
                .querySelectorAll(
                    '.itc-modal-backdrop.open'
                )
                .forEach(
                    function (modal) {

                        closeModal(
                            modal
                        );

                    }
                );

        }
    );

})();
</script>
@endpush