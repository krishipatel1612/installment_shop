@extends('layouts.admin')

@section('content')

<h3>Add Product</h3>

<form method="POST" action="{{ route('product.store') }}" enctype="multipart/form-data">
@csrf

<div class="mb-2">
    <label>Category</label>
    <select name="category_id" class="form-control">
        <option value="">-- Select Category --</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
        @endforeach
    </select>
    @error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="mb-2">
    <label>Product Name</label>
    <input type="text" name="name" class="form-control">
    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="mb-2">
    <label>Price</label>
    <input type="number" name="price" class="form-control">
    @error('price') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="mb-2">
    <label>Image</label>
    <input type="file" name="image" class="form-control">
    @error('image') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="mb-2">
    <label>Description</label>
    <textarea name="description" class="form-control"></textarea>
</div>

<button class="btn btn-success">Save Product</button>
<a href="{{ route('product.index') }}" class="btn btn-secondary">Back</a>

</form>

@endsection
