<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lab;
use App\Models\Asset;
use App\Models\Booking;
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

        return view('superadmin.dashboard', compact(
            'totalUsers',
            'totalLabs',
            'totalAssets',
            'totalBookings',
            'pendingBookings',
            'approvedBookings'
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

        return view('staff.dashboard', compact('pendingBookings'));
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
