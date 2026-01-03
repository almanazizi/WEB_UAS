@extends('layouts.app')

@section('title', 'Ubah Aset')

@section('content')
<div class="mb-4">
    <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali ke Inventaris
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="bi bi-pencil"></i> Ubah Data Aset: {{ $asset->code }}
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
                
                <form action="{{ route('assets.update', $asset) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="code" class="form-label">
                                    <i class="bi bi-upc"></i> Kode Aset <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                       id="code" name="code" value="{{ old('code', $asset->code) }}" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="lab_id" class="form-label">
                                    <i class="bi bi-building"></i> Laboratorium <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('lab_id') is-invalid @enderror" 
                                        id="lab_id" name="lab_id" required>
                                    @foreach($labs as $lab)
                                        <option value="{{ $lab->id }}" 
                                            {{ old('lab_id', $asset->lab_id) == $lab->id ? 'selected' : '' }}>
                                            {{ $lab->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="bi bi-laptop"></i> Nama Aset <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $asset->name) }}" required>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="condition" class="form-label">
                                    <i class="bi bi-heart-pulse"></i> Kondisi <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('condition') is-invalid @enderror" 
                                        id="condition" name="condition" required>
                                    <option value="good" {{ old('condition', $asset->condition) === 'good' ? 'selected' : '' }}>
                                        Baik
                                    </option>
                                    <option value="bad" {{ old('condition', $asset->condition) === 'bad' ? 'selected' : '' }}>
                                        Rusak
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="spec" class="form-label">
                            <i class="bi bi-card-list"></i> Spesifikasi
                        </label>
                        <textarea class="form-control @error('spec') is-invalid @enderror" 
                                  id="spec" name="spec" rows="4">{{ old('spec', $asset->spec) }}</textarea>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Perbarui Aset
                        </button>
                        <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
