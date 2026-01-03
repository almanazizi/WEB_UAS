<?php

namespace App\Http\Controllers;

use App\Models\Lab;
use Illuminate\Http\Request;

class LabController extends Controller
{
    /**
     * Display a listing of labs
     */
    public function index()
    {
        $labs = Lab::withCount(['assets', 'bookings'])
            ->orderBy('name')
            ->paginate(10);
        
        return view('labs.index', compact('labs'));
    }

    /**
     * Show the form for creating a new lab
     */
    public function create()
    {
        return view('labs.create');
    }

    /**
     * Store a newly created lab
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:1000',
            'location' => 'required|string|max:255',
        ]);

        Lab::create($validated);

        return redirect()->route('labs.index')
            ->with('success', 'Lab created successfully.');
    }

    /**
     * Display the specified lab
     */
    public function show(Lab $lab)
    {
        $lab->loadCount(['assets', 'bookings']);
        $lab->load(['assets' => function($query) {
            $query->orderBy('code');
        }]);
        
        return view('labs.show', compact('lab'));
    }

    /**
     * Show the form for editing the specified lab
     */
    public function edit(Lab $lab)
    {
        return view('labs.edit', compact('lab'));
    }

    /**
     * Update the specified lab
     */
    public function update(Request $request, Lab $lab)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:1000',
            'location' => 'required|string|max:255',
        ]);

        $lab->update($validated);

        return redirect()->route('labs.index')
            ->with('success', 'Lab updated successfully.');
    }

    /**
     * Remove the specified lab
     */
    public function destroy(Lab $lab)
    {
        // Check if lab has bookings
        if ($lab->bookings()->count() > 0) {
            return back()->withErrors([
                'delete' => 'Cannot delete lab with existing bookings. Please delete bookings first.'
            ]);
        }

        $lab->delete();

        return redirect()->route('labs.index')
            ->with('success', 'Lab deleted successfully.');
    }
}
