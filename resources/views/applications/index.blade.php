@extends('layouts.app')

@section('title', 'App Management')

@section('content')
<div class="welcome-hero" style="padding: 24px 32px; background: linear-gradient(135deg, #0f172a, #1e293b); border-radius: 16px; margin-bottom: 14px; position: relative; overflow: hidden;">
    <div style="position: relative; z-index: 2;">
        <h1 style="color: #fff; font-size: 24px; font-weight: 700; margin: 0 0 8px 0; letter-spacing: -0.5px;">App Management</h1>
        <p style="color: #94a3b8; font-size: 14px; margin: 0; max-width: 600px; line-height: 1.5;">
            Manage the list of applications, UPTI, and their mappings.
        </p>
    </div>
</div>

    <div class="col-12">
        <div class="d-flex justify-content-end align-items-center gap-2" style="margin-bottom:4px;">
            <button type="button" id="btn-add-period"
                    style="display:inline-flex; align-items:center; justify-content:center; gap:7px; background:#198754; color:#fff; border:none; padding:7px 14px; border-radius:8px; font-size:12.5px; font-weight:600; cursor:pointer; white-space:nowrap;">
                <i class="bi bi-calendar-plus-fill"></i> Add Period
            </button>
            <button type="button" id="btn-delete-period"
                    style="display:inline-flex; align-items:center; justify-content:center; gap:7px; background:linear-gradient(135deg,#dc2626,#b91c1c); color:#fff; border:none; padding:7px 14px; border-radius:8px; font-size:12.5px; font-weight:600; cursor:pointer; white-space:nowrap;">
                <i class="bi bi-trash-fill"></i> Delete Period
            </button>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 style="font-weight:700; color:#1e293b; margin:0;">Applications List</h5>
        </div>
        <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 14px;">
                        <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                            <tr>
                                <th style="padding: 16px 24px; color: #475569; font-weight: 600; width: 50px;">#</th>
                                <th style="padding: 16px 24px; color: #475569; font-weight: 600;">Application Name</th>
                                <th style="padding: 16px 24px; color: #475569; font-weight: 600; width: 160px; text-align: center;">Total IT RCM</th>
                                <th style="padding: 16px 24px; color: #475569; font-weight: 600; width: 180px;">UPTI Mapping</th>
                                <th style="padding: 16px 24px; color: #475569; font-weight: 600; width: 220px;">Active Quarters</th>
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
                                        {{ $categoryCountsByPeriod[$app->id]->first()['count'] ?? 0 }}
                                    </span>
                                </td>
                                <td style="padding: 16px 24px;">
                                    @if($app->upti)
                                        <span class="badge bg-secondary rounded-pill" style="font-weight: 600;">{{ $app->upti->name }}</span>
                                    @else
                                        <span class="text-muted" style="font-size:12px; font-style:italic;">Unmapped</span>
                                    @endif
                                </td>
                                <td style="padding: 16px 24px;">
                                    @php $quarters = $activeQuarters[$app->id] ?? collect(); @endphp
                                    @if($quarters->isEmpty())
                                        <span style="font-size:12px; color:#94a3b8; font-style:italic;">No periods</span>
                                    @else
                                        <div style="display:flex; flex-wrap:wrap; gap:5px;">
                                            @foreach($quarters->sort()->values() as $qLabel)
                                            @php
                                                [$yr, $q] = explode('-', $qLabel, 2);
                                                $colors = ['Q1'=>['#dbeafe','#1d4ed8'],'Q2'=>['#dcfce7','#15803d'],'Q3'=>['#fef3c7','#b45309'],'Q4'=>['#f3e8ff','#7e22ce']];
                                                [$bg,$fg] = $colors[$q] ?? ['#f1f5f9','#475569'];
                                            @endphp
                                            <span style="display:inline-flex; align-items:center; gap:3px; background:{{ $bg }}; color:{{ $fg }}; padding:3px 8px; border-radius:6px; font-size:11.5px; font-weight:700; white-space:nowrap;">
                                                {{ $yr }} <span style="opacity:0.6;">·</span> {{ $q }}
                                            </span>
                                            @endforeach
                                        </div>
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
                                    <button type="button" class="btn btn-sm btn-outline-primary" style="border-radius: 8px; font-weight: 600; margin-right:5px;" onclick="openAppModal({{ $app->id }}, '{{ addslashes($app->name) }}', '{{ $app->upti_id }}', {{ $app->is_active ? 1 : 0 }})">
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
                                <td colspan="7" style="padding: 48px; text-align: center; color: #64748b;">
                                    <i class="bi bi-inbox" style="font-size: 32px; color: #cbd5e1; margin-bottom: 12px; display: block;"></i>
                                    No applications found.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div style="display:flex; justify-content:center; padding:18px 0;">
                    <button type="button" onclick="openAppModal()" title="Add Application"
                            style="width:48px; height:48px; border-radius:50%; background:#198754; color:#fff; border:none; display:flex; align-items:center; justify-content:center; font-size:20px; cursor:pointer;">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12" style="margin-top:12px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 style="font-weight:700; color:#1e293b; margin:0;">UPTI List</h5>
            <button class="btn btn-sm btn-outline-dark" style="border-radius:8px; font-weight:600;" onclick="openUptiModal()">
                <i class="bi bi-plus-lg"></i> Add UPTI
            </button>
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
        <div style="background:#198754; padding:20px 24px; display:flex; align-items:center; justify-content:space-between;">
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
            <div id="am-upti-wrapper">
                <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px;">UPTI Mapping</label>
                <select id="am-upti" style="width:100%; padding:10px 12px; border:1.5px solid #d1d5db; border-radius:8px; font-size:13.5px; outline:none;">
                    <option value="">-- None --</option>
                    @foreach($uptis as $u)
                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px;">Status</label>
                <select id="am-status" style="width:100%; padding:10px 12px; border:1.5px solid #d1d5db; border-radius:8px; font-size:13.5px; outline:none; cursor:pointer;">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
        </div>
        <div style="padding:16px 24px; background:#f8fafc; border-top:1px solid #e5e7eb; display:flex; align-items:center; justify-content:flex-end; gap:10px;">
            <button type="button" onclick="closeAppModal()" style="padding:8px 18px; border:1px solid #d1d5db; background:#fff; border-radius:7px; font-size:13px; font-weight:600; color:#374151; cursor:pointer;">Cancel</button>
            <button type="button" id="appModalSave" style="padding:8px 20px; background:#198754; color:#fff; border:none; border-radius:7px; font-size:13px; font-weight:600; cursor:pointer;">Save Application</button>
        </div>
    </div>
