<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductModel;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display the Admin Dashboard with full product management.
     */
    public function dashboard(Request $request)
    {
        $search = $request->query('search');

        $query = ProductModel::query()->latest();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('tagline', 'like', "%{$search}%")
                  ->orWhere('badge', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->get();

        $stats = [
            'total_products' => ProductModel::count(),
            'avg_price' => ProductModel::avg('price') ?? 0,
            'badges_count' => ProductModel::whereNotNull('badge')->where('badge', '!=', '')->count(),
        ];

        return view('dashboard', [
            'products' => $products,
            'stats' => $stats,
            'search' => $search,
        ]);
    }

    /**
     * Display the customer product catalog/menu (View only).
     */
    public function productView(Request $request)
    {
        $search = $request->query('search');
        $query = ProductModel::query()->latest();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('tagline', 'like', "%{$search}%")
                  ->orWhere('badge', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->get();

        return view('productView', [
            'products' => $products,
            'search' => $search,
        ]);
    }

    /**
     * Fetch single product detail (JSON or for modal).
     */
    public function show($id)
    {
        $product = ProductModel::findOrFail($id);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'id' => $product->id,
                'name' => $product->name,
                'tagline' => $product->tagline,
                'badge' => $product->badge,
                'price' => $product->price,
                'currency' => $product->currency,
                'description' => $product->description,
                'image_url' => $product->image_url,
                'created_at' => $product->created_at?->format('M d, Y'),
            ]);
        }

        return view('productView', ['products' => ProductModel::all(), 'selectedProduct' => $product]);
    }

    /**
     * Display the form to create a new product (Admin only).
     */
    public function productFormView()
    {
        return view('productFormView');
    }

    /**
     * Store a newly created product (Admin only).
     */
    public function createProduct(Request $request)
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

        return redirect()->route('dashboard')->with('success', 'Product created successfully!');
    }

    /**
     * Display the edit form for a product (Admin only).
     */
    public function editProductView($id)
    {
        $product = ProductModel::findOrFail($id);
        return view('productEditView', ['product' => $product]);
    }

    /**
     * Update an existing product (Admin only).
     */
    public function updateProduct(Request $request, $id)
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

        return redirect()->route('dashboard')->with('success', 'Product updated successfully!');
    }

    /**
     * Delete a product (Admin only).
     */
    public function deleteProduct($id)
    {
        $product = ProductModel::findOrFail($id);
        if ($product->images && Storage::disk('public')->exists($product->images)) {
            Storage::disk('public')->delete($product->images);
        }
        $product->delete();

        return redirect()->route('dashboard')->with('success', 'Product deleted successfully!');
    }
}
