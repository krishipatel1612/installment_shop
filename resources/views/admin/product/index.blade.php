@extends('layouts.admin')

@section('title','Products List')

@section('content')
<div class="container mt-4">
    <h2>Products List</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('product.create') }}" class="btn btn-success mb-3">Add New Product</a>

    <table class="table table-bordered table-striped">
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
                <!-- Product Image -->
                <td>
                    @if($product->image)
                        <img src="{{ asset('uploads/products/'.$product->image) }}"
                             alt="{{ $product->name }}"
                             width="60">
                    @else
                        <span>No Image</span>
                    @endif
                </td>

                <!-- Name & Description -->
                <td>{{ $product->name }}</td>
                <td>{{ $product->description }}</td>

                <!-- Price -->
                <td>₹ {{ number_format($product->price, 2) }}</td>

                <!-- EMI Plans -->
                <td>
                    @foreach($product->installmentPlans as $emi)
                        <div>
                            {{ $emi->months }} Months : 
                            Total ₹{{ number_format($emi->total_amount, 2) }}, 
                            Monthly ₹{{ number_format($emi->monthly_amount, 2) }}
                        </div>
                    @endforeach
                </td>

                <!-- Actions -->
                <td>
                    <a href="{{ route('product.edit', $product->id) }}" 
                       class="btn btn-sm btn-primary mb-1">Edit</a>

                    <form action="{{ route('product.delete', $product->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"
                            onclick="return confirm('Are you sure you want to delete this product?');">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
