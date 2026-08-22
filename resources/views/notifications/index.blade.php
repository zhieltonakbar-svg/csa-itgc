@extends('layouts.app')

@section('title', 'Notifications — CSA - ITGC')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Notifications
            </h4>

            <div class="text-muted small">
                Your workflow notifications
            </div>

        </div>

        @if(auth()->user()->unreadNotifications->count())

            <form
                method="POST"
                action="{{ route('notifications.readAll') }}"
                id="markAllForm"
            >

                @csrf

                <button
                    type="submit"
                    class="btn btn-sm btn-outline-success"
                >

                    <i class="bi bi-check2-all"></i>

                    Mark All as Read

                </button>

            </form>

        @endif

    </div>

    <div class="card border-0 shadow-sm">

        <div class="list-group list-group-flush">

            @forelse($notifications as $notification)

                <div
                    class="list-group-item"
                    style="
                        background:
                            {{ $notification->read_at
                                ? '#ffffff'
                                : '#f8fafc' }};
                    "
                >

                    <div class="d-flex align-items-start gap-3">

                        <div
                            style="
                                width:38px;
                                height:38px;
                                border-radius:50%;
                                background:#eaf6ef;
                                color:#198754;
                                display:flex;
                                align-items:center;
                                justify-content:center;
                                flex-shrink:0;
                            "
                        >

                            <i class="bi bi-bell-fill"></i>

                        </div>

                        <div class="flex-grow-1">

                            <div
                                style="
                                    font-size:14px;
                                    font-weight:600;
                                    color:#152238;
                                "
                            >

                                {{ $notification->data['message'] ?? 'New notification' }}

                            </div>

                            <div
                                style="
                                    font-size:12px;
                                    color:#64748b;
                                    margin-top:4px;
                                "
                            >

                                {{ $notification->created_at->diffForHumans() }}

                            </div>

                            @if(
                                !empty(
                                    $notification->data['url']
                                )
                            )

                                <a
                                    href="{{ $notification->data['url'] }}"
                                    class="btn btn-sm btn-outline-success mt-2"
                                >

                                    Open

                                </a>

                            @endif

                        </div>

                        @if(!$notification->read_at)

                            <button
                                type="button"
                                class="btn btn-sm btn-light mark-notification-read"
                                data-id="{{ $notification->id }}"
                                title="Mark as read"
                            >

                                <i class="bi bi-check2"></i>

                            </button>

                        @endif

                    </div>

                </div>

            @empty

                <div class="p-5 text-center">

                    <i
                        class="bi bi-bell-slash"
                        style="
                            font-size:38px;
                            color:#94a3b8;
                        "
                    ></i>

                    <div
                        class="mt-3"
                        style="
                            font-weight:600;
                            color:#475569;
                        "
                    >

                        No notifications

                    </div>

                    <div
                        class="small mt-1"
                        style="
                            color:#94a3b8;
                        "
                    >

                        You do not have any notifications yet.

                    </div>

                </div>

            @endforelse

        </div>

    </div>

    <div class="mt-3">

        {{ $notifications->links() }}

    </div>

</div>

@endsection

@push('scripts')

<script>

document.querySelectorAll(
    '.mark-notification-read'
).forEach(function (button) {

    button.addEventListener(
        'click',
        async function () {

            const id =
                this.dataset.id;

            try {

                const response =
                    await fetch(
                        `/notifications/${id}/read`,
                        {
                            method:'POST',
                            headers:{
                                'X-CSRF-TOKEN':
                                    document
                                        .querySelector(
                                            'meta[name="csrf-token"]'
                                        )
                                        ?.getAttribute(
                                            'content'
                                        ),

                                'Accept':
                                    'application/json'
                            }
                        }
                    );

                if (
                    response.ok
                ) {

                    window.location.reload();

                }

            } catch (
                error
            ) {

                console.error(
                    error
                );

            }

        }
    );

});

</script>

@endpush