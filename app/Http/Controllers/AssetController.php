<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Lab;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    /**
     * Display a listing of assets
     */
    public function index(Request $request)
    {
        $query = Asset::with('lab');

        // Filter by lab
        if ($request->filled('lab_id')) {
            $query->where('lab_id', $request->lab_id);
        }

        // Filter by condition
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        $assets = $query->orderBy('code')->paginate(15);
        $labs = Lab::orderBy('name')->get();

        return view('assets.index', compact('assets', 'labs'));
    }

    /**
     * Show the form for creating a new asset
     */
    public function create()
    {
        $labs = Lab::orderBy('name')->get();
        return view('assets.create', compact('labs'));
    }

    /**
     * Store a newly created asset
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lab_id' => 'required|exists:labs,id',
            'code' => 'required|string|max:50|unique:assets,code',
            'name' => 'required|string|max:255',
            'spec' => 'nullable|string',
            'condition' => 'required|in:good,bad',
        ]);

        Asset::create($validated);

        return redirect()->route('assets.index')
            ->with('success', 'Asset created successfully.');
    }

    /**
     * Display the specified asset
     */
    public function show(Asset $asset)
    {
        $asset->load(['lab', 'tickets' => function($query) {
            $query->with('user')->latest();
        }]);

        return view('assets.show', compact('asset'));
    }

    /**
     * Show the form for editing the specified asset
     */
    public function edit(Asset $asset)
    {
        $labs = Lab::orderBy('name')->get();
        return view('assets.edit', compact('asset', 'labs'));
    }

    /**
     * Update the specified asset
     */
    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'lab_id' => 'required|exists:labs,id',
            'code' => 'required|string|max:50|unique:assets,code,' . $asset->id,
            'name' => 'required|string|max:255',
            'spec' => 'nullable|string',
            'condition' => 'required|in:good,bad',
        ]);

        $asset->update($validated);

        return redirect()->route('assets.index')
            ->with('success', 'Asset updated successfully.');
    }

    /**
     * Remove the specified asset
     */
    public function destroy(Asset $asset)
    {
        // Check if asset has open tickets
        if ($asset->tickets()->where('status', 'open')->count() > 0) {
            return back()->withErrors([
                'delete' => 'Cannot delete asset with open tickets. Please resolve tickets first.'
            ]);
        }

        $asset->delete();

        return redirect()->route('assets.index')
            ->with('success', 'Asset deleted successfully.');
    }
}
