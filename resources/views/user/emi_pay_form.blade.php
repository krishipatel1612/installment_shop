@extends('layouts.user')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h3 class="text-white mb-4">Submit EMI Payment</h3>

            <div class="card bg-dark text-white shadow-lg border-0">
                <div class="card-body p-4">

                    <!-- EMI Details -->
                    <div class="mb-4">
                        <h5 class="text-info">{{ $emi->order->product_name }}</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Month Number:</p>
                                <p class="fw-bold">{{ $emi->month_no }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Due Date:</p>
                                <p class="fw-bold">{{ $emi->due_date->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Amount Section -->
                    <div class="alert alert-info mb-4">
                        <p class="mb-1">
                            <strong>EMI Amount:</strong> ₹{{ number_format($emi->amount, 2) }}
                        </p>
                        @if($emi->penalty_amount > 0)
                            <p class="mb-1 text-warning">
                                <strong>Penalty:</strong> ₹{{ number_format($emi->penalty_amount, 2) }}
                            </p>
                        @endif
                        <p class="mb-0">
                            <strong class="fs-5 text-success">Total Due: ₹{{ number_format($emi->total_due, 2) }}</strong>
                        </p>
                    </div>

                    <!-- Payment Form -->
                    <form action="{{ route('user.emi.submit-payment', $emi->id) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select name="payment_method" id="payment_method" class="form-select" required>
                                <option value="">-- Select Payment Method --</option>
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="upi">UPI</option>
                                <option value="online">Online Payment</option>
                            </select>
                            @error('payment_method')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="transaction_id" class="form-label">Transaction ID (Optional)</label>
                            <input type="text" name="transaction_id" id="transaction_id" class="form-control" placeholder="UTR / Ref number">
                            @error('transaction_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-warning">
                            <small>Your payment will be submitted for admin verification. You'll receive confirmation once approved.</small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">Submit Payment</button>
                            <a href="{{ route('user.orders') }}" class="btn btn-outline-light">Cancel</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
