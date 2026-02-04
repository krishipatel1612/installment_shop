<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::latest()->get();
        return view('admin.category.index', compact('categories'));
    }

    public function create(){
        return view('admin.category.create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|min:3'
        ]);

        Category::create([
            'name' => $request->name,
            'status' => $request->status ?? 1
        ]);

        return redirect()->route('category.index')->with('success','Category added successfully!');
    }

    public function edit($id){
        $category = Category::findOrFail($id);
        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required|min:3'
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'status' => $request->status ?? 1
        ]);

        return redirect()->route('category.index')->with('success','Category updated successfully!');
    }

    public function destroy($id){
        Category::findOrFail($id)->delete();
        return redirect()->route('category.index')->with('success','Category deleted successfully!');
    }
}
