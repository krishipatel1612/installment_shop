@extends('layouts.user')

@section('content')

<div class="container">
    <h3>My Profile</h3>
    <hr>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('user.profile.update') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', Auth::user()->name) }}" required>
            @error('name') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
        </div><br>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', Auth::user()->email) }}" required>
            @error('email') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
        </div><br>

        <div class="mb-3">
            <label for="phone_no" class="form-label">Phone</label>
            <input type="text" name="phone_no" id="phone_no" class="form-control @error('phone_no') is-invalid @enderror" value="{{ old('phone_no', Auth::user()->phone_no) }}" required>
            @error('phone_no') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
        </div><br>

        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" rows="3" required>{{ old('address', Auth::user()->address) }}</textarea>
            @error('address') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror  
        </div><br>

        <div class="mb-3">
            <label for="password" class="form-label">Password <small>(Leave blank if unchanged)</small></label>
            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
            @error('password') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
        </div><br>

        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>

@endsection
