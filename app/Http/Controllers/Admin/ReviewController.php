<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // Show all reviews
    public function index()
    {
        $reviews = Review::with(['order', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    // View single review
    public function show($id)
    {
        $review = Review::with(['order', 'user', 'order.product'])
            ->findOrFail($id);

        return view('admin.reviews.show', compact('review'));
    }
}
