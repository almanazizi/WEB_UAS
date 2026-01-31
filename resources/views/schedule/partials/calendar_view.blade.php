{{-- Calendar/Timeline View (Option A) --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0"><i class="bi bi-calendar-week"></i> Tampilan Kalender/Timeline</h5>
    </div>
    <div class="card-body">
        @php
            $timeSlots = [];
            for ($hour = 8; $hour <= 18; $hour++) {
                $timeSlots[] = sprintf('%02d:00', $hour);
            }
        @endphp

        @if($bookings->count() > 0)
        <div class="timeline-container">
            @foreach($timeSlots as $slot)
            @php
                // Define this hour slot boundaries
                $slotStart = \Carbon\Carbon::parse($date . ' ' . $slot);
                $slotEnd = $slotStart->copy()->addHour();
                
                // Find all bookings that overlap with this hour slot
                $bookingsInSlot = $bookings->filter(function($booking) use ($slotStart, $slotEnd) {
                    return $booking->start_time < $slotEnd && $booking->end_time > $slotStart;
                });
                
                // Calculate available time segments within this hour
                $segments = [];
                $currentTime = $slotStart->copy();
                
                // Sort bookings by start time
                $sortedBookings = $bookingsInSlot->sortBy('start_time');
                
                foreach ($sortedBookings as $booking) {
                    $bookingStart = max($booking->start_time, $slotStart);
                    $bookingEnd = min($booking->end_time, $slotEnd);
                    
                    // If there's available time before this booking
                    if ($currentTime < $bookingStart) {
                        $segments[] = [
                            'type' => 'available',
                            'start' => $currentTime->format('H:i'),
                            'end' => $bookingStart->format('H:i'),
                        ];
                    }
                    
                    // Add the booking segment
                    $segments[] = [
                        'type' => 'booking',
                        'start' => $bookingStart->format('H:i'),
                        'end' => $bookingEnd->format('H:i'),
                        'booking' => $booking,
                    ];
                    
                    $currentTime = $bookingEnd->copy();
                }
                
                // If there's available time after all bookings
                if ($currentTime < $slotEnd) {
                    $segments[] = [
                        'type' => 'available',
                        'start' => $currentTime->format('H:i'),
                        'end' => $slotEnd->format('H:i'),
                    ];
                }
                
                // If no bookings, entire hour is available
                if ($bookingsInSlot->count() == 0) {
                    $segments[] = [
                        'type' => 'available',
                        'start' => $slotStart->format('H:i'),
                        'end' => $slotEnd->format('H:i'),
                    ];
                }
            @endphp
            
            <div class="timeline-slot mb-3 p-3 border rounded">
                <div class="d-flex align-items-start">
                    <div class="time-label fw-bold text-muted me-3" style="min-width: 80px;">
                        {{ $slot }} WIB
                    </div>
                    <div class="flex-grow-1">
                        @foreach($segments as $segment)
                            @if($segment['type'] === 'available')
                                {{-- Available time segment --}}
                                <div class="available-slot p-2 rounded mb-2 text-center" style="background: #f0f7ff; border: 2px dashed #0d6efd;">
                                    <i class="bi bi-check-circle text-success"></i> 
                                    <strong>Tersedia</strong>
                                    <br>
                                    <small class="text-muted">{{ $segment['start'] }} - {{ $segment['end'] }} WIB</small>
                                </div>
                            @else
                                {{-- Booking segment --}}
                                @php $booking = $segment['booking']; @endphp
                                <div class="booking-card card mb-2 booking-block" style="border-left-color: {{ '#' . substr(md5($booking->lab->id), 0, 6) }}; border-left-width: 4px;">
                                    <div class="card-body py-2 px-3">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">
                                                    <i class="bi bi-building"></i> {{ $booking->lab->name }}
                                                </h6>
                                                <p class="mb-1 small">
                                                    <i class="bi bi-clock"></i> 
                                                    <strong class="text-primary">{{ $segment['start'] }} - {{ $segment['end'] }} WIB</strong>
                                                    @if($segment['start'] != $booking->start_time->format('H:i') || $segment['end'] != $booking->end_time->format('H:i'))
                                                        <br>
                                                        <span class="text-muted small">
                                                            (Total booking: {{ $booking->start_time->format('H:i') }} - {{ $booking->end_time->format('H:i') }})
                                                        </span>
                                                    @endif
                                                </p>
                                                <p class="mb-1 small">
                                                    <i class="bi bi-person"></i> {{ $booking->user->name }}
                                                </p>
                                                <p class="mb-0 small text-muted">
                                                    <i class="bi bi-file-text"></i> {{ Str::limit($booking->purpose, 60) }}
                                                </p>
                                            </div>
                                            <span class="badge {{ $booking->status === 'approved' ? 'bg-success' : 'bg-warning' }}">
                                                {{ $booking->status === 'approved' ? 'Disetujui' : 'Pending' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-calendar-x" style="font-size: 3rem;"></i>
            <p class="mt-3">Tidak ada peminjaman untuk tanggal ini. Semua slot tersedia!</p>
        </div>
        @endif
    </div>
</div>
