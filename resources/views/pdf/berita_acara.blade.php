<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <title>
        Official Report - {{ $control->it_control_id }}
    </title>

    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9.5pt;
            line-height: 1.4;
            color: #1e293b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table {
            margin-bottom: 4px;
        }

        .header-table td {
            padding: 6px 14px;
            vertical-align: middle;
            border: none;
        }

        .header-bottom-rule {
            border-bottom: 2px solid #3457a6;
            margin-bottom: 14px;
        }

        .header-logo {
            width: 20%;
            text-align: left;
        }

        .header-title {
            width: 36%;
            text-align: center;
            border-left: 1px solid #cbd5e1;
            border-right: 1px solid #cbd5e1;
        }

        .header-title h1 {
            margin: 0;
            font-size: 17pt;
            font-weight: 800;
            font-style: normal;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #1e3a6e;
            font-family: 'Helvetica', 'Arial', sans-serif;
        }

        .header-meta {
            width: 44%;
        }

        .header-meta table td {
            border: none;
            padding: 1.5px 4px;
            font-size: 8.5pt;
            line-height: 1.3;
        }

        .header-meta .company-name {
            font-weight: bold;
            font-size: 9.5pt;
            line-height: 1.35;
            padding-bottom: 4px !important;
        }

        .section-title {
            background-color: #3457a6;
            color: #fff;
            font-weight: bold;
            padding: 5px 10px;
            font-size: 9.5pt;
        }

        .info-table {
            margin-bottom: 14px;
        }

        .info-table td {
            border: 1px solid #94a3b8;
            padding: 6px 10px;
            vertical-align: top;
        }

        .info-table .label {
            background-color: #eaf1fb;
            font-weight: bold;
            width: 22%;
        }

        .info-table .value {
            width: 28%;
            font-style: italic;
        }

        .evidence-table td,
        .evidence-table th {
            border: 1px solid #94a3b8;
            padding: 6px 10px;
        }

        .evidence-table th {
            background-color: #eaf1fb;
            font-weight: bold;
            text-align: left;
        }

        .evidence-table .no-col {
            width: 6%;
            text-align: center;
        }

        .checkbox-row td {
            border: 1px solid #94a3b8;
            padding: 8px 10px;
        }

        .checkbox-label {
            font-weight: bold;
            background-color: #eaf1fb;
            width: 22%;
        }

        .statement {
            font-style: italic;
            font-size: 9pt;
            margin: 10px 0 14px 0;
        }

        .signatures td {
            border: 1px solid #94a3b8;
            padding: 6px 10px;
            vertical-align: top;
        }

        .signatures .sig-header {
            background-color: #3457a6;
            color: #fff;
            font-weight: bold;
            text-align: center;
        }

        .sig-space {
            height: 60px;
            text-align: center;
            vertical-align: middle;
        }

        .checkbox-box {
            display: inline-block;
            width: 11px;
            height: 11px;
            border: 1.3px solid #1e293b;
            text-align: center;
            line-height: 10px;
            font-size: 9px;
            font-weight: bold;
            margin-right: 4px;
            vertical-align: middle;
        }

        .sig-ttd {
            text-align: center;
            font-style: italic;
            color: #64748b;
            font-size: 8.5pt;
        }

        .sig-date {
            text-align: center;
            font-size: 8pt;
            font-style: italic;
            color: #94a3b8;
            margin-top: 2px;
        }

        .footer-note {
            margin-top: 20px;
            font-size: 8pt;
            color: #64748b;
            text-align: right;
        }
    </style>
</head>

