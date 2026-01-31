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
     * Staff Dashboard - Show 4 widgets (Opsi A redesign)
     */
    public function staff()
    {
        // Widget 1: Jadwal Hari Ini (today's bookings - all statuses)
        $todaySchedule = Booking::with(['user', 'lab'])
            ->whereDate('start_time', today())
            ->whereIn('status', ['pending', 'approved'])
            ->orderBy('start_time')
            ->limit(4)
            ->get();
        
        // Widget 2: Tiket Perbaikan Terbaru (recent tickets, all statuses)
        $recentTickets = Ticket::with('asset.lab')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();
        
        // Widget 3: Status Inventaris (pie chart data)
        $inventoryStats = [
            'good' => Asset::where('condition', 'good')->count(),
            'maintenance' => Asset::where('condition', 'maintenance')->count(),
            'bad' => Asset::where('condition', 'bad')->count(),
        ];
        
        // Widget 4: Kunjungan Mingguan (line chart - last 10 days)
        $endDate = today();
        $startDate = today()->subDays(9); // 10 days total including today
        
        // Get guest visitors grouped by date
        $guestStats = GuestVisitor::whereDate('check_in_time', '>=', $startDate)
            ->whereDate('check_in_time', '<=', $endDate)
            ->selectRaw('DATE(check_in_time) as date, count(*) as count')
            ->groupBy('date')
            ->get()
            ->keyBy('date');
        
        // Get booking visitors grouped by date
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
        
        // Prepare chart data (fill missing dates)
        $visitChartLabels = [];
        $visitChartData = [];
        $current = $startDate->copy();
        
        while ($current <= $endDate) {
            $dateStr = $current->format('Y-m-d');
            $visitChartLabels[] = $current->format('d M');
            
            $guestCount = $guestStats[$dateStr]->count ?? 0;
            $bookingCount = $bookingStats[$dateStr]->count ?? 0;
            
            $visitChartData[] = $guestCount + $bookingCount;
            $current->addDay();
        }

        return view('staff.dashboard', compact(
            'todaySchedule',
            'recentTickets', 
            'inventoryStats',
            'visitChartLabels',
            'visitChartData'
        ));
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
