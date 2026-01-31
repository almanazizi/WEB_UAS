@extends('layouts.app')

@section('title', 'Booking Details')

@section('content')
<div class="mb-4">
    <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Back to Bookings
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-check"></i> Booking #{{ $booking->id }}
                    </h5>
                    <span class="badge 
                        @if($booking->status === 'approved') bg-success
                        @elseif($booking->status === 'pending') bg-warning
                        @else bg-danger
                        @endif" style="font-size: 1rem;">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">
                            <i class="bi bi-person-circle"></i> Booked By
                        </h6>
                        <p class="mb-0">{{ $booking->user->name }}</p>
                        <small class="text-muted">{{ $booking->user->email }}</small>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">
                            <i class="bi bi-building"></i> Laboratory
                        </h6>
                        <p class="mb-0">{{ $booking->lab->name }}</p>
                        <small class="text-muted">{{ $booking->lab->location }}</small>
                    </div>
                </div>
                
                <hr>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">
                            <i class="bi bi-clock"></i> Start Time
                        </h6>
                        <p class="mb-0 fw-semibold">{{ $booking->start_time->format('d F Y') }}</p>
                        <p class="mb-0">{{ $booking->start_time->format('H:i') }} WIB</p>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">
                            <i class="bi bi-clock-fill"></i> End Time
                        </h6>
                        <p class="mb-0 fw-semibold">{{ $booking->end_time->format('d F Y') }}</p>
                        <p class="mb-0">{{ $booking->end_time->format('H:i') }} WIB</p>
                    </div>
                </div>
                
                <hr>
                
                <div class="mb-4">
                    <h6 class="text-muted mb-2">
                        <i class="bi bi-file-text"></i> Purpose
                    </h6>
                    <p class="mb-0">{{ $booking->purpose }}</p>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-6">
                        <small class="text-muted">Created at:</small>
                        <p class="mb-0">{{ $booking->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Last updated:</small>
                        <p class="mb-0">{{ $booking->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                
                @if(auth()->user()->isStaff() && $booking->status === 'pending')
                <hr>
                <div class="d-flex gap-2">
                    <form action="{{ route('bookings.updateStatus', $booking) }}" method="POST" class="flex-fill">
                        @csrf
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-check-circle"></i> Approve Booking
                        </button>
                    </form>
                    <form action="{{ route('bookings.updateStatus', $booking) }}" method="POST" class="flex-fill">
                        @csrf
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-x-circle"></i> Reject Booking
                        </button>
                    </form>
                </div>
                @endif
                
                @if($booking->user_id === auth()->id() && $booking->status === 'pending')
                <hr>
                <form action="{{ route('bookings.destroy', $booking) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100" 
                            onclick="return confirm('Are you sure you want to cancel this booking?')">
                        <i class="bi bi-trash"></i> Cancel Booking
                    </button>
                </form>
                @endif
                
                {{-- Visitor Tracking Section --}}
                <hr>
                <div class="mb-4">
                    <h5 class="mb-3">
                        <i class="bi bi-people-fill text-primary"></i> Daftar Pengunjung Lab
                    </h5>
                    
                    @if($booking->visitors->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="60">No</th>
                                    <th>NIM</th>
                                    <th>Nama</th>
                                    @if(auth()->user()->isSuperadmin() || auth()->user()->isStaff() || $booking->user_id === auth()->id())
                                    <th width="80" class="text-center">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($booking->visitors as $index => $visitor)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><span class="badge bg-info">{{ $visitor->nim }}</span></td>
                                    <td>{{ $visitor->nama }}</td>
                                    @if(auth()->user()->isSuperadmin() || auth()->user()->isStaff() || $booking->user_id === auth()->id())
                                    <td class="text-center">
                                        <form action="{{ route('bookings.visitors.remove', [$booking, $visitor->id]) }}" 
                                              method="POST" 
                                              style="display: inline;"
                                              onsubmit="return confirm('Yakin ingin menghapus pengunjung ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="alert alert-info mb-3">
                        <i class="bi bi-info-circle"></i> Belum ada pengunjung yang terdaftar untuk peminjaman ini.
                    </div>
                    @endif
                    
                    {{-- Add Visitor Form --}}
                    @if(auth()->user()->isSuperadmin() || auth()->user()->isStaff() || $booking->user_id === auth()->id())
                    <div class="card bg-light border-0 mt-3">
                        <div class="card-body">
                            <h6 class="card-title mb-3">
                                <i class="bi bi-person-plus-fill"></i> Tambah Pengunjung
                            </h6>
                            
                            <form action="{{ route('bookings.visitors.add', $booking) }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="nim" class="form-label">NIM <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('nim') is-invalid @enderror" 
                                               id="nim" 
                                               name="nim" 
                                               value="{{ old('nim') }}"
                                               placeholder="Masukkan NIM"
                                               maxlength="20"
                                               required>
                                        @error('nim')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('nama') is-invalid @enderror" 
                                               id="nama" 
                                               name="nama" 
                                               value="{{ old('nama') }}"
                                               placeholder="Masukkan nama lengkap"
                                               maxlength="100"
                                               required>
                                        @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="bi bi-plus-circle"></i> Tambah
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
