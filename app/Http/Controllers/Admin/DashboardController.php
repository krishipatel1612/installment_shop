<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCategories = Category::count();
        $totalProducts = Product::count();
        $totalUsers = User::where('role','user')->count();

        return view('admin.dashboard', compact(
            'totalCategories',
            'totalProducts',
            'totalUsers'
        ));
    }
}
