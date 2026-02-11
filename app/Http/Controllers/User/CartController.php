<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\InstallmentPlan;

class CartController extends Controller
{
    // ===============================
    // VIEW CART
    // ===============================
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('user.cart', compact('cart'));
    }

    // ===============================
    // ADD TO CART WITH EMI / DIRECT BUY
    // ===============================
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'emi_id' => 'required'
        ]);

        $product = Product::findOrFail($request->product_id);
        $cart = session()->get('cart', []);

        // EMI option selected
        if ($request->emi_id != 0) {

            $emi = InstallmentPlan::findOrFail($request->emi_id);

            $cart[$product->id] = [
                'id' => $product->id, // ✅ ADDED (important for checkout)
                'name' => $product->name,
                'price' => $product->price,
                'months' => $emi->months,
                'monthly_amount' => $emi->monthly_amount,
                'image' => $product->image
            ];

            $message = "Product added to cart with EMI option.";

        } else {

            // Direct Buy (Full Payment)
            $cart[$product->id] = [
                'id' => $product->id, // ✅ ADDED (important for checkout)
                'name' => $product->name,
                'price' => $product->price,
                'months' => 1,
                'monthly_amount' => $product->price,
                'image' => $product->image
            ];

            $message = "Product purchased directly!";
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', $message);
    }

    // ===============================
    // REMOVE ITEM
    // ===============================
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Item removed');
    }
}
