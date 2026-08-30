@extends('layouts.app')

@section('title', 'App Management')

@section('content')
<div class="welcome-hero" style="padding: 24px 32px; background: linear-gradient(135deg, #0f172a, #1e293b); border-radius: 16px; margin-bottom: 24px; position: relative; overflow: hidden;">
    <div style="position: relative; z-index: 2; display: flex; align-items: center; justify-content: space-between; flex-wrap:wrap; gap:16px;">
        <div>
            <h1 style="color: #fff; font-size: 24px; font-weight: 700; margin: 0 0 8px 0; letter-spacing: -0.5px;">App Management</h1>
            <p style="color: #94a3b8; font-size: 14px; margin: 0; max-width: 600px; line-height: 1.5;">
                Manage the list of applications, UPTI, and their mappings.
            </p>
        </div>
        <div style="display:flex; flex-direction:column; gap:10px; align-items:stretch;">
            <button type="button" id="btn-add-period"
                    style="display:inline-flex; align-items:center; justify-content:center; gap:7px; background:linear-gradient(135deg,#059669,#047857); color:#fff; border:none; padding:8px 16px; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; transition:all 0.2s; white-space:nowrap; flex-shrink:0;">
                <i class="bi bi-calendar-plus-fill"></i> Add Period
            </button>
            <button type="button" id="btn-delete-period"
                    style="display:inline-flex; align-items:center; justify-content:center; gap:7px; background:linear-gradient(135deg,#dc2626,#b91c1c); color:#fff; border:none; padding:8px 16px; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; transition:all 0.2s; white-space:nowrap; flex-shrink:0;">
                <i class="bi bi-trash-fill"></i> Delete Period
            </button>
        </div>
    </div>