<body>

    {{-- ============================================================
         HEADER
         ============================================================ --}}
    <table class="header-table">
        <tr>
            <td class="header-logo">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" style="max-width:150px; max-height:60px;">
                @else
                    <div style="font-weight:bold; font-size:12pt;">CSA - ITGC</div>
                @endif
            </td>

            <td class="header-title">
                <h1>
                    Official Report
                </h1>
            </td>

            <td class="header-meta">
                <table>
                    <tr>
                        <td colspan="2" class="company-name">PT. Telkom Infrastruktur Indonesia</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold; width:38%;">Document No.</td>
                        <td>{{ $docNumber }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold;">Version</td>
                        <td>1.0</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold;">Classification</td>
                        <td>Internal</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="header-bottom-rule"></div>

    {{-- ============================================================
         A. DOCUMENT INFORMATION
         ============================================================ --}}
    <table class="info-table">
        <tr>
            <td colspan="4" class="section-title">A. Document Information</td>
        </tr>
        <tr>
            <td class="label">Unit</td>
            <td class="value">{{ $control->upti ?? '-' }}</td>
            <td class="label">Control Period</td>
            <td class="value">{{ $control->keterangan_frekuensi ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Control No. (IT RCM)</td>
            <td class="value">{{ $control->it_control_id ?? '-' }}</td>
            <td class="label">Document Date</td>
            <td class="value">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td class="label">Control Description</td>
            <td class="value">{{ $control->control_description ?? '-' }}</td>
            <td class="label">Review Period</td>
            <td class="value">
                {{ strtoupper($control->quarter ?? '-') }}
                {{ $control->year ?? '' }}
            </td>
        </tr>
    </table>

    {{-- ============================================================
         B. CONTROL EXECUTION
         ============================================================ --}}
    <table class="info-table">
        <tr>
            <td colspan="2" class="section-title">B. Control Execution</td>
        </tr>
        <tr>
            <td class="label" style="width:22%;">Scope</td>
            <td class="value" style="width:78%;">{{ optional($control->application)->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Execution Notes</td>
            <td class="value">{{ $executionNotes ?? '-' }}</td>
        </tr>
    </table>

    {{-- ============================================================
         C. SUPPORTING EVIDENCE
         ============================================================ --}}
    <table class="evidence-table info-table">
        <tr>
            <td colspan="2" class="section-title">C. Supporting Evidence</td>
        </tr>
        <tr>
            <th class="no-col">No.</th>
            <th>Evidence Name / Description</th>
        </tr>
        @forelse($evidences as $i => $ev)
            <tr>
                <td class="no-col">{{ $i + 1 }}</td>
                <td>{{ $ev->original_name ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td class="no-col">1</td>
                <td>-</td>
            </tr>
        @endforelse
    </table>

    {{-- ============================================================
         D. REVIEW RESULT
         ============================================================ --}}
    <table class="info-table">
        <tr>
            <td colspan="2" class="section-title">D. Review Result</td>
        </tr>
        <tr class="checkbox-row">
            <td class="checkbox-label">Review Result</td>
            <td class="value">
                <span class="checkbox-box">{{ $control->review_result === 'effective' ? 'X' : '' }}</span> <i>Effective</i> &nbsp;&nbsp;
                <span class="checkbox-box">{{ $control->review_result === 'partially_effective' ? 'X' : '' }}</span> <i>Partially Effective</i> &nbsp;&nbsp;
                <span class="checkbox-box">{{ $control->review_result === 'ineffective' ? 'X' : '' }}</span> <i>Ineffective</i>
            </td>
        </tr>
        <tr>
            <td class="checkbox-label">Reviewer Notes</td>
            <td class="value">{{ $control->reviewer_notes ?: '-' }}</td>
        </tr>
        <tr>
            <td class="checkbox-label">Approver Notes</td>
            <td class="value">{{ $control->approver_notes ?: '-' }}</td>
        </tr>
    </table>

    {{-- ============================================================
         E. STATEMENT AND SIGNATURES
         ============================================================ --}}
    <table class="info-table">
        <tr>
            <td class="section-title">E. Statement and Signatures</td>
        </tr>
    </table>

    <p class="statement">
        By signing this document, the undersigned parties declare that the IT control
        has been executed, reviewed, and approved based on the evidence attached,
        and in accordance with applicable IT policies and procedures.
    </p>

    <table class="signatures">
        <tr>
            <td class="sig-header">Prepared By:</td>
            <td class="sig-header">Reviewed By:</td>
            <td class="sig-header">Approved By:</td>
        </tr>
        <tr>
            <td class="sig-space">
                <div class="sig-ttd">( Signature )</div>
                <div class="sig-date">
                    {{ optional($control->submitted_at)->format('d/m/Y') ?? '-' }}
                </div>
            </td>
            <td class="sig-space">
                <div class="sig-ttd">( Signature )</div>
                <div class="sig-date">
                    {{ optional($control->reviewed_at)->format('d/m/Y') ?? '-' }}
                </div>
            </td>
            <td class="sig-space">
                <div class="sig-ttd">( Signature )</div>
                <div class="sig-date">
                    {{ optional($control->approved_at)->format('d/m/Y') ?? '-' }}
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <strong>Name:</strong>
                {{ $officerName !== '( Officer / Creator )' ? $officerName : '.....................' }}
            </td>
            <td>
                <strong>Name:</strong>
                {{ $reviewerName !== '( Manager / Reviewer )' ? $reviewerName : '.....................' }}
            </td>
            <td>
                <strong>Name:</strong>
                {{ $approverName !== '( Senior Manager / Approver )' ? $approverName : '.....................' }}
            </td>
        </tr>
        <tr>
            <td><strong>Position:</strong> Officer</td>
            <td><strong>Position:</strong> Manager</td>
            <td><strong>Position:</strong> Senior Manager</td>
        </tr>
    </table>

    <div class="footer-note">
        Printed on: {{ \Carbon\Carbon::now()->format('d F Y, H:i') }} (Server Time)
    </div>

</body>
</html>
