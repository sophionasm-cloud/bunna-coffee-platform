<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Message;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function stats()
    {
        return response()->json([
            'users' => [
                'total'     => User::count(),
                'farmers'   => User::where('role', 'farmer')->count(),
                'traders'   => User::where('role', 'trader')->count(),
                'investors' => User::where('role', 'investor')->count(),
                'customers' => User::where('role', 'customer')->count(),
            ],
            'orders' => [
                'total'      => Order::count(),
                'pending'    => Order::where('status', 'pending')->count(),
                'processing' => Order::where('status', 'processing')->count(),
                'completed'  => Order::where('status', 'delivered')->count(),
                'cancelled'  => Order::where('status', 'cancelled')->count(),
                'revenue'    => Order::where('status', 'delivered')->sum('total'),
            ],
            'products' => [
                'total'     => Product::count(),
                'available' => Product::where('is_available', true)->count(),
                'featured'  => Product::where('is_featured', true)->count(),
            ],
            'messages' => [
                'total'  => Message::count(),
                'unread' => Message::where('is_read', false)->count(),
            ],
        ]);
    }

    public function users(Request $request)
    {
        $users = User::when($request->role,   fn($q) => $q->where('role', $request->role))
                     ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")
                                                          ->orWhere('email', 'like', "%{$request->search}%"))
                     ->latest()
                     ->paginate(20);

        return response()->json($users);
    }

    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate(['role' => 'required|in:admin,farmer,trader,investor,customer']);
        $user->update(['role' => $request->role]);
        return response()->json($user);
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted.']);
    }

    public function toggleUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => ! $user->is_active]);
        return response()->json($user);
    }
}