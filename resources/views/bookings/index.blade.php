@extends('layouts.app')

@section('title', 'Daftar Peminjaman')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">
        <i class="bi bi-calendar-check"></i> Daftar Peminjaman
    </h2>
    @if(auth()->user()->isUser())
    <a href="{{ route('bookings.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Peminjaman
    </a>
    @endif
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        @if($bookings->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            @if(!auth()->user()->isUser())
                            <th>Pengguna</th>
                            @endif
                            <th>Lab</th>
                            <th>Waktu Mulai</th>
                            <th>Waktu Selesai</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                        <tr>
                            <td>{{ $booking->id }}</td>
                            @if(!auth()->user()->isUser())
                            <td>
                                <div>{{ $booking->user->name }}</div>
                                <small class="text-muted">{{ $booking->user->email }}</small>
                            </td>
                            @endif
                            <td>
                                <i class="bi bi-building"></i> {{ $booking->lab->name }}
                            </td>
                            <td>{{ $booking->start_time->format('d M Y, H:i') }}</td>
                            <td>{{ $booking->end_time->format('d M Y, H:i') }}</td>
                            <td>
                                <span class="badge 
                                    @if($booking->status === 'approved') bg-success
                                    @elseif($booking->status === 'pending') bg-warning
                                    @else bg-danger
                                    @endif">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $bookings->links() }}
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                <p class="mt-3">Belum ada peminjaman</p>
            </div>
        @endif
    </div>
</div>
@endsection
