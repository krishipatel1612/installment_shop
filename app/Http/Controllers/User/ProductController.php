<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // ===============================
    // PRODUCT LIST (SEARCH + CATEGORY)
    // ===============================
    public function index(Request $request)
    {
        // Categories (status hoy to thik, nai hoy to aa pan hatai sakay)
        $categories = Category::all();

        $query = Product::with('category');

        // 🔍 SEARCH
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 📂 CATEGORY FILTER
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->get();

        return view('user.products', compact('products', 'categories'));
    }

    // ===============================
    // PRODUCT DETAIL + EMI
    // ===============================
    public function show($id)
    {
        $product = Product::with('installmentPlans')->findOrFail($id);

        return view('user.product_detail', compact('product'));
    }
}
