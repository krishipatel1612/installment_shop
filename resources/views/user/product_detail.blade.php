@extends('layouts.user')

@section('content')

<div class="row product-detail-wrapper">

    <div class="col-md-12 product-detail-col">

        <div class="card product-detail-card">

            <div class="card-body">

                <div class="row product-detail-row">

                    <!-- LEFT IMAGE -->
                    <div class="col-md-4 product-detail-image">
                        <img src="{{ asset('uploads/products/'.$product->image) }}"
                             class="product-detail-img">
                    </div>

                    <!-- RIGHT DETAILS -->
                    <div class="col-md-8 product-detail-content">

                        <h3>{{ $product->name }}</h3>
                        <h4>{{$product->description}}</h4>
                        <h4 class="text-success">
                            ₹ {{ number_format($product->price) }}
                        </h4>

                        <p class="fw-bold mt-2">
                            Select EMI Option (Or Direct Buy)
                        </p>

                        <form method="POST" action="{{ route('cart.add') }}">
                            @csrf

                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            @foreach($product->installmentPlans as $emi)
                                <label class="emi-option">
                                    <input type="radio" name="emi_id" value="{{ $emi->id }}" required>
                                    {{ $emi->months }} Months —
                                    ₹ {{ number_format($emi->monthly_amount, 2) }} / month
                                </label>
                            @endforeach


                            <!-- DIRECT BUY -->
                            <label class="emi-option direct-buy">
                                <input type="radio" name="emi_id" value="0" required>
                                Direct Buy — Pay ₹ {{ number_format($product->price) }} now
                            </label>

                            <div class="product-actions">
                                <button class="btn btn-success">
                                    Add to Cart
                                </button>

                                <button type="button"
                                        class="btn btn-secondary"
                                        onclick="window.history.back()">
                                    Back to Products
                                </button>
                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
