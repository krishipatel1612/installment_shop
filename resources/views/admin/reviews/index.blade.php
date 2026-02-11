@extends('layouts.admin')

@section('content')

<h2 class="mb-4">Customer Reviews</h2>

@if($reviews->count() > 0)
    <div class="table-wrapper">
        <table class="compact-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Product</th>
                    <th>Rating</th>
                    <th>Comment Preview</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reviews as $review)
                    <tr>
                        <td>
                            <strong>{{ $review->user->name }}</strong><br>
                            <small class="text-muted">{{ $review->user->email }}</small>
                        </td>
                        <td>{{ $review->order->product_name }}</td>
                        <td>
                            <div class="text-warning">
                                @for($i = 0; $i < $review->rating; $i++)
                                    ⭐
                                @endfor
                            </div>
                        </td>
                        <td>
                            <small>{{ substr($review->comment, 0, 50) }}{{ strlen($review->comment) > 50 ? '...' : '' }}</small>
                        </td>
                        <td>
                            <small>{{ $review->created_at->format('d M Y H:i') }}</small>
                        </td>
                        <td>
                            <a href="{{ route('admin.reviews.show', $review->id) }}" class="btn btn-sm btn-info">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $reviews->links() }}
    </div>
@else
    <div class="alert alert-info">
        No reviews yet.
    </div>
@endif

@endsection
