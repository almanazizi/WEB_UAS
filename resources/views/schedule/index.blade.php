@extends('layouts.app')

@section('title', 'Jadwal Peminjaman Lab')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">
            <i class="bi bi-calendar-week"></i> Jadwal Peminjaman Laboratorium
        </h2>
        <a href="{{ route('login') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <p class="text-muted mb-4">
        <i class="bi bi-info-circle"></i> Jadwal publik ini menampilkan semua peminjaman yang telah disetujui. Gunakan untuk melihat ketersediaan lab.
    </p>

    {{-- View Toggle Buttons --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6 mb-3 mb-md-0">
                    <h6 class="mb-2"><i class="bi bi-eye"></i> Pilih Tampilan</h6>
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="viewOption" id="viewTable" value="table" {{ $view == 'table' ? 'checked' : '' }}>
                        <label class="btn btn-outline-primary" for="viewTable">
                            <i class="bi bi-table"></i> Tabel
                        </label>

                        <input type="radio" class="btn-check" name="viewOption" id="viewCalendar" value="calendar" {{ $view == 'calendar' ? 'checked' : '' }}>
                        <label class="btn btn-outline-primary" for="viewCalendar">
                            <i class="bi bi-calendar-week"></i> Kalender
                        </label>

                        <input type="radio" class="btn-check" name="viewOption" id="viewLab" value="lab" {{ $view == 'lab' ? 'checked' : '' }}>
                        <label class="btn btn-outline-primary" for="viewLab">
                            <i class="bi bi-building"></i> Per Lab
                        </label>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label for="lab_id" class="form-label small mb-1">Filter Lab</label>
                            <select class="form-select form-select-sm" id="lab_id" name="lab_id">
                                <option value="">Semua Lab</option>
                                @foreach($labs as $lab)
                                <option value="{{ $lab->id }}" {{ $labId == $lab->id ? 'selected' : '' }}>
                                    {{ $lab->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="date" class="form-label small mb-1">Tanggal</label>
                            <input type="date" class="form-control form-control-sm" id="date" name="date" value="{{ $date }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Table View (Option B) --}}
    <div id="table-view" class="view-content" style="display: {{ $view == 'table' ? 'block' : 'none' }};">
        @include('schedule.partials.table_view')
    </div>

    {{-- Calendar View (Option A) --}}
    <div id="calendar-view" class="view-content" style="display: {{ $view == 'calendar' ? 'block' : 'none' }};">
        @include('schedule.partials.calendar_view')
    </div>

    {{-- Per Lab View (Option C) --}}
    <div id="lab-view" class="view-content" style="display: {{ $view == 'lab' ? 'block' : 'none' }};">
        @include('schedule.partials.lab_view')
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // View switcher
    document.querySelectorAll('input[name="viewOption"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.view-content').forEach(view => view.style.display = 'none');
            document.getElementById(this.value + '-view').style.display = 'block';
            
            // Update URL
            const url = new URL(window.location);
            url.searchParams.set('view', this.value);
            window.history.pushState({}, '', url);
        });
    });

    // Filter change handlers
    document.getElementById('lab_id').addEventListener('change', filterBookings);
    document.getElementById('date').addEventListener('change', filterBookings);

    function filterBookings() {
        const labId = document.getElementById('lab_id').value;
        const date = document.getElementById('date').value;
        const view = document.querySelector('input[name="viewOption"]:checked').value;
        
        window.location.href = `{{ route('schedule.index') }}?lab_id=${labId}&date=${date}&view=${view}`;
    }
});
</script>

<style>
.booking-block {
    border-left: 4px solid;
    transition: all 0.3s;
}
.booking-block:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.available-slot {
    border: 2px dashed #dee2e6;
    background: #f8f9fa;
}
</style>
@endsection
