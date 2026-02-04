<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\EmiSchedule;
use Auth;

class CheckoutController extends Controller
{
    /**
     * PROCESS CHECKOUT & CREATE ORDERS & EMI
     */
    public function store(Request $request)
    {
        $cart = session()->get('cart');

        if (!$cart) {
            return redirect('/cart')->with('error', 'Your cart is empty.');
        }

        foreach ($cart as $item) {

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'product_name' => $item['name'],
                'price' => $item['price'],
                'months' => $item['months'],
                'monthly_amount' => $item['monthly_amount'],
            ]);

            // Generate EMI schedules
            if($item['months'] > 1){
                for ($i = 1; $i <= $item['months']; $i++) {
                    EmiSchedule::create([
                        'order_id' => $order->id,
                        'month_no' => $i,
                        'amount' => $item['monthly_amount'],
                        'status' => 'Pending',
                    ]);
                }
            } else {
                // Direct Buy – mark as Paid immediately
                EmiSchedule::create([
                    'order_id' => $order->id,
                    'month_no' => 1,
                    'amount' => $item['price'],
                    'status' => 'Paid',
                    'paid_at' => now(),
                ]);
            }
        }

        // Clear cart
        session()->forget('cart');

        return redirect('/my-orders')->with('success', 'Order placed successfully!');
    }
}
