@extends('layouts.user')

@section('content')

<div class="container mt-4">
    <h4>My Cart</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(count($cart) == 0)
        <p>No items in cart</p>
        <a href="/products" class="btn btn-secondary mt-2">Continue Shopping</a>
    @else
        <table class="table table-bordered table-striped mt-3">
            <thead class="table-light">
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>EMI Plan</th>
                    <th>Monthly Amount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cart as $id => $item)
                    <tr>
                        <td>
                            <img src="{{ asset('uploads/products/'.$item['image']) }}"
                                 alt="{{ $item['name'] }}" style="width:60px; height:auto;">
                            <span class="ms-2">{{ $item['name'] }}</span>
                        </td>
                        <td>₹ {{ number_format($item['price']) }}</td>
                        <td>{{ $item['months'] }} Months</td>
                        <td>₹ {{ number_format($item['monthly_amount']) }}</td>
                        <td>
                            <a href="{{ route('cart.remove', $id) }}" class="btn btn-sm btn-danger">
                                Remove
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

       <div class="mt-3 d-flex gap-2">
    <a href="/products" class="btn btn-secondary">Continue Shopping</a>

    <!-- POST form for checkout -->
    <form action="{{ route('checkout.process') }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-success">Proceed to Checkout</button>
</form>

</div>

    @endif
</div>

@endsection
