{{-- Per Lab View (Option C) --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0"><i class="bi bi-building"></i> Tampilan Per Laboratorium</h5>
    </div>
    <div class="card-body">
        @php
            // Group bookings by lab
            $bookingsByLab = $bookings->groupBy('lab_id');
        @endphp

        @if($labs->count() > 0)
        <div class="accordion" id="labAccordion">
            @foreach($labs as $index => $lab)
            @php
                $labBookings = $bookingsByLab->get($lab->id, collect());
                $labColor = '#' . substr(md5($lab->id), 0, 6);
            @endphp
            
            <div class="accordion-item mb-3 border-0 shadow-sm">
                <h2 class="accordion-header">
                    <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" 
                            type="button" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#lab{{ $lab->id }}"
                            style="border-left: 5px solid {{ $labColor }};">
                        <div class="w-100 d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $lab->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $lab->location }} • Kapasitas: {{ $lab->capacity }} orang</small>
                            </div>
                            <span class="badge bg-primary">
                                {{ $labBookings->count() }} peminjaman
                            </span>
                        </div>
                    </button>
                </h2>
                <div id="lab{{ $lab->id }}" 
                     class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" 
                     data-bs-parent="#labAccordion">
                    <div class="accordion-body">
                        @if($labBookings->count() > 0)
                        <div class="timeline">
                            @foreach($labBookings as $booking)
                            <div class="booking-card card mb-2 booking-block" style="border-left-color: {{ $labColor }}; border-left-width: 4px;">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <i class="bi bi-clock"></i> 
                                            <strong>{{ $booking->start_time->format('H:i') }} - {{ $booking->end_time->format('H:i') }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $booking->start_time->format('d M Y') }}</small>
                                        </div>
                                        <div class="col-md-3">
                                            <i class="bi bi-person"></i> {{ $booking->user->name }}
                                        </div>
                                        <div class="col-md-5">
                                            <i class="bi bi-file-text"></i> {{ $booking->purpose }}
                                        </div>
                                        <div class="col-md-1 text-end">
                                            <span class="badge {{ $booking->status === 'approved' ? 'bg-success' : 'bg-warning' }}">
                                                <i class="bi {{ $booking->status === 'approved' ? 'bi-check-circle' : 'bi-clock' }}"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        {{-- Show available message --}}
                        <div class="alert alert-info mt-3 mb-0">
                            <i class="bi bi-info-circle"></i> 
                            <strong>Slot Tersedia:</strong> 
                            Lab ini masih bisa dipinjam di luar waktu yang telah terisi di atas 
                            (jam operasional: 08:00 - 18:00 WIB)
                        </div>
                        @else
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                            <p class="mt-2 mb-0">Lab ini sepenuhnya tersedia untuk tanggal yang dipilih!</p>
                            <small class="text-muted">Jam operasional: 08:00 - 18:00 WIB</small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-building-x" style="font-size: 3rem;"></i>
            <p class="mt-3">Tidak ada laboratorium yang tersedia.</p>
        </div>
        @endif
    </div>
</div>
