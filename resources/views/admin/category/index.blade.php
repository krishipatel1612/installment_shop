@extends('layouts.admin')

@section('content')

<h3>Category List</h3>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<a href="{{ route('category.create') }}" class="btn btn-primary mb-2">+ Add Category</a>

<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Status</th>
        <th>Action</th>
    </tr>

    @foreach($categories as $cat)
    <tr>
        <td>{{ $cat->id }}</td>
        <td>{{ $cat->name }}</td>
        <td>
            @if($cat->status==1)
                <span class="badge bg-success">Active</span>
            @else
                <span class="badge bg-danger">Inactive</span>
            @endif
        </td>
        <td>
            <a href="{{ route('category.edit',$cat->id) }}" class="btn btn-sm btn-warning">Edit</a>
            <a href="{{ route('category.delete',$cat->id) }}" class="btn btn-sm btn-danger"
               onclick="return confirm('Are you sure?')">Delete</a>
        </td>
    </tr>
    @endforeach

</table>

@endsection
