@extends('layouts.user')

@section('content')

<h4 class="mb-3">Products</h4>

<header class="filter-box">

<form method="GET" action="{{ url('/products') }}" class="filter-form">

<div>
    <label>Category</label>
    <select class="form-select"
            onchange="location.href='{{ url('/products') }}' + (this.value ? '?category_id=' + this.value : '')">

        <!-- ALL CATEGORIES -->
        <option value="">
            All Categories
        </option>

        @foreach($categories as $category)
            <option value="{{ $category->id }}"
                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach

    </select>
</div>

    <div>
        <label>Search Product</label>
        <input type="text" name="search" class="form-control"
               value="{{ request('search') }}"
              >
    </div>

    <div>
        <label>&nbsp;</label>
        <button class="btn">Search</button>
    </div>

    

</form>
</header>

<div class="product-grid">

@foreach($products as $product)
    <div class="product-card">
        <img src="{{ asset('uploads/products/'.$product->image) }}">

        <div class="product-body">
            <h2>{{ $product->name }}</h2>
            <p>₹ {{ number_format($product->price) }}</p>

            <a href="{{ route('product.detail',$product->id) }}"
               class="btn-primary block">
                View Details
            </a>
        </div>
    </div>
@endforeach

</div>

@endsection
