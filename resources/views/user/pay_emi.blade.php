@extends('layouts.user')

@section('content')

<div class="container mt-4">

    <h3 class="text-white mb-3">Pay EMI</h3>

    <div class="card bg-dark text-white shadow-lg rounded-4 border-0">
        <div class="card-body">

            <h5>{{ $emi->order->product->name ?? 'Product' }}</h5>

            <p class="mb-1">Due Date:
                <b>{{ $emi->due_date ? $emi->due_date->format('d M Y') : 'N/A' }}</b>
            </p>

            <p class="mb-1">EMI Amount: <b>₹{{ number_format($emi->amount, 2) }}</b></p>
            <p class="mb-1">Penalty: <b>₹{{ number_format($emi->penalty_amount, 2) }}</b></p>

            <p class="mt-2 fs-5">
                Total Payable:
                <b class="text-warning">₹{{ number_format($emi->total_due, 2) }}</b>
            </p>

            <form action="{{ route('user.emi.pay.store', $emi->id) }}" method="POST">
    @csrf


                <input type="hidden" name="emi_schedule_id" value="{{ $emi->id }}">

                <div class="mb-3">
                    <label class="form-label">Payment Method</label>
                    <select name="payment_method" class="form-control">
                        <option value="manual">Manual</option>
                        <option value="cash">Cash</option>
                        <option value="upi">UPI</option>
                    </select>
                </div>

               <button type="submit" class="btn btn-primary">Confirm Pay</button>
                <a href="{{ route('user.orders') }}" class="btn btn-outline-light ms-2">
                    Back
                </a>
            </form>

        </div>
    </div>

</div>

@endsection
