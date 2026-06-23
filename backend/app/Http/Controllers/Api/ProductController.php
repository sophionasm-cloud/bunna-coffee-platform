<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Public listing
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->where('is_available', true)
            ->when($request->search,   fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->when($request->category, fn($q) => $q->where('category_id', $request->category))
            ->when($request->grade,    fn($q) => $q->where('grade', $request->grade))
            ->when($request->origin,   fn($q) => $q->where('origin', 'like', "%{$request->origin}%"))
            ->orderByDesc($request->sort === 'price_asc' ? 'price_per_kg' : 'created_at')
            ->paginate(12);

        return response()->json($products);
    }

    // Public single product
    public function show($id)
    {
        $product = Product::with(['category', 'user'])->findOrFail($id);
        return response()->json($product);
    }

    // Admin full listing
    public function adminIndex(Request $request)
    {
        $products = Product::with(['category', 'user'])
            ->withTrashed()
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(20);

        return response()->json($products);
    }

    // Admin create
    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id'     => 'required|exists:categories,id',
            'name'            => 'required|string|max:255',
            'name_am'         => 'nullable|string|max:255',
            'description'     => 'nullable|string',
            'description_am'  => 'nullable|string',
            'origin'          => 'required|string|max:255',
            'grade'           => 'nullable|string|max:50',
            'process'         => 'nullable|string|max:100',
            'price_per_kg'    => 'required|numeric|min:0',
            'stock_kg'        => 'required|integer|min:0',
            'is_available'    => 'boolean',
            'is_featured'     => 'boolean',
            'image'           => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $data['user_id'] = $request->user()->id;
        $product = Product::create($data);

        return response()->json($product, 201);
    }

    // Admin update
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $data = $request->validate([
            'name'          => 'sometimes|string|max:255',
            'name_am'       => 'nullable|string|max:255',
            'description'   => 'nullable|string',
            'origin'        => 'sometimes|string|max:255',
            'grade'         => 'nullable|string|max:50',
            'process'       => 'nullable|string|max:100',
            'price_per_kg'  => 'sometimes|numeric|min:0',
            'stock_kg'      => 'sometimes|integer|min:0',
            'is_available'  => 'boolean',
            'is_featured'   => 'boolean',
            'image'         => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) Storage::disk('public')->delete($product->image);
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return response()->json($product);
    }

    // Admin delete
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Product deleted.']);
    }
}