<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Asset;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of tickets
     */
    public function index(Request $request)
    {
        $query = Ticket::with(['user', 'asset.lab']);

        $user = auth()->user();

        // Users only see their own tickets
        if ($user->isUser()) {
            $query->where('user_id', $user->id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Ticket statistics for pie chart
        $openTickets = Ticket::where('status', 'open')->count();
        $resolvedTickets = Ticket::where('status', 'resolved')->count();

        return view('tickets.index', compact('tickets', 'openTickets', 'resolvedTickets'));
    }

    /**
     * Show the form for creating a new ticket
     */
    public function create()
    {
        // Staff cannot create tickets
        if (auth()->user()->isStaff()) {
            abort(403, 'Staff tidak dapat membuat laporan masalah baru.');
        }
        
        $assets = Asset::with('lab')->orderBy('code')->get();
        return view('tickets.create', compact('assets'));
    }

    /**
     * Store a newly created ticket
     */
    public function store(Request $request)
    {
        // Staff cannot create tickets
        if (auth()->user()->isStaff()) {
            abort(403, 'Staff tidak dapat membuat laporan masalah baru.');
        }
        
        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'issue_description' => 'required|string|max:2000',
        ]);

        Ticket::create([
            'user_id' => auth()->id(),
            'asset_id' => $validated['asset_id'],
            'issue_description' => $validated['issue_description'],
            'status' => 'open',
        ]);

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket created successfully. Our staff will review it soon.');
    }

    /**
     * Display the specified ticket
     */
    public function show(Ticket $ticket)
    {
        // Authorization check
        if (auth()->user()->isUser() && $ticket->user_id != auth()->id()) {
            abort(403);
        }

        $ticket->load(['user', 'asset.lab']);
        return view('tickets.show', compact('ticket'));
    }

    /**
     * Update ticket status (Staff only)
     */
    public function updateStatus(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,resolved',
        ]);

        $ticket->update([
            'status' => $validated['status'],
        ]);

        $message = $validated['status'] === 'resolved' 
            ? 'Ticket marked as resolved.' 
            : 'Ticket reopened.';

        return back()->with('success', $message);
    }

    /**
     * Remove the specified ticket
     */
    public function destroy(Ticket $ticket)
    {
        // Only allow user to delete their own tickets if status is open
        if ($ticket->user_id != auth()->id() || $ticket->status != 'open') {
            abort(403);
        }

        $ticket->delete();

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket deleted successfully.');
    }
}
