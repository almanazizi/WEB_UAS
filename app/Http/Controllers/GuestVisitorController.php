<?php

namespace App\Http\Controllers;

use App\Models\GuestVisitor;
use App\Models\Lab;
use Illuminate\Http\Request;

class GuestVisitorController extends Controller
{
    /**
     * Show the guest check-in form (public access)
     */
    public function showCheckInForm()
    {
        $labs = Lab::all();
        return view('guest.check-in', compact('labs'));
    }

    /**
     * Store a new guest visitor (public access)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim' => 'required|string|max:20',
            'nama' => 'required|string|max:100',
            'lab_id' => 'required|exists:labs,id',
            'purpose' => 'required|string|max:500',
        ]);

        $guest = GuestVisitor::create([
            ...$validated,
            'check_in_time' => now(),
        ]);

        return redirect()->route('guest.success')
            ->with('guest', $guest->load('lab'));
    }

    /**
     * Show success page after check-in
     */
    public function success()
    {
        // Get guest data from session
        if (!session()->has('guest')) {
            return redirect()->route('guest.checkin');
        }

        return view('guest.success');
    }

    /**
     * Export guest visitors to CSV
     */
    public function export(Request $request)
    {
        $query = GuestVisitor::with('lab')
            ->orderBy('check_in_time', 'desc');

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('check_in_time', $request->date);
        }

        // Filter by lab
        if ($request->filled('lab_id')) {
            $query->where('lab_id', $request->lab_id);
        }

        $guests = $query->get();

        $filename = 'rekap-pengunjung-' . date('Y-m-d') . '.xls';
        
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $view = view('staff.guests.export_excel', compact('guests'))->render();

        return response($view, 200, $headers);
    }

    /**
     * Display guest visitors list (Staff/Superadmin only)
     */
    public function index(Request $request)
    {
        $query = GuestVisitor::with('lab')
            ->orderBy('check_in_time', 'desc');
        
        // Filter by date (default: today)
        if ($request->filled('date')) {
            $query->whereDate('check_in_time', $request->date);
        } else {
             // If explicit date not provided, default to today for the View, 
             // BUT for Consistency with Export (which might export ALL if no date),
             // existing code defaults to TODAY.
             // Let's keep existing logic: default view is TODAY.
             $query->whereDate('check_in_time', today());
        }

        // Filter by lab
        if ($request->filled('lab_id')) {
            $query->where('lab_id', $request->lab_id);
        }

        $guests = $query->paginate(20);
        $labs = Lab::all();
        
        // Statistics for today (Walk-in + Booking Visitors)
        $walkInToday = GuestVisitor::whereDate('check_in_time', today())->count();
        $bookingToday = \App\Models\BookingVisitor::whereHas('booking', function ($q) {
            $q->whereDate('start_time', today())
              ->where('status', 'approved');
        })->count();
        
        $todayCount = $walkInToday + $bookingToday;

        return view('staff.guests.index', compact('guests', 'labs', 'todayCount'));
    }

    /**
     * Get visitor statistics for chart (AJAX)
     */
    public function getStats(Request $request)
    {
        // Default to last 7 days including today
        $endDate = $request->input('end_date') ? \Carbon\Carbon::parse($request->end_date) : today();
        $startDate = $request->input('start_date') ? \Carbon\Carbon::parse($request->start_date) : today()->subDays(6);

        // Fetch data grouped by date
        // Fetch Guest Visitors grouped by date
        $guestStats = GuestVisitor::whereDate('check_in_time', '>=', $startDate)
            ->whereDate('check_in_time', '<=', $endDate)
            ->selectRaw('DATE(check_in_time) as date, count(*) as count')
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        // Fetch Booking Visitors grouped by date (from approved bookings)
        $bookingStats = \App\Models\BookingVisitor::whereHas('booking', function ($q) use ($startDate, $endDate) {
                $q->whereDate('start_time', '>=', $startDate)
                  ->whereDate('start_time', '<=', $endDate)
                  ->where('status', 'approved');
            })
            ->join('bookings', 'booking_visitors.booking_id', '=', 'bookings.id')
            ->selectRaw('DATE(bookings.start_time) as date, count(*) as count')
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        // Fill in missing dates and sum counts
        $data = [];
        $labels = [];
        $current = $startDate->copy();

        while ($current <= $endDate) {
            $dateStr = $current->format('Y-m-d');
            $labels[] = $current->format('d M'); 
            
            $guestCount = $guestStats[$dateStr]->count ?? 0;
            $bookingCount = $bookingStats[$dateStr]->count ?? 0;
            
            $data[] = $guestCount + $bookingCount;
            $current->addDay();
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
            'total' => array_sum($data),
            'start_date' => $startDate->format('d M Y'),
            'end_date' => $endDate->format('d M Y'),
        ]);
    }
}
