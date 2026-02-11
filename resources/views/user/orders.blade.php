@extends('layouts.user')

@section('content')

<div class="container mt-4">
    <h4>My Orders</h4>

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

    @forelse($orders as $order)
        <div class="card mb-3 p-3 bg-dark text-white border-secondary">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="mb-0">Order #{{ $order->id }} – {{ $order->product_name }}</h5>
                    <p class="text-muted mb-0">
                        Total: <strong>₹{{ number_format($order->price) }}</strong> | 
                        Months: <strong>{{ $order->months }}</strong> | 
                        Monthly: <strong>₹{{ number_format($order->monthly_amount) }}</strong>
                    </p>
                </div>
                <a href="{{ route('user.orders.show', $order->id) }}" class="btn btn-sm btn-info">View Details</a>
            </div>

            <table class="table table-bordered mt-2 table-dark">
                <thead class="table-dark">
                    <tr>
                        <th>Month</th>
                        <th>Amount</th>
                        <th>Due Date</th>
                        <th>Penalty</th>
                        <th>Total Due</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->emiSchedules as $emi)
                        <tr class="@if($emi->is_overdue && $emi->status !== 'Paid') table-danger @endif">
                            <td>{{ $emi->month_no }}</td>
                            <td>₹{{ number_format($emi->amount, 2) }}</td>
                            <td>
                                {{ $emi->due_date->format('d M Y') ?? 'N/A' }}
                                @if($emi->is_overdue && $emi->status !== 'Paid')
                                    <br><small class="text-danger">[OVERDUE] {{ $emi->days_overdue }} days late</small>
                                @endif
                            </td>
                            <td>
                                @if($emi->penalty_amount > 0)
                                    <span class="badge bg-warning">₹{{ number_format($emi->penalty_amount, 2) }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td><strong>₹{{ number_format($emi->total_due, 2) }}</strong></td>
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

                        @php
                            // Get the latest verification for this EMI
                            $latestVerification = null;
                            if ($emi->paymentVerifications && count($emi->paymentVerifications) > 0) {
                                $latestVerification = $emi->paymentVerifications->sortByDesc('submitted_at')->first();
                            }
                            // Only show rejection if LATEST verification is rejected
                            $isLatestRejected = $latestVerification && $latestVerification->status === 'rejected';
                        @endphp
                        @if($isLatestRejected && $latestVerification->rejection_reason)
                        <tr>
                            <td colspan="7">
                                <button type="button" class="btn btn-sm btn-info" onclick="showRejectionModal({{ $emi->id }}, '{{ addslashes($latestVerification->rejection_reason) }}');">
                                    ⓘ View Rejection Reason
                                </button>

                                <!-- Rejection Modal -->
                                <div id="rejectionModal{{ $emi->id }}" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); background:#1a202c; border:2px solid #f56565; padding:25px; border-radius:8px; z-index:9999; width:95%; max-width:500px; box-shadow:0 10px 40px rgba(0,0,0,0.8);">
                                    <div style="text-align:right; margin-bottom:15px;">
                                        <button type="button" class="btn btn-sm btn-outline-light" onclick="hideRejectionModal({{ $emi->id }});">✕ Close</button>
                                    </div>
                                    <div style="background:#2d3748; padding:15px; border-radius:5px; color:#e2e8f0;">
                                        <h6 style="color:#fc8181; margin-bottom:15px;">Payment Rejection Reason</h6>
                                        <p style="font-size:14px; line-height:1.6;">{{ $latestVerification->rejection_reason }}</p>
                                        <div style="margin-top:15px; padding-top:15px; border-top:1px solid #4a5568;">
                                            <small style="color:#a0aec0;">You may resubmit your payment using the Pay Now button.</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Overlay -->
                                <div id="rejectionOverlay{{ $emi->id }}" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.7); z-index:9998;" onclick="hideRejectionModal({{ $emi->id }});"></div>
                            </td>
                        </tr>
                        @endif

                    @endforeach
                </tbody>
            </table>
        </div>
    @empty
        <p class="text-muted">No orders yet.</p>
    @endforelse
</div>

<script>
function showRejectionModal(emiId, reason) {
    document.getElementById('rejectionModal' + emiId).style.display = 'block';
    document.getElementById('rejectionOverlay' + emiId).style.display = 'block';
}

function hideRejectionModal(emiId) {
    document.getElementById('rejectionModal' + emiId).style.display = 'none';
    document.getElementById('rejectionOverlay' + emiId).style.display = 'none';
}
</script>
@endsection
