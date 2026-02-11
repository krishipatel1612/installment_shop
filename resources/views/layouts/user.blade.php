<!DOCTYPE html>
<html>
<head>
    <title>Installment Shop - User Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm px-3">
    <a href="{{ route('user.home') }}" class="navbar-brand text-white fw-bold">🛒Installment Shop</a>
    <div class="ms-auto">
        <a href="{{ route('user.home') }}" class="btn btn-light btn-sm me-2">Home</a>
        <a href="{{ url('/products') }}" class="btn btn-light btn-sm me-2">Products</a>
         <a href="{{ route('cart.index') }}" class="btn btn-light btn-sm me-2">Cart</a>
        <a href="{{ route('user.orders') }}" class="btn btn-light btn-sm me-2">My Orders</a>
        <a href="/profile" class="btn btn-light btn-sm me-2">Profile</a>
       <form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-danger btn-sm">Logout</button>
</form>

    </div>
</nav>

<div class="page-wrapper">
    <main class="page-content">
        <div class="container mt-4">
            @yield('content')
        </div>
    </main>

    <footer class="footer-dark">
        <div class="footer-content">
            <p>&copy; 2026 Installment Shop. All rights reserved.</p>
            <p style="font-size: 12px; color: #8b949e; margin: 5px 0 0 0;">Easy Installment Payment Solutions</p>
        </div>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
