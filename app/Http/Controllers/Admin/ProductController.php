<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\InstallmentPlan;

class ProductController extends Controller
{
    // ===============================
    // PRODUCT LIST
    // ===============================
    public function index()
    {
        $products = Product::with('installmentPlans')->latest()->get();
        return view('admin.product.index', compact('products'));
    }

    // ===============================
    // CREATE PRODUCT
    // ===============================
    public function create()
    {
        $categories = Category::where('status',1)->get();
        return view('admin.product.create', compact('categories'));
    }

    // ===============================
    // STORE PRODUCT + EMI AUTO
    // ===============================
    public function store(Request $request)
    {
        $request->validate([
            'category_id'   => 'required',
            'name'          => 'required|min:3',
            'price'         => 'required|numeric|min:1',
            'image'         => 'required|image',
            'interest_rate' => 'nullable|numeric|min:0'
        ]);

        // IMAGE UPLOAD
        $imgName = time().'.'.$request->image->extension();
        $request->image->move(public_path('uploads/products'), $imgName);

        // PRODUCT SAVE
        $product = Product::create([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'price'       => $request->price,
            'image'       => $imgName,
            'description' => $request->description
        ]);

        // EMI CONFIG
        $monthsArr    = [3,6,9,12];
        $interestRate = $request->interest_rate ?? 10; // use input or default 10%

        foreach ($monthsArr as $month) {
            $totalAmount = $product->price + ($product->price * $interestRate / 100);
            $monthlyAmount = round($totalAmount / $month, 2);

            InstallmentPlan::create([
                'product_id'     => $product->id,
                'months'         => $month,
                'interest_rate'  => $interestRate,
                'total_amount'   => round($totalAmount,2),
                'monthly_amount' => $monthlyAmount
            ]);
        }

        return redirect()->route('product.index')
            ->with('success','Product & EMI added successfully');
    }

    // ===============================
    // EDIT PRODUCT
    // ===============================
    public function edit($id)
    {
        $product = Product::with('installmentPlans')->findOrFail($id);
        $categories = Category::where('status',1)->get();

        return view('admin.product.edit', compact('product','categories'));
    }

    // ===============================
    // UPDATE PRODUCT + EMI DYNAMIC
    // ===============================
    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id'   => 'required',
            'name'          => 'required|min:3',
            'price'         => 'required|numeric|min:1',
            'interest_rate' => 'nullable|numeric|min:0'
        ]);

        $product   = Product::findOrFail($id);
        $oldPrice  = (float) $product->price;
        $newPrice  = (float) $request->price;
        $interestRate = $request->interest_rate ?? 10; // dynamic interest rate

        // IMAGE
        $imgName = $product->image;
        if ($request->hasFile('image')) {
            $imgName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/products'), $imgName);
        }

        // UPDATE PRODUCT
        $product->update([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'price'       => $newPrice,
            'image'       => $imgName,
            'description' => $request->description
        ]);

        // 🔥 REGENERATE EMI IF PRICE OR INTEREST CHANGED
        $oldInterest = $product->installmentPlans->first()->interest_rate ?? 10;

        if ($oldPrice != $newPrice || $oldInterest != $interestRate) {

            InstallmentPlan::where('product_id', $product->id)->delete();

            $monthsArr = [3,6,9,12];

            foreach ($monthsArr as $month) {
                $totalAmount = $newPrice + ($newPrice * $interestRate / 100);
                $monthlyAmount = round($totalAmount / $month, 2);

                InstallmentPlan::create([
                    'product_id'     => $product->id,
                    'months'         => $month,
                    'interest_rate'  => $interestRate,
                    'total_amount'   => round($totalAmount,2),
                    'monthly_amount' => $monthlyAmount
                ]);
            }
        }

        return redirect()->route('product.index')
            ->with('success','Product updated successfully');
    }

    // ===============================
    // DELETE PRODUCT
    // ===============================
    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return redirect()->route('product.index')
            ->with('success','Product deleted');
    }
}
