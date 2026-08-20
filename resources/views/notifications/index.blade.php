@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 text-gray-800" style="font-weight: 700;">Notification History</h4>
            <p class="text-muted mb-0" style="font-size: 14px;">View and manage all notifications you have received.</p>
        </div>
        
        @if($notifications->count() > 0)
        <form action="{{ route('notifications.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete ALL notification history? This action cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm" style="font-weight: 600; padding: 8px 16px;">
                <i class="bi bi-trash3-fill me-1"></i> Clear All History
            </button>
        </form>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="border-radius: 8px;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        @if($notifications->count() > 0)
            <div class="list-group list-group-flush">
                @foreach($notifications as $notification)
                    <div class="list-group-item d-flex justify-content-between align-items-center p-4 hover-bg-light" style="border-left: 4px solid {{ empty($notification->read_at) ? '#3b82f6' : 'transparent' }};">
                        <div class="d-flex align-items-start">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bi bi-bell-fill fs-5"></i>
                            </div>
                            <div>
                                <h6 class="mb-1" style="font-weight: 600; color: #1e293b;">
                                    {{ $notification->data['message'] ?? 'System notification' }}
                                </h6>
                                <div class="text-muted" style="font-size: 13px;">
                                    <i class="bi bi-clock me-1"></i> {{ $notification->created_at->diffForHumans() }} 
                                    ({{ $notification->created_at->format('d M Y, H:i') }})
                                </div>
                                @if(isset($notification->data['url']))
                                    <a href="{{ $notification->data['url'] }}" class="btn btn-sm btn-outline-primary mt-2" style="font-size: 12px; border-radius: 6px;">
                                        View Data <i class="bi bi-arrow-right-short"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                        
                        <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-light text-danger btn-sm rounded-circle" title="Delete this notification" style="width: 35px; height: 35px;" onclick="return confirm('Delete this notification?');">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
            
            <div class="card-footer bg-white p-3 d-flex justify-content-center border-top">
                {{ $notifications->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="card-body text-center py-5">
                <div class="text-muted mb-3">
                    <i class="bi bi-bell-slash text-gray-300" style="font-size: 4rem; color: #cbd5e1;"></i>
                </div>
                <h5 style="color: #475569; font-weight: 600;">No notification history</h5>
                <p class="text-muted" style="font-size: 14px;">There are currently no notifications in your inbox.</p>
            </div>
        @endif
    </div>
</div>

<style>
    .hover-bg-light:hover {
        background-color: #f8fafc;
    }
</style>
@endsection
