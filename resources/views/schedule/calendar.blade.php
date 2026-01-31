<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Peminjaman Lab - Calendar View</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        
        .calendar-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 30px;
            margin: 0 auto;
            max-width: 1400px;
        }
        
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .view-switcher {
            display: flex;
            gap: 5px;
            background: #f1f5f9;
            padding: 4px;
            border-radius: 8px;
        }
        
        .view-switcher .btn {
            padding: 8px 20px;
            border: none;
            background: transparent;
            color: #64748b;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .view-switcher .btn.active {
            background: white;
            color: #3b82f6;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .month-navigation {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .month-navigation h3 {
            margin: 0;
            font-weight: 700;
            color: #1e293b;
            min-width: 200px;
            text-align: center;
        }
        
        .month-navigation .btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #e2e8f0;
            background: white;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }
        
        .month-navigation .btn:hover {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }
        
        .calendar-grid {
            display: grid;
            grid-template-rows: auto repeat(6, 1fr);
            gap: 1px;
            background: #e2e8f0;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .calendar-header-row {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            background: #f8fafc;
        }
        
        .day-header {
            padding: 15px;
            text-align: center;
            font-weight: 700;
            color: #475569;
            background: #f1f5f9;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .calendar-week {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
        }
        
        .calendar-day {
            background: white;
            min-height: 120px;
            padding: 10px;
            position: relative;
            transition: all 0.3s;
        }
        
        .calendar-day:hover {
            background: #f8fafc;
        }
        
        .calendar-day.today {
            background: #eff6ff;
            box-shadow: inset 0 0 0 2px #3b82f6;
        }
        
        .calendar-day.other-month {
            background: #f9fafb;
            opacity: 0.5;
        }
        
        .calendar-day.weekend:not(.today) {
            background: #fef2f2;
        }
        
        .day-number {
            font-weight: 700;
            font-size: 16px;
            color: #1e293b;
            margin-bottom: 8px;
            display: inline-block;
            width: 30px;
            height: 30px;
            line-height: 30px;
            text-align: center;
            border-radius: 50%;
        }
        
        .calendar-day.today .day-number {
            background: #3b82f6;
            color: white;
        }
        
        .day-events {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        
        .event-item {
            background: white;
            padding: 6px 8px;
            margin-bottom: 3px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
            border-left: 3px solid;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            display: flex;
            gap: 6px;
            align-items: center;
        }
        
        .event-item:hover {
            transform: translateX(3px);
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }
        
        .event-time {
            font-weight: 600;
            color: #475569;
            flex-shrink: 0;
        }
        
        .event-title {
            color: #64748b;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            flex: 1;
        }
        
        .event-status {
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 10px;
            flex-shrink: 0;
        }
        
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        
        .status-approved {
            background: #d1fae5;
            color: #065f46;
        }
        
        .more-events {
            font-size: 11px;
            color: #3b82f6;
            font-weight: 600;
            cursor: pointer;
            padding: 4px 8px;
            text-align: center;
            background: #eff6ff;
            border-radius: 4px;
            margin-top: 2px;
        }
        
        .more-events:hover {
            background: #dbeafe;
        }
        
        .filters {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        @media (max-width: 992px) {
            .calendar-header {
                justify-content: center;
            }
            
            .calendar-day {
                min-height: 100px;
                padding: 6px;
            }
            
            .event-title {
                display: none;
            }
            
            .day-number {
                font-size: 14px;
            }
        }
        
        @media (max-width: 768px) {
            .calendar-day {
                min-height: 80px;
            }
            
            .day-header {
                padding: 10px 5px;
                font-size: 12px;
            }
            
            .event-item {
                font-size: 10px;
                padding: 4px 6px;
            }
            
            .month-navigation h3 {
                font-size: 18px;
                min-width: 150px;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="calendar-container">
            <!-- Header -->
            <div class="calendar-header">
                <!-- View Switcher -->
                <div class="view-switcher">
                    <a href="{{ route('schedule.index', ['view' => 'table', 'lab_id' => $labId]) }}" 
                       class="btn {{ $view === 'table' ? 'active' : '' }}">
                        <i class="bi bi-table"></i> Tabel
                    </a>
                    <a href="{{ route('schedule.index', ['view' => 'calendar', 'month' => $month, 'year' => $year, 'lab_id' => $labId]) }}" 
                       class="btn {{ $view === 'calendar' ? 'active' : '' }}">
                        <i class="bi bi-calendar3"></i> Kalender
                    </a>
                </div>
                
                <!-- Month Navigation -->
                <div class="month-navigation">
                    <a href="{{ route('schedule.index', ['view' => 'calendar', 'month' => $calendarData['prevMonth'], 'year' => $calendarData['prevYear'], 'lab_id' => $labId]) }}" 
                       class="btn">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                    
                    <h3>{{ $calendarData['monthName'] }} {{ $calendarData['year'] }}</h3>
                    
                    <a href="{{ route('schedule.index', ['view' => 'calendar', 'month' => $calendarData['nextMonth'], 'year' => $calendarData['nextYear'], 'lab_id' => $labId]) }}" 
                       class="btn">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                    
                    <a href="{{ route('schedule.index', ['view' => 'calendar', 'lab_id' => $labId]) }}" 
                       class="btn btn-sm btn-outline-primary px-3">
                        <i class="bi bi-calendar-day"></i> Hari Ini
                    </a>
                </div>
                
                <!-- Filters -->
                <div class="filters">
                    <select class="form-select" id="labFilter" onchange="filterByLab(this.value)">
                        <option value="">Semua Lab</option>
                        @foreach($labs as $lab)
                            <option value="{{ $lab->id }}" {{ $labId == $lab->id ? 'selected' : '' }}>
                                {{ $lab->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- Calendar Grid -->
            <div class="calendar-grid">
                <!-- Day Headers -->
                <div class="calendar-header-row">
                    <div class="day-header">Min</div>
                    <div class="day-header">Sen</div>
                    <div class="day-header">Sel</div>
                    <div class="day-header">Rab</div>
                    <div class="day-header">Kam</div>
                    <div class="day-header">Jum</div>
                    <div class="day-header">Sab</div>
                </div>
                
                <!-- Week Rows -->
                @foreach($calendarData['weeks'] as $week)
                <div class="calendar-week">
                    @foreach($week['days'] as $day)
                    <div class="calendar-day 
                        {{ $day['isCurrentMonth'] ? '' : 'other-month' }} 
                        {{ $day['isToday'] ? 'today' : '' }}
                        {{ $day['isWeekend'] ? 'weekend' : '' }}">
                        
                        <div class="day-number">{{ $day['date'] }}</div>
                        
                        <!-- Events for this day -->
                        <div class="day-events">
                            @foreach($day['bookings'] as $booking)
                            <div class="event-item" 
                                 style="border-left-color: {{ $booking->lab->color ?? '#3b82f6' }};"
                                 data-bs-toggle="modal" 
                                 data-bs-target="#eventModal"
                                 data-booking-id="{{ $booking->id }}"
                                 onclick="loadBookingDetails({{ $booking->id }})">
                                <span class="event-time">{{ $booking->start_time->format('H:i') }}</span>
                                <span class="event-title">{{ Str::limit($booking->purpose, 12) }}</span>
                                <span class="event-status status-{{ $booking->status }}">
                                    {{ $booking->status === 'approved' ? '✓' : '⏱' }}
                                </span>
                            </div>
                            @endforeach
                            
                            @if($day['hasMore'])
                            <div class="more-events" 
                                 data-bs-toggle="modal" 
                                 data-bs-target="#dayModal"
                                 onclick="loadDayBookings('{{ $day['fullDate'] }}')">
                                +{{ $day['totalBookings'] - 3 }} lagi
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- Event Detail Modal -->
    <div class="modal fade" id="eventModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Peminjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="eventModalBody">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Day Bookings Modal (for "more events") -->
    <div class="modal fade" id="dayModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Semua Peminjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="dayModalBody">
                    <!-- Content loaded via JS -->
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Filter by Lab
        function filterByLab(labId) {
            const params = new URLSearchParams(window.location.search);
            if (labId) {
                params.set('lab_id', labId);
            } else {
                params.delete('lab_id');
            }
            params.set('view', 'calendar');
            params.set('month', {{ $month }});
            params.set('year', {{ $year }});
            window.location.href = '?' + params.toString();
        }
        
        // Load booking details (placeholder - will be enhanced)
        function loadBookingDetails(bookingId) {
            const modalBody = document.getElementById('eventModalBody');
            modalBody.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>';
            
            // Fetch booking details via AJAX (to be implemented)
            setTimeout(() => {
                modalBody.innerHTML = `
                    <p><strong>Booking ID:</strong> ${bookingId}</p>
                    <p>Detail will be loaded here...</p>
                `;
            }, 500);
        }
        
        // Load all bookings for a specific day
        function loadDayBookings(date) {
            const modalBody = document.getElementById('dayModalBody');
            modalBody.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>';
            
            // Fetch all day bookings via AJAX (to be implemented)
            setTimeout(() => {
                modalBody.innerHTML = `
                    <p><strong>Tanggal:</strong> ${date}</p>
                    <p>All bookings for this day will be shown here...</p>
                `;
            }, 500);
        }
    </script>
</body>
</html>
