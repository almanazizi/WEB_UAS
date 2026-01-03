<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lab;
use App\Models\Asset;
use App\Models\Booking;
use App\Models\Ticket;
use App\Models\GuestVisitor;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Superadmin Dashboard - Show stats cards
     */
    public function superadmin()
    {
        $totalUsers = User::count();
        $totalLabs = Lab::count();
        $totalAssets = Asset::count();
        $totalBookings = Booking::count();
        $pendingBookings = Booking::pending()->count();
        $approvedBookings = Booking::approved()->count();
        
        // Ticket statistics for pie chart
        $openTickets = Ticket::where('status', 'open')->count();
        $resolvedTickets = Ticket::where('status', 'resolved')->count();

        return view('superadmin.dashboard', compact(
            'totalUsers',
            'totalLabs',
            'totalAssets',
            'totalBookings',
            'pendingBookings',
            'approvedBookings',
            'openTickets',
            'resolvedTickets'
        ));
    }

    /**
     * Staff Dashboard - Show pending bookings table
     */
    public function staff()
    {
        $pendingBookings = Booking::with(['user', 'lab'])
            ->pending()
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Ticket statistics for pie chart
        $openTickets = Ticket::where('status', 'open')->count();
        $resolvedTickets = Ticket::where('status', 'resolved')->count();
        
        // Guest visitor statistics
        // Guest visitor statistics (Walk-in)
        $walkInVisitors = GuestVisitor::with('lab')
            ->whereDate('check_in_time', today())
            ->get();
            
        // Booking visitor statistics (Approved bookings today)
        $bookingVisitors = \App\Models\BookingVisitor::whereHas('booking', function($q) {
                $q->whereDate('start_time', today())
                  ->where('status', 'approved');
            })
            ->with(['booking.lab'])
            ->get();
            
        $todayGuestsCount = $walkInVisitors->count() + $bookingVisitors->count();
        
        // Merge and group by lab for breakdown
        $guestsByLab = collect();
        
        // Process walk-ins
        $walkInVisitors->groupBy('lab_id')->each(function($items, $labId) use ($guestsByLab) {
            $guestsByLab->put($labId, [
                'lab' => $items->first()->lab,
                'count' => $items->count()
            ]);
        });
        
        // Process booking visitors
        $bookingVisitors->groupBy('booking.lab_id')->each(function($items, $labId) use ($guestsByLab) {
            $lab = $items->first()->booking->lab;
            if ($guestsByLab->has($labId)) {
                $current = $guestsByLab->get($labId);
                $guestsByLab->put($labId, [
                    'lab' => $lab,
                    'count' => $current['count'] + $items->count()
                ]);
            } else {
                $guestsByLab->put($labId, [
                    'lab' => $lab,
                    'count' => $items->count()
                ]);
            }
        });

        return view('staff.dashboard', compact('pendingBookings', 'openTickets', 'resolvedTickets', 'todayGuestsCount', 'guestsByLab'));
    }

    /**
     * User Dashboard - Show active bookings
     */
    public function user()
    {
        $myBookings = Booking::with('lab')
            ->where('user_id', auth()->id())
            ->where('status', '!=', 'rejected')
            ->orderBy('start_time', 'asc')
            ->get();

        return view('user.dashboard', compact('myBookings'));
    }
}
