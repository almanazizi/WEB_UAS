@extends('layouts.app')

@section('title', 'Tambah Peminjaman')

@section('content')
<div class="mb-4">
    <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Peminjaman
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="bi bi-plus-circle"></i> Tambah Peminjaman Baru
                </h5>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle"></i> <strong>Kesalahan!</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('bookings.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="lab_id" class="form-label">
                            <i class="bi bi-building"></i> Pilih Laboratorium <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('lab_id') is-invalid @enderror" 
                                id="lab_id" name="lab_id" required>
                            <option value="">Pilih laboratorium...</option>
                            @foreach($labs as $lab)
                                <option value="{{ $lab->id }}" {{ old('lab_id') == $lab->id ? 'selected' : '' }}>
                                    {{ $lab->name }} - {{ $lab->location }} (Kapasitas: {{ $lab->capacity }})
                                </option>
                            @endforeach
                        </select>
                        @error('lab_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_time" class="form-label">
                                    <i class="bi bi-clock"></i> Waktu Mulai <span class="text-danger">*</span>
                                </label>
                                <input type="datetime-local" 
                                       class="form-control @error('start_time') is-invalid @enderror" 
                                       id="start_time" name="start_time" 
                                       value="{{ old('start_time') }}" required>
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_time" class="form-label">
                                    <i class="bi bi-clock-fill"></i> Waktu Selesai <span class="text-danger">*</span>
                                </label>
                                <input type="datetime-local" 
                                       class="form-control @error('end_time') is-invalid @enderror" 
                                       id="end_time" name="end_time" 
                                       value="{{ old('end_time') }}" required>
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="purpose" class="form-label">
                            <i class="bi bi-file-text"></i> Keperluan <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control @error('purpose') is-invalid @enderror" 
                                  id="purpose" name="purpose" rows="4" 
                                  placeholder="Jelaskan keperluan peminjaman laboratorium..." required>{{ old('purpose') }}</textarea>
                        @error('purpose')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Maksimal 1000 karakter</small>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> 
                        <strong>Catatan:</strong> Peminjaman Anda akan berstatus pending hingga disetujui oleh staff.
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-send"></i> Kirim Permohonan Peminjaman
                        </button>
                        <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
