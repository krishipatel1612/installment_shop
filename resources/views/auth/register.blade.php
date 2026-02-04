<!DOCTYPE html>
<html>
<head>
    <title>User Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center" style="height:100vh;">

<div class="card p-4" style="width:380px;">
    <h4 class="text-center mb-3">User Registration</h4>

    <form method="POST" action="/register">
        @csrf

        <input type="text" name="name" class="form-control mb-2" placeholder="Full Name">
        @error('name') <small class="text-danger">{{ $message }}</small> @enderror

        <input type="email" name="email" class="form-control mb-2" placeholder="Email">
        @error('email') <small class="text-danger">{{ $message }}</small> @enderror

        <input type="password" name="password" class="form-control mb-2" placeholder="Password">
        @error('password') <small class="text-danger">{{ $message }}</small> @enderror

        <input type="password" name="password_confirmation" class="form-control mb-2" placeholder="Confirm Password">

        <button class="btn btn-success w-100 mt-2">Register</button>
    </form>

    <div class="mt-2 text-center">
        <small>Already have an account? <a href="/login">Login</a></small>
    </div>
</div>

</body>
</html>
