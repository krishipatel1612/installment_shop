<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    /**
     * Show list of all orders
     */
    public function index()
    {
        $orders = Order::orderBy('created_at', 'desc')->get();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show details of a single order including EMI schedule
     */
    public function show($id)
    {
        $order = Order::with('emiSchedules')->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }
}
