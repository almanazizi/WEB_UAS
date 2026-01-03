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
            </div>
        </div>
    </div>
</div>
@endsection