</div>


    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 style="font-weight:700; color:#1e293b; margin:0;">Applications List</h5>
            <div style="display:flex; gap:10px; align-items:center;">
                <button class="btn btn-sm btn-outline-dark" style="border-radius:8px; font-weight:600;" onclick="openUptiModal()">
                    <i class="bi bi-plus-lg"></i> Add UPTI
                </button>
                <button class="btn btn-sm btn-dark" style="border-radius:8px; font-weight:600;" onclick="openAppModal()">
                    <i class="bi bi-plus-lg"></i> Add Application
                </button>
            </div>
        </div>
        <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 14px;">
                        <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                            <tr>
                                <th style="padding: 16px 24px; color: #475569; font-weight: 600; width: 50px;">#</th>
                                <th style="padding: 16px 24px; color: #475569; font-weight: 600;">Application Name</th>
                                <th style="padding: 16px 24px; color: #475569; font-weight: 600; width: 180px; text-align: center;">Total IT RCM</th>
                                <th style="padding: 16px 24px; color: #475569; font-weight: 600; width: 180px;">UPTI Mapping</th>
                                <th style="padding: 16px 24px; color: #475569; font-weight: 600; width: 120px; text-align: center;">Status</th>
                                <th style="padding: 16px 24px; color: #475569; font-weight: 600; width: 200px; text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($applications as $index => $app)
                            <tr>
                                <td style="padding: 16px 24px; color: #64748b;">{{ $index + 1 }}</td>
                                <td style="padding: 16px 24px; font-weight: 500; color: #1e293b;">
                                    {{ $app->name }}
                                </td>
                                <td style="padding: 16px 24px; text-align: center; color: #475569; font-weight: 600;">
                                    <span style="background: #f1f5f9; padding: 4px 10px; border-radius: 6px; font-size: 13px;">
                                        {{ $app->itCategories->count() }}
                                    </span>
                                </td>
                                <td style="padding: 16px 24px;">
                                    @if($app->upti)
                                        <span class="badge bg-secondary rounded-pill" style="font-weight: 600;">{{ $app->upti->name }}</span>
                                    @else
                                        <span class="text-muted" style="font-size:12px; font-style:italic;">Unmapped</span>
                                    @endif
                                </td>
                                <td style="padding: 16px 24px; text-align: center;">
                                    @if($app->is_active)
                                        <span style="background: #dcfce7; color: #166534; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 600;">Active</span>
                                    @else
                                        <span style="background: #fee2e2; color: #991b1b; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 600;">Inactive</span>
                                    @endif
                                </td>
                                <td style="padding: 16px 24px; text-align: center;">
                                    <button type="button" class="btn btn-sm btn-outline-primary" style="border-radius: 8px; font-weight: 600; margin-right:5px;" onclick="openAppModal({{ $app->id }}, '{{ addslashes($app->name) }}', '{{ $app->upti_id }}')">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    @if($app->is_active)
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-delete-app" data-id="{{ $app->id }}" data-name="{{ $app->name }}" style="border-radius: 8px; font-weight: 600;">
                                        <i class="bi bi-pause-circle"></i> Deact.
                                    </button>
                                    @else
                                    <button type="button" class="btn btn-sm btn-outline-success btn-activate-app" data-id="{{ $app->id }}" data-name="{{ $app->name }}" style="border-radius: 8px; font-weight: 600; margin-right:5px;">
                                        <i class="bi bi-check-circle"></i> Act.
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-force-delete-app" data-id="{{ $app->id }}" data-name="{{ $app->name }}" style="border-radius: 8px; font-weight: 600;">
                                        <i class="bi bi-trash"></i> Del.
                                    </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding: 48px; text-align: center; color: #64748b;">
                                    <i class="bi bi-inbox" style="font-size: 32px; color: #cbd5e1; margin-bottom: 12px; display: block;"></i>
                                    No applications found.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 style="font-weight:700; color:#1e293b; margin:0;">UPTI List</h5>
        </div>
        <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: visible;">
            <div class="card-body p-0">
                <div class="table-responsive" style="overflow: visible;">
                    <table class="table table-hover align-middle mb-0" style="font-size: 14px;">
                        <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; border-radius: 16px 16px 0 0;">
                            <tr>
                                <th style="padding: 16px 24px; color: #475569; font-weight: 600; width: 50px;">#</th>
                                <th style="padding: 16px 24px; color: #475569; font-weight: 600;">UPTI Name</th>
                                <th style="padding: 16px 24px; color: #475569; font-weight: 600; width: 120px; text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($uptis as $index => $u)
                            <tr>
                                <td style="padding: 16px 24px; color: #64748b;">{{ $index + 1 }}</td>
                                <td style="padding: 16px 24px; font-weight: 500; color: #1e293b;">{{ $u->name }}</td>
                                <td style="padding: 16px 24px; text-align: center; position: relative;">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm btn-light" style="border-radius: 8px; border: 1px solid #e2e8f0; width: 34px;" onclick="toggleUptiActionMenu(event, {{ $u->id }})">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <div id="upti-menu-{{ $u->id }}" class="upti-action-menu" style="display:none; position:absolute; right:24px; top:100%; margin-top:4px; background:#fff; border:1px solid #e2e8f0; border-radius:10px; box-shadow:0 8px 24px rgba(0,0,0,0.12); z-index:50; min-width:140px; overflow:hidden; text-align:left;">
                                            <button type="button" style="width:100%; padding:10px 14px; background:none; border:none; text-align:left; font-size:13.5px; font-weight:600; color:#1d4ed8; cursor:pointer; display:flex; align-items:center; gap:8px;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='none'" onclick="openUptiModal({{ $u->id }}, '{{ addslashes($u->name) }}'); closeAllUptiMenus();">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </button>
                                            <button type="button" style="width:100%; padding:10px 14px; background:none; border:none; text-align:left; font-size:13.5px; font-weight:600; color:#dc2626; cursor:pointer; display:flex; align-items:center; gap:8px; border-top:1px solid #f1f5f9;" onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='none'" onclick="deleteUpti({{ $u->id }}, '{{ addslashes($u->name) }}'); closeAllUptiMenus();">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" style="padding: 48px; text-align: center; color: #64748b;">No UPTI found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->isAdmin())
{{-- MODALS SECTION --}}

