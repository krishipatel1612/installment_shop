@extends('layouts.user')

@section('content')
<div class="container mt-4">
    <h3 class="text-white mb-3">Confirm EMI Payment</h3>

    <form method="POST" action="{{ route('user.emi.pay.store', $emi->id) }}">
        @csrf
        <p class="text-white">Pay ₹{{ $emi->total_due }} for this EMI?</p>
        <button type="submit" class="btn btn-primary">Confirm Pay</button>
        <p class="text-white">EMI ID: {{ $emi->id }}</p>
<p class="text-white">Route: {{ route('user.emi.pay.store', $emi->id) }}</p>

        <a href="{{ route('user.orders') }}" class="btn btn-light">Cancel</a>
    </form>
</div>
@endsection
