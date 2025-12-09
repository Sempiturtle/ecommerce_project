<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Redis;
use App\Models\Product;

use PhpParser\Node\Expr\Print_;
use function Flasher\Toastr\Prime\toastr;

class AdminController extends Controller
{
    public function view_category()
    {
        $data = Category::all();
        return view('admin.category', compact('data'));
    }

    public function add_category(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255'
        ]);

        Category::create([
            'category_name' => $request->category
        ]);
        toastr()->timeOut(5000)->closeButton()->addSuccess('Category Added Successfully.');

        return redirect()->back();
    }

    public function delete_category($id)
    {
        $data = Category::findOrFail($id);
        $data->delete();
        toastr()->timeOut(5000)->closeButton()->addSuccess('Category Deleted Successfully.');
        return redirect()->back();
    }

    public function edit_category($id)
    {
        $data = Category::findOrFail($id);

        return view('admin.edit_category', compact('data'));
    }

    public function update_category(Request $request, $id)
    {
        $data = Category::findOrFail($id);
        $data->category_name = $request->category;
        $data->save();
        toastr()->timeOut(5000)->closeButton()->addSuccess('Category Updated Successfully.');
        return redirect('/view_category');
    }

    public function add_product()
    {

        $category = Category::all();

        return view('admin.add_product', compact('category'));
    }

    public function upload_product(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'category' => 'required',
            'image' => 'required|image'
        ]);


        $image = $request->file('image');
        $imagename = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('products'), $imagename);

        Product::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'category' => $request->category,
            'image' => $image,  
        ]);

        return redirect()->back()->with('success', 'Product uploaded successfully!');
    }

    public function view_product()
    {

        $product = Product::all();
        return view('admin.view_product', compact('product'));
    }
}
