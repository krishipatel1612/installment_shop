<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center" style="height:100vh;">

<div class="card p-4" style="width:350px;">
    <h4 class="text-center mb-3">Login</h4>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="/login">
        @csrf
        <input type="email" name="email" class="form-control mb-2" placeholder="Email">
        @error('email') <small class="text-danger">{{ $message }}</small> @enderror

        <input type="password" name="password" class="form-control mb-2" placeholder="Password">
        @error('password') <small class="text-danger">{{ $message }}</small> @enderror

        <button class="btn btn-primary w-100 mt-2">Login</button>
    </form>

    <div class="mt-2 text-center">
    <small>Don't have an account? <a href="/register">Register</a></small>
</div>

</div>

</body>
</html>
