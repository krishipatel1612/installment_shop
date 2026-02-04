@extends('layouts.admin')
@section('content')
<h3>Order Details - Order #{{ $order->id }}</h3>
<p><strong>User ID:</strong> {{ $order->user_id }}</p>
<p><strong>Product:</strong> {{ $order->product_name }}</p>
<p><strong>Total Price:</strong> ₹ {{ number_format($order->price) }}</p>
<p><strong>EMI Duration:</strong> {{ $order->months }} Months</p>

<h5>EMI Schedule</h5>
<table class="table table-bordered table-striped mt-2">
    <thead>
        <tr>
            <th>Month</th>
            <th>Monthly Amount</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->emiSchedules as $emi)
            <tr>
                <td>{{ $emi->month_no }}</td>
                <td>₹ {{ number_format($emi->amount) }}</td>
                <td>{{ $emi->status }}</td>
            </tr>
        @endforeach
        <tr>
            <td><strong>Total Paid</strong></td>
            <td>₹ {{ number_format($order->totalPaid()) }}</td>
            <td></td>
        </tr>
        <tr>
            <td><strong>Remaining</strong></td>
            <td>₹ {{ number_format($order->totalPending()) }}</td>
            <td></td>
        </tr>
    </tbody>
</table>

@endsection