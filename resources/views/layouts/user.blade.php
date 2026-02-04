<!DOCTYPE html>
<html>
<head>
    <title>Installment Shop - User Panel</title>
    
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-3">
    <a href="{{ route('user.home') }}" class="navbar-brand">Installment Shop</a>
    <div class="ms-auto">
        <a href="{{ route('user.home') }}" class="btn btn-outline-primary btn-sm me-2">Home</a>
        <a href="{{ url('/products') }}" class="btn btn-outline-primary btn-sm me-2">Products</a>
        <a href="{{ route('user.orders') }}" class="btn btn-outline-primary btn-sm me-2">My Orders</a>
        <a href="{{ route('cart.index') }}" class="btn btn-outline-primary btn-sm me-2">Cart</a>
        <a href="/profile" class="btn btn-outline-primary btn-sm me-2">Profile</a>
        <a href="{{ route('logout') }}" class="btn btn-danger btn-sm">Logout</a>
    </div>
</nav>


<div class="container mt-4">
    @yield('content')
</div>

<footer class="bg-dark text-white text-center p-2 mt-4">
    © Installment Shop
</footer>

</body>
</html>
