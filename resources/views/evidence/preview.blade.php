<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Read-Only Preview - {{ $evidence->original_name }} | CSA-ITGC</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary: #059669;
            --primary-dark: #047857;
            --bg-main: #f8fafc;
            --text-dark: #0f172a;
            --text-muted: #64748b;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background: var(--bg-main);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--text-dark);
        }

        .brand-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .brand-text h1 { font-size: 15px; font-weight: 700; color: var(--text-dark); }
        .brand-text p { font-size: 11px; color: var(--text-muted); }

        .header-actions { display: flex; align-items: center; gap: 12px; }

        .readonly-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #ecfdf5;
            color: var(--primary-dark);
            border: 1px solid #a7f3d0;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .close-btn {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .close-btn:hover { background: #e2e8f0; color: #0f172a; }

        .main-container {
            flex: 1;
            padding: 24px;
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .meta-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        }

        .meta-left { display: flex; align-items: center; gap: 16px; }
        
        .file-icon-box {
            width: 52px;
            height: 52px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
        }

        .file-icon-pdf { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
        .file-icon-doc { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
        .file-icon-xls { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }

        .meta-info h2 { font-size: 16px; font-weight: 700; margin-bottom: 4px; }
        .meta-info p { font-size: 13px; color: var(--text-muted); }

        .meta-details { display: flex; gap: 16px; font-size: 12.5px; color: var(--text-muted); }
        .meta-details span { display: flex; align-items: center; gap: 5px; }

        .viewer-box {
            flex: 1;
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            min-height: 600px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        }

        iframe { width: 100%; height: 100%; min-height: 600px; border: none; }

        .doc-fallback {
            padding: 60px 20px;
            text-align: center;
            margin: auto;
            max-width: 540px;
        }

        .doc-fallback i { font-size: 48px; margin-bottom: 16px; }
        .doc-fallback h3 { font-size: 18px; font-weight: 700; margin-bottom: 8px; }
        .doc-fallback p { font-size: 13.5px; color: var(--text-muted); margin-bottom: 24px; line-height: 1.6; }

        .btn-download {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(5, 150, 105, 0.25);
        }

        .btn-download:hover { opacity: 0.95; }
    </style>
</head>
<body>

    <header class="header">
        <a href="#" class="brand">
            <div class="brand-icon"><i class="bi bi-shield-check"></i></div>
            <div class="brand-text">
                <h1>CSA - ITGC</h1>
                <p>Read-Only Document Viewer</p>
            </div>
        </a>
        <div class="header-actions">
            <span class="readonly-badge">
                <i class="bi bi-lock-fill"></i> Read-Only Mode
            </span>
            <button class="close-btn" onclick="window.close()"><i class="bi bi-x-lg"></i> Close Window</button>
        </div>
    </header>

    <div class="main-container">
        @php
            $ext = strtolower(pathinfo($evidence->original_name, PATHINFO_EXTENSION));
            $isPdf = $ext === 'pdf';
            $isWord = in_array($ext, ['doc', 'docx']);
            $isExcel = in_array($ext, ['xls', 'xlsx']);
            
            $iconClass = $isPdf ? 'file-icon-pdf' : ($isExcel ? 'file-icon-xls' : 'file-icon-doc');
            $biIcon = $isPdf ? 'bi-file-earmark-pdf-fill' : ($isExcel ? 'bi-file-earmark-excel-fill' : 'bi-file-earmark-word-fill');
        @endphp

        <div class="meta-card">
            <div class="meta-left">
                <div class="file-icon-box {{ $iconClass }}">
                    <i class="bi {{ $biIcon }}"></i>
                </div>
                <div class="meta-info">
                    <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                        <h2>{{ $evidence->original_name }}</h2>
                        @if(!empty($evidence->file_type))
                            <span style="background:#e0e7ff; color:#3730a3; border:1px solid #c7d2fe; font-size:12px; font-weight:600; padding:2px 8px; border-radius:4px;">
                                <i class="bi bi-tag-fill me-1" style="font-size:11px;"></i>{{ $evidence->file_type }}
                            </span>
                        @endif
                    </div>
                    <p>Attached to Control: <strong>{{ $control->it_control_id ?? 'N/A' }}</strong> ({{ $control->application->name ?? 'Application' }} — {{ $control->itCategory->name ?? 'Category' }})</p>
                </div>
            </div>
            <div class="meta-details">
                <span><i class="bi bi-hdd-fill"></i> {{ number_format($evidence->size / 1024, 1) }} KB</span>
                <span><i class="bi bi-calendar-event"></i> {{ $evidence->created_at->format('M d, Y H:i') }}</span>
                <a href="{{ route('evidence.show', $evidence->id) }}" download class="btn-download" style="padding:6px 14px; font-size:12.5px;">
                    <i class="bi bi-download"></i> Download
                </a>
            </div>
        </div>

        <div class="viewer-box">
            <iframe src="{{ route('evidence.preview-pdf', $evidence->id) }}"></iframe>
        </div>
    </div>

</body>
</html>
