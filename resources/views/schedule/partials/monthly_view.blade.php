{{-- Monthly Calendar View with Event Blocks --}}
<div class="card border-0 shadow-sm">
    <div class="card-body">
        {{-- Month Navigation --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-0 fw-bold">
                    {{ \Carbon\Carbon::parse($date)->format('F Y') }}
                </h4>
            </div>
            <div class="btn-group">
                <a href="{{ route('schedule.index', ['view' => 'monthly', 'date' => \Carbon\Carbon::parse($date)->subMonth()->format('Y-m-d'), 'lab_id' => $labId]) }}" 
                   class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-chevron-left"></i>
                </a>
                <a href="{{ route('schedule.index', ['view' => 'monthly', 'date' => now()->format('Y-m-d'), 'lab_id' => $labId]) }}" 
                   class="btn btn-outline-secondary btn-sm">
                    Today
                </a>
                <a href="{{ route('schedule.index', ['view' => 'monthly', 'date' => \Carbon\Carbon::parse($date)->addMonth()->format('Y-m-d'), 'lab_id' => $labId]) }}" 
                   class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </div>
        </div>

        {{-- Calendar Grid --}}
        <div class="table-responsive">
            <table class="table table-bordered monthly-calendar">
                <thead>
                    <tr class="text-center">
                        <th class="text-muted small fw-normal">Sun</th>
                        <th class="text-muted small fw-normal">Mon</th>
                        <th class="text-muted small fw-normal">Tue</th>
                        <th class="text-muted small fw-normal">Wed</th>
                        <th class="text-muted small fw-normal">Thu</th>
                        <th class="text-muted small fw-normal">Fri</th>
                        <th class="text-muted small fw-normal">Sat</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $currentDate = \Carbon\Carbon::parse($date)->startOfMonth();
                        $endDate = \Carbon\Carbon::parse($date)->endOfMonth();
                        $startDay = $currentDate->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
                        $endDay = $endDate->copy()->endOfWeek(\Carbon\Carbon::SUNDAY);
                        
                        // Group bookings by date
                        $bookingsByDate = $bookings->groupBy(function($booking) {
                            return $booking->start_time->format('Y-m-d');
                        });
                    @endphp

                    @while($startDay <= $endDay)
                        <tr>
                            @for($i = 0; $i < 7; $i++)
                                @php
                                    $day = $startDay->copy()->addDays($i);
                                    $dateKey = $day->format('Y-m-d');
                                    $isCurrentMonth = $day->month == $currentDate->month;
                                    $isToday = $day->isToday();
                                    $dayBookings = $bookingsByDate->get($dateKey, collect());
                                @endphp
                                <td class="calendar-cell {{ !$isCurrentMonth ? 'other-month' : '' }} {{ $isToday ? 'today' : '' }}" 
                                    style="min-height: 100px; vertical-align: top; padding: 8px;">
                                    <div class="date-number {{ $isToday ? 'fw-bold text-primary' : '' }} mb-2">
                                        {{ $day->format('d') }}
                                    </div>
                                    
                                    @if($dayBookings->count() > 0)
                                        @foreach($dayBookings->take(3) as $booking)
                                            <div class="event-block mb-1 p-1 rounded small" 
                                                 style="background: {{ $booking->status === 'approved' ? '#e3f2fd' : '#fff3cd' }}; 
                                                        border-left: 3px solid {{ $booking->status === 'approved' ? '#2196f3' : '#ffc107' }};"
                                                 title="{{ $booking->lab->name }} - {{ $booking->user->name }}">
                                                <div class="text-truncate" style="font-size: 0.75rem;">
                                                    <strong>{{ $booking->start_time->format('H:i') }}</strong> 
                                                    {{ $booking->lab->name }}
                                                </div>
                                                <div class="text-truncate text-muted" style="font-size: 0.7rem;">
                                                    {{ Str::limit($booking->purpose, 20) }}
                                                </div>
                                            </div>
                                        @endforeach
                                        
                                        @if($dayBookings->count() > 3)
                                            <div class="small text-muted" style="font-size: 0.7rem;">
                                                +{{ $dayBookings->count() - 3 }} more
                                            </div>
                                        @endif
                                    @endif
                                </td>
                            @endfor
                        </tr>
                        @php $startDay->addWeek(); @endphp
                    @endwhile
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Daily Schedule Table Below Calendar --}}
<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-list-ul"></i> Jadwal Harian</h5>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label small">Show 
                    <select class="form-select form-select-sm d-inline-block" style="width: auto;" id="entriesCount">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    entries
                </label>
            </div>
            <div class="col-md-6 text-end">
                <label class="form-label small">Search:
                    <input type="text" class="form-control form-control-sm d-inline-block" style="width: 200px;" id="searchInput" placeholder="Cari...">
                </label>
            </div>
        </div>

        @if($bookings->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="dailyScheduleTable">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal <i class="bi bi-arrow-down-up small"></i></th>
                            <th>Jam</th>
                            <th>Lab</th>
                            <th>Keterangan</th>
                            <th>Pengajuan Oleh</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings->sortBy('start_time') as $booking)
                        <tr>
                            <td>{{ $booking->start_time->format('d M Y') }}</td>
                            <td>
                                <div>{{ $booking->start_time->format('H:i') }} - {{ $booking->end_time->format('H:i') }}</div>
                                <small class="text-muted">{{ $booking->start_time->diffInHours($booking->end_time) }} jam</small>
                            </td>
                            <td>
                                <i class="bi bi-building text-primary"></i> {{ $booking->lab->name }}
                                <br><small class="text-muted">{{ $booking->lab->location }}</small>
                            </td>
                            <td>{{ Str::limit($booking->purpose, 50) }}</td>
                            <td>
                                <div>{{ $booking->user->name }}</div>
                                <small class="text-muted">{{ $booking->user->email }}</small>
                            </td>
                            <td>
                                <span class="badge 
                                    @if($booking->status === 'approved') bg-success
                                    @elseif($booking->status === 'pending') bg-warning
                                    @else bg-danger
                                    @endif">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="bi bi-calendar-x" style="font-size: 3rem;"></i>
                <p class="mt-3">Tidak ada jadwal untuk bulan ini</p>
            </div>
        @endif
    </div>
</div>

<style>
.monthly-calendar {
    margin-bottom: 0;
}
.monthly-calendar td {
    width: 14.28%;
    min-height: 120px;
}
.calendar-cell {
    position: relative;
}
.calendar-cell.other-month {
    background-color: #f8f9fa;
    color: #adb5bd;
}
.calendar-cell.today {
    background-color: #fff3cd;
}
.date-number {
    font-size: 0.875rem;
}
.event-block {
    cursor: pointer;
    transition: all 0.2s;
}
.event-block:hover {
    transform: translateX(2px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>

<script>
// Simple table search and pagination
document.getElementById('searchInput')?.addEventListener('keyup', function() {
    const value = this.value.toLowerCase();
    const rows = document.querySelectorAll('#dailyScheduleTable tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(value) ? '' : 'none';
    });
});
</script>
