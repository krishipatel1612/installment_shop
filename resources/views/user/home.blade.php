@extends('layouts.user')

@section('content')

<div class="user-home">

    <!-- HERO SECTION -->
   <center><div class="hero-box mb-5 text-center">
        <h2>Welcome, {{ Auth::user()->name }}</h2>
        <p>Shop products easily with monthly EMI options</p>
        <a href="{{ url('/products') }}" class="btn btn-light">
            Browse All Products
        </a>
    </div></center>

    <!-- FEATURES -->
    <div class="row text-center mb-5">
        <div class="col-md-4">
            <div class="feature-box">
                <h5>📦 Products</h5>
                <p>Multiple categories & brands</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-box">
                <h5>💳 EMI Options</h5>
                <p>3 / 6 / 9 / 12 months EMI</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-box">
                <h5>🛒 Easy Checkout</h5>
                <p>Fast & secure payments</p>
            </div>
        </div>
    </div>

</div>

@endsection
