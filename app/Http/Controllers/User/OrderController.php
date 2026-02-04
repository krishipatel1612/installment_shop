<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Auth;

class OrderController extends Controller
{
    // Show all orders of the authenticated user
    public function index()
    {
        $orders = Order::with('emiSchedules')  // eager load EMIs
            ->where('user_id', Auth::id())
            ->get();

        return view('user.orders', compact('orders'));
    }

    // Show details of a single order
    public function show($id)
    {
        $order = Order::with('emiSchedules')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('user.order_detail', compact('order'));
    }

    // Pay single EMI
    public function payEmi($id)
    {
        $emi = \App\Models\EmiSchedule::findOrFail($id);

        if ($emi->status == 'Paid') {
            return back()->with('error', 'This EMI is already paid.');
        }

        $emi->status = 'Paid';
        $emi->paid_at = now();
        $emi->save();

        return back()->with('success', "Order #{$emi->order->id} ,Month {$emi->month_no} EMI paid successfully.");
    }
}
