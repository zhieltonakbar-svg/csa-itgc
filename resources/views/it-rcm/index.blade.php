@extends('layouts.app')

@section('title', 'IT RCM Management — CSA - ITGC')

@push('styles')
<style>
/* ─── Page Header ─────────────────────────────────── */
.rcm-page-header { margin-bottom: 2rem; }
.rcm-page-header h1 {
    font-size: 1.6rem; font-weight: 700; color: var(--text-primary);
    margin: 0 0 6px; display: flex; align-items: center; gap: 10px;
}
.rcm-admin-badge {
    font-size: 0.52em; font-weight: 700;
    background: linear-gradient(135deg, #2563eb, #7c3aed);
    color: #fff; padding: 3px 10px; border-radius: 20px;
    letter-spacing: 0.5px; vertical-align: middle;
}
.rcm-page-header p { font-size: 14px; color: var(--text-secondary); margin: 0; }

/* ─── Category Grid ────────────────────────────────── */
.rcm-cat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 16px;
}
.rcm-cat-card {
    background: var(--card-bg, #fff);
    border: 1px solid var(--border-color, #e5e7eb);
    border-radius: 14px; padding: 22px 24px;
    text-decoration: none; color: var(--text-primary);
    display: flex; flex-direction: column; gap: 14px;
    transition: border-color 0.2s, box-shadow 0.2s, transform 0.15s;
}
.rcm-cat-card:hover {
    border-color: #2563eb;
    box-shadow: 0 6px 24px rgba(37,99,235,0.13);
    transform: translateY(-3px); color: var(--text-primary);
}
.rcm-cat-icon {
    width: 48px; height: 48px; border-radius: 12px;
    background: linear-gradient(135deg, #eff6ff, #dbeafe);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; color: #2563eb;
}
.rcm-cat-name { font-size: 14px; font-weight: 700; line-height: 1.35; flex: 1; }
.rcm-cat-footer {
    display: flex; align-items: center; justify-content: space-between;
    font-size: 12px; color: #2563eb; font-weight: 600;
    border-top: 1px solid #e5e7eb; padding-top: 12px; margin-top: 4px;
}
.rcm-cat-footer .rcm-badge-edit {
    background: #eff6ff; color: #2563eb;
    padding: 3px 9px; border-radius: 6px; font-size: 11.5px;
    display: flex; align-items: center; gap: 4px;
}
</style>
@endpush

@section('content')

<div class="rcm-page-header">
    <h1>
        <i class="bi bi-shield-lock-fill" style="color:#2563eb;"></i>
        IT RCM Management
        <span class="rcm-admin-badge">ADMIN</span>
    </h1>
    <p>Kelola semua IT Risk Control Matrix — pilih kategori untuk mulai edit, tambah, atau hapus data kontrol.</p>
</div>

<div class="rcm-cat-grid">
    @forelse($categories as $cat)
        <a href="{{ route('rcm.controls', ['category' => $cat->id]) }}" class="rcm-cat-card">
            <div class="rcm-cat-icon">
                <i class="bi {{ $cat->icon ?? 'bi-shield' }}"></i>
            </div>
            <div class="rcm-cat-name">{{ $cat->name }}</div>
            <div class="rcm-cat-footer">
                <span class="rcm-badge-edit">
                    <i class="bi bi-pencil-square"></i> Edit &amp; Manage
                </span>
                <i class="bi bi-arrow-right-circle-fill" style="font-size:18px;"></i>
            </div>
        </a>
    @empty
        <div style="grid-column:1/-1; text-align:center; padding:60px; color:var(--text-secondary);">
            <i class="bi bi-inbox" style="font-size:40px; display:block; margin-bottom:12px; opacity:0.4;"></i>
            Tidak ada kategori tersedia.
        </div>
    @endforelse
</div>

@endsection
