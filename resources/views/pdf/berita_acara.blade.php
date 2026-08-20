<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Berita Acara - {{ $control->it_control_id }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 16pt;
            text-transform: uppercase;
        }
        .content {
            margin-bottom: 30px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            vertical-align: top;
        }
        .table th {
            text-align: left;
            background-color: #f2f2f2;
            width: 30%;
        }
        .signatures {
            margin-top: 50px;
            width: 100%;
        }
        .signatures td {
            text-align: center;
            width: 33%;
            padding-top: 80px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Berita Acara</h1>
        <p>Control Self Assessment - IT General Control</p>
    </div>

    <div class="content">
        <p>Pada hari ini, telah disetujui secara final dokumen Control Self Assessment dengan rincian sebagai berikut:</p>
        
        <table class="table">
            <tr>
                <th>Control ID</th>
                <td>{{ $control->it_control_id }}</td>
            </tr>
            <tr>
                <th>Application</th>
                <td>{{ optional($control->application)->name ?? '-' }}</td>
            </tr>
            <tr>
                <th>IT Category</th>
                <td>{{ optional($control->itCategory)->name ?? '-' }}</td>
            </tr>
            <tr>
                <th>UPTI</th>
                <td>{{ $control->upti ?? '-' }}</td>
            </tr>
            <tr>
                <th>Period</th>
                <td>{{ strtoupper($control->quarter) }} - {{ $control->year }}</td>
            </tr>
            <tr>
                <th>Control Description</th>
                <td>{{ $control->control_description }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>Completed (Final Approved)</td>
            </tr>
            @if($control->reviewer_notes)
            <tr>
                <th>Manager Notes</th>
                <td>{{ $control->reviewer_notes }}</td>
            </tr>
            @endif
            @if($control->approver_notes)
            <tr>
                <th>Senior Manager Notes</th>
                <td>{{ $control->approver_notes }}</td>
            </tr>
            @endif
        </table>
        
        <p>Demikian Berita Acara ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
        <p style="font-size: 10pt; color: #555;"><em>Dicetak pada: {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y, H:i') }} WIB</em></p>
    </div>

    <table class="signatures">
        <tr>
            <td>
                <div>Disiapkan Oleh,</div>
                <div style="margin-top: 25px; height: 55px; font-size: 9pt; color: #555;">
                    <div style="font-style: italic; color: #999; margin-bottom: 3px;">(ttd)</div>
                    <div>{{ $control->submitted_at ? $control->submitted_at->locale('id')->translatedFormat('d M Y, H:i') : '-' }}</div>
                </div>
                <div style="font-weight: bold; text-decoration: underline;">{{ $officerName !== '( Officer / Creator )' ? $officerName : '..........................................' }}</div>
                <div style="font-weight: bold; font-size: 10pt; margin-top: 2px;">Officer</div>
            </td>
            <td>
                <div>Diperiksa Oleh,</div>
                <div style="margin-top: 25px; height: 55px; font-size: 9pt; color: #555;">
                    <div style="font-style: italic; color: #999; margin-bottom: 3px;">(ttd)</div>
                    <div>{{ $control->reviewed_at ? $control->reviewed_at->locale('id')->translatedFormat('d M Y, H:i') : '-' }}</div>
                </div>
                <div style="font-weight: bold; text-decoration: underline;">{{ $reviewerName !== '( Manager / Reviewer )' ? $reviewerName : '..........................................' }}</div>
                <div style="font-weight: bold; font-size: 10pt; margin-top: 2px;">Manager</div>
            </td>
            <td>
                <div>Disetujui Oleh,</div>
                <div style="margin-top: 25px; height: 55px; font-size: 9pt; color: #555;">
                    <div style="font-style: italic; color: #999; margin-bottom: 3px;">(ttd)</div>
                    <div>{{ $control->approved_at ? $control->approved_at->locale('id')->translatedFormat('d M Y, H:i') : '-' }}</div>
                </div>
                <div style="font-weight: bold; text-decoration: underline;">{{ $approverName !== '( Senior Manager / Approver )' ? $approverName : '..........................................' }}</div>
                <div style="font-weight: bold; font-size: 10pt; margin-top: 2px;">Senior Manager</div>
            </td>
        </tr>
    </table>
</body>
</html>
