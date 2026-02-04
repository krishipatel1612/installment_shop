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

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Default Welcome Page
Route::get('/', function () {
    return view('auth.login');
});

// ================= AUTH ROUTES =================

// Login
Route::get('/login',[AuthController::class,'loginForm'])->name('login');
Route::post('/login',[AuthController::class,'login']);
Route::get('/logout',[AuthController::class,'logout'])->name('logout');

// Register (User)
Route::get('/register',[AuthController::class,'registerForm'])->name('register');
Route::post('/register',[AuthController::class,'register']);

// ================= USER ROUTES =================
Route::middleware('auth')->group(function(){

    // User Home Page
    Route::get('/home', [HomeController::class, 'index'])->name('user.home');

    // User Products
    Route::get('/products', [UserProductController::class, 'index'])->name('products.index');
    Route::get('/product/{id}', [UserProductController::class, 'show'])->name('product.detail');

    // User Cart
    Route::get('/cart', [CartController::class,'index'])->name('cart.index');
    Route::post('/add-to-cart', [CartController::class,'add'])->name('cart.add');
    Route::get('/remove-cart/{id}', [CartController::class,'remove'])->name('cart.remove');

    // User Checkout
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.process');

    // User Orders
    Route::get('/my-orders', [UserOrderController::class, 'index'])->name('user.orders');
    Route::get('/my-orders/{id}', [UserOrderController::class, 'show'])->name('user.orders.show');

    // EMI Payment
    Route::post('/emi/pay/{id}', [UserOrderController::class, 'payEmi'])->name('user.emi.pay');

    // User Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('user.profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('user.profile.update');

});

// ================= ADMIN ROUTES =================
Route::middleware('auth')->prefix('admin')->group(function(){

    // Dashboard
    Route::get('/dashboard', [DashboardController::class,'index'])->name('admin.dashboard');

    // Category CRUD (RESTful)
    Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
    Route::get('/category/create', [CategoryController::class, 'create'])->name('category.create');
    Route::post('/category', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/category/{id}/edit', [CategoryController::class, 'edit'])->name('category.edit');
    Route::put('/category/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('category.delete');

    // Product CRUD (RESTful)
    Route::get('/product', [ProductController::class,'index'])->name('product.index');
    Route::get('/product/create', [ProductController::class,'create'])->name('product.create');
    Route::post('/product', [ProductController::class,'store'])->name('product.store');
    Route::get('/product/{id}/edit', [ProductController::class,'edit'])->name('product.edit');
    Route::put('/product/{id}', [ProductController::class,'update'])->name('product.update');
    Route::delete('/product/{id}', [ProductController::class,'destroy'])->name('product.delete');

    // Orders (Admin)
    Route::get('/orders', [AdminOrderController::class,'index'])->name('admin.orders');
    Route::get('/orders/{id}', [AdminOrderController::class,'show'])->name('admin.orders.show');

});
