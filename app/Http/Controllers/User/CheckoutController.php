<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\EmiSchedule;
use App\Models\InstallmentPlan;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $cart = session()->get('cart');

        if (!$cart) {
            return redirect('/cart')->with('error', 'Your cart is empty.');
        }

        foreach ($cart as $item) {

            // Get months
            $months = (int)($item['months'] ?? 1);
            $originalPrice = (float)$item['price'];

            // Calculate monthly amount with interest (if EMI)
            if ($months > 1) {
                // Find the installment plan to get interest rate
                $plan = InstallmentPlan::where('months', $months)
                    ->where('product_id', $item['id'] ?? null)
                    ->first();
                
                $interestRate = $plan ? ($plan->interest_rate ?? 0) : 0;
                
                // Calculate total with interest
                $totalWithInterest = InstallmentPlan::calculateTotalWithInterest($originalPrice, $interestRate);
                $monthlyAmount = (int)ceil($totalWithInterest / $months);
            } else {
                // Direct buy (full payment) - no interest
                $monthlyAmount = (int)$originalPrice;
            }

            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'product_id' => $item['id'] ?? null,
                'product_name' => $item['name'],
                'price' => $originalPrice,
                'months' => $months,
                'monthly_amount' => $monthlyAmount,
                'status' => 'pending',
            ]);

            // Generate EMI schedules
            if ($months > 1) {
                for ($i = 1; $i <= $months; $i++) {
                    EmiSchedule::create([
                        'order_id' => $order->id,
                        'month_no' => $i,
                        'amount' => $monthlyAmount,
                        'due_date' => now()->addMonths($i),
                        'status' => 'Pending',
                        'penalty_amount' => 0,
                    ]);
                }
            } else {
                // Direct buy (full payment)
                EmiSchedule::create([
                    'order_id' => $order->id,
                    'month_no' => 1,
                    'amount' => $originalPrice,
                    'due_date' => now(),
                    'status' => 'Paid',
                    'paid_at' => now(),
                    'penalty_amount' => 0,
                ]);
            }
        }

        // Clear cart
        session()->forget('cart');

        return redirect('/my-orders')->with('success', 'Order placed successfully!');
    }
}
