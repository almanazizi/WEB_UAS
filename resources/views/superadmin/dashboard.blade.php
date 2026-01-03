@extends('layouts.app')

@section('title', 'Beranda Superadmin')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold">
        <i class="bi bi-speedometer2"></i> Beranda Superadmin
    </h2>
    <p class="text-muted">Selamat datang di Sistem Manajemen Laboratorium</p>
</div>

<!-- Statistics Cards -->
<div class="row g-4">
    <!-- Total Users -->
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm stat-card" style="border-left-color: #667eea;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2"><i class="bi bi-people"></i> Total Pengguna</h6>
                        <h2 class="mb-0">{{ $totalUsers }}</h2>
                    </div>
                    <div class="text-primary" style="font-size: 2.5rem; opacity: 0.3;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Total Labs -->
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm stat-card" style="border-left-color: #28a745;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2"><i class="bi bi-building"></i> Total Laboratorium</h6>
                        <h2 class="mb-0">{{ $totalLabs }}</h2>
                    </div>
                    <div class="text-success" style="font-size: 2.5rem; opacity: 0.3;">
                        <i class="bi bi-building-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Total Assets -->
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm stat-card" style="border-left-color: #ffc107;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2"><i class="bi bi-laptop"></i> Total Aset</h6>
                        <h2 class="mb-0">{{ $totalAssets }}</h2>
                    </div>
                    <div class="text-warning" style="font-size: 2.5rem; opacity: 0.3;">
                        <i class="bi bi-laptop-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Total Bookings -->
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm stat-card" style="border-left-color: #17a2b8;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2"><i class="bi bi-calendar-check"></i> Total Peminjaman</h6>
                        <h2 class="mb-0">{{ $totalBookings }}</h2>
                    </div>
                    <div class="text-info" style="font-size: 2.5rem; opacity: 0.3;">
                        <i class="bi bi-calendar-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Booking Status Cards -->
<div class="row mt-4 g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="bi bi-clock text-warning"></i> Peminjaman Menunggu Persetujuan
                </h5>
                <h1 class="display-4 mb-0">{{ $pendingBookings }}</h1>
                <p class="text-muted mb-0">memerlukan review</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="bi bi-check-circle text-success"></i> Peminjaman Disetujui
                </h5>
                <h1 class="display-4 mb-0">{{ $approvedBookings }}</h1>
                <p class="text-muted mb-0">peminjaman aktif</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Links -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="bi bi-link-45deg"></i> Akses Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <a href="{{ route('labs.index') }}" class="btn btn-outline-primary w-100">
                            <i class="bi bi-building"></i> Kelola Laboratorium
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('assets.index') }}" class="btn btn-outline-success w-100">
                            <i class="bi bi-laptop"></i> Kelola Inventaris
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('bookings.index') }}" class="btn btn-outline-info w-100">
                            <i class="bi bi-calendar-check"></i> Lihat Semua Peminjaman
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('tickets.index') }}" class="btn btn-outline-warning w-100">
                            <i class="bi bi-tools"></i> Laporan Masalah
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
