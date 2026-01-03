@extends('layouts.app')

@section('title', 'Detail Laboratorium')

@section('content')
<div class="mb-4">
    <a href="{{ route('labs.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Laboratorium
    </a>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h4 class="mb-3">
                    <i class="bi bi-building text-primary"></i> {{ $lab->name }}
                </h4>
                
                <div class="mb-3">
                    <h6 class="text-muted mb-2"><i class="bi bi-geo-alt"></i> Lokasi</h6>
                    <p class="mb-0">{{ $lab->location }}</p>
                </div>
                
                <div class="mb-3">
                    <h6 class="text-muted mb-2"><i class="bi bi-people"></i> Kapasitas</h6>
                    <p class="mb-0">{{ $lab->capacity }} orang</p>
                </div>
                
                <hr>
                
                <div class="row text-center mb-3">
                    <div class="col-6">
                        <div class="text-muted small">Total Aset</div>
                        <h4 class="mb-0">{{ $lab->assets->count() }}</h4>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">Peminjaman</div>
                        <h4 class="mb-0">{{ $lab->bookings->count() }}</h4>
                    </div>
                </div>
                
                <hr>
                
                <div class="small text-muted">
                    <div class="mb-1">
                        <strong>Dibuat:</strong> {{ $lab->created_at->format('d M Y, H:i') }}
                    </div>
                    <div>
                        <strong>Diperbarui:</strong> {{ $lab->updated_at->format('d M Y, H:i') }}
                    </div>
                </div>
                
                @if(auth()->user()->isStaff() || auth()->user()->isSuperadmin())
                <hr>
                <div class="d-grid gap-2">
                    <a href="{{ route('labs.edit', $lab) }}" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Ubah Data
                    </a>
                    <form action="{{ route('labs.destroy', $lab) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" 
                                onclick="return confirm('Yakin ingin menghapus laboratorium ini?')">
                            <i class="bi bi-trash"></i> Hapus Laboratorium
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="bi bi-laptop"></i> Daftar Aset di Laboratorium
                </h5>
            </div>
            <div class="card-body">
                @if($lab->assets->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Aset</th>
                                    <th>Spesifikasi</th>
                                    <th>Kondisi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lab->assets as $asset)
                                <tr>
                                    <td><code>{{ $asset->code }}</code></td>
                                    <td>{{ $asset->name }}</td>
                                    <td><small>{{ Str::limit($asset->spec, 40) }}</small></td>
                                    <td>
                                        <span class="badge {{ $asset->condition === 'good' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $asset->condition === 'good' ? 'Baik' : 'Rusak' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('assets.show', $asset) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                        <p class="mt-2">Belum ada aset di laboratorium ini</p>
                        @if(auth()->user()->isStaff())
                        <a href="{{ route('assets.create') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle"></i> Tambah Aset
                        </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
