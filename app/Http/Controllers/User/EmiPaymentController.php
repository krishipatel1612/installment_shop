<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\EmiSchedule;
use App\Models\PaymentVerification;
use Illuminate\Support\Facades\Auth;

class EmiPaymentController extends Controller
{
    // Show EMI dashboard
    public function index()
    {
        $user = auth()->user();
        $orders = Order::with('emiSchedules')
            ->where('user_id', $user->id)
            ->get();

        // Extract all EMI schedules from orders
        $emis = [];
        foreach ($orders as $order) {
            foreach ($order->emiSchedules as $emi) {
                // Check if overdue and update penalty
                $emi->markAsOverdue();
                $emis[] = $emi;
            }
        }

        if(empty($emis)){
            return view('user.emi_dashboard', ['emis' => []])
                ->with('message', 'No EMI records found for this user');
        }

        return view('user.emi_dashboard', compact('emis'));
    }

    // Show payment form for EMI
    public function paymentForm($id)
    {
        $emi = EmiSchedule::with('order')->find($id);

        if (!$emi) {
            return back()->with('error', 'EMI record not found.');
        }

        if ($emi->status === 'Paid') {
            return back()->with('info', 'This EMI is already paid.');
        }

        return view('user.emi_pay_form', compact('emi'));
    }

    // Submit payment for verification
    public function submitPayment(Request $request, $id)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,bank_transfer,upi,online',
            'transaction_id' => 'nullable|string|max:100'
        ], [
            'payment_method.required' => 'Please select a payment method',
            'payment_method.in' => 'Invalid payment method selected',
            'transaction_id.max' => 'Transaction ID cannot exceed 100 characters'
        ]);

        $emi = EmiSchedule::with('order')->find($id);

        if (!$emi) {
            return back()->with('error', 'EMI record not found.');
        }

        if ($emi->status === 'Paid') {
            return back()->with('info', 'This EMI is already paid.');
        }

        $user = Auth::user();

        // Create payment verification record (PENDING)
        $verification = PaymentVerification::create([
            'order_id' => $emi->order_id,
            'emi_schedule_id' => $emi->id,
            'user_id' => $user->id,
            'amount' => $emi->total_due,
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        return redirect()->route('user.orders')
            ->with('success', 'Payment submitted for verification. Please wait for admin approval.');
    }

    // Admin: Verify payment
    public function verifyPayment($verificationId)
    {
        $verification = PaymentVerification::find($verificationId);

        if (!$verification) {
            return back()->with('error', 'Payment verification record not found.');
        }

        $emi = $verification->emiSchedule;

        // Mark EMI as paid
        $emi->status = 'Paid';
        $emi->paid_at = now();
        $emi->save();

        // Mark verification as verified
        $verification->status = 'verified';
        $verification->verified_at = now();
        $verification->verified_by = auth()->id();
        $verification->save();

        // Record payment in EmiPayment table
        $emiPayment = \App\Models\EmiPayment::create([
            'order_id' => $emi->order_id,
            'emi_schedule_id' => $emi->id,
            'user_id' => $verification->user_id,
            'amount' => $verification->amount,
            'payment_method' => $verification->payment_method,
            'transaction_id' => $verification->transaction_id,
            'paid_at' => now(),
        ]);

        // Check if all EMIs are paid
        $order = $emi->order;
        if ($order->emiSchedules()->where('status', 'Pending')->count() == 0) {
            $order->status = 'paid';
            $order->order_status = 'completed';
            $order->save();
        }

        return back()->with('success', 'Payment verified and recorded successfully.');
    }

    // Admin: Reject payment
    public function rejectPayment(Request $request, $verificationId)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:255',
        ]);

        $verification = PaymentVerification::find($verificationId);

        if (!$verification) {
            return back()->with('error', 'Payment verification record not found.');
        }

        $verification->status = 'rejected';
        $verification->rejection_reason = $request->rejection_reason;
        $verification->verified_at = now();
        $verification->verified_by = auth()->id();
        $verification->save();

        return back()->with('success', 'Payment rejected. User can resubmit.');
    }

    // Legacy: Direct payment (kept for backward compatibility)
    public function emiPayment(Request $request, $id)
    {
        $emi = EmiSchedule::with('order')->find($id);

        if (!$emi) {
            return back()->with('error', 'EMI record not found.');
        }

        $order = $emi->order;
        if (!$order) {
            return back()->with('error', 'Related order not found.');
        }

        // Mark EMI as paid
        $emi->status = 'Paid';
        $emi->paid_at = now();
        $emi->save();

        // Update order if all EMIs paid
        if ($order->emiSchedules()->where('status', 'Pending')->count() == 0) {
            $order->status = 'paid';
            $order->order_status = 'completed';
            $order->save();
        }

        return back()->with('success', 'EMI paid successfully.');
    }
}

