@extends('layouts.app')

@section('title', 'Daftar Pengunjung')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold">
        <i class="bi bi-people"></i> Daftar Pengunjung Laboratorium
    </h2>
    <p class="text-muted">Kelola dan pantau pengunjung harian laboratorium</p>
</div>

<!-- Statistics Card -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <div class="card-body text-white">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-1"><i class="bi bi-calendar-check"></i> Pengunjung Hari Ini</h5>
                        <h2 class="fw-bold mb-0">{{ $todayCount }} Pengunjung</h2>
                        <small>{{ now()->format('d F Y') }}</small>
                    </div>
                    <div class="col-md-4 text-end">
                        <i class="bi bi-people-fill" style="font-size: 4rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0"><i class="bi bi-funnel"></i> Filter Pengunjung</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('staff.guests.index') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="date" class="form-label">Tanggal</label>
                    <input type="date" class="form-control" id="date" name="date" 
                           value="{{ request('date', today()->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label for="lab_id" class="form-label">Laboratorium</label>
                    <select class="form-select" id="lab_id" name="lab_id">
                        <option value="">Semua Laboratorium</option>
                        @foreach($labs as $lab)
                            <option value="{{ $lab->id }}" {{ request('lab_id') == $lab->id ? 'selected' : '' }}>
                                {{ $lab->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search"></i> Filter
                    </button>
                    <a href="{{ route('staff.guests.export', request()->query()) }}" class="btn btn-success me-2" target="_blank">
                        <i class="bi bi-file-earmark-spreadsheet"></i> Export Excel
                    </a>
                    <a href="{{ route('staff.guests.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Guest Visitors Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">
            <i class="bi bi-list-ul"></i> Data Pengunjung
            @if(request('date'))
                - {{ \Carbon\Carbon::parse(request('date'))->format('d M Y') }}
            @else
                - Hari Ini
            @endif
        </h5>
    </div>
    <div class="card-body">
        @if($guests->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="12%">NIM</th>
                            <th width="20%">Nama</th>
                            <th width="18%">Laboratorium</th>
                            <th width="25%">Keperluan</th>
                            <th width="15%">Waktu Check-in</th>
                            <th width="5%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($guests as $index => $guest)
                        <tr>
                            <td>{{ $guests->firstItem() + $index }}</td>
                            <td><span class="badge bg-primary">{{ $guest->nim }}</span></td>
                            <td>
                                <div>
                                    <strong>{{ $guest->nama }}</strong>
                                </div>
                            </td>
                            <td>
                                <i class="bi bi-building text-primary"></i> {{ $guest->lab->name }}
                                <br>
                                <small class="text-muted">{{ $guest->lab->location }}</small>
                            </td>
                            <td>
                                <small>{{ Str::limit($guest->purpose, 60) }}</small>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <i class="bi bi-calendar"></i> {{ $guest->check_in_time->format('d M Y') }}
                                    <br>
                                    <i class="bi bi-clock"></i> {{ $guest->check_in_time->format('H:i') }} WIB
                                </small>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-info" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#detailModal{{ $guest->id }}"
                                        title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Detail Modal -->
                        <div class="modal fade" id="detailModal{{ $guest->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Detail Pengunjung</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th width="35%">NIM</th>
                                                <td>{{ $guest->nim }}</td>
                                            </tr>
                                            <tr>
                                                <th>Nama</th>
                                                <td>{{ $guest->nama }}</td>
                                            </tr>
                                            <tr>
                                                <th>Laboratorium</th>
                                                <td>{{ $guest->lab->name }} - {{ $guest->lab->location }}</td>
                                            </tr>
                                            <tr>
                                                <th>Keperluan</th>
                                                <td>{{ $guest->purpose }}</td>
                                            </tr>
                                            <tr>
                                                <th>Waktu Check-in</th>
                                                <td>{{ $guest->check_in_time->format('d M Y, H:i') }} WIB</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $guests->links() }}
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                <p class="mt-3">
                    Tidak ada data pengunjung 
                    @if(request('date'))
                        pada tanggal {{ \Carbon\Carbon::parse(request('date'))->format('d M Y') }}
                    @else
                        hari ini
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
@endsection