{{-- 1. App Modal (Add/Edit Application) --}}
<div id="appModal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.45); backdrop-filter:blur(3px); align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:16px; width:100%; max-width:440px; margin:16px; box-shadow:0 20px 60px rgba(0,0,0,0.2); overflow:hidden;">
        <div style="background:linear-gradient(135deg,#1e293b,#0f172a); padding:20px 24px; display:flex; align-items:center; justify-content:space-between;">
            <h5 id="appModalTitle" style="margin:0; color:#fff; font-size:16px; font-weight:700;">Add Application</h5>
            <button type="button" onclick="closeAppModal()" style="background:rgba(255,255,255,0.15); border:none; color:#fff; width:32px; height:32px; border-radius:6px; cursor:pointer; font-size:18px; display:flex; align-items:center; justify-content:center;">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div style="padding:24px; display:flex; flex-direction:column; gap:16px;">
            <input type="hidden" id="am-id">
            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px;">Application Name <span style="color:#dc2626;">*</span></label>
                <input type="text" id="am-name" style="width:100%; padding:10px 12px; border:1.5px solid #d1d5db; border-radius:8px; font-size:13.5px; outline:none;" required>
            </div>
            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px;">UPTI Mapping</label>
                <select id="am-upti" style="width:100%; padding:10px 12px; border:1.5px solid #d1d5db; border-radius:8px; font-size:13.5px; outline:none;">
                    <option value="">-- None --</option>
                    @foreach($uptis as $u)
                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div style="padding:16px 24px; background:#f8fafc; border-top:1px solid #e5e7eb; display:flex; align-items:center; justify-content:flex-end; gap:10px;">
            <button type="button" onclick="closeAppModal()" style="padding:8px 18px; border:1px solid #d1d5db; background:#fff; border-radius:7px; font-size:13px; font-weight:600; color:#374151; cursor:pointer;">Cancel</button>
            <button type="button" id="appModalSave" style="padding:8px 20px; background:#1e293b; color:#fff; border:none; border-radius:7px; font-size:13px; font-weight:600; cursor:pointer;">Save Application</button>
        </div>
    </div>
</div>

{{-- 2. UPTI Modal (Add/Edit UPTI) --}}
<div id="uptiModal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.45); backdrop-filter:blur(3px); align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:16px; width:100%; max-width:440px; margin:16px; box-shadow:0 20px 60px rgba(0,0,0,0.2); overflow:hidden;">
        <div style="background:linear-gradient(135deg,#3b82f6,#2563eb); padding:20px 24px; display:flex; align-items:center; justify-content:space-between;">
            <h5 id="uptiModalTitle" style="margin:0; color:#fff; font-size:16px; font-weight:700;">Add UPTI</h5>
            <button type="button" onclick="closeUptiModal()" style="background:rgba(255,255,255,0.15); border:none; color:#fff; width:32px; height:32px; border-radius:6px; cursor:pointer; font-size:18px; display:flex; align-items:center; justify-content:center;">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div style="padding:24px; display:flex; flex-direction:column; gap:16px;">
            <input type="hidden" id="um-id">
            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px;">UPTI Name <span style="color:#dc2626;">*</span></label>
                <input type="text" id="um-name" placeholder="e.g. BSS, ESS" style="width:100%; padding:10px 12px; border:1.5px solid #d1d5db; border-radius:8px; font-size:13.5px; outline:none;" required>
            </div>
        </div>
        <div style="padding:16px 24px; background:#f8fafc; border-top:1px solid #e5e7eb; display:flex; align-items:center; justify-content:flex-end; gap:10px;">
            <button type="button" onclick="closeUptiModal()" style="padding:8px 18px; border:1px solid #d1d5db; background:#fff; border-radius:7px; font-size:13px; font-weight:600; color:#374151; cursor:pointer;">Cancel</button>
            <button type="button" id="uptiModalSave" style="padding:8px 20px; background:#2563eb; color:#fff; border:none; border-radius:7px; font-size:13px; font-weight:600; cursor:pointer;">Save UPTI</button>
        </div>
    </div>
