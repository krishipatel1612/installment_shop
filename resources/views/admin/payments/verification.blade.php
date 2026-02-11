@extends('layouts.admin')

@section('content')
<h2 class="mb-4">Payment Verifications</h2>

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

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Validation Error:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Stats -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-dark text-white border-info">
            <div class="card-body">
                <h6 class="text-info"> Pending</h6>
                <h3>{{ $pendingVerifications->total() }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Pending Verifications Table -->
@if($pendingVerifications->count() > 0)
    <div class="table-wrapper">
        <table class="compact-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Order ID</th>
                    <th>EMI Month</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Transaction ID</th>
                    <th>Submitted</th>
                    <th>Verify</th>
                    <th>Reject</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingVerifications as $pv)
                    <tr>
                        <td>
                            <strong>{{ $pv->user->name }}</strong><br>
                            <small class="text-muted">{{ $pv->user->email }}</small>
                        </td>
                        <td>#{{ $pv->order_id }}</td>
                        <td>Month {{ $pv->emiSchedule->month_no }}</td>
                        <td><strong>₹{{ number_format($pv->amount, 2) }}</strong></td>
                        <td>
                            @if($pv->payment_method === 'cash')
                                 Cash
                            @elseif($pv->payment_method === 'bank_transfer')
                                 Bank Transfer
                            @elseif($pv->payment_method === 'upi')
                                 UPI
                            @else
                                 Online
                            @endif
                        </td>
                        <td>
                            @if($pv->transaction_id)
                                <code>{{ substr($pv->transaction_id, 0, 20) }}...</code>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <small>{{ $pv->submitted_at->format('d M Y H:i') }}</small>
                        </td>
                        <td>
                            <!-- Verify Button -->
                            <form action="{{ route('admin.payment.verify', $pv->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">✓ Verify</button>
                            </form>
                        </td>
                        <td>
                            <!-- Reject Button -->
                            <button type="button" 
                                    class="btn btn-sm btn-danger rejectBtn{{ $pv->id }}" 
                                    onclick="showRejectForm({{ $pv->id }});">
                                Reject
                            </button>

                            <!-- Overlay backdrop (placed before modal so modal is on top) -->
                            <div id="rejectOverlay{{ $pv->id }}" class="rejectOverlay" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.7); z-index:9998;" onclick="hideRejectForm({{ $pv->id }});"></div>

                            <!-- Reject Form Modal -->
                            <div id="rejectForm{{ $pv->id }}" class="rejectFormContainer" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); background:#1a202c; border:2px solid #f56565; padding:25px; border-radius:8px; z-index:9999; width:95%; max-width:450px; box-shadow:0 10px 40px rgba(0,0,0,0.8);">
                                
                                <!-- Close button -->
                                <div style="text-align:right; margin-bottom:15px;">
                                    <button type="button" class="btn btn-sm btn-outline-light" onclick="hideRejectForm({{ $pv->id }});">✕ Close</button>
                                </div>

                                <!-- Payment Info -->
                                <div style="background:#2d3748; padding:15px; border-radius:5px; margin-bottom:20px; color:#e2e8f0;">
                                    <h6 style="color:#fc8181; margin-bottom:10px;">Payment Details</h6>
                                    <p style="margin:5px 0; font-size:13px;">
                                        <strong>User:</strong> {{ $pv->user->name }}<br>
                                        <strong>Amount:</strong> ₹{{ number_format($pv->amount, 2) }}<br>
                                        <strong>EMI Month:</strong> Month {{ $pv->emiSchedule->month_no }}<br>
                                        <strong>Submitted:</strong> {{ $pv->submitted_at->format('d M Y H:i') }}
                                    </p>
                                </div>

                                <!-- Reject Form -->
                                <form method="POST" action="{{ route('admin.payment.reject', $pv->id) }}" onsubmit="disableFormButtons(this);">
                                    @csrf
                                    
                                    <div style="margin-bottom:20px;">
                                        <label style="color:#cbd5e0; font-weight:bold; display:block; margin-bottom:8px; font-size:14px;">
                                            Why are you rejecting this payment? <span style="color:#fc8181;">*</span>
                                        </label>
                                        <textarea name="rejection_reason" 
                                                  class="form-control" 
                                                  rows="5" 
                                                  minlength="5" 
                                                  maxlength="255" 
                                                  placeholder="Enter detailed reason for rejection (minimum 5 characters)..."
                                                  required
                                                  style="background:#2d3748; color:#e2e8f0; border:1px solid #4a5568; font-size:13px;"></textarea>
                                        <small style="color:#a0aec0; display:block; margin-top:5px;">Min 5 characters • Max 255 characters</small>
                                    </div>
                                    <!-- Action Buttons -->
                                    <div style="display:flex; gap:10px;">
                                        <button type="submit" class="btn btn-danger flex-grow-1" style="font-weight:bold;">
                                            Confirm Reject
                                        </button>
                                        <button type="button" class="btn btn-secondary" onclick="hideRejectForm({{ $pv->id }});">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <script>
                                function showRejectForm(id) {
                                    document.getElementById('rejectForm' + id).style.display = 'block';
                                    document.getElementById('rejectOverlay' + id).style.display = 'block';
                                    document.querySelector('.rejectBtn' + id).disabled = true;
                                    // focus the rejection textarea so typing works immediately
                                    const ta = document.querySelector('#rejectForm' + id + ' textarea[name="rejection_reason"]');
                                    if(ta) { ta.focus(); }
                                }

                                function hideRejectForm(id) {
                                    document.getElementById('rejectForm' + id).style.display = 'none';
                                    document.getElementById('rejectOverlay' + id).style.display = 'none';
                                    document.querySelector('.rejectBtn' + id).disabled = false;
                                }

                                function disableFormButtons(form) {
                                    const buttons = form.querySelectorAll('button');
                                    buttons.forEach(btn => btn.disabled = true);
                                }
                            </script>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $pendingVerifications->links() }}
    </div>
@else
    <div class="alert alert-info">
        ✓ No pending payments. All payments have been verified!
    </div>
@endif

@endsection
