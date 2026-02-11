<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

<!-- HEADER -->
<header class="admin-header">
    <div class="logo">🧑🏻‍💻 Admin Panel</div>
    <div class="logout-btn" onclick="window.location='/logout'">Logout</div>
</header>

<!-- MAIN -->
<div class="admin-wrapper">

    <!-- SIDEBAR -->
<aside class="admin-sidebar">
    <h6 class="menu-title">Menu</h6>

    <a href="{{ route('admin.dashboard') }}" class="menu-item">
        Dashboard
    </a>

    <a href="{{ route('category.index') }}" class="menu-item">
        Category
    </a>

    <a href="{{ route('product.index') }}" class="menu-item">
        Products
    </a>

    <a href="{{ url('admin/orders') }}" class="menu-item">
        Orders
    </a>

    <a href="{{ route('admin.payments.verification') }}" class="menu-item">
        Payment Verifications
    </a>

    <a href="{{ route('admin.reviews.index') }}" class="menu-item">
        Customer Reviews
    </a>
</aside>

    <!-- CONTENT -->
    <main class="admin-content">
        @yield('content')
    </main>

</div>

<!-- FOOTER -->
<footer class="admin-footer">
    © {{ date('Y') }} Installment E-Commerce Admin
</footer>

</body>
</html>