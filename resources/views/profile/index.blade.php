@extends('layouts.app')

@section('title', 'Profile — CSA - ITGC')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">CSA - ITGC</a>
    <span class="separator">/</span>
    <span class="current">Profile</span>
@endsection

@section('content')

@php $user = auth()->user(); @endphp

{{-- Page Header --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="page-title">Profile</h1>
        <p class="page-subtitle mb-0">Your Account Information</p>
    </div>
</div>

{{-- Profile Card --}}
<div class="row g-3">
    <div class="col-lg-4">
        <div class="content-card">
            <div class="card-body text-center" style="padding: 40px 24px;">
                {{-- Avatar --}}
                <div class="mx-auto mb-3"
                     style="width: 80px; height: 80px; background: var(--primary-gradient); border-radius: 50%;
                            display: flex; align-items: center; justify-content: center;
                            font-size: 28px; font-weight: 700; color: white;
                            box-shadow: 0 4px 14px rgba(25,135,84,0.25);">
                    {{ $user ? strtoupper(substr($user->name, 0, 2)) : 'US' }}
                </div>
                <h5 class="mb-1" style="font-weight: 700;">{{ $user->name ?? 'User' }}</h5>
                <p class="mb-2" style="font-size: 13px; color: var(--text-secondary);">{{ $user->email ?? '' }}</p>
                <span class="badge"
                      style="background: var(--primary-light); color: var(--primary); font-size: 11px; padding: 4px 12px; border-radius: 20px;">
                    Active Account
                </span>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="content-card">
            <div class="card-header">
                <h5><i class="bi bi-person me-2 text-success"></i>Account Details</h5>
            </div>
            <div class="card-body">

                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <label style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted);">Full Name</label>
                        <div class="mt-1" style="font-size: 15px; font-weight: 500; color: var(--text-primary);">{{ $user->name ?? '—' }}</div>
                    </div>
                    <div class="col-sm-6">
                        <label style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted);">Email Address</label>
                        <div class="mt-1" style="font-size: 15px; font-weight: 500; color: var(--text-primary);">{{ $user->email ?? '—' }}</div>
                    </div>
                    <div class="col-sm-6">
                        <label style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted);">Member Since</label>
                        <div class="mt-1" style="font-size: 15px; font-weight: 500; color: var(--text-primary);">
                            {{ $user && $user->created_at ? $user->created_at->format('d M Y') : '—' }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted);">Account Status</label>
                        <div class="mt-1">
                            <span class="status-badge badge-approved">Active</span>
                        </div>
                    </div>
                </div>

                {{-- Coming Soon Notice --}}
                <div class="p-3" style="background: var(--primary-light); border-radius: var(--radius-sm); border-left: 3px solid var(--primary);">
                    <p class="mb-0" style="font-size: 13px; color: var(--primary);">
                        <i class="bi bi-info-circle me-2"></i>
                        Profile editing and password change functionality will be available in a future development phase.
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
