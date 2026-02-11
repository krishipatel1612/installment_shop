@extends('layouts.admin')

@section('content')

<h3>Edit Category</h3>

<form method="POST" action="{{ route('category.update',$category->id) }}">
@csrf

<div class="mb-2">
    <label>Category Name</label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" required>
    @error('name')
        <small class="text-danger d-block mt-1">{{ $message }}</small>
    @enderror
</div>



<button class="btn btn-success">Update Category</button>
<a href="{{ route('category.index') }}" class="btn btn-secondary">Back</a>

</form>

@endsection
