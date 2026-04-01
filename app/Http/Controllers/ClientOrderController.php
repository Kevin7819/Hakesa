<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ClientOrderController extends Controller
{
    public function index(): View
    {
        $orders = Auth::user()->orders()->withCount('items')->latest()->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('items.product');

        return view('orders.show', compact('order'));
    }
}
