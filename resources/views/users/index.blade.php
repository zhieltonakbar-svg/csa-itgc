@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="welcome-hero" style="padding: 24px 32px; background: linear-gradient(135deg, #0f172a, #1e293b); border-radius: 16px; margin-bottom: 24px; position: relative; overflow: hidden;">
    <div style="position: relative; z-index: 2; display: flex; align-items: center; justify-content: space-between; flex-wrap:wrap; gap:16px;">
        <div>
            <h1 style="color: #fff; font-size: 24px; font-weight: 700; margin: 0 0 8px 0; letter-spacing: -0.5px;">User Management</h1>
            <p style="color: #94a3b8; font-size: 14px; margin: 0; max-width: 600px; line-height: 1.5;">
                Manage user access, roles, and UPTI assignments.
            </p>
        </div>
    </div>
</div>

<div class="col-12">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 style="font-weight:700; color:#1e293b; margin:0;">Users List</h5>
        <button type="button" class="btn btn-primary" style="border-radius: 8px; font-weight: 600; padding: 8px 20px; background: #198754; border: none;" onclick="openCreateUserModal()">
            <i class="bi bi-person-plus-fill me-1"></i> Create User
        </button>
    </div>
    <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 14px;">
                    <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <tr>
                            <th style="padding: 16px 24px; color: #475569; font-weight: 600; width: 50px;">#</th>
                            <th style="padding: 16px 24px; color: #475569; font-weight: 600;">Name</th>
                            <th style="padding: 16px 24px; color: #475569; font-weight: 600;">Email / Username</th>
                            <th style="padding: 16px 24px; color: #475569; font-weight: 600; width: 90px;">Auth</th>
                            <th style="padding: 16px 24px; color: #475569; font-weight: 600; width: 120px;">Role</th>
                            <th style="padding: 16px 24px; color: #475569; font-weight: 600; width: 180px;">Assigned UPTI</th>
                            <th style="padding: 16px 24px; color: #475569; font-weight: 600; width: 120px; text-align: center;">Status</th>
                            <th style="padding: 16px 24px; color: #475569; font-weight: 600; width: 120px; text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                        <tr>
                            <td style="padding: 16px 24px; color: #64748b;">{{ $index + 1 }}</td>
                            <td style="padding: 16px 24px; font-weight: 500; color: #1e293b;">
                                {{ $user->name }}
                            </td>
                            <td style="padding: 16px 24px; color: #64748b;">
                                {{ $user->email ?: $user->username }}
                            </td>
                            <td style="padding: 16px 24px;">
                                @if($user->isLdap())
                                    <span style="background:#ede9fe; color:#6d28d9; padding:3px 10px; border-radius:9999px; font-size:11px; font-weight:700;">LDAP</span>
                                @else
                                    <span style="background:#e0f2fe; color:#075985; padding:3px 10px; border-radius:9999px; font-size:11px; font-weight:700;">Local</span>
                                @endif
                            </td>
                            <td style="padding: 16px 24px;">
                                <span class="badge bg-primary" style="font-weight: 600; text-transform: capitalize;">{{ $user->role }}</span>
                            </td>
                            <td style="padding: 16px 24px;">
                                @if($user->upti)
                                    <span class="badge bg-secondary rounded-pill" style="font-weight: 600;">{{ $user->upti->name }}</span>
                                @else
                                    <span class="text-muted" style="font-size:12px; font-style:italic;">Unassigned</span>
                                @endif
                            </td>
                            <td style="padding: 16px 24px; text-align: center;">
                                @if($user->is_active)
                                    <span style="background: #dcfce7; color: #166534; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 600;">Active</span>
                                @else
                                    <span style="background: #fee2e2; color: #991b1b; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 600;">Inactive</span>
                                @endif
                            </td>
                            <td style="padding: 16px 24px; text-align: center;">
                                @if($user->isAdmin())
                                    <span class="text-muted" style="font-size: 12px; font-style: italic;">Admin</span>
                                @else
                                    <button type="button" class="btn btn-sm btn-outline-success" style="border-radius: 8px; font-weight: 600; font-size: 12px; padding: 4px 10px;" 
                                            onclick="openUserModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->upti_id }}', {{ $user->is_active ? 'true' : 'false' }})">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    
                                    @if($user->is_active)
                                        <form action="{{ route('users.deactivate', $user->id) }}" method="POST" style="display:inline-block; margin-left:5px;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-danger" style="border-radius: 8px; font-weight: 600; font-size: 12px; padding: 4px 10px;" onclick="return confirm('Deactivate this user?')">
                                                <i class="bi bi-pause-circle"></i> Deactivate
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('users.activate', $user->id) }}" method="POST" style="display:inline-block; margin-left:5px;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" style="border-radius: 8px; font-weight: 600; font-size: 12px; padding: 4px 10px;" onclick="return confirm('Activate this user?')">
                                                <i class="bi bi-check-circle"></i> Activate
                                            </button>
                                        </form>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block; margin-left:5px;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" style="border-radius: 8px; font-weight: 600; font-size: 12px; padding: 4px 10px;" onclick="return confirm('Permanently delete this user?')">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="padding: 48px; text-align: center; color: #64748b;">
                                <i class="bi bi-people" style="font-size: 32px; color: #cbd5e1; margin-bottom: 12px; display: block;"></i>
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Create User Modal --}}
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" id="createUserForm" method="POST" action="{{ route('users.store') }}" style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
            @csrf
            <div class="modal-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 20px 24px;">
                <h5 class="modal-title" id="createUserModalLabel" style="font-weight: 700; color: #1e293b; font-size: 18px;">Create New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 24px;">

                <div class="mb-4">
                    <label class="form-label" style="font-weight: 600; color: #475569; font-size: 14px;">Account Type <span class="text-danger">*</span></label>
                    <div style="display:flex; gap:10px;">
                        <label style="flex:1; display:flex; align-items:center; gap:8px; border:1.5px solid #cbd5e1; border-radius:8px; padding:10px 14px; cursor:pointer;">
                            <input type="radio" name="auth_type" value="local" id="authTypeLocal" checked>
                            <span>Local Account</span>
                        </label>
                        <label style="flex:1; display:flex; align-items:center; gap:8px; border:1.5px solid #cbd5e1; border-radius:8px; padding:10px 14px; cursor:pointer;">
                            <input type="radio" name="auth_type" value="ldap" id="authTypeLdap">
                            <span>LDAP Account</span>
                        </label>
                    </div>
                    <div id="ldapHint" style="display:none; font-size:12px; color:#64748b; margin-top:6px;">
                        <i class="bi bi-info-circle"></i>
                        No password needed here — this user logs in with their company LDAP password.
                    </div>
                </div>

                <div class="mb-4">
                    <label for="createName" class="form-label" style="font-weight: 600; color: #475569; font-size: 14px;">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="createName" name="name" required style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px 14px;">
                </div>

                <div class="mb-4" id="ldapUsernameGroup" style="display:none;">
                    <label for="createUsername" class="form-label" style="font-weight: 600; color: #475569; font-size: 14px;">LDAP Username <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="createUsername" name="username" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px 14px;" placeholder="e.g. jdoe">
                </div>

                <div class="mb-4" id="emailGroup">
                    <label for="createEmail" class="form-label" style="font-weight: 600; color: #475569; font-size: 14px;">
                        Email <span class="text-danger" id="emailRequiredMark">*</span>
                    </label>
                    <input type="email" class="form-control" id="createEmail" name="email" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px 14px;">
                </div>

                <div class="mb-4" id="passwordGroup">
                    <label for="createPassword" class="form-label" style="font-weight: 600; color: #475569; font-size: 14px;">Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="createPassword" name="password" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px 14px;">
                </div>

                <div class="mb-4" id="passwordConfirmGroup">
                    <label for="createPasswordConfirmation" class="form-label" style="font-weight: 600; color: #475569; font-size: 14px;">Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="createPasswordConfirmation" name="password_confirmation" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px 14px;">
                </div>

                <div class="mb-4">
                    <label for="createRole" class="form-label" style="font-weight: 600; color: #475569; font-size: 14px;">Role <span class="text-danger">*</span></label>
                    <select class="form-select" id="createRole" name="role" required style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px 14px; cursor: pointer;">
                        <option value="" disabled selected>-- Select Role --</option>
                        <option value="creator">Officer (Creator)</option>
                        <option value="reviewer">Manager (Reviewer)</option>
                        <option value="approver">Senior Manager (Approver)</option>
                    </select>
                </div>

            </div>
            <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 16px 24px;">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 8px; font-weight: 600; border: 1px solid #e2e8f0;">Cancel</button>
                <button type="submit" class="btn btn-primary" style="border-radius: 8px; font-weight: 600; padding: 8px 24px; background: #198754; border: none;">Create User</button>
            </div>
        </form>
    </div>
