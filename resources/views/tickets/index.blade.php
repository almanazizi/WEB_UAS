@extends('layouts.app')

@section('title', 'Laporan Masalah')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">
        <i class="bi bi-tools"></i> Laporan Masalah & Maintenance
    </h2>
    @if(!auth()->user()->isStaff())
    <a href="{{ route('tickets.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Laporkan Masalah Baru
    </a>
    @endif
</div>

<!-- Filter -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('tickets.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="status" class="form-label">Filter berdasarkan Status</label>
                <select class="form-select" id="status" name="status" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Terbuka</option>
                    <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            
            <div class="col-md-4 d-flex align-items-end">
                <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle"></i> Hapus Filter
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Maintenance Ticket Chart -->
<div class="mb-4">
    @include('components.ticket-pie-chart')
</div>

<!-- Tickets List -->
<div class="row g-3">
    @forelse($tickets as $ticket)
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="mb-1">Tiket #{{ $ticket->id }}</h5>
                        <small class="text-muted">
                            <i class="bi bi-person"></i> {{ $ticket->user->name }}
                        </small>
                    </div>
                    <span class="badge {{ $ticket->status === 'open' ? 'bg-warning' : 'bg-success' }}" style="font-size: 0.9rem;">
                        {{ $ticket->status === 'open' ? 'Terbuka' : 'Selesai' }}
                    </span>
                </div>
                
                <div class="mb-2">
                    <small class="text-muted"><i class="bi bi-laptop"></i> Aset:</small>
                    <div>
                        <code>{{ $ticket->asset->code }}</code> - {{ $ticket->asset->name }}
                    </div>
                    <small class="text-muted">
                        <i class="bi bi-building"></i> {{ $ticket->asset->lab->name }}
                    </small>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted"><i class="bi bi-file-text"></i> Masalah:</small>
                    <p class="mb-0 small">{{ Str::limit($ticket->issue_description, 100) }}</p>
                </div>
                
                <div class="d-flex justify-content-between align-items-center border-top pt-2">
                    <small class="text-muted">
                        {{ $ticket->created_at->diffForHumans() }}
                    </small>
                    <div>
                        <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> Lihat
                        </a>
                        @if(auth()->user()->isStaff() && $ticket->status === 'open')
                        <form action="{{ route('tickets.updateStatus', $ticket) }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="status" value="resolved">
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="bi bi-check-circle"></i> Selesaikan
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5 text-muted">
            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
            <p class="mt-3">Tidak ada laporan masalah ditemukan</p>
        </div>
    </div>
    @endforelse
</div>

@if($tickets->hasPages())
<div class="mt-4">
    {{ $tickets->appends(request()->query())->links() }}
</div>
@endif
@endsection
