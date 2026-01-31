{{-- Table List View (Option B) --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0"><i class="bi bi-table"></i> Daftar Peminjaman (Tabel)</h5>
    </div>
    <div class="card-body p-0">
        @if($bookings->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Lab</th>
                        <th>Tanggal</th>
                        <th>Waktu Mulai</th>
                        <th>Waktu Selesai</th>
                        <th>Pemesan</th>
                        <th>Tujuan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                    <tr class="booking-block" style="border-left-color: {{ '#' . substr(md5($booking->lab->id), 0, 6) }}">
                        <td>
                            <strong>{{ $booking->lab->name }}</strong><br>
                            <small class="text-muted">{{ $booking->lab->location }}</small>
                        </td>
                        <td>{{ $booking->start_time->format('d M Y') }}</td>
                        <td>{{ $booking->start_time->format('H:i') }} WIB</td>
                        <td>{{ $booking->end_time->format('H:i') }} WIB</td>
                        <td>{{ $booking->user->name }}</td>
                        <td>{{ Str::limit($booking->purpose, 50) }}</td>
                        <td>
                            <span class="badge {{ $booking->status === 'approved' ? 'bg-success' : 'bg-warning' }}">
                                {{ $booking->status === 'approved' ? 'Disetujui' : 'Pending' }}
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
            <p class="mt-3">Tidak ada peminjaman untuk tanggal dan lab yang dipilih.</p>
        </div>
        @endif
    </div>
</div>
