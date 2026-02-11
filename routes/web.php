<?php

use Illuminate\Support\Facades\Route;

// ================= CONTROLLERS =================
use App\Http\Controllers\AuthController;

// Admin Controllers
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

// User Controllers
use App\Http\Controllers\User\ProductController as UserProductController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Controllers\User\ProfileController;

// EMI Controller
use App\Http\Controllers\User\EmiPaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Default Welcome Page - Redirect to login
Route::get('/', function () {
    return redirect('/login');
});

// ================= AUTH ROUTES =================

// Login
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Register (User)
Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);


// ================= USER ROUTES =================
Route::middleware('auth')->group(function () {

    // ✅ Logout (MUST be POST + inside auth)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // User Home Page
    Route::get('/home', [HomeController::class, 'index'])->name('user.home');

    // User Products
    Route::get('/products', [UserProductController::class, 'index'])->name('products.index');
    Route::get('/product/{id}', [UserProductController::class, 'show'])->name('product.detail');

    // User Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add-to-cart', [CartController::class, 'add'])->name('cart.add');
    Route::get('/remove-cart/{id}', [CartController::class, 'remove'])->name('cart.remove');

    // User Checkout
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.process');

    // User Orders
    Route::get('/my-orders', [UserOrderController::class, 'index'])->name('user.orders');
    Route::get('/my-orders/{id}', [UserOrderController::class, 'show'])->name('user.orders.show');

    // ================= EMI ROUTES =================
    // Redirect EMI Dashboard to My Orders (consolidated)
    Route::get('/user/emi-dashboard', function () {
        return redirect()->route('user.orders');
    });

    Route::get('/user/emi-pay/{id}', [EmiPaymentController::class, 'paymentForm'])->name('user.emi.pay.form');
    Route::post('/user/emi-submit-payment/{id}', [EmiPaymentController::class, 'submitPayment'])->name('user.emi.submit-payment');
    Route::post('/user/emi-pay/{id}', [EmiPaymentController::class, 'emiPayment'])->name('user.emi.pay');

    // User Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('user.profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('user.profile.update');

    // Reviews
    Route::post('/order/{orderId}/review', [App\Http\Controllers\User\ReviewController::class, 'store'])->name('user.review.store');

});


// ================= ADMIN ROUTES =================
Route::middleware('auth')->prefix('admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Category CRUD (RESTful)
    Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
    Route::get('/category/create', [CategoryController::class, 'create'])->name('category.create');
    Route::post('/category', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/category/{id}/edit', [CategoryController::class, 'edit'])->name('category.edit');
    Route::put('/category/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('category.delete');

    // Product CRUD (RESTful)
    Route::get('/product', [ProductController::class, 'index'])->name('product.index');
    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product', [ProductController::class, 'store'])->name('product.store');
    Route::get('/product/{id}/edit', [ProductController::class, 'edit'])->name('product.edit');
    Route::put('/product/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/product/{id}', [ProductController::class, 'destroy'])->name('product.delete');

    // Orders (Admin)
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');

    // Payment Verification (Admin)
    Route::get('/payments/verification', [App\Http\Controllers\Admin\PaymentVerificationController::class, 'index'])->name('admin.payments.verification');
    Route::post('/payments/verify/{id}', [App\Http\Controllers\Admin\PaymentVerificationController::class, 'verify'])->name('admin.payment.verify');
    Route::post('/payments/reject/{id}', [App\Http\Controllers\Admin\PaymentVerificationController::class, 'reject'])->name('admin.payment.reject');

    // Reviews (Admin)
    Route::get('/reviews', [App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('admin.reviews.index');
    Route::get('/reviews/{id}', [App\Http\Controllers\Admin\ReviewController::class, 'show'])->name('admin.reviews.show');

});


// ======================================================
// ✅ IMPORTANT: Fallback Route (Always keep at LAST)
// Any random URL will redirect to login
// ======================================================
Route::fallback(function () {
    return redirect('/login');
});
