<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <title>
        Berita Acara - {{ $control->it_control_id }}
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

        .header-table td {
            border: 1px solid #94a3b8;
            padding: 6px 10px;
            vertical-align: middle;
        }

        .header-logo {
            width: 22%;
            text-align: center;
        }

        .header-title {
            width: 46%;
            text-align: center;
        }

        .header-title h1 {
            margin: 0;
            font-size: 13pt;
            font-style: italic;
            text-transform: uppercase;
        }

        .header-meta {
            width: 32%;
        }

        .header-meta table td {
            border: none;
            padding: 2px 4px;
            font-size: 8.5pt;
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
                <div style="width:40px; height:40px; margin:0 auto;">
                    <x-logo />
                </div>
                <div style="font-size:8pt; font-weight:bold; margin-top:4px;">
                    CSA - ITGC
                </div>
            </td>

            <td class="header-title">
                <h1>
                    Berita Acara<br>
                    Review Kontrol IT
                </h1>
            </td>

            <td class="header-meta">
                <table>
                    <tr>
                        <td style="font-weight:bold; width:35%;">PT. Telkom Infrastruktur Indonesia</td>
                        <td colspan="1"></td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold;">No. Dokumen</td>
                        <td>{{ $docNumber }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold;">Versi</td>
                        <td>1.0</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold;">Klasifikasi</td>
                        <td>Internal</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <br>

    {{-- ============================================================
         A. INFORMASI DOKUMEN
         ============================================================ --}}
    <table class="info-table">
        <tr>
            <td colspan="4" class="section-title">A. Informasi Dokumen</td>
        </tr>
        <tr>
            <td class="label">Unit</td>
            <td class="value">{{ $control->upti ?? '-' }}</td>
            <td class="label">Periode Kontrol</td>
            <td class="value">{{ $control->keterangan_frekuensi ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">No. Kontrol (IT RCM)</td>
            <td class="value">{{ $control->it_control_id ?? '-' }}</td>
            <td class="label">Tanggal Dokumen</td>
            <td class="value">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td class="label">Deskripsi Kontrol</td>
            <td class="value">{{ $control->control_description ?? '-' }}</td>
            <td class="label">Periode Review</td>
            <td class="value">
                {{ strtoupper($control->quarter ?? '-') }}
                {{ $control->year ?? '' }}
            </td>
        </tr>
    </table>

    {{-- ============================================================
         B. PELAKSANAAN KONTROL
         ============================================================ --}}
    <table class="info-table">
        <tr>
            <td colspan="2" class="section-title">B. Pelaksanaan Kontrol</td>
        </tr>
        <tr>
            <td class="label" style="width:22%;">Lingkup</td>
            <td class="value" style="width:78%;">{{ optional($control->application)->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Catatan Pelaksanaan Kontrol</td>
            <td class="value">{{ $control->control_description ?? '-' }}</td>
        </tr>
    </table>

    {{-- ============================================================
         C. EVIDENCE PENDUKUNG
         ============================================================ --}}
    <table class="evidence-table info-table">
        <tr>
            <td colspan="2" class="section-title">C. Evidence Pendukung</td>
        </tr>
        <tr>
            <th class="no-col">No.</th>
            <th>Nama / Deskripsi Evidence</th>
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
         D. HASIL REVIEW
         ============================================================ --}}
    <table class="info-table">
        <tr>
            <td colspan="2" class="section-title">D. Hasil Review</td>
        </tr>
        <tr class="checkbox-row">
            <td class="checkbox-label">Hasil Review</td>
            <td class="value">
                &#9745; <i>Effective</i> &nbsp;&nbsp;
                &#9744; <i>Partially Effective</i> &nbsp;&nbsp;
                &#9744; <i>Ineffective</i>
            </td>
        </tr>
        <tr>
            <td class="checkbox-label">Catatan Reviewer</td>
            <td class="value">{{ $control->reviewer_notes ?: '-' }}</td>
        </tr>
        <tr>
            <td class="checkbox-label">Catatan Approver</td>
            <td class="value">{{ $control->approver_notes ?: '-' }}</td>
        </tr>
    </table>

    {{-- ============================================================
         E. PERNYATAAN DAN TANDA TANGAN
         ============================================================ --}}
    <table class="info-table">
        <tr>
            <td class="section-title">E. Pernyataan dan Tanda Tangan</td>
        </tr>
    </table>

    <p class="statement">
        Dengan ditandatanganinya dokumen ini, para pihak menyatakan bahwa pelaksanaan
        kontrol IT telah dilaksanakan, direview, dan disetujui berdasarkan evidence
        yang dilampirkan, serta sesuai dengan kebijakan dan prosedur IT yang berlaku.
    </p>

    <table class="signatures">
        <tr>
            <td class="sig-header">Dibuat Oleh:</td>
            <td class="sig-header">Direview Oleh:</td>
            <td class="sig-header">Disetujui Oleh:</td>
        </tr>
        <tr>
            <td class="sig-space"></td>
            <td class="sig-space"></td>
            <td class="sig-space"></td>
        </tr>
        <tr>
            <td>
                <strong>Nama :</strong>
                {{ $officerName !== '( Officer / Creator )' ? $officerName : '.....................' }}
            </td>
            <td>
                <strong>Nama :</strong>
                {{ $reviewerName !== '( Manager / Reviewer )' ? $reviewerName : '.....................' }}
            </td>
            <td>
                <strong>Nama :</strong>
                {{ $approverName !== '( Senior Manager / Approver )' ? $approverName : '.....................' }}
            </td>
        </tr>
        <tr>
            <td><strong>Jabatan :</strong> Officer</td>
            <td><strong>Jabatan :</strong> Manager</td>
            <td><strong>Jabatan :</strong> Senior Manager</td>
        </tr>
    </table>

    <div class="footer-note">
        Dicetak pada: {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y, H:i') }} WIB
    </div>

</body>
</html>