</div>

{{-- 2. UPTI Modal (Add/Edit UPTI) --}}
<div id="uptiModal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.45); backdrop-filter:blur(3px); align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:16px; width:100%; max-width:440px; margin:16px; box-shadow:0 20px 60px rgba(0,0,0,0.2); overflow:hidden;">
        <div style="background:#1e293b; padding:20px 24px; display:flex; align-items:center; justify-content:space-between;">
            <h5 id="uptiModalTitle" style="margin:0; color:#fff; font-size:16px; font-weight:700;">Add UPTI</h5>
            <button type="button" onclick="closeUptiModal()" style="background:rgba(255,255,255,0.15); border:none; color:#fff; width:32px; height:32px; border-radius:6px; cursor:pointer; font-size:18px; display:flex; align-items:center; justify-content:center;">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div style="padding:24px; display:flex; flex-direction:column; gap:16px;">
            <input type="hidden" id="um-id">
            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px;">UPTI Name <span style="color:#dc2626;">*</span></label>
                <input type="text" id="um-name" placeholder="e.g. BSS, ESS" style="width:100%; padding:10px 12px; border:1.5px solid #d1d5db; border-radius:8px; font-size:13.5px; outline:none; transition: all 0.2s;" onfocus="this.style.borderColor='#10b981'; this.style.boxShadow='0 0 0 3px rgba(16,185,129,0.2)';" onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none';" required>
            </div>
        </div>
        <div style="padding:16px 24px; background:#f8fafc; border-top:1px solid #e5e7eb; display:flex; align-items:center; justify-content:flex-end; gap:10px;">
            <button type="button" onclick="closeUptiModal()" style="padding:8px 18px; border:1px solid #d1d5db; background:#fff; border-radius:7px; font-size:13px; font-weight:600; color:#374151; cursor:pointer;">Cancel</button>
            <button type="button" id="uptiModalSave" style="padding:8px 20px; background:#1e293b; color:#fff; border:none; border-radius:7px; font-size:13px; font-weight:600; cursor:pointer;">Save UPTI</button>
        </div>
    </div>
