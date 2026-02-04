@extends('layouts.admin')

@section('content')

<h3 class="mb-3">Admin Dashboard</h3>

<div class="row">

    <div class="col-md-4">
        <div class="card shadow-sm p-3 mb-3">
            <h5>Total Categories</h5>
            <h2>{{ $totalCategories }}</h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm p-3 mb-3">
            <h5>Total Products</h5>
            <h2>{{ $totalProducts }}</h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm p-3 mb-3">
            <h5>Total Users</h5>
            <h2>{{ $totalUsers }}</h2>
        </div>
    </div>

</div>

@endsection
