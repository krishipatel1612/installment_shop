@extends('layouts.admin')

@section('content')

<h3>Edit Category</h3>

<form method="POST" action="{{ route('category.update',$category->id) }}">
@csrf

<div class="mb-2">
    <label>Category Name</label>
    <input type="text" name="name" class="form-control" value="{{ $category->name }}">
    @error('name')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="mb-2">
    <label>Status</label>
    <select name="status" class="form-control">
        <option value="1" {{ $category->status==1?'selected':'' }}>Active</option>
        <option value="0" {{ $category->status==0?'selected':'' }}>Inactive</option>
    </select>
</div>

<button class="btn btn-success">Update Category</button>
<a href="{{ route('category.index') }}" class="btn btn-secondary">Back</a>

</form>

@endsection
