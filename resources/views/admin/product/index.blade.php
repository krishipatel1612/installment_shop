@extends('layouts.admin')

@section('content')
<h2 class="mb-4">Products List</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<a href="{{ route('product.create') }}" class="btn btn-success mb-3">+ Add New Product</a>

<div class="table-wrapper">
    <table class="compact-table product-table">
        <thead>
            <tr>
                <th>Image</th>    
                <th>Name</th>
                <th>Description</th>
                <th>Price (₹)</th>
                <th>EMI Plans</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>
                        @if($product->image)
                            <img src="{{ asset('uploads/products/'.$product->image) }}"
                                 alt="{{ $product->name }}"
                                 width="60"
                                 style="border-radius:4px;">
                        @else
                            <span class="text-muted">No Image</span>
                        @endif
                    </td>
                    <td>{{ $product->name }}</td>
                    <td>{{ substr($product->description, 0, 50) }}...</td>
                    <td><strong>₹ {{ number_format($product->price, 2) }}</strong></td>
                    <td>
                        @foreach($product->installmentPlans as $emi)
                            <small>
                                {{ $emi->months }}M: ₹{{ number_format($emi->monthly_amount, 0) }}/mo
                            </small><br>
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route('product.edit', $product->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('product.delete', $product->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('Delete this product?');">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
