@extends('layouts.app')

@section('title', 'Inventaris')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">
        <i class="bi bi-laptop"></i> Inventaris Aset
    </h2>
    @if(auth()->user()->isStaff())
    <a href="{{ route('assets.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Aset Baru
    </a>
    @endif
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('assets.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="lab_id" class="form-label">Filter berdasarkan Lab</label>
                <select class="form-select" id="lab_id" name="lab_id" onchange="this.form.submit()">
                    <option value="">Semua Laboratorium</option>
                    @foreach($labs as $lab)
                        <option value="{{ $lab->id }}" {{ request('lab_id') == $lab->id ? 'selected' : '' }}>
                            {{ $lab->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-4">
                <label for="condition" class="form-label">Filter berdasarkan Kondisi</label>
                <select class="form-select" id="condition" name="condition" onchange="this.form.submit()">
                    <option value="">Semua Kondisi</option>
                    <option value="good" {{ request('condition') === 'good' ? 'selected' : '' }}>Baik</option>
                    <option value="bad" {{ request('condition') === 'bad' ? 'selected' : '' }}>Rusak</option>
                </select>
            </div>
            
            <div class="col-md-4 d-flex align-items-end">
                <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle"></i> Hapus Filter
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Assets Table -->
<div class="card border-0 shadow-sm">
    <div class="card-body">
        @if($assets->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Lab</th>
                            <th>Spesifikasi</th>
                            <th>Kondisi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assets as $asset)
                        <tr>
                            <td><code>{{ $asset->code }}</code></td>
                            <td>{{ $asset->name }}</td>
                            <td>
                                <i class="bi bi-building"></i> {{ $asset->lab->name }}
                            </td>
                            <td><small>{{ Str::limit($asset->spec, 50) }}</small></td>
                            <td>
                                <span class="badge {{ $asset->condition === 'good' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $asset->condition === 'good' ? 'Baik' : 'Rusak' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('assets.show', $asset) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(auth()->user()->isStaff())
                                <a href="{{ route('assets.edit', $asset) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $assets->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                <p class="mt-3">Tidak ada aset ditemukan</p>
            </div>
        @endif
    </div>
</div>
@endsection
