@extends('layouts.admin')
@section('content')
<h3>All Orders</h3>
<table class="table table-bordered table-striped mt-2">

<thead class="table-light">
<tr>
    <th>ID</th>
    <th>User</th>
    <th>Product</th>
    <th>Price</th>
    <th>Months</th>
    <th>Monthly Amount</th>
    <th>Paid</th>
    <th>Remaining</th>
    <th>Action</th>
</tr>
</thead>
<tbody>
@foreach($orders as $order)
<tr>
    <td>{{ $order->id }}</td>
    <td>{{ $order->user_id }}</td>
    <td>{{ $order->product_name }}</td>
    <td>₹ {{ number_format($order->price) }}</td>
    <td>{{ $order->months }}</td>
    <td>₹ {{ number_format($order->monthly_amount) }}</td>
    <td>₹ {{ number_format($order->totalPaid()) }}</td>
    <td>₹ {{ number_format($order->totalPending()) }}</td>
    <td>
        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary">View</a>
    </td>
</tr>
@endforeach
</tbody>
</table>
@endsection
