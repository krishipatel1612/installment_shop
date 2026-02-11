<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentVerification;
use App\Models\EmiSchedule;
use App\Models\EmiPayment;
use Illuminate\Http\Request;

class PaymentVerificationController extends Controller
{
    // Show pending verifications
    public function index()
    {
        $pendingVerifications = PaymentVerification::where('status', 'pending')
            ->with(['order', 'emiSchedule', 'user'])
            ->orderBy('submitted_at', 'desc')
            ->paginate(20);

        return view('admin.payments.verification', compact('pendingVerifications'));
    }

    // Verify payment
    public function verify(Request $request, $verificationId)
    {
        $verification = PaymentVerification::findOrFail($verificationId);

        if ($verification->status !== 'pending') {
            return back()->with('error', 'This verification has already been processed.');
        }

        try {
            $emi = $verification->emiSchedule;

            // Mark EMI as paid
            $emi->status = 'Paid';
            $emi->paid_at = now();
            $emi->save();

            // Record payment in EmiPayment
            EmiPayment::create([
                'order_id' => $verification->order_id,
                'emi_schedule_id' => $verification->emi_schedule_id,
                'user_id' => $verification->user_id,
                'amount' => $verification->amount,
                'payment_method' => $verification->payment_method,
                'transaction_id' => $verification->transaction_id,
                'paid_at' => now(),
            ]);

            // Mark verification as verified
            $verification->status = 'verified';
            $verification->verified_at = now();
            $verification->verified_by = auth()->id();
            $verification->save();

            // Check if all EMIs are paid
            $order = $emi->order;
            if ($order->emiSchedules()->where('status', 'Pending')->count() == 0 &&
                $order->emiSchedules()->where('status', 'Overdue')->count() == 0) {
                $order->status = 'paid';
                $order->order_status = 'completed';
                $order->save();
            }

            return back()->with('success', 'Payment verified and recorded successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error verifying payment: ' . $e->getMessage());
        }
    }

    // Reject payment
    public function reject(Request $request, $verificationId)
    {
        try {
            // Get the verification record
            $verification = PaymentVerification::findOrFail($verificationId);

            // Check if already processed
            if ($verification->status !== 'pending') {
                return back()->with('error', 'This payment has already been processed.');
            }

            // Validate rejection reason
            $request->validate([
                'rejection_reason' => 'required|min:5|max:255'
            ], [
                'rejection_reason.required' => 'Please enter rejection reason',
                'rejection_reason.min' => 'Reason must be at least 5 characters'
            ]);

            // Update the verification record
            $verification->status = 'rejected';
            $verification->rejection_reason = $request->rejection_reason;
            $verification->verified_at = now();
            $verification->verified_by = auth()->id();
            $verification->save();

            return back()->with('success', 'Payment rejected successfully!');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'Payment record not found.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
