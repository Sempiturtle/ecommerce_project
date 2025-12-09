<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

use function Flasher\Toastr\Prime\toastr;

class AdminController extends Controller
{
    // ---------------------------
    // CATEGORY METHODS
    // ---------------------------
    public function view_category()
    {
        $categories = Category::all();
        return view('admin.category', compact('categories'));
    }

    public function add_category(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
        ]);

        Category::create([
            'category_name' => $request->category,
        ]);

        toastr()->timeOut(5000)->closeButton()->addSuccess('Category Added Successfully.');

        return redirect()->back();
    }

    public function delete_category($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        toastr()->timeOut(5000)->closeButton()->addSuccess('Category Deleted Successfully.');
        return redirect()->back();
    }

    public function edit_category($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.edit_category', compact('category'));
    }

    public function update_category(Request $request, $id)
    {
        $request->validate([
            'category' => 'required|string|max:255',
        ]);

        $category = Category::findOrFail($id);
        $category->category_name = $request->category;
        $category->save();

        toastr()->timeOut(5000)->closeButton()->addSuccess('Category Updated Successfully.');
        return redirect()->route('view_category'); // Assuming you have named route
    }

    // ---------------------------
    // PRODUCT METHODS
    // ---------------------------
    public function add_product()
    {
        $categories = Category::all();
        return view('admin.add_product', compact('categories'));
    }

    public function upload_product(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'category' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Handle image upload
        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('products'), $imageName);
        }

        Product::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'category' => $request->category,
            'image' => $imageName,
        ]);

        toastr()->timeOut(5000)->closeButton()->addSuccess('Product uploaded successfully!');
        return redirect()->back();
    }

    public function view_product()
    {
        $products = Product::paginate(3);
        return view('admin.view_product', compact('products'));
    }
}
