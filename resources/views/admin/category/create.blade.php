@extends('layouts.admin')

@section('content')

<h3>Add Category</h3>

<form method="POST" action="{{ route('category.store') }}">
@csrf

<div class="mb-2">
    <label>Category Name</label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter category name" value="{{ old('name') }}" required>
    @error('name')
        <small class="text-danger d-block mt-1">{{ $message }}</small>
    @enderror
</div>


<button class="btn btn-success">Save Category</button>
<a href="{{ route('category.index') }}" class="btn btn-secondary">Back</a>

</form>

@endsection
