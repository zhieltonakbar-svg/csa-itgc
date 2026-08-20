@extends('layouts.app')

@section('title', 'Settings — CSA - ITGC')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<style>
    /* Circular crop area */
    .cropper-view-box,
    .cropper-face {
        border-radius: 50%;
    }
    
    .settings-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }
    .settings-header {
        margin-bottom: 2rem;
    }
    .settings-header h2 {
        font-weight: 700;
        font-size: 1.75rem;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }
    .settings-header p {
        color: var(--text-secondary);
        font-size: 0.95rem;
    }
    .settings-card {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        padding: 2rem;
        margin-bottom: 1.5rem;
    }
    .settings-card-title {
        font-size: 1.15rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .settings-card-title i {
        color: var(--primary);
    }
    .settings-info-row {
        display: flex;
        flex-direction: column;
        margin-bottom: 1.25rem;
    }
    .settings-info-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        font-weight: 700;
        color: var(--text-muted);
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }
    .settings-info-val {
        font-size: 1rem;
        color: var(--text-primary);
        font-weight: 500;
    }
    
    .danger-zone {
        border-color: #fecaca;
        background-color: #fef2f2;
    }
    .danger-zone .settings-card-title {
        color: #dc2626;
    }
    .danger-zone .settings-card-title i {
        color: #dc2626;
    }
    .danger-text {
        font-size: 0.9rem;
        color: #991b1b;
        margin-bottom: 1.5rem;
    }
</style>
@endpush

@section('content')
<div class="settings-container">
    <div class="settings-header">
        <h2>Settings</h2>
        <p>Manage your account settings and preferences.</p>
    </div>

    <div class="settings-card">
        <h3 class="settings-card-title"><i class="bi bi-person-badge"></i> Profile Information</h3>
        
        <form action="{{ route('settings.updateProfile') }}" method="POST" enctype="multipart/form-data" class="mt-4">
            @csrf
            @method('PUT')
            
            <input type="hidden" name="cropped_photo" id="cropped_photo">
            
            <div class="row align-items-center mb-4">
                <div class="col-auto">
                    <div id="profile_preview" style="width: 80px; height: 80px; border-radius: 50%; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 32px; font-weight: 700; overflow: hidden; border: 2px solid var(--border-color);">
                        @if($user->profile_photo_path)
                            <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profile Photo" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        @endif
                    </div>
                </div>
                <div class="col">
                    <label for="profile_photo" class="form-label" style="font-size: 13px; font-weight: 600;">Profile Photo</label>
                    <input type="file" class="form-control" id="profile_photo" accept="image/*" style="font-size: 13px; max-width: 300px;">
                    <div class="form-text" style="font-size: 11px;">Optional. You can adjust your photo before saving.</div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="settings-info-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required style="font-size: 14px; padding: 10px 12px; border-radius: 8px;">
                </div>
                <div class="col-md-6 mb-3">
                    <div class="settings-info-row">
                        <span class="settings-info-label">Email Address</span>
                        <span class="settings-info-val text-muted">{{ $user->email }}</span>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="settings-info-row">
                        <span class="settings-info-label">Role</span>
                        <span class="settings-info-val">
                            <span class="badge bg-light text-primary border border-primary">{{ strtoupper($user->role) }}</span>
                        </span>
                    </div>
                </div>
                @if($user->upti)
                <div class="col-md-6 mb-3">
                    <div class="settings-info-row">
                        <span class="settings-info-label">UPTI</span>
                        <span class="settings-info-val text-muted">{{ $user->upti->name }}</span>
                    </div>
                </div>
                @endif
            </div>
            
            <div class="mt-2">
                <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2 fw-semibold px-4 py-2">
                    <i class="bi bi-floppy-fill"></i> Save Profile
                </button>
            </div>
        </form>
    </div>

    <div class="settings-card danger-zone">
        <h3 class="settings-card-title"><i class="bi bi-exclamation-triangle-fill"></i> Danger Zone</h3>
        <p class="danger-text">
            Permanently delete your account and all of your personal data. This action cannot be undone. 
            Once you delete your account, you will be logged out immediately and will lose access to the system.
        </p>
        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you absolutely sure you want to permanently delete your account? This action cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger d-inline-flex align-items-center gap-2 fw-semibold px-4 py-2">
                <i class="bi bi-trash3-fill"></i> Delete My Account
            </button>
        </form>
    </div>

</div>

<!-- Cropper Modal -->
<div class="modal fade" id="cropperModal" tabindex="-1" aria-labelledby="cropperModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cropperModalLabel" style="font-weight: 600; font-size: 1.1rem;">Adjust Profile Photo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body bg-light">
        <div class="img-container" style="max-height: 60vh; display: flex; justify-content: center; overflow: hidden;">
          <img id="imageToCrop" src="" style="max-width: 100%; display: block;">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="cropButton">Crop & Apply</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let cropper;
    const fileInput = document.getElementById('profile_photo');
    const imageToCrop = document.getElementById('imageToCrop');
    const cropperModalEl = document.getElementById('cropperModal');
    const cropperModal = new bootstrap.Modal(cropperModalEl);
    const cropButton = document.getElementById('cropButton');
    const croppedInput = document.getElementById('cropped_photo');
    const profilePreview = document.getElementById('profile_preview');

    fileInput.addEventListener('change', function(e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            const file = files[0];
            const reader = new FileReader();
            reader.onload = function(event) {
                imageToCrop.src = event.target.result;
                cropperModal.show();
            };
            reader.readAsDataURL(file);
        }
    });

    cropperModalEl.addEventListener('shown.bs.modal', function () {
        cropper = new Cropper(imageToCrop, {
            aspectRatio: 1,
            viewMode: 1,
            autoCropArea: 1,
            dragMode: 'move',
            background: false
        });
    });

    cropperModalEl.addEventListener('hidden.bs.modal', function () {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        // clear the file input so it can trigger change again for same file
        fileInput.value = '';
    });

    cropButton.addEventListener('click', function() {
        if (!cropper) return;
        const canvas = cropper.getCroppedCanvas({
            width: 300,
            height: 300,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });
        const base64Url = canvas.toDataURL('image/jpeg', 0.9);
        
        croppedInput.value = base64Url;
        
        profilePreview.innerHTML = '<img src="' + base64Url + '" alt="Profile Photo" style="width: 100%; height: 100%; object-fit: cover;">';

        cropperModal.hide();
    });
});
</script>
@endpush
