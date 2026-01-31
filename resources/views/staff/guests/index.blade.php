@extends('layouts.app')

@section('title', 'Daftar Pengunjung')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold">
            <i class="bi bi-people-fill"></i> Daftar Pengunjung
        </h2>
        <p class="text-muted">Rekap kehadiran pengunjung laboratorium</p>
    </div>
</div>

<!-- Statistics Card -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="bi bi-people-fill text-success" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <div class="ms-3">
                        <h3 class="fw-bold mb-0">{{ $todayCount }}</h3>
                        <p class="text-muted mb-0">Total Pengunjung Hari Ini</p>
                        <small class="text-muted">{{ now()->format('d F Y') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Export Section -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('staff.guests.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="date" class="form-label">
                    <i class="bi bi-calendar"></i> Tanggal
                </label>
                <input type="date" 
                       class="form-control" 
                       id="date" 
                       name="date" 
                       value="{{ request('date', today()->format('Y-m-d')) }}">
            </div>
            
            <div class="col-md-4">
                <label for="lab_id" class="form-label">
                    <i class="bi bi-building"></i> Laboratorium
                </label>
                <select class="form-select" id="lab_id" name="lab_id">
                    <option value="">Semua Laboratorium</option>
                    @foreach($labs as $lab)
                        <option value="{{ $lab->id }}" 
                                {{ request('lab_id') == $lab->id ? 'selected' : '' }}>
                            {{ $lab->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                <a href="{{ route('staff.guests.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-clockwise"></i> Reset
                </a>
                <button type="button" 
                        class="btn btn-success ms-auto" 
                        data-bs-toggle="modal" 
                        data-bs-target="#exportModal">
                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Guest List Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">
            <i class="bi bi-table"></i> Data Pengunjung
            @if(request('date'))
                <small class="text-muted">({{ \Carbon\Carbon::parse(request('date'))->format('d F Y') }})</small>
            @endif
        </h5>
    </div>
    <div class="card-body">
        @if($guests->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th style="width: 120px;">Tanggal</th>
                            <th style="width: 80px;">Jam</th>
                            <th style="width: 120px;">NIM</th>
                            <th>Nama</th>
                            <th>Laboratorium</th>
                            <th>Keperluan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($guests as $index => $guest)
                        <tr>
                            <td>{{ $guests->firstItem() + $index }}</td>
                            <td>
                                <small class="text-muted">
                                    <i class="bi bi-calendar"></i> 
                                    {{ $guest->check_in_time->format('d/m/Y') }}
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    <i class="bi bi-clock"></i> 
                                    {{ $guest->check_in_time->format('H:i') }}
                                </span>
                            </td>
                            <td>
                                <code class="text-primary">{{ $guest->nim }}</code>
                            </td>
                            <td>
                                <strong>{{ $guest->nama }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info">
                                    <i class="bi bi-building"></i> 
                                    {{ $guest->lab->name }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">{{ Str::limit($guest->purpose, 50) }}</small>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-3">
                {{ $guests->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                <p class="mt-3">Tidak ada data pengunjung</p>
                @if(request('date') || request('lab_id'))
                    <a href="{{ route('staff.guests.index') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-arrow-clockwise"></i> Lihat Semua Data
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

<!-- Export Options Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="exportModalLabel">
                    <i class="bi bi-file-earmark-excel"></i> Export Data Pengunjung
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info alert-sm">
                    <i class="bi bi-info-circle"></i> 
                    <small>Kosongkan filter untuk mengexport semua data</small>
                </div>
                
                <form id="exportForm">
                    <!-- Date Range Section -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-calendar-range"></i> Rentang Tanggal (Opsional)
                        </label>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label for="export_start_date" class="form-label small text-muted">Dari Tanggal</label>
                                <input type="date" 
                                       class="form-control" 
                                       id="export_start_date" 
                                       name="start_date">
                            </div>
                            <div class="col-md-6">
                                <label for="export_end_date" class="form-label small text-muted">Sampai Tanggal</label>
                                <input type="date" 
                                       class="form-control" 
                                       id="export_end_date" 
                                       name="end_date">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Lab Selection -->
                    <div class="mb-3">
                        <label for="export_lab_id" class="form-label fw-bold">
                            <i class="bi bi-building"></i> Laboratorium (Opsional)
                        </label>
                        <select class="form-select" id="export_lab_id" name="lab_id">
                            <option value="">Semua Laboratorium</option>
                            @foreach($labs as $lab)
                                <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Preview Info -->
                    <div class="alert alert-light border">
                        <small class="text-muted">
                            <i class="bi bi-lightning-charge"></i> 
                            <strong>Quick Actions:</strong><br>
                            • Isi tanggal untuk periode tertentu<br>
                            • Pilih lab untuk data laboratorium spesifik<br>
                            • Kosongkan semua untuk export total data
                        </small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Batal
                </button>
                <button type="button" class="btn btn-success" onclick="exportToExcel()">
                    <i class="bi bi-download"></i> Download Excel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function exportToExcel() {
    const startDate = document.getElementById('export_start_date').value;
    const endDate = document.getElementById('export_end_date').value;
    const labId = document.getElementById('export_lab_id').value;
    
    // Validation: end date must be >= start date
    if (startDate && endDate && startDate > endDate) {
        alert('⚠️ Tanggal akhir harus lebih besar atau sama dengan tanggal awal!');
        return;
    }
    
    // Build URL with query parameters
    const params = new URLSearchParams();
    
    if (startDate) params.append('start_date', startDate);
    if (endDate) params.append('end_date', endDate);
    if (labId) params.append('lab_id', labId);
    
    const url = '{{ route("staff.guests.export") }}?' + params.toString();
    
    // Close modal
    const modalElement = document.getElementById('exportModal');
    const modal = bootstrap.Modal.getInstance(modalElement);
    if (modal) {
        modal.hide();
    }
    
    // Trigger download
    window.location.href = url;
    
    // Reset form after export
    setTimeout(() => {
        document.getElementById('exportForm').reset();
    }, 500);
}
</script>
@endsection