</div>

{{-- Add Period / Delete Period Modals --}}
<div id="addPeriodModal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.45); backdrop-filter:blur(3px); align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:16px; width:100%; max-width:440px; margin:16px; box-shadow:0 20px 60px rgba(0,0,0,0.2); overflow:hidden;">
        <div style="background:linear-gradient(135deg,#059669,#047857); padding:20px 24px; display:flex; align-items:center; justify-content:space-between;">
            <div style="display:flex; align-items:center; gap:12px;">
                <div style="width:36px; height:36px; background:rgba(255,255,255,0.2); border-radius:8px; display:flex; align-items:center; justify-content:center;">
                    <i class="bi bi-calendar-plus-fill" style="color:#fff; font-size:16px;"></i>
                </div>
                <div>
                    <h5 style="margin:0; color:#fff; font-size:15px; font-weight:700;">Add Period</h5>
                    <p style="margin:0; color:rgba(255,255,255,0.75); font-size:12px;">Set year, quarter, and application to open.</p>
                </div>
            </div>
            <button id="addPeriodClose" type="button" style="background:rgba(255,255,255,0.15); border:none; color:#fff; width:32px; height:32px; border-radius:6px; cursor:pointer; font-size:18px; display:flex; align-items:center; justify-content:center;">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div style="padding:24px; display:flex; flex-direction:column; gap:16px;">
            <div>
                <label style="display:block; font-size:12.5px; font-weight:600; color:#374151; margin-bottom:6px;">Year <span style="color:#dc2626;">*</span></label>
                <input type="number" id="ap-year" placeholder="e.g. 2026" style="width:100%; padding:9px 12px; border:1.5px solid #d1d5db; border-radius:8px; font-size:13.5px; background:#fff; outline:none;" required>
            </div>
            <div>
                <label style="display:block; font-size:12.5px; font-weight:600; color:#374151; margin-bottom:6px;">Quarter <span style="color:#dc2626;">*</span></label>
                <div style="display:grid; grid-template-columns:1fr 1fr 1fr 1fr; gap:8px;">
                    @foreach(['q1'=>'Q1','q2'=>'Q2','q3'=>'Q3','q4'=>'Q4'] as $val=>$lbl)
                    <label style="display:flex; align-items:center; justify-content:center; gap:6px; padding:8px; border:1.5px solid #d1d5db; border-radius:8px; cursor:pointer; font-size:13px; font-weight:600; transition:all 0.15s; user-select:none;" id="ap-q-label-{{$val}}">
                        <input type="radio" name="ap-quarter" value="{{$val}}" id="ap-q-{{$val}}" style="display:none;" {{ $val==='q1' ? 'checked' : '' }}>
                        {{$lbl}}
                    </label>
                    @endforeach
                </div>
            </div>
            <div>
                <label style="display:block; font-size:12.5px; font-weight:600; color:#374151; margin-bottom:6px;">Application <span style="color:#dc2626;">*</span></label>
                <input type="text" id="ap-application" placeholder="e.g. SAP S/4HANA" style="width:100%; padding:9px 12px; border:1.5px solid #d1d5db; border-radius:8px; font-size:13.5px; background:#fff; outline:none;" required>
            </div>
        </div>
        <div style="padding:16px 24px; background:#f8fafc; border-top:1px solid #e5e7eb; display:flex; align-items:center; justify-content:flex-end; gap:10px;">
            <button id="addPeriodCancel" type="button" style="padding:8px 18px; border:1px solid #d1d5db; background:#fff; border-radius:7px; font-size:13px; font-weight:600; color:#374151; cursor:pointer;">Cancel</button>
            <button id="addPeriodConfirm" type="button" style="padding:8px 20px; background:linear-gradient(135deg,#059669,#047857); color:#fff; border:none; border-radius:7px; font-size:13px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:8px;">
                <i class="bi bi-arrow-right-circle-fill"></i> Open Period
            </button>
        </div>
    </div>
