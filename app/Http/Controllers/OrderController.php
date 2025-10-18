<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $orders = Order::where('user_id', $user->id)
                        ->with(['products'])
                        ->orderBy('created_at', 'desc')
                        ->paginate(12);

        return view('orderHistory', [
            'orders' => $orders,
        ]);
    }

    public function show($id)
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        $order = Order::with('products')->find($id);
        if (!$order) abort(404, 'Order not found');

        if ($order->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        return view('orderDetails', ['order' => $order, 'user' => $user]);
    }
}
