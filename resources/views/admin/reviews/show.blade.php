@extends('layouts.admin')

@section('content')



<div class="card bg-dark text-white border-secondary">
    <div class="card-body">
        <!-- User Info -->
        <div class="mb-4">
            <h5>Customer Information</h5>
            <p><strong>Name:</strong> {{ $review->user->name }}</p>
            <p><strong>Email:</strong> {{ $review->user->email }}</p>
            <p><strong>Order ID:</strong> #{{ $review->order->id }}</p>
        </div>

        <!-- Product Info -->
        <div class="mb-4">
            <h5>Product</h5>
            <div class="row">
                @if($review->order->product && $review->order->product->image)
                    <div class="col-md-2">
                        <img src="{{ asset('uploads/products/'.$review->order->product->image) }}" alt="{{ $review->order->product_name }}" class="img-fluid rounded">
                    </div>
                @endif
                <div class="col-md-10">
                    <p><strong>Product Name:</strong> {{ $review->order->product_name }}</p>
                    <p><strong>Price:</strong> ₹{{ number_format($review->order->price, 2) }}</p>
                    <p><strong>Order Date:</strong> {{ $review->order->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Review -->
        <div class="mb-4">
            <h5>Review Details</h5>
            <div style="background:#2d3748; padding:15px; border-radius:8px;">
                <p><strong>Rating:</strong></p>
                <div class="text-warning mb-3">
                    @for($i = 0; $i < $review->rating; $i++)
                        ⭐
                    @endfor
                </div>

                <p><strong>Comment:</strong></p>
                <p style="margin:0; white-space: pre-wrap;">{{ $review->comment }}</p>

                <p class="mt-3 text-muted"><strong>Submitted:</strong> {{ $review->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>
    </div>
</div><br>
<a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary mb-3">← Back to Reviews</a>

@endsection
