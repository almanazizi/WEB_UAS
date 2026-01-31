@extends('layouts.app')

@section('title', 'Dashboard Staff')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold mb-1">Selamat Datang, {{ auth()->user()->name }}</h2>
    <p class="text-muted mb-0">Berikut ringkasan aktivitas laboratorium hari ini</p>
</div>

<!-- 2x2 Grid Layout -->
<div class="row g-4">
    <!-- Widget 1: Jadwal Hari Ini -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">Jadwal Hari Ini</h6>
                <a href="{{ route('bookings.index') }}" class="text-primary text-decoration-none small">
                    View all <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="card-body">
                @if($todaySchedule->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($todaySchedule as $booking)
                        <div class="list-group-item px-0 py-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="badge bg-secondary me-2">
                                            {{ $booking->lab->name }}
                                        </span>
                                        @if($booking->status === 'pending')
                                            <span class="badge bg-warning text-dark me-2">Pending</span>
                                        @elseif($booking->status === 'approved')
                                            <span class="badge bg-success me-2">Approved</span>
                                        @endif
                                        <small class="text-muted">
                                            {{ $booking->start_time->format('H:i') }} - {{ $booking->end_time->format('H:i') }}
                                        </small>
                                    </div>
                                    <div class="fw-semibold">{{ $booking->user->name }}</div>
                                    <small class="text-muted">{{ Str::limit($booking->purpose, 40) }}</small>
                                </div>
                                <a href="{{ route('bookings.show', $booking) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    Tinjau
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-calendar-x" style="font-size: 2rem;"></i>
                        <p class="mt-2 mb-0">Tidak ada jadwal hari ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Widget 2: Tiket Perbaikan Terbaru -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">Tiket Perbaikan Terbaru</h6>
                <a href="{{ route('tickets.index') }}" class="text-primary text-decoration-none small">
                    View all <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="card-body">
                @if($recentTickets->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentTickets as $ticket)
                        <div class="list-group-item px-0 py-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="flex-grow-1">
                                    <div class="mb-1">
                                        <span class="fw-semibold">Tiket Perbaikan #{{ $ticket->id }}</span>
                                    </div>
                                    <small class="text-muted d-block mb-1">{{ Str::limit($ticket->issue_description, 50) }}</small>
                                    <small class="text-muted">
                                        <i class="bi bi-building"></i> {{ $ticket->asset->lab->name ?? 'N/A' }}
                                    </small>
                                </div>
                                <div class="text-end">
                                    @if($ticket->status === 'open')
                                        <span class="badge bg-danger">Open</span>
                                    @elseif($ticket->status === 'in_progress')
                                        <span class="badge bg-warning text-dark">In Progress</span>
                                    @else
                                        <span class="badge bg-success">Resolved</span>
                                    @endif
                                    <a href="{{ route('tickets.show', $ticket) }}" 
                                       class="btn btn-sm btn-link p-0 mt-1">
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-wrench" style="font-size: 2rem;"></i>
                        <p class="mt-2 mb-0">Tidak ada tiket terbaru</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Widget 3: Status Inventaris (Pie Chart) -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-bold">Status Inventaris</h6>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center">
                        <canvas id="inventoryChart" style="max-width: 200px; max-height: 200px; margin: 0 auto;"></canvas>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2">
                            <div class="d-flex align-items-center">
                                <span class="badge" style="width: 12px; height: 12px; background-color: #3b82f6;"></span>
                                <span class="ms-2 small">Baik</span>
                                <span class="ms-auto fw-semibold">{{ $inventoryStats['good'] }}</span>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="d-flex align-items-center">
                                <span class="badge" style="width: 12px; height: 12px; background-color: #f59e0b;"></span>
                                <span class="ms-2 small">Maintenance</span>
                                <span class="ms-auto fw-semibold">{{ $inventoryStats['maintenance'] }}</span>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="d-flex align-items-center">
                                <span class="badge" style="width: 12px; height: 12px; background-color: #ef4444;"></span>
                                <span class="ms-2 small">Rusak</span>
                                <span class="ms-auto fw-semibold">{{ $inventoryStats['bad'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Widget 4: Kunjungan Mingguan (Line Chart) -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-bold">Kunjungan Mingguan</h6>
            </div>
            <div class="card-body">
                <canvas id="visitChart" style="max-height: 240px;"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pie Chart - Status Inventaris (Enhanced Interactivity)
    const inventoryCtx = document.getElementById('inventoryChart').getContext('2d');
    new Chart(inventoryCtx, {
        type: 'doughnut',
        data: {
            labels: ['Baik', 'Maintenance', 'Rusak'],
            datasets: [{
                data: [
                    {{ $inventoryStats['good'] }},
                    {{ $inventoryStats['maintenance'] }},
                    {{ $inventoryStats['bad'] }}
                ],
                backgroundColor: [
                    '#3b82f6', // blue - Baik
                    '#f59e0b', // orange - Maintenance
                    '#ef4444'  // red - Rusak
                ],
                borderWidth: 3,
                borderColor: '#ffffff',
                hoverBorderWidth: 4,
                hoverBorderColor: '#ffffff',
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            animation: {
                animateRotate: true,
                animateScale: true,
                duration: 1000,
                easing: 'easeInOutQuart'
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: true,
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return `${label}: ${value} unit (${percentage}%)`;
                        }
                    }
                }
            },
            onHover: (event, activeElements) => {
                event.native.target.style.cursor = activeElements.length > 0 ? 'pointer' : 'default';
            }
        }
    });

    // Line Chart - Kunjungan Mingguan (Enhanced Interactivity)
    const visitCtx = document.getElementById('visitChart').getContext('2d');
    new Chart(visitCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($visitChartLabels) !!},
            datasets: [{
                label: 'Jumlah Kunjungan',
                data: {!! json_encode($visitChartData) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 8,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointHoverBackgroundColor: '#3b82f6',
                pointHoverBorderColor: '#ffffff',
                pointHoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 1500,
                easing: 'easeInOutQuart'
            },
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: true,
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        title: function(context) {
                            return 'Tanggal: ' + context[0].label;
                        },
                        label: function(context) {
                            const value = context.parsed.y;
                            return `Total Kunjungan: ${value} orang`;
                        },
                        afterLabel: function(context) {
                            const allData = context.dataset.data;
                            const currentValue = context.parsed.y;
                            const previousValue = context.dataIndex > 0 ? allData[context.dataIndex - 1] : currentValue;
                            const diff = currentValue - previousValue;
                            
                            if (context.dataIndex > 0 && diff !== 0) {
                                const arrow = diff > 0 ? '↑' : '↓';
                                const color = diff > 0 ? 'Naik' : 'Turun';
                                return `${arrow} ${color}: ${Math.abs(diff)} dari hari sebelumnya`;
                            }
                            return '';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        font: {
                            size: 11
                        },
                        callback: function(value) {
                            return value + ' orang';
                        }
                    },
                    grid: {
                        borderDash: [5, 5],
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            },
            onHover: (event, activeElements) => {
                event.native.target.style.cursor = activeElements.length > 0 ? 'pointer' : 'default';
            }
        }
    });
});
</script>
@endsection
