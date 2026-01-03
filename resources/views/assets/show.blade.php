@extends('layouts.app')

@section('title', 'Detail Aset')

@section('content')
<div class="mb-4">
    <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali ke Inventaris
    </a>
</div>

<div class="row">
    <div class="col-md-5">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h4 class="mb-0">
                        <code>{{ $asset->code }}</code>
                    </h4>
                    <span class="badge {{ $asset->condition === 'good' ? 'bg-success' : 'bg-danger' }}" style="font-size: 1rem;">
                        {{ $asset->condition === 'good' ? 'Baik' : 'Rusak' }}
                    </span>
                </div>
                
                <h5 class="mb-3">{{ $asset->name }}</h5>
                
                <div class="mb-3">
                    <h6 class="text-muted mb-2"><i class="bi bi-building"></i> Laboratorium</h6>
                    <p class="mb-0">{{ $asset->lab->name }}</p>
                    <small class="text-muted">{{ $asset->lab->location }}</small>
                </div>
                
                <div class="mb-3">
                    <h6 class="text-muted mb-2"><i class="bi bi-card-list"></i> Spesifikasi</h6>
                    <p class="mb-0">{{ $asset->spec ?? 'Tidak ada spesifikasi' }}</p>
                </div>
                
                <hr>
                
                <div class="small text-muted">
                    <div class="mb-1">
                        <strong>Dibuat:</strong> {{ $asset->created_at->format('d M Y, H:i') }}
                    </div>
                    <div>
                        <strong>Diperbarui:</strong> {{ $asset->updated_at->format('d M Y, H:i') }}
                    </div>
                </div>
                
                @if(auth()->user()->isStaff())
                <hr>
                <div class="d-grid gap-2">
                    <a href="{{ route('assets.edit', $asset) }}" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Ubah Data
                    </a>
                    <form action="{{ route('assets.destroy', $asset) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" 
                                onclick="return confirm('Yakin ingin menghapus aset ini?')">
                            <i class="bi bi-trash"></i> Hapus Aset
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="bi bi-tools"></i> Riwayat Laporan Masalah
                </h5>
            </div>
            <div class="card-body">
                @if($asset->tickets->count() > 0)
                    @foreach($asset->tickets as $ticket)
                    <div class="card mb-3 border">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="mb-1">Tiket #{{ $ticket->id }}</h6>
                                    <small class="text-muted">
                                        <i class="bi bi-person"></i> {{ $ticket->user->name }}
                                    </small>
                                </div>
                                <span class="badge {{ $ticket->status === 'open' ? 'bg-warning' : 'bg-success' }}">
                                    {{ $ticket->status === 'open' ? 'Terbuka' : 'Selesai' }}
                                </span>
                            </div>
                            
                            <p class="mb-2 small">{{ $ticket->issue_description }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    {{ $ticket->created_at->diffForHumans() }}
                                </small>
                                <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Lihat
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                        <p class="mt-2">Belum ada laporan masalah untuk aset ini</p>
                        <a href="{{ route('tickets.create') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle"></i> Laporkan Masalah
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
