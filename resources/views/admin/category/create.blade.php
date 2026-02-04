@extends('layouts.admin')

@section('content')

<h3>Add Category</h3>

<form method="POST" action="{{ route('category.store') }}">
@csrf

<div class="mb-2">
    <label>Category Name</label>
    <input type="text" name="name" class="form-control" placeholder="Enter category name">
    @error('name')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="mb-2">
    <label>Status</label>
    <select name="status" class="form-control">
        <option value="1">Active</option>
        <option value="0">Inactive</option>
    </select>
</div>

<button class="btn btn-success">Save Category</button>
<a href="{{ route('category.index') }}" class="btn btn-secondary">Back</a>

</form>

@endsection
