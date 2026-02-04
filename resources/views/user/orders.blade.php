@extends('layouts.user')

@section('content')

<div class="container mt-4">
    <h4>My Orders</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @forelse($orders as $order)
        <div class="card mb-3 p-3">
            <h5>Order #{{ $order->id }} – {{ $order->product_name }}</h5>
            <p>Total: ₹ {{ number_format($order->price) }} | Months: {{ $order->months }}</p>

            <table class="table table-bordered mt-2">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->emiSchedules as $emi)
                        <tr>
                            <td>{{ $emi->month_no }}</td>
                            <td>₹ {{ number_format($emi->amount) }}</td>
                            <td>
                                @if($emi->status == 'Paid')
                                    <span class="text-success">Paid</span>
                                @else
                                    <span class="text-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if($emi->status == 'Pending')
                                    <form action="{{ route('user.emi.pay', $emi->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-primary">Pay EMI</button>
                                    </form>
                                @else
                                    --
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @empty
        <p>No orders yet.</p>
    @endforelse
</div>
@endsection
