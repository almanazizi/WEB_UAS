<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Lab;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of bookings
     */
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isSuperadmin() || $user->isStaff()) {
            $bookings = Booking::with(['user', 'lab'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            $bookings = Booking::with('lab')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new booking
     */
    public function create()
    {
        $labs = Lab::all();
        return view('bookings.create', compact('labs'));
    }

    /**
     * Store a newly created booking
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lab_id' => 'required|exists:labs,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'purpose' => 'required|string|max:1000',
        ]);

        // Check for double booking
        $hasOverlap = Booking::where('lab_id', $validated['lab_id'])
            ->where('status', '!=', 'rejected')
            ->where(function($query) use ($validated) {
                $query->where(function($q) use ($validated) {
                    $q->where('start_time', '<', $validated['end_time'])
                      ->where('end_time', '>', $validated['start_time']);
                });
            })->exists();

        if ($hasOverlap) {
            return back()->withErrors([
                'booking' => 'The selected time slot is already booked. Please choose a different time.'
            ])->withInput();
        }

        Booking::create([
            'user_id' => auth()->id(),
            'lab_id' => $validated['lab_id'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'purpose' => $validated['purpose'],
            'status' => 'pending',
        ]);

        return redirect()->route('bookings.index')
            ->with('success', 'Booking created successfully and pending approval.');
    }

    /**
     * Display the specified booking
     */
    public function show(Booking $booking)
    {
        // Check authorization
        if (!auth()->user()->isSuperadmin() && !auth()->user()->isStaff() && $booking->user_id != auth()->id()) {
            abort(403);
        }

        $booking->load(['user', 'lab']);
        return view('bookings.show', compact('booking'));
    }

    /**
     * Update booking status (approve/reject) - Staff only
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $booking->update([
            'status' => $validated['status'],
        ]);

        $message = $validated['status'] === 'approved' 
            ? 'Booking approved successfully.' 
            : 'Booking rejected.';

        return back()->with('success', $message);
    }

    /**
     * Cancel booking - User can cancel their own pending bookings
     */
    public function destroy(Booking $booking)
    {
        // Only allow user to cancel their own pending bookings
        if ($booking->user_id != auth()->id() || $booking->status != 'pending') {
            abort(403);
        }

        $booking->delete();
        return redirect()->route('bookings.index')
            ->with('success', 'Booking cancelled successfully.');
    }

    /**
     * Add a visitor to a booking
     */
    public function addVisitor(Request $request, Booking $booking)
    {
        // Check authorization - owner, staff, or superadmin
        $user = auth()->user();
        if (!$user->isSuperadmin() && !$user->isStaff() && $booking->user_id != $user->id) {
            abort(403, 'Unauthorized to add visitors to this booking.');
        }

        // Validate input
        $validated = $request->validate([
            'nim' => 'required|string|max:20',
            'nama' => 'required|string|max:100',
        ]);

        // Check for duplicate NIM in this booking
        $exists = $booking->visitors()->where('nim', $validated['nim'])->exists();
        if ($exists) {
            return back()->withErrors([
                'nim' => 'NIM sudah terdaftar pada peminjaman ini.'
            ])->withInput();
        }

        // Create visitor
        $booking->visitors()->create([
            'nim' => $validated['nim'],
            'nama' => $validated['nama'],
        ]);

        return back()->with('success', 'Pengunjung berhasil ditambahkan.');
    }

    /**
     * Remove a visitor from a booking
     */
    public function removeVisitor(Booking $booking, $visitorId)
    {
        // Check authorization
        $user = auth()->user();
        if (!$user->isSuperadmin() && !$user->isStaff() && $booking->user_id != $user->id) {
            abort(403, 'Unauthorized to remove visitors from this booking.');
        }

        // Find and delete the visitor
        $visitor = $booking->visitors()->findOrFail($visitorId);
        $visitor->delete();

        return back()->with('success', 'Pengunjung berhasil dihapus.');
    }
}
