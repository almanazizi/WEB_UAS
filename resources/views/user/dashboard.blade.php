@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold">
        <i class="bi bi-speedometer2"></i> Beranda
    </h2>
    <p class="text-muted">Selamat datang, {{ auth()->user()->name }}</p>
</div>

<!-- My Active Bookings -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-calendar-check"></i> Peminjaman Lab Saya
            </h5>
            <a href="{{ route('bookings.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> Pinjam Lab Baru
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($myBookings->count() > 0)
            <div class="row g-3">
                @foreach($myBookings as $booking)
                <div class="col-md-6">
                    <div class="card border h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-building text-primary"></i> {{ $booking->lab->name }}
                                </h5>
                                <span class="badge 
                                    {{ $booking->status === 'approved' ? 'bg-success' : '' }}
                                    {{ $booking->status === 'pending' ? 'bg-warning' : '' }}
                                    {{ $booking->status === 'rejected' ? 'bg-danger' : '' }}">
                                    @if($booking->status === 'approved')
                                        Disetujui
                                    @elseif($booking->status === 'pending')
                                        Menunggu
                                    @else
                                        Ditolak
                                    @endif
                                </span>
                            </div>
                            
                            <p class="text-muted small mb-2">
                                <i class="bi bi-geo-alt"></i> {{ $booking->lab->location }}
                            </p>
                            
                            <hr>
                            
                            <div class="mb-2">
                                <small class="text-muted"><i class="bi bi-calendar"></i> Tanggal:</small>
                                <div>{{ $booking->start_time->format('d F Y') }}</div>
                            </div>
                            
                            <div class="mb-2">
                                <small class="text-muted"><i class="bi bi-clock"></i> Waktu:</small>
                                <div>{{ $booking->start_time->format('H:i') }} - {{ $booking->end_time->format('H:i') }} WIB</div>
                            </div>
                            
                            <div class="mb-3">
                                <small class="text-muted"><i class="bi bi-file-text"></i> Tujuan:</small>
                                <div class="small">{{ $booking->purpose }}</div>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary flex-fill">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                                @if($booking->status === 'pending')
                                <form action="{{ route('bookings.destroy', $booking) }}" method="POST" class="flex-fill">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100" 
                                            onclick="return confirm('Batalkan peminjaman ini?')">
                                        <i class="bi bi-x-circle"></i> Batal
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                <p class="mt-3">Anda belum memiliki peminjaman lab</p>
                <a href="{{ route('bookings.create') }}" class="btn btn-primary mt-2">
                    <i class="bi bi-plus-circle"></i> Pinjam Lab Sekarang
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Quick Links -->
<div class="row g-3">
    <div class="col-md-3">
        <a href="{{ route('labs.index') }}" class="btn btn-outline-primary w-100">
            <i class="bi bi-building"></i> Daftar Laboratorium
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('assets.index') }}" class="btn btn-outline-success w-100">
            <i class="bi bi-laptop"></i> Lihat Inventaris
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('bookings.index') }}" class="btn btn-outline-info w-100">
            <i class="bi bi-calendar-check"></i> Riwayat Peminjaman
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('tickets.create') }}" class="btn btn-outline-warning w-100">
            <i class="bi bi-tools"></i> Laporkan Masalah
        </a>
    </div>
</div>
@endsection
