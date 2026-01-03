@extends('layouts.app')

@section('title', 'Laporkan Masalah')

@section('content')
<div class="mb-4">
    <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Laporan
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="bi bi-plus-circle"></i> Laporkan Masalah Maintenance
                </h5>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('tickets.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="asset_id" class="form-label">
                            <i class="bi bi-laptop"></i> Pilih Aset <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('asset_id') is-invalid @enderror" 
                                id="asset_id" name="asset_id" required>
                            <option value="">Pilih aset yang bermasalah...</option>
                            @foreach($assets as $asset)
                                <option value="{{ $asset->id }}" {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
                                    [{{ $asset->code }}] {{ $asset->name }} - {{ $asset->lab->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="issue_description" class="form-label">
                            <i class="bi bi-file-text"></i> Deskripsi Masalah <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control @error('issue_description') is-invalid @enderror" 
                                  id="issue_description" name="issue_description" rows="6" 
                                  placeholder="Jelaskan masalah yang terjadi secara detail..." required>{{ old('issue_description') }}</textarea>
                        <small class="text-muted">Maksimal 2000 karakter</small>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> 
                        <strong>Catatan:</strong> Laporan Anda akan segera ditinjau oleh staff kami.
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-send"></i> Kirim Laporan
                        </button>
                        <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
