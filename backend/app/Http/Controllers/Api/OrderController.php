<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    // User's own orders
    public function index(Request $request)
    {
        $orders = Order::with('items.product')
            ->where('user_id', $request->user()->id)
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(10);

        return response()->json($orders);
    }

    // Single order (own)
    public function show(Request $request, $id)
    {
        $order = Order::with('items.product')
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json($order);
    }

    // Place order
    public function store(Request $request)
    {
        $data = $request->validate([
            'items'             => 'required|array|min:1',
            'items.*.product_id'=> 'required|exists:products,id',
            'items.*.quantity_kg'=> 'required|integer|min:1',
            'shipping_address'  => 'nullable|string',
            'shipping_country'  => 'nullable|string',
            'notes'             => 'nullable|string',
        ]);

        $subtotal = 0;
        $itemsData = [];

        foreach ($data['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);
            if ($product->stock_kg < $item['quantity_kg']) {
                return response()->json([
                    'message' => "Insufficient stock for {$product->name}."
                ], 422);
            }
            $lineTotal = $product->price_per_kg * $item['quantity_kg'];
            $subtotal += $lineTotal;
            $itemsData[] = [
                'product_id'    => $product->id,
                'quantity_kg'   => $item['quantity_kg'],
                'price_per_kg'  => $product->price_per_kg,
                'total'         => $lineTotal,
            ];
        }

        $shipping = $subtotal > 1000 ? 0 : 50;
        $total    = $subtotal + $shipping;

        $order = Order::create([
            'user_id'          => $request->user()->id,
            'order_number'     => 'BUN-' . strtoupper(Str::random(8)),
            'status'           => 'pending',
            'subtotal'         => $subtotal,
            'shipping'         => $shipping,
            'total'            => $total,
            'shipping_address' => $data['shipping_address'] ?? null,
            'shipping_country' => $data['shipping_country'] ?? null,
            'notes'            => $data['notes'] ?? null,
        ]);

        foreach ($itemsData as $item) {
            $order->items()->create($item);
            Product::find($item['product_id'])->decrement('stock_kg', $item['quantity_kg']);
        }

        $request->user()->notify(new \App\Notifications\OrderPlaced($order));

        return response()->json($order->load('items.product'), 201);
    }

    // User cancel
    public function updateStatus(Request $request, $id)
    {
        $order = Order::where('user_id', $request->user()->id)->findOrFail($id);
        $request->validate(['status' => 'required|in:cancelled']);
        if (! in_array($order->status, ['pending'])) {
            return response()->json(['message' => 'Order cannot be cancelled at this stage.'], 422);
        }
        $order->update(['status' => 'cancelled']);
        return response()->json($order);
    }

    // Admin: all orders
    public function adminIndex(Request $request)
    {
        $orders = Order::with(['user', 'items.product'])
            ->when($request->status,  fn($q) => $q->where('status', $request->status))
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->latest()
            ->paginate(20);

        return response()->json($orders);
    }

    // Admin: update status
    public function adminUpdateStatus(Request $request, $id)
    {
        $order = Order::with('user')->findOrFail($id);
        $data = $request->validate([
            'status' => 'required|in:pending,approved,processing,shipped,delivered,cancelled'
        ]);

        $timestamps = [
            'approved'  => 'approved_at',
            'shipped'   => 'shipped_at',
            'delivered' => 'delivered_at',
        ];

        $update = ['status' => $data['status']];
        if (isset($timestamps[$data['status']])) {
            $update[$timestamps[$data['status']]] = now();
        }

        $order->update($update);
        $order->user->notify(new \App\Notifications\OrderStatusUpdated($order));

        return response()->json($order);
    }
}