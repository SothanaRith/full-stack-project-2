<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductModel;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    function productView()
    {
        $products = ProductModel::all();
        return view('productView', ['products' => $products]);
    }

    function productFormView()
    {
        return view('productFormView');
    }

    function createProduct(Request $request)
    {
        $validatedData = $request->validate([
            'badge' => 'nullable|string|max:50',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'tagline' => 'required|string|max:255',
            'images' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
        ]);

        if ($request->hasFile('images')) {
            $imagePath = $request->file('images')->store('products', 'public');
            $validatedData['images'] = $imagePath;
        }

        ProductModel::create($validatedData);

        return redirect('/product-view');
    }

    function editProductView($id)
    {
        $product = ProductModel::findOrFail($id);
        return view('productEditView', ['product' => $product]);
    }

    function updateProduct(Request $request, $id)
    {
        $product = ProductModel::findOrFail($id);

        $validatedData = $request->validate([
            'badge' => 'nullable|string|max:50',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'tagline' => 'required|string|max:255',
            'images' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
        ]);

        if ($request->hasFile('images')) {
            // Delete old stored image if it exists on disk
            if ($product->images && Storage::disk('public')->exists($product->images)) {
                Storage::disk('public')->delete($product->images);
            }
            $imagePath = $request->file('images')->store('products', 'public');
            $validatedData['images'] = $imagePath;
        } else {
            unset($validatedData['images']);
        }

        $product->update($validatedData);

        return redirect('/product-view');
    }

    function deleteProduct($id)
    {
        $product = ProductModel::findOrFail($id);
        if ($product->images && Storage::disk('public')->exists($product->images)) {
            Storage::disk('public')->delete($product->images);
        }
        $product->delete();
        return redirect('/product-view');
    }

    function displaySubject(Request $request)
    {
        echo $request->id . "<br>" . $request->type . "<br>";
    }
}
