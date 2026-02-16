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

            // Default values
            $interestRate = 0;
            $totalWithInterest = $originalPrice;
            $monthlyAmount = $originalPrice;

            // Calculate monthly amount with interest (if EMI)
            if ($months > 1) {

                // Find the installment plan to get interest rate
                $plan = InstallmentPlan::where('months', $months)
                    ->where('product_id', $item['id'] ?? null)
                    ->first();

                $interestRate = $plan ? ($plan->interest_rate ?? 0) : 0;

                // Calculate total with interest
                $totalWithInterest = InstallmentPlan::calculateTotalWithInterest($originalPrice, $interestRate);

                // Monthly EMI (keep 2 decimal)
                $monthlyAmount = round($totalWithInterest / $months, 2);
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

                // To keep total exact (last month adjustment)
                $totalPaid = 0;

                for ($i = 1; $i <= $months; $i++) {

                    // Default EMI amount
                    $emiAmount = $monthlyAmount;

                    // Last month: adjust to match totalWithInterest exactly
                    if ($i == $months) {
                        $emiAmount = round($totalWithInterest - $totalPaid, 2);
                    }

                    $totalPaid += $emiAmount;

                    EmiSchedule::create([
                        'order_id' => $order->id,
                        'month_no' => $i,
                        'amount' => $emiAmount,
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
                    'amount' => round($originalPrice, 2),
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
