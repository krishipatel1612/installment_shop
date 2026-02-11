@extends('layouts.admin')

@section('content')

<h3>Add Product</h3>

<form method="POST" action="{{ route('product.store') }}" enctype="multipart/form-data">
@csrf

<div class="mb-2">
    <label>Category</label>
    <select name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
        <option value="">-- Select Category --</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ old('category_id')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
        @endforeach
    </select>
    @error('category_id') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
</div>

<div class="mb-2">
    <label>Product Name</label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
    @error('name') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
</div>

<div class="mb-2">
    <label>Price</label>
    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" step="0.01" required>
    @error('price') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
</div>

<div class="mb-2">
    <label>Image</label>
    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" required>
    @error('image') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
</div>

<div class="mb-2">
    <label>Description</label>
    <textarea name="description" class="form-control"></textarea>
</div>

<button class="btn btn-success">Save Product</button>
<a href="{{ route('product.index') }}" class="btn btn-secondary">Back</a>

</form>

@endsection
