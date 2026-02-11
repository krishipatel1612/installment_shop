@extends('layouts.admin')

@section('content')

<h3 class="mb-4">Admin Dashboard</h3>

<div class="dashboard-grid">

    <div class="card shadow-sm p-4">
        <h5>Total Categories</h5>
        <h2>{{ $totalCategories }}</h2>
    </div>

    <div class="card shadow-sm p-4">
        <h5>Total Products</h5>
        <h2>{{ $totalProducts }}</h2>
    </div>

    <div class="card shadow-sm p-4">
        <h5>Total Users</h5>
        <h2>{{ $totalUsers }}</h2>
    </div>

</div>

@endsection
