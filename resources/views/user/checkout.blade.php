@extends('layouts.user')

@section('content')
<div class="container mt-4">
    <h4>Checkout</h4>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf

        <table class="table table-bordered table-striped mt-3">
            <thead class="table-light">
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>EMI Plan</th>
                    <th>Monthly Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cart as $id => $item)
                    <tr>
                        <td>
                            <img src="{{ asset('uploads/products/'.$item['image']) }}" alt="{{ $item['name'] }}" style="width:60px; height:auto;">
                            <span class="ms-2">{{ $item['name'] }}</span>
                        </td>
                        <td>₹ {{ number_format($item['price']) }}</td>
                        <td>{{ $item['months'] }} Months</td>
                        <td>₹ {{ number_format($item['monthly_amount']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            <a href="/cart" class="btn btn-secondary">Back to Cart</a>
            <button type="submit" class="btn btn-success">Place Order</button>
        </div>
    </form>
</div>
@endsection
