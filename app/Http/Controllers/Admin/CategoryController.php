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
            'name' => 'required|min:3|max:255|unique:categories'
        ], [
            'name.required' => 'Category name is required',
            'name.min' => 'Category name must be at least 3 characters',
            'name.max' => 'Category name cannot exceed 255 characters',
            'name.unique' => 'This category name already exists'
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
            'name' => 'required|min:3|max:255|unique:categories,name,' . $id
        ], [
            'name.required' => 'Category name is required',
            'name.min' => 'Category name must be at least 3 characters',
            'name.max' => 'Category name cannot exceed 255 characters',
            'name.unique' => 'This category name already exists'
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
