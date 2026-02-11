@extends('layouts.user')

@section('content')

<div class="container mt-4">
    <!-- Back Button -->
    <a href="{{ route('user.orders') }}" class="btn btn-secondary mb-3">← Back to Orders</a>

    <!-- Order Header -->
    <div class="card mb-4 bg-dark text-white border-secondary">
        <div class="card-body">
            <h4>Order #{{ $order->id }}</h4>
            <p class="text-muted mb-2">
                <strong>Status:</strong> 
                @if($order->status === 'paid')
                    <span class="badge bg-success">Completed</span>
                @else
                    <span class="badge bg-warning">In Progress</span>
                @endif
            </p>
        </div>
    </div>

    <!-- Product Details -->
    <div class="card mb-4 bg-dark text-white border-secondary">
        <div class="card-body">
            <div class="row">
                <!-- Product Image -->
                <div class="col-md-3">
                    @if($order->product && $order->product->image)
                       <img src="{{ asset('uploads/products/'.$order->product->image) }}" class="img-fluid rounded">
                    @else
                        <div style="width:100%; height:250px; background:#2d3748; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                            <span class="text-muted">No Image</span>
                        </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="col-md-9">
                    <h5>{{ $order->product_name }}</h5>
                    <p class="text-muted">Product ID: #{{ $order->product_id }}</p>
                    
                    <table class="table table-dark">
                        <tbody>
                            <tr>
                                <td><strong>Total Price</strong></td>
                                <td>₹{{ number_format($order->price, 2) }}</td>
                            </tr>
                            <tr>
                                <td><strong>EMI Duration</strong></td>
                                <td>{{ $order->months }} months</td>
                            </tr>
                            <tr>
                                <td><strong>Monthly Amount</strong></td>
                                <td>₹{{ number_format($order->monthly_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Total Paid</strong></td>
                                <td>₹{{ number_format($order->totalPaid(), 2) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Remaining</strong></td>
                                <td>₹{{ number_format($order->totalPending(), 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Section -->
    <div class="card bg-dark text-white border-secondary">
        <div class="card-header border-secondary">
            <h5 class="mb-0">Review & Comment</h5>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Review Form -->
            <form method="POST" action="{{ route('user.review.store', $order->id) }}">
                @csrf

                <!-- Star Rating -->
                <div class="mb-3">
                    <label class="form-label"><strong>Rating</strong></label>
                    <div class="d-flex gap-2">
                        @for($i = 1; $i <= 5; $i++)
                            <input type="radio" name="rating" id="rating{{ $i }}" value="{{ $i }}" 
                                @if(old('rating', $order->reviews->first()?->rating ?? 0) == $i) checked @endif>
                            <label for="rating{{ $i }}" style="cursor:pointer; font-size:24px; margin:0;">
                                ⭐
                            </label>
                        @endfor
                    </div>
                </div>

                <!-- Comment -->
                <div class="mb-3">
                    <label for="comment" class="form-label"><strong>Your Comment</strong></label>
                    <textarea name="comment" id="comment" class="form-control" rows="5" 
                              placeholder="Share your experience with this product..."
                              style="background:#2d3748; color:#e2e8f0; border:1px solid #4a5568;">{{ old('comment', $order->reviews->first()?->comment ?? '') }}</textarea>
                    <small class="text-muted d-block mt-1">Minimum 5 characters • Maximum 500 characters</small>
                </div>

                <button type="submit" class="btn btn-primary">Submit Review</button>
            </form>

            <!-- Existing Review -->
            @if($order->reviews->count() > 0)
                <hr>
                <h6 class="mt-4 mb-3">Your Review</h6>
                @foreach($order->reviews as $review)
                    <div style="background:#2d3748; padding:15px; border-radius:8px;">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <strong>{{ $review->user->name }}</strong>
                                <div class="text-warning">
                                    @for($i = 0; $i < $review->rating; $i++)
                                        ⭐
                                    @endfor
                                </div>
                            </div>
                            <small class="text-muted">{{ $review->created_at->format('d M Y H:i') }}</small>
                        </div>
                        <p style="margin:0;">{{ $review->comment }}</p>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

@endsection