</div>

<div id="deletePeriodModal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.45); backdrop-filter:blur(3px); align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:16px; width:100%; max-width:440px; margin:16px; box-shadow:0 20px 60px rgba(0,0,0,0.2); overflow:hidden;">
        <div style="background:linear-gradient(135deg,#dc2626,#b91c1c); padding:20px 24px; display:flex; align-items:center; justify-content:space-between;">
            <div style="display:flex; align-items:center; gap:12px;">
                <div style="width:36px; height:36px; background:rgba(255,255,255,0.2); border-radius:8px; display:flex; align-items:center; justify-content:center;">
                    <i class="bi bi-trash-fill" style="color:#fff; font-size:16px;"></i>
                </div>
                <div>
                    <h5 style="margin:0; color:#fff; font-size:15px; font-weight:700;">Delete Period</h5>
                    <p style="margin:0; color:rgba(255,255,255,0.75); font-size:12px;">Permanently delete all controls.</p>
                </div>
            </div>
            <button id="deletePeriodClose" type="button" style="background:rgba(255,255,255,0.15); border:none; color:#fff; width:32px; height:32px; border-radius:6px; cursor:pointer; font-size:18px; display:flex; align-items:center; justify-content:center;">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div style="padding:24px; display:flex; flex-direction:column; gap:16px;">
            <div>
                <label style="display:block; font-size:12.5px; font-weight:600; color:#374151; margin-bottom:6px;">Application <span style="color:#dc2626;">*</span></label>
                <select id="dp-application" style="width:100%; padding:9px 12px; border:1.5px solid #d1d5db; border-radius:8px; font-size:13.5px; background:#fff; outline:none; cursor:pointer;">
                    <option value="">— Select Application —</option>
                    @foreach($applications as $app)
                    <option value="{{ $app->id }}">{{ $app->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block; font-size:12.5px; font-weight:600; color:#374151; margin-bottom:6px;">Year <span style="color:#dc2626;">*</span></label>
                <select id="dp-year" style="width:100%; padding:9px 12px; border:1.5px solid #d1d5db; border-radius:8px; font-size:13.5px; background:#fff; outline:none; cursor:pointer;">
                    <option value="2026">2026</option>
                    <option value="2025">2025</option>
                    <option value="2024">2024</option>
                    <option value="2023">2023</option>
                </select>
            </div>
            <div>
                <label style="display:block; font-size:12.5px; font-weight:600; color:#374151; margin-bottom:6px;">Quarter <span style="color:#dc2626;">*</span></label>
                <div style="display:grid; grid-template-columns:1fr 1fr 1fr 1fr; gap:8px;">
                    @foreach(['q1'=>'Q1','q2'=>'Q2','q3'=>'Q3','q4'=>'Q4'] as $val=>$lbl)
                    <label style="display:flex; align-items:center; justify-content:center; gap:6px; padding:8px; border:1.5px solid #d1d5db; border-radius:8px; cursor:pointer; font-size:13px; font-weight:600; transition:all 0.15s; user-select:none;" id="dp-q-label-{{$val}}">
                        <input type="radio" name="dp-quarter" value="{{$val}}" id="dp-q-{{$val}}" style="display:none;" {{ $val==='q1' ? 'checked' : '' }}>
                        {{$lbl}}
                    </label>
                    @endforeach
                </div>
            </div>
            <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:8px; padding:10px 14px; font-size:12.5px; color:#991b1b; margin-top:4px;">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                <strong>Warning:</strong> Deleting a period will permanently remove all controls and evidence uploaded for it.
            </div>
        </div>
        <div style="padding:16px 24px; background:#f8fafc; border-top:1px solid #e5e7eb; display:flex; align-items:center; justify-content:flex-end; gap:10px;">
            <button id="deletePeriodCancel" type="button" style="padding:8px 18px; border:1px solid #d1d5db; background:#fff; border-radius:7px; font-size:13px; font-weight:600; color:#374151; cursor:pointer;">Cancel</button>
            <button id="deletePeriodConfirm" type="button" style="padding:8px 20px; background:linear-gradient(135deg,#dc2626,#b91c1c); color:#fff; border:none; border-radius:7px; font-size:13px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:8px;">
                <i class="bi bi-trash-fill"></i> Delete Period
            </button>
        </div>
    </div>
</div>

@endif
@endsection

@push('scripts')
<script>
var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// ---- App Modal Logic ----
var appModal = document.getElementById('appModal');
var amId = document.getElementById('am-id');
var amName = document.getElementById('am-name');
function openAppModal(id = '', name = '', upti = '') {
    if(!appModal) return;
    document.getElementById('appModalTitle').textContent = id ? 'Edit Application' : 'Add Application';
    amId.value = id;
    amName.value = name;
    document.getElementById('am-upti').value = upti;
    appModal.style.display = 'flex';
    amName.focus();
}
function closeAppModal() { if(appModal) appModal.style.display = 'none'; }

document.getElementById('appModalSave')?.addEventListener('click', function() {
    var id = amId.value;
    var name = amName.value.trim();
    var upti_id = document.getElementById('am-upti').value;
    if(!name) { alert('Name is required.'); return; }
    
    var url = id ? `{{ url('applications') }}/${id}` : `{{ route('applications.store') }}`;
    var method = id ? 'PUT' : 'POST';

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ name: name, upti_id: upti_id })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) { location.reload(); }
        else { alert('Error: ' + (data.message || 'Validation failed.')); }
    })
    .catch(err => alert('Network error.'));
});

