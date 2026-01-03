@extends('layouts.app')

@section('title', 'Beranda Staff')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold">
        <i class="bi bi-speedometer2"></i> Beranda

 Staff/PJ Lab
    </h2>
    <p class="text-muted">Kelola peminjaman laboratorium dan maintenance</p>
</div>

<!-- Pending Bookings Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">
            <i class="bi bi-clock-history text-warning"></i> Peminjaman Menunggu Persetujuan
        </h5>
    </div>
    <div class="card-body">
        @if($pendingBookings->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Peminjam</th>
                            <th>Lab</th>
                            <th>Waktu</th>
                            <th>Tujuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingBookings as $booking)
                        <tr>
                            <td>
                                <div>
                                    <strong>{{ $booking->user->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $booking->user->email }}</small>
                                </div>
                            </td>
                            <td>
                                <i class="bi bi-building"></i> {{ $booking->lab->name }}
                            </td>
                            <td>
                                <div>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar"></i> {{ $booking->start_time->format('d M Y') }}
                                    </small>
                                    <br>
                                    <i class="bi bi-clock"></i> 
                                    {{ $booking->start_time->format('H:i') }} - {{ $booking->end_time->format('H:i') }}
                                </div>
                            </td>
                            <td>
                                <small>{{ Str::limit($booking->purpose, 50) }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('bookings.show', $booking) }}" 
                                       class="btn btn-outline-info" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('bookings.updateStatus', $booking) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="btn btn-success" title="Setujui">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('bookings.updateStatus', $booking) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="btn btn-danger" title="Tolak">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                <p class="mt-3">Tidak ada peminjaman yang menunggu persetujuan</p>
            </div>
        @endif
    </div>
</div>

<!-- Dashboard Analysis Section -->
<div class="row mt-4">
    <!-- Guest Visitor Widget (With Mini Chart) -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-people text-success"></i> Pengunjung Hari Ini
                </h5>
                <a href="{{ route('staff.guests.index') }}" class="btn btn-sm btn-outline-success">
                    Lihat Detail
                </a>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-5">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-success bg-opacity-10 p-3">
                                    <i class="bi bi-people-fill text-success" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                            <div class="ms-3">
                                <h3 class="fw-bold mb-0">{{ $todayGuestsCount }}</h3>
                                <p class="text-muted mb-0">Total Pengunjung</p>
                                <small class="text-muted">{{ now()->format('d F Y') }}</small>
                            </div>
                        </div>
                        
                        @if($guestsByLab->count() > 0)
                            <div class="small">
                                <strong class="d-block mb-1 text-muted">Breakdown:</strong>
                                @foreach($guestsByLab as $labGuest)
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-truncate" style="max-width: 150px;">{{ $labGuest['lab']->name }}</span>
                                        <span class="badge bg-light text-dark">{{ $labGuest['count'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    
                    <div class="col-md-7 border-start">
                        <h6 class="text-muted small mb-2">Tren 7 Hari Terakhir</h6>
                        <div style="height: 150px;">
                            <canvas id="guestSparkline"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Maintenance Ticket Chart -->
    <div class="col-md-4">
        @include('components.ticket-pie-chart')
    </div>
</div>

<!-- Quick Links -->
<div class="row mt-4 g-3">
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
            <i class="bi bi-calendar-check"></i> Semua Peminjaman
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('tickets.index') }}" class="btn btn-outline-warning w-100">
            <i class="bi bi-tools"></i> Laporan Masalah
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('guestSparkline').getContext('2d');
    
    // Fetch last 7 days stats
    fetch("{{ route('staff.guests.stats') }}?range=7")
        .then(response => response.json())
        .then(data => {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Pengunjung',
                        data: data.data,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 2,
                        pointHoverRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    },
                    scales: {
                        y: {
                            display: true, // Show Y axis but minimal
                            ticks: { display: true, precision: 0 },
                            grid: { borderDash: [2, 4] }
                        },
                        x: {
                            display: true,
                            grid: { display: false },
                            ticks: { 
                                maxTicksLimit: 7,
                                font: { size: 10 }
                            }
                        }
                    }
                }
            });
        });
});
</script>
@endsection
