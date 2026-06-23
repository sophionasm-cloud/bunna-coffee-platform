<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ListingController extends Controller
{
    // Public: Get all approved listings
    public function index()
    {
        $listings = Listing::where('status', 'approved')
            ->orWhere('status', 'pending')
            ->latest()
            ->paginate(12);
        
        return response()->json($listings);
    }

    // Public: Get single listing
    public function show($id)
    {
        $listing = Listing::findOrFail($id);
        return response()->json($listing);
    }

    // Farmer: Create listing
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'origin' => 'required|string|max:255',
            'grade' => 'nullable|string|max:50',
            'process' => 'nullable|string|max:100',
            'quantity_kg' => 'required|integer|min:1',
            'asking_price_per_kg' => 'required|numeric|min:0',
            'harvest_date' => 'nullable|date',
            'image' => 'nullable|image|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('listings', 'public');
        }

        $data['user_id'] = $request->user()->id;
        $data['status'] = 'pending';

        $listing = Listing::create($data);

        return response()->json([
            'message' => 'Listing created successfully!',
            'listing' => $listing
        ], 201);
    }

    // Farmer: Update listing
    public function update(Request $request, $id)
    {
        $listing = Listing::findOrFail($id);
        
        if ($listing->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'origin' => 'sometimes|string|max:255',
            'grade' => 'nullable|string|max:50',
            'process' => 'nullable|string|max:100',
            'quantity_kg' => 'sometimes|integer|min:1',
            'asking_price_per_kg' => 'sometimes|numeric|min:0',
            'harvest_date' => 'nullable|date',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($listing->image) {
                Storage::disk('public')->delete($listing->image);
            }
            $data['image'] = $request->file('image')->store('listings', 'public');
        }

        $listing->update($data);

        return response()->json([
            'message' => 'Listing updated successfully!',
            'listing' => $listing
        ]);
    }

    // Farmer: Delete listing
    public function destroy(Request $request, $id)
    {
        $listing = Listing::findOrFail($id);
        
        if ($listing->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($listing->image) {
            Storage::disk('public')->delete($listing->image);
        }

        $listing->delete();

        return response()->json(['message' => 'Listing deleted successfully.']);
    }

    // Admin: Approve listing
    public function approve($id)
    {
        $listing = Listing::findOrFail($id);
        $listing->update(['status' => 'approved']);
        
        return response()->json([
            'message' => 'Listing approved successfully!',
            'listing' => $listing
        ]);
    }

    // Admin: Reject listing
    public function reject($id)
    {
        $listing = Listing::findOrFail($id);
        $listing->update(['status' => 'draft']);
        
        return response()->json([
            'message' => 'Listing rejected.',
            'listing' => $listing
        ]);
    }
}