// ---- UPTI Modal Logic ----
var uptiModal = document.getElementById('uptiModal');
var umId = document.getElementById('um-id');
var umName = document.getElementById('um-name');

function closeAllUptiMenus() {
    document.querySelectorAll('.upti-action-menu').forEach(function(menu) {
        menu.style.display = 'none';
    });
}

function toggleUptiActionMenu(event, id) {
    event.stopPropagation();
    var menu = document.getElementById('upti-menu-' + id);
    var isOpen = menu.style.display === 'block';
    closeAllUptiMenus();
    menu.style.display = isOpen ? 'none' : 'block';
}

document.addEventListener('click', function() {
    closeAllUptiMenus();
});

function openUptiModal(id = '', name = '') {
    if(!uptiModal) return;
    document.getElementById('uptiModalTitle').textContent = id ? 'Edit UPTI' : 'Add UPTI';
    umId.value = id;
    umName.value = name;
    uptiModal.style.display = 'flex';
    umName.focus();
}
function closeUptiModal() { if(uptiModal) uptiModal.style.display = 'none'; }

document.getElementById('uptiModalSave')?.addEventListener('click', function() {
    var id = umId.value;
    var name = umName.value.trim();
    if(!name) { alert('Name is required.'); return; }
    
    var url = id ? `{{ url('uptis') }}/${id}` : `{{ route('uptis.store') }}`;
    var method = id ? 'PUT' : 'POST';

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ name: name })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            // Because we want to show UPTI tab on reload
            sessionStorage.setItem('activeAppMgtTab', '#uptis-tab');
            location.reload(); 
        }
        else { alert('Error: ' + (data.message || 'Validation failed.')); }
    })
    .catch(err => alert('Network error.'));
});

