@extends('layouts.user')
@section('content')

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h2>EMI Dashboard</h2>
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(empty($emis))
                <div class="alert alert-info">
                    No EMI records found for this user.
                </div>
            @else
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Order ID</th>
                            <th>Product</th>
                            <th>Month</th>
                            <th>Due Date</th>
                            <th>Amount</th>
                            <th>Penalty</th>
                            <th>Total Due</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($emis as $emi)
                            <tr class="@if($emi->is_overdue && $emi->status !== 'Paid') table-danger @endif">
                                <td>#{{ $emi->order->id ?? 'N/A' }}</td>
                                <td>{{ $emi->order->product_name ?? 'N/A' }}</td>
                                <td>Month {{ $emi->month_no }}</td>
                                <td>
                                    {{ $emi->due_date->format('d M Y') ?? 'N/A' }}
                                    @if($emi->is_overdue && $emi->status !== 'Paid')
                                        <br><small class="text-danger">[OVERDUE] {{ $emi->days_overdue }} days late</small>
                                    @endif
                                </td>
                                <td>₹{{ number_format($emi->amount, 2) }}</td>
                                <td>
                                    @if($emi->penalty_amount > 0)
                                        <span class="badge bg-warning">₹{{ number_format($emi->penalty_amount, 2) }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td><b>₹{{ number_format($emi->total_due, 2) }}</b></td>
                                <td>
                                    @if($emi->status === 'Paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($emi->status === 'Overdue')
                                        <span class="badge bg-danger">Overdue</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if($emi->status !== 'Paid')
                                        <a href="{{ route('user.emi.pay.form', $emi->id) }}" class="btn btn-sm btn-success">
                                            Pay Now
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No EMI records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

@endsection