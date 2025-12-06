@extends(Auth::user()->isAdmin() ? 'layouts.admin' : 'layouts.app')

@section('title', 'Semua Notifikasi')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">
                    <i class="fas fa-bell text-primary-green"></i> Notifikasi
                </h2>
                <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-check-double"></i> Tandai Semua Sudah Dibaca
                    </button>
                </form>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    @forelse($notifications as $notification)
                        <div class="notification-item-full {{ !$notification->is_read ? 'unread-full' : '' }}">
                            <div class="d-flex gap-3">
                                <div class="notification-icon-full {{ getNotificationColor($notification->type) }}">
                                    <i class="fas {{ getNotificationIcon($notification->type) }}"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="mb-1 fw-bold">{{ $notification->title }}</h6>
                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-2 text-muted">{{ $notification->message }}</p>
                                    <div class="d-flex gap-2">
                                        @if($notification->link)
                                            <a href="{{ route('notifications.read', $notification->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> Lihat Detail
                                            </a>
                                        @endif
                                        @if(!$notification->is_read)
                                            <form action="{{ route('notifications.read', $notification->id) }}" method="GET" class="d-inline">
                                                <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-check"></i> Tandai Dibaca
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus notifikasi ini?')">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(!$loop->last)
                            <hr>
                        @endif
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fa-4x text-muted mb-3"></i>
                            <p class="text-muted">Tidak ada notifikasi</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="mt-4">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</div>

@php
function getNotificationColor($type) {
    $colors = [
        'donasi_baru' => 'bg-info',
        'donasi_berhasil' => 'bg-success',
        'donasi_ditolak' => 'bg-danger',
        'donasi_menunggu' => 'bg-warning',
        'kebutuhan_baru' => 'bg-primary'
    ];
    return $colors[$type] ?? 'bg-secondary';
}

function getNotificationIcon($type) {
    $icons = [
        'donasi_baru' => 'fa-hand-holding-usd',
        'donasi_berhasil' => 'fa-check-circle',
        'donasi_ditolak' => 'fa-times-circle',
        'donasi_menunggu' => 'fa-clock',
        'kebutuhan_baru' => 'fa-bullhorn'
    ];
    return $icons[$type] ?? 'fa-bell';
}
@endphp

<style>
.notification-item-full {
    padding: 1rem;
    border-radius: 0.5rem;
    transition: background 0.2s;
}

.notification-item-full:hover {
    background: #f9fafb;
}

.notification-item-full.unread-full {
    background: #f0fdf4;
    border-left: 4px solid #10b981;
}

.notification-icon-full {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}

.text-primary-green {
    color: #10b981 !important;
}
</style>
@endsection