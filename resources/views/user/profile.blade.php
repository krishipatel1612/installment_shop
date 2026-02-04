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
            <input type="text" name="name" id="name" class="form-control" value="{{ Auth::user()->name }}" required>
        </div><br>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ Auth::user()->email }}" required>
        </div><br>

        <div class="mb-3">
            <label for="password" class="form-label">Password <small>(Leave blank if unchanged)</small></label>
            <input type="password" name="password" id="password" class="form-control">
        </div><br>

        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>

@endsection