</div>

{{-- Add Period / Delete Period Modals --}}
<div id="addPeriodModal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.45); backdrop-filter:blur(3px); align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:16px; width:100%; max-width:440px; margin:16px; box-shadow:0 20px 60px rgba(0,0,0,0.2); overflow:hidden;">
        <div style="background:#198754; padding:20px 24px; display:flex; align-items:center; justify-content:space-between;">
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
                <label style="display:block; font-size:12.5px; font-weight:600; color:#374151; margin-bottom:6px;">Application <span style="color:#dc2626;">*</span></label>
                <select id="ap-application" style="width:100%; padding:9px 12px; border:1.5px solid #d1d5db; border-radius:8px; font-size:13.5px; background:#fff; outline:none; cursor:pointer;">
                    <option value="">— Select Application —</option>
                    @foreach($applications as $app)
                        @if($app->is_active)
                            <option value="{{ $app->id }}">{{ $app->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block; font-size:12.5px; font-weight:600; color:#374151; margin-bottom:6px;">Year <span style="color:#dc2626;">*</span></label>
                <input type="number" id="ap-year" min="{{ now()->year }}" placeholder="e.g. 2026" style="width:100%; padding:9px 12px; border:1.5px solid #d1d5db; border-radius:8px; font-size:13.5px; background:#fff; outline:none;" required>
                <div id="ap-year-error" style="display:none; font-size:11.5px; color:#dc2626; margin-top:6px;">
                    <i class="bi bi-exclamation-circle"></i>
                    Year cannot be earlier than {{ now()->year }}.
                </div>
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
                <div id="ap-quarter-taken-msg" style="display:none; font-size:11.5px; color:#dc2626; margin-top:6px;">
                    <i class="bi bi-exclamation-circle"></i>
                    That quarter already exists for this application/year — pick another one.
                </div>
            </div>
        </div>
        <div style="padding:16px 24px; background:#f8fafc; border-top:1px solid #e5e7eb; display:flex; align-items:center; justify-content:flex-end; gap:10px;">
            <button id="addPeriodCancel" type="button" style="padding:8px 18px; border:1px solid #d1d5db; background:#fff; border-radius:7px; font-size:13px; font-weight:600; color:#374151; cursor:pointer;">Cancel</button>
            <button id="addPeriodConfirm" type="button" style="padding:8px 20px; background:#198754; color:#fff; border:none; border-radius:7px; font-size:13px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:8px;">
                <i class="bi bi-arrow-right-circle-fill"></i> Open Period
            </button>
        </div>
    </div>
</div>

<div id="deletePeriodModal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.45); backdrop-filter:blur(3px); align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:16px; width:100%; max-width:480px; margin:16px; box-shadow:0 20px 60px rgba(0,0,0,0.2); overflow:hidden;">
        <div style="background:linear-gradient(135deg,#dc2626,#b91c1c); padding:20px 24px; display:flex; align-items:center; justify-content:space-between;">
            <div style="display:flex; align-items:center; gap:12px;">
                <div style="width:36px; height:36px; background:rgba(255,255,255,0.2); border-radius:8px; display:flex; align-items:center; justify-content:center;">
                    <i class="bi bi-trash-fill" style="color:#fff; font-size:16px;"></i>
                </div>
                <div>
                    <h5 style="margin:0; color:#fff; font-size:15px; font-weight:700;">Delete Period</h5>
                    <p style="margin:0; color:rgba(255,255,255,0.75); font-size:12px;">Permanently delete all controls for selected period.</p>
                </div>
            </div>
            <button id="deletePeriodClose" type="button" style="background:rgba(255,255,255,0.15); border:none; color:#fff; width:32px; height:32px; border-radius:6px; cursor:pointer; font-size:18px; display:flex; align-items:center; justify-content:center;">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div style="padding:24px; display:flex; flex-direction:column; gap:16px;">

            {{-- Application Single-Select --}}
            <div>
                <label style="display:block; font-size:12.5px; font-weight:600; color:#374151; margin-bottom:6px;">
                    Application <span style="color:#dc2626;">*</span>
                </label>
                <select id="dp-app-select" style="width:100%; padding:9px 12px; border:1.5px solid #d1d5db; border-radius:8px; font-size:13.5px; background:#fff; outline:none; cursor:pointer;">
                    <option value="">— Pilih aplikasi —</option>
                    @foreach($applications as $app)
                    <option value="{{ $app->id }}">{{ $app->name }}</option>
                    @endforeach
                </select>
                <div id="dp-app-error" style="display:none; font-size:11.5px; color:#dc2626; margin-top:6px;">
                    <i class="bi bi-exclamation-circle"></i> Pilih aplikasi terlebih dahulu.
                </div>
            </div>

            {{-- Year (dinamis dari DB) --}}
            <div>
                <label style="display:block; font-size:12.5px; font-weight:600; color:#374151; margin-bottom:6px;">Year <span style="color:#dc2626;">*</span></label>
                <select id="dp-year" style="width:100%; padding:9px 12px; border:1.5px solid #d1d5db; border-radius:8px; font-size:13.5px; background:#fff; outline:none; cursor:pointer;" disabled>
                    <option value="">— Pilih aplikasi dulu —</option>
                </select>
            </div>

            {{-- Quarter (dinamis, hanya yang ada di DB) --}}
            <div>
                <label style="display:block; font-size:12.5px; font-weight:600; color:#374151; margin-bottom:6px;">Quarter <span style="color:#dc2626;">*</span></label>
                <div id="dp-quarter-grid" style="display:grid; grid-template-columns:1fr 1fr 1fr 1fr; gap:8px;">
                    @foreach(['q1'=>'Q1','q2'=>'Q2','q3'=>'Q3','q4'=>'Q4'] as $val=>$lbl)
                    <label style="display:flex; align-items:center; justify-content:center; gap:6px; padding:8px; border:1.5px solid #e5e7eb; border-radius:8px; font-size:13px; font-weight:600; transition:all 0.15s; user-select:none; opacity:0.4; cursor:not-allowed; color:#9ca3af; background:#f9fafb;" id="dp-q-label-{{$val}}">
                        <input type="radio" name="dp-quarter" value="{{$val}}" id="dp-q-{{$val}}" style="display:none;" disabled>
                        {{$lbl}}
                    </label>
                    @endforeach
                </div>
                <div id="dp-quarter-empty-msg" style="display:none; font-size:11.5px; color:#dc2626; margin-top:6px;">
                    <i class="bi bi-exclamation-circle"></i> Tidak ada quarter yang tersedia untuk kombinasi ini.
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
function openAppModal(id = '', name = '', upti = '', isActive = 1) {
    if(!appModal) return;
    document.getElementById('appModalTitle').textContent = id ? 'Edit Application' : 'Add Application';
    amId.value = id;
    amName.value = name;
    document.getElementById('am-upti').value = upti;
    document.getElementById('am-status').value = (isActive === true || isActive === 1 || isActive === '1') ? '1' : '0';
    appModal.style.display = 'flex';
    amName.focus();
}
function closeAppModal() { if(appModal) appModal.style.display = 'none'; }

document.getElementById('appModalSave')?.addEventListener('click', function() {
    var id = amId.value;
    var name = amName.value.trim();
    var upti_id = document.getElementById('am-upti').value;
    var is_active = document.getElementById('am-status').value;
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
        body: JSON.stringify({ name: name, upti_id: upti_id, is_active: is_active })
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

function openAddPeriodModal() {
    document.getElementById('addPeriodModal').style.display='flex';
    document.getElementById('ap-year').value = '';
    document.getElementById('ap-application').value = '';
    document.getElementById('ap-year-error').style.display = 'none';
    document.getElementById('ap-year').style.borderColor = '#d1d5db';
    takenPeriods = [];
    applyTakenPeriods();
    updateAddPeriodButtonState();
}
function closeAddPeriodModal() { document.getElementById('addPeriodModal').style.display='none'; }
document.getElementById('btn-add-period')?.addEventListener('click', openAddPeriodModal);
document.getElementById('addPeriodClose')?.addEventListener('click', closeAddPeriodModal);
document.getElementById('addPeriodCancel')?.addEventListener('click', closeAddPeriodModal);

var currentYear = {{ now()->year }};
var takenPeriods = [];

function isYearValid() {
    var year = parseInt(document.getElementById('ap-year').value, 10);
    return !isNaN(year) && year >= currentYear;
}

function validateYearRealtime() {
    var yearInput = document.getElementById('ap-year');
    var errorMsg = document.getElementById('ap-year-error');
    var hasValue = yearInput.value !== '';

    if (hasValue && !isYearValid()) {
        yearInput.style.borderColor = '#dc2626';
        yearInput.style.background = '#fef2f2';
        errorMsg.style.display = 'block';
    } else {
        yearInput.style.borderColor = '#d1d5db';
        yearInput.style.background = '#fff';
        errorMsg.style.display = 'none';
    }

    updateAddPeriodButtonState();
}

function updateAddPeriodButtonState() {
    var btn = document.getElementById('addPeriodConfirm');
    if (!btn) return;

    var appId = document.getElementById('ap-application').value;
    var checkedInput = document.querySelector('input[name="ap-quarter"]:checked');
    var yearOk = document.getElementById('ap-year').value !== '' && isYearValid();

    var ready = !!appId && yearOk && !!checkedInput && !checkedInput.disabled;

    btn.disabled = !ready;
    btn.style.opacity = ready ? '1' : '0.5';
    btn.style.cursor = ready ? 'pointer' : 'not-allowed';
}

function applyTakenPeriods() {
    var year = parseInt(document.getElementById('ap-year').value, 10);
    var takenQuartersForYear = takenPeriods
        .filter(function(p) { return p.year === year; })
        .map(function(p) { return p.quarter; });

    var anyTaken = false;

    ['q1','q2','q3','q4'].forEach(function(val) {
        var input = document.getElementById('ap-q-' + val);
        var label = document.getElementById('ap-q-label-' + val);
        if (!input || !label) return;

        var isTaken = takenQuartersForYear.indexOf(val) !== -1;

        input.disabled = isTaken;

        if (isTaken) {
            anyTaken = true;
            label.style.opacity = '0.45';
            label.style.cursor = 'not-allowed';
            label.style.textDecoration = 'line-through';
            if (input.checked) {
                input.checked = false;
            }
        } else {
            label.style.opacity = '1';
            label.style.cursor = 'pointer';
            label.style.textDecoration = 'none';
        }
    });

    document.getElementById('ap-quarter-taken-msg').style.display = anyTaken ? 'block' : 'none';

    // If nothing is checked anymore (its quarter just got disabled),
    // auto-select the first available one so the form still has a value.
    if (!document.querySelector('input[name="ap-quarter"]:checked')) {
        for (var i = 0; i < 4; i++) {
            var v = 'q' + (i + 1);
            var inp = document.getElementById('ap-q-' + v);
            if (inp && !inp.disabled) {
                inp.checked = true;
                break;
            }
        }
    }

    updateAddPeriodQuarterStyles();
    updateAddPeriodButtonState();
}

function updateAddPeriodQuarterStyles() {
    ['q1','q2','q3','q4'].forEach(function(v) {
        var l = document.getElementById('ap-q-label-' + v);
        var i = document.getElementById('ap-q-' + v);
        if (!l || !i) return;
        if (i.checked) {
            l.style.borderColor = '#198754';
            l.style.background = '#ecfdf5';
            l.style.color = '#146c43';
        } else if (!i.disabled) {
            l.style.borderColor = '#d1d5db';
            l.style.background = '#fff';
            l.style.color = '#111827';
        }
    });
}

function refreshTakenPeriods() {
    var appId = document.getElementById('ap-application').value;

    if (!appId) {
        takenPeriods = [];
        applyTakenPeriods();
        return;
    }

    fetch('{{ route("applications.existingPeriods") }}?application_id=' + encodeURIComponent(appId), {
        headers: { 'Accept': 'application/json' }
    })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            takenPeriods = (data && data.periods) ? data.periods : [];
            applyTakenPeriods();
        })
        .catch(function() {
            takenPeriods = [];
            applyTakenPeriods();
        });
}

document.getElementById('ap-application')?.addEventListener('change', refreshTakenPeriods);
document.getElementById('ap-year')?.addEventListener('input', function() {
    validateYearRealtime();
    applyTakenPeriods();
});
['q1','q2','q3','q4'].forEach(function(val) {
    document.getElementById('ap-q-' + val)?.addEventListener('change', function() {
        updateAddPeriodQuarterStyles();
        updateAddPeriodButtonState();
    });
});

function openDeletePeriodModal() {
    // reset state
    document.getElementById('dp-app-select').value = '';
    var yearSel = document.getElementById('dp-year');
    yearSel.innerHTML = '<option value="">— Pilih aplikasi dulu —</option>';
    yearSel.disabled = true;
    dpResetQuarters();
    document.getElementById('dp-app-error').style.display = 'none';
    document.getElementById('deletePeriodModal').style.display = 'flex';
}
function closeDeletePeriodModal() { document.getElementById('deletePeriodModal').style.display = 'none'; }
document.getElementById('btn-delete-period')?.addEventListener('click', openDeletePeriodModal);
document.getElementById('deletePeriodClose')?.addEventListener('click', closeDeletePeriodModal);
document.getElementById('deletePeriodCancel')?.addEventListener('click', closeDeletePeriodModal);

// Visual feedback for the Q1/Q2/Q3/Q4 pill selectors (add period only)
function wireQuarterPills(prefix) {
    function updateAll() {
        ['q1','q2','q3','q4'].forEach(function(v) {
            var l = document.getElementById(prefix + '-q-label-' + v);
            var i = document.getElementById(prefix + '-q-' + v);
            if (!l || !i) return;
            if (i.checked) {
                l.style.borderColor = '#198754';
                l.style.background = '#ecfdf5';
                l.style.color = '#146c43';
            } else {
                l.style.borderColor = '#d1d5db';
                l.style.background = '#fff';
                l.style.color = '#111827';
            }
        });
    }

    ['q1','q2','q3','q4'].forEach(function(val) {
        var input = document.getElementById(prefix + '-q-' + val);
        if (input) {
            input.addEventListener('change', updateAll);
        }
    });

    updateAll();
}
wireQuarterPills('ap');

// ---- Delete Period: Dynamic year & quarter from DB ----

var dpPeriodsData = []; // [{year, quarter}, ...]

function dpGetSelectedAppIds() {
    var val = document.getElementById('dp-app-select').value;
    return val ? [val] : [];
}

function dpResetQuarters() {
    ['q1','q2','q3','q4'].forEach(function(v) {
        var lbl = document.getElementById('dp-q-label-' + v);
        var inp = document.getElementById('dp-q-' + v);
        if (!lbl || !inp) return;
        inp.checked = false;
        inp.disabled = true;
        lbl.style.opacity = '0.4';
        lbl.style.cursor = 'not-allowed';
        lbl.style.borderColor = '#e5e7eb';
        lbl.style.background = '#f9fafb';
        lbl.style.color = '#9ca3af';
    });
    document.getElementById('dp-quarter-empty-msg').style.display = 'none';
}

function dpApplyQuarters(year) {
    dpResetQuarters();
    if (!year) return;

    var availableQuarters = dpPeriodsData
        .filter(function(p) { return p.year === parseInt(year); })
        .map(function(p) { return p.quarter; });

    var hasAny = false;
    ['q1','q2','q3','q4'].forEach(function(v) {
        var lbl = document.getElementById('dp-q-label-' + v);
        var inp = document.getElementById('dp-q-' + v);
        if (!lbl || !inp) return;

        if (availableQuarters.indexOf(v) !== -1) {
            inp.disabled = false;
            lbl.style.opacity = '1';
            lbl.style.cursor = 'pointer';
            lbl.style.borderColor = '#d1d5db';
            lbl.style.background = '#fff';
            lbl.style.color = '#111827';
            hasAny = true;
        }
    });

    document.getElementById('dp-quarter-empty-msg').style.display = hasAny ? 'none' : 'block';

    // Wire click events
    ['q1','q2','q3','q4'].forEach(function(v) {
        var lbl = document.getElementById('dp-q-label-' + v);
        var inp = document.getElementById('dp-q-' + v);
        if (!lbl || !inp || inp.disabled) return;
        lbl.onclick = function() {
            if (inp.disabled) return;
            inp.checked = true;
            dpStyleQuarters();
        };
    });

    // Auto-select first available
    for (var i = 0; i < ['q1','q2','q3','q4'].length; i++) {
        var qv = ['q1','q2','q3','q4'][i];
        var inp = document.getElementById('dp-q-' + qv);
        if (inp && !inp.disabled) {
            inp.checked = true;
            break;
        }
    }
    dpStyleQuarters();
}

function dpStyleQuarters() {
    ['q1','q2','q3','q4'].forEach(function(v) {
        var lbl = document.getElementById('dp-q-label-' + v);
        var inp = document.getElementById('dp-q-' + v);
        if (!lbl || !inp || inp.disabled) return;
        if (inp.checked) {
            lbl.style.borderColor = '#dc2626';
            lbl.style.background = '#fef2f2';
            lbl.style.color = '#991b1b';
        } else {
            lbl.style.borderColor = '#d1d5db';
            lbl.style.background = '#fff';
            lbl.style.color = '#111827';
        }
    });
}

function dpFetchAndUpdate() {
    var ids = dpGetSelectedAppIds();
    var yearSel = document.getElementById('dp-year');

    if (ids.length === 0) {
        yearSel.innerHTML = '<option value="">— Pilih aplikasi dulu —</option>';
        yearSel.disabled = true;
        dpPeriodsData = [];
        dpResetQuarters();
        return;
    }

    var qs = ids.map(function(id) { return 'application_ids[]=' + encodeURIComponent(id); }).join('&');
    fetch('{{ route("applications.existingPeriodsForDelete") }}?' + qs, {
        headers: { 'Accept': 'application/json' }
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        dpPeriodsData = (data && data.periods) ? data.periods : [];
        var years = (data && data.years) ? data.years : [];

        yearSel.innerHTML = '';
        if (years.length === 0) {
            yearSel.innerHTML = '<option value="">— Tidak ada data —</option>';
            yearSel.disabled = true;
            dpResetQuarters();
            return;
        }

        yearSel.disabled = false;
        years.forEach(function(y) {
            var opt = document.createElement('option');
            opt.value = y;
            opt.textContent = y;
            yearSel.appendChild(opt);
        });

        dpApplyQuarters(yearSel.value);
    })
    .catch(function() {
        yearSel.innerHTML = '<option value="">— Error —</option>';
        yearSel.disabled = true;
        dpResetQuarters();
    });
}

// Wire app select
document.getElementById('dp-app-select')?.addEventListener('change', function() {
    document.getElementById('dp-app-error').style.display = 'none';
    dpFetchAndUpdate();
});

// Wire year change
document.getElementById('dp-year')?.addEventListener('change', function() {
    dpApplyQuarters(this.value);
});

document.getElementById('deletePeriodConfirm')?.addEventListener('click', function() {
    var appIds = dpGetSelectedAppIds();
    if (appIds.length === 0) {
        document.getElementById('dp-app-error').style.display = 'block';
        return;
    }

    var year = document.getElementById('dp-year').value;
    if (!year) { alert('Pilih tahun terlebih dahulu.'); return; }

    var checkedQ = document.querySelector('input[name="dp-quarter"]:checked:not(:disabled)');
    if (!checkedQ) { alert('Pilih quarter yang tersedia.'); return; }

    var appName = document.getElementById('dp-app-select').options[document.getElementById('dp-app-select').selectedIndex].text;
    if (!confirm('Yakin ingin menghapus period ' + checkedQ.value.toUpperCase() + ' ' + year + ' untuk aplikasi ' + appName + '? Tindakan ini tidak dapat dibatalkan.')) return;

    fetch('{{ route("controls.destroyPeriod") }}', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ application_ids: appIds, year: parseInt(year), quarter: checkedQ.value })
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success) { location.reload(); }
        else { alert('Gagal: ' + (data.message || 'Terjadi kesalahan.')); }
    })
    .catch(function() { alert('Network error.'); });
});

document.getElementById('addPeriodConfirm')?.addEventListener('click', function() {
    var year = document.getElementById('ap-year').value;
    var appId = document.getElementById('ap-application').value;
    var checkedInput = document.querySelector('input[name="ap-quarter"]:checked');

    if (!appId) {
        alert('Please select an Application.');
        return;
    }

    if (!year || !isYearValid()) {
        alert('Year cannot be earlier than ' + currentYear + '.');
        return;
    }

    if (!checkedInput || checkedInput.disabled) {
        alert('Please select an available Quarter for this Application/Year.');
        return;
    }

    var params = new URLSearchParams({
        year: year,
        quarter: checkedInput.value,
        application_id: appId
    });

    fetch('{{ route("applications.storePeriod") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            application_id: appId,
            year: year,
            quarter: checkedInput.value
        })
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success) {
            window.location.href = '{{ route("dashboard") }}?' + params.toString();
        } else {
            alert('Error: ' + (data.message || 'Failed to open period.'));
        }
    })
    .catch(function() {
        alert('Network error.');
    });
});

</script>
@endpush
