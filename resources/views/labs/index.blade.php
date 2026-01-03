@extends('layouts.app')

@section('title', 'Laboratorium')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">
        <i class="bi bi-building"></i> Daftar Laboratorium
    </h2>
    @if(auth()->user()->isStaff() || auth()->user()->isSuperadmin())
    <a href="{{ route('labs.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Laboratorium
    </a>
    @endif
</div>

<!-- Labs Grid -->
<div class="row g-4">
    @forelse($labs as $lab)
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h4 class="card-title mb-3">
                    <i class="bi bi-building text-primary"></i> {{ $lab->name }}
                </h4>
                
                <p class="text-muted mb-3">
                    <i class="bi bi-geo-alt"></i> {{ $lab->location }}
                </p>
                
                <hr>
                
                <div class="row text-center mb-3">
                    <div class="col-4">
                        <div class="text-muted small">Kapasitas</div>
                        <h5 class="mb-0">{{ $lab->capacity }}</h5>
                        <small class="text-muted">orang</small>
                    </div>
                    <div class="col-4">
                        <div class="text-muted small">Aset</div>
                        <h5 class="mb-0">{{ $lab->assets->count() }}</h5>
                        <small class="text-muted">unit</small>
                    </div>
                    <div class="col-4">
                        <div class="text-muted small">Peminjaman</div>
                        <h5 class="mb-0">{{ $lab->bookings->count() }}</h5>
                        <small class="text-muted">booking</small>
                    </div>
                </div>
                
                <hr>
                
                <div class="d-flex gap-2">
                    <a href="{{ route('labs.show', $lab) }}" class="btn btn-outline-primary btn-sm flex-fill">
                        <i class="bi bi-eye"></i> Detail
                    </a>
                    @if(auth()->user()->isStaff() || auth()->user()->isSuperadmin())
                    <a href="{{ route('labs.edit', $lab) }}" class="btn btn-outline-secondary btn-sm flex-fill">
                        <i class="bi bi-pencil"></i> Ubah
                    </a>
                    <form action="{{ route('labs.destroy', $lab) }}" method="POST" class="flex-fill">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100" 
                                onclick="return confirm('Yakin ingin menghapus laboratorium ini?')">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5 text-muted">
            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
            <p class="mt-3">Belum ada laboratorium terdaftar</p>
        </div>
    </div>
    @endforelse
</div>
@endsection