function deleteUpti(id, name) {
    if(confirm('Are you sure you want to delete UPTI "' + name + '"?')) {
        fetch(`{{ url('uptis') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                sessionStorage.setItem('activeAppMgtTab', '#uptis-tab');
                location.reload();
            } else { alert('Error: ' + data.message); }
        })
        .catch(err => alert('Network error.'));
    }
}

    // App Activation logic
    document.querySelectorAll('.btn-delete-app').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var appId = this.getAttribute('data-id');
            var appName = this.getAttribute('data-name');
            if (confirm('Are you sure you want to deactivate "' + appName + '"?')) {
                fetch('{{ url("applications") }}/' + appId, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                }).then(res => res.json()).then(data => {
                    if(data.success) { location.reload(); }
                });
            }
        });
    });

    document.querySelectorAll('.btn-activate-app').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var appName = this.getAttribute('data-name');
            fetch('{{ route("applications.store") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ name: appName })
            }).then(res => res.json()).then(data => {
                if(data.success) { location.reload(); }
            });
        });
    });

    document.querySelectorAll('.btn-force-delete-app').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var appId = this.getAttribute('data-id');
            var appName = this.getAttribute('data-name');
            if (confirm('Are you sure you want to PERMANENTLY delete "' + appName + '"? This will remove all associated controls and evidences!')) {
                fetch('{{ url("applications") }}/' + appId + '/force', {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                }).then(res => res.json()).then(data => {
                    if(data.success) { location.reload(); }
                    else { alert('Error: ' + (data.message || 'Failed to delete.')); }
                }).catch(err => alert('Network error.'));
            }
        });
    });

// Period Modals from original code
var apQuarterRadios = document.querySelectorAll('input[name="ap-quarter"]');
var dpQuarterRadios = document.querySelectorAll('input[name="dp-quarter"]');

function openAddPeriodModal() { document.getElementById('addPeriodModal').style.display='flex'; }
function closeAddPeriodModal() { document.getElementById('addPeriodModal').style.display='none'; }
document.getElementById('btn-add-period')?.addEventListener('click', openAddPeriodModal);
document.getElementById('addPeriodClose')?.addEventListener('click', closeAddPeriodModal);
document.getElementById('addPeriodCancel')?.addEventListener('click', closeAddPeriodModal);

function openDeletePeriodModal() { document.getElementById('deletePeriodModal').style.display='flex'; }
function closeDeletePeriodModal() { document.getElementById('deletePeriodModal').style.display='none'; }
document.getElementById('btn-delete-period')?.addEventListener('click', openDeletePeriodModal);
document.getElementById('deletePeriodClose')?.addEventListener('click', closeDeletePeriodModal);
document.getElementById('deletePeriodCancel')?.addEventListener('click', closeDeletePeriodModal);

document.getElementById('addPeriodConfirm')?.addEventListener('click', function() {
    var year = document.getElementById('ap-year').value;
    var appName = document.getElementById('ap-application').value;
    var quarter = 'q1';
    apQuarterRadios.forEach(function(r) { if(r.checked) quarter = r.value; });
    if(!year || !appName) return;
    
    fetch('{{ route("applications.store") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ name: appName })
    }).then(res => res.json()).then(data => {
        if(data.success) {
            sessionStorage.setItem('dashboard_filter', JSON.stringify({ appId: data.application.id.toString(), year: year, quarter: quarter }));
            window.location.href = '{{ route("dashboard") }}';
        }
    });
});

document.getElementById('deletePeriodConfirm')?.addEventListener('click', function() {
    var year = document.getElementById('dp-year').value;
    var appId = document.getElementById('dp-application').value;
    var quarter = 'q1';
    dpQuarterRadios.forEach(function(r) { if(r.checked) quarter = r.value; });
    if(!appId) return;
    
    fetch('{{ route("controls.destroyPeriod") }}', {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ application_id: appId, year: year, quarter: quarter })
    }).then(res => res.json()).then(data => {
        if(data.success) { location.reload(); }
    });
});

</script>
@endpush
