<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Order;
use Illuminate\Http\Request;
use Auth;

class ReviewController extends Controller
{
    // Submit review for an order
    public function store(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        // Check if user owns this order
        if ($order->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized');
        }

        // Validate
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5|max:500',
        ], [
            'rating.required' => 'Please select a rating',
            'comment.required' => 'Please enter a comment',
            'comment.min' => 'Comment must be at least 5 characters'
        ]);

        // Create or update review
        Review::updateOrCreate(
            ['order_id' => $orderId, 'user_id' => Auth::id()],
            [
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]
        );

        return back()->with('success', 'Review submitted successfully!');
    }
}