</div>

{{-- User Modal --}}
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" id="userForm" method="POST" action="" style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
            @csrf
            @method('PUT')
            <div class="modal-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 20px 24px;">
                <h5 class="modal-title" id="userModalLabel" style="font-weight: 700; color: #1e293b; font-size: 18px;">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 24px;">
                <div class="mb-4">
                    <label class="form-label" style="font-weight: 600; color: #475569; font-size: 14px;">User Name</label>
                    <input type="text" class="form-control" id="userName" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px 14px; background-color: #f1f5f9;" readonly>
                </div>
                
                <div class="mb-4">
                    <label for="upti_id" class="form-label" style="font-weight: 600; color: #475569; font-size: 14px;">Assign UPTI</label>
                    <select class="form-select" id="upti_id" name="upti_id" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px 14px; cursor: pointer;">
                        <option value="">-- No UPTI Assigned --</option>
                        @foreach($uptis as $upti)
                            <option value="{{ $upti->id }}">{{ $upti->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="is_active" class="form-label" style="font-weight: 600; color: #475569; font-size: 14px;">Account Status</label>
                    <select class="form-select" id="is_active" name="is_active" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px 14px; cursor: pointer;">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 16px 24px;">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 8px; font-weight: 600; border: 1px solid #e2e8f0;">Cancel</button>
                <button type="submit" class="btn btn-primary" style="border-radius: 8px; font-weight: 600; padding: 8px 24px; background: #198754; border: none;">Save Changes</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const createUserModal = new bootstrap.Modal(document.getElementById('createUserModal'));

    const authTypeLocal = document.getElementById('authTypeLocal');
    const authTypeLdap = document.getElementById('authTypeLdap');
    const ldapUsernameGroup = document.getElementById('ldapUsernameGroup');
    const ldapHint = document.getElementById('ldapHint');
    const emailGroup = document.getElementById('emailGroup');
    const emailInput = document.getElementById('createEmail');
    const emailRequiredMark = document.getElementById('emailRequiredMark');
    const passwordGroup = document.getElementById('passwordGroup');
    const passwordConfirmGroup = document.getElementById('passwordConfirmGroup');
    const passwordInput = document.getElementById('createPassword');
    const passwordConfirmInput = document.getElementById('createPasswordConfirmation');
    const usernameInput = document.getElementById('createUsername');

    function applyAuthTypeUI() {
        const isLdap = authTypeLdap.checked;

        ldapUsernameGroup.style.display = isLdap ? 'block' : 'none';
        ldapHint.style.display = isLdap ? 'block' : 'none';
        passwordGroup.style.display = isLdap ? 'none' : 'block';
        passwordConfirmGroup.style.display = isLdap ? 'none' : 'block';
        emailRequiredMark.style.display = isLdap ? 'none' : 'inline';

        usernameInput.required = isLdap;
        passwordInput.required = !isLdap;
        passwordConfirmInput.required = !isLdap;
        emailInput.required = !isLdap;
    }

    authTypeLocal.addEventListener('change', applyAuthTypeUI);
    authTypeLdap.addEventListener('change', applyAuthTypeUI);

    function openCreateUserModal() {
        document.getElementById('createUserForm').reset();
        authTypeLocal.checked = true;
        applyAuthTypeUI();
        createUserModal.show();
    }

    const userModal = new bootstrap.Modal(document.getElementById('userModal'));
    const userForm = document.getElementById('userForm');
    const userNameInput = document.getElementById('userName');
    const uptiSelect = document.getElementById('upti_id');
    const statusSelect = document.getElementById('is_active');

    function openUserModal(id, name, uptiId, isActive) {
        userForm.action = `/users/${id}`;
        userNameInput.value = name;
        uptiSelect.value = uptiId || '';
        statusSelect.value = isActive ? '1' : '0';
        userModal.show();
    }
</script>
@endpush
@endsection
