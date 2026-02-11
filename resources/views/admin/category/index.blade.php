@extends('layouts.admin')

@section('content')

<h3 class="mb-3">Category List</h3>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<a href="{{ route('category.create') }}" class="btn btn-primary mb-3">+ Add Category</a>

<div class="table-wrapper">
    <table class="compact-table category-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $cat)
            <tr>
                <td>{{ $cat->id }}</td>
                <td>{{ $cat->name }}</td>
               
                <td>
                    <a href="{{ route('category.edit',$cat->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('category.delete', $cat->id) }}" method="POST" style="display:inline-block; margin:0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
