@extends('layouts.app')

@section('title', 'Detail Laporan')

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
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-tools"></i> Tiket #{{ $ticket->id }}
                    </h5>
                    <span class="badge {{ $ticket->status === 'open' ? 'bg-warning' : 'bg-success' }}" style="font-size: 1rem;">
                        {{ $ticket->status === 'open' ? 'Terbuka' : 'Selesai' }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">
                            <i class="bi bi-person-circle"></i> Dilaporkan Oleh
                        </h6>
                        <p class="mb-0">{{ $ticket->user->name }}</p>
                        <small class="text-muted">{{ $ticket->user->email }}</small>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">
                            <i class="bi bi-calendar"></i> Tanggal Laporan
                        </h6>
                        <p class="mb-0">{{ $ticket->created_at->format('d F Y, H:i') }}</p>
                        <small class="text-muted">{{ $ticket->created_at->diffForHumans() }}</small>
                    </div>
                </div>
                
                <hr>
                
                <div class="mb-4">
                    <h6 class="text-muted mb-2">
                        <i class="bi bi-laptop"></i> Informasi Aset
                    </h6>
                    <div class="card border">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">
                                        <code>{{ $ticket->asset->code }}</code> - {{ $ticket->asset->name }}
                                    </h6>
                                    <p class="mb-1">
                                        <i class="bi bi-building"></i> {{ $ticket->asset->lab->name }}
                                    </p>
                                    <small class="text-muted">{{ $ticket->asset->lab->location }}</small>
                                </div>
                                <a href="{{ route('assets.show', $ticket->asset) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Lihat Aset
                                </a>
                            </div>
                            @if($ticket->asset->spec)
                            <hr class="my-2">
                            <small class="text-muted">{{ $ticket->asset->spec }}</small>
                            @endif
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="mb-4">
                    <h6 class="text-muted mb-2">
                        <i class="bi bi-file-text"></i> Deskripsi Masalah
                    </h6>
                    <div class="bg-light p-3 rounded">
                        {{ $ticket->issue_description }}
                    </div>
                </div>
                
                <hr>
                
                <div class="text-muted small">
                    <strong>Terakhir Diperbarui:</strong> {{ $ticket->updated_at->format('d M Y, H:i') }}
                </div>
                
                @if(auth()->user()->isStaff())
                <hr>
                <div class="d-flex gap-2">
                    @if($ticket->status === 'open')
                    <form action="{{ route('tickets.updateStatus', $ticket) }}" method="POST" class="flex-fill">
                        @csrf
                        <input type="hidden" name="status" value="resolved">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-check-circle"></i> Tandai Selesai
                        </button>
                    </form>
                    @else
                    <form action="{{ route('tickets.updateStatus', $ticket) }}" method="POST" class="flex-fill">
                        @csrf
                        <input type="hidden" name="status" value="open">
                        <button type="submit" class="btn btn-warning w-100">
                            <i class="bi bi-arrow-counterclockwise"></i> Buka Kembali
                        </button>
                    </form>
                    @endif
                </div>
                @endif
                
                @if($ticket->user_id === auth()->id() && $ticket->status === 'open')
                <hr>
                <form action="{{ route('tickets.destroy', $ticket) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100" 
                            onclick="return confirm('Yakin ingin menghapus laporan ini?')">
                        <i class="bi bi-trash"></i> Hapus Laporan
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
