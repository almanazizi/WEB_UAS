<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Lab;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PublicScheduleController extends Controller
{
    /**
     * Display the public schedule page
     */
    public function index(Request $request)
    {
        $labs = Lab::orderBy('name')->get();
        
        // Default to today
        $date = $request->get('date', now()->format('Y-m-d'));
        $labId = $request->get('lab_id');
        $view = $request->get('view', 'table'); // table, calendar, or lab
        
        $bookings = $this->getBookingsQuery($date, $labId)->get();
        
        return view('schedule.index', compact('labs', 'bookings', 'date', 'labId', 'view'));
    }
    
    /**
     * Get bookings data (for AJAX or initial load)
     */
    public function getBookings(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));
        $labId = $request->get('lab_id');
        $startDate = $request->get('start_date', $date);
        $endDate = $request->get('end_date', $date);
        
        $bookings = $this->getBookingsQuery($startDate, $endDate, $labId)->get();
        
        return response()->json([
            'bookings' => $bookings,
            'available_slots' => $this->getAvailableSlots($labId, $date)
        ]);
    }
    
    /**
     * Build the bookings query
     */
    private function getBookingsQuery($startDate, $endDate = null, $labId = null)
    {
        $endDate = $endDate ?? $startDate;
        
        $query = Booking::with(['user', 'lab'])
            ->whereIn('status', ['approved', 'pending']) // Show both approved and pending
            ->whereDate('start_time', '>=', $startDate)
            ->whereDate('start_time', '<=', $endDate);
        
        if ($labId) {
            $query->where('lab_id', $labId);
        }
        
        return $query->orderBy('start_time');
    }
    
    /**
     * Calculate available time slots for a specific lab and date
     */
    private function getAvailableSlots($labId = null, $date = null)
    {
        if (!$labId || !$date) {
            return [];
        }
        
        // Operating hours: 8:00 - 18:00
        $operatingStart = Carbon::parse($date . ' 08:00:00');
        $operatingEnd = Carbon::parse($date . ' 18:00:00');
        
        // Get all approved bookings for this lab on this date
        $bookings = Booking::where('lab_id', $labId)
            ->where('status', 'approved')
            ->whereDate('start_time', $date)
            ->orderBy('start_time')
            ->get(['start_time', 'end_time']);
        
        $availableSlots = [];
        $currentTime = $operatingStart->copy();
        
        foreach ($bookings as $booking) {
            $bookingStart = Carbon::parse($booking->start_time);
            $bookingEnd = Carbon::parse($booking->end_time);
            
            // If there's a gap before this booking
            if ($currentTime->lt($bookingStart)) {
                $availableSlots[] = [
                    'start' => $currentTime->format('H:i'),
                    'end' => $bookingStart->format('H:i'),
                ];
            }
            
            // Move current time to the end of this booking
            $currentTime = $bookingEnd->copy();
        }
        
        // Check if there's time left after the last booking
        if ($currentTime->lt($operatingEnd)) {
            $availableSlots[] = [
                'start' => $currentTime->format('H:i'),
                'end' => $operatingEnd->format('H:i'),
            ];
        }
        
        return $availableSlots;
    }
}
