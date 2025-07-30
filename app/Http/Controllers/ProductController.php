<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['brand', 'category'])->latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $brands = Brand::where('status', 'active')->get();
        $categories = Category::where('status', 'active')->get();
        return view('products.create', compact('brands', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'model' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'wholesale_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'min_stock_alert' => 'required|integer|min:1',
            'imei' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'warranty_months' => 'nullable|integer|min:0',
            'warranty_terms' => 'nullable|string',
            'product_type' => 'nullable|in:mobile,accessory,other',
            'status' => 'required|in:active,inactive'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['sku'] = 'PRD-' . strtoupper(Str::random(8));

        Product::create($data);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['brand', 'category', 'purchaseItems', 'saleItems']);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $brands = Brand::where('status', 'active')->get();
        $categories = Category::where('status', 'active')->get();
        return view('products.edit', compact('product', 'brands', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'model' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'wholesale_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'min_stock_alert' => 'required|integer|min:1',
            'imei' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'warranty_months' => 'nullable|integer|min:0',
            'warranty_terms' => 'nullable|string',
            'product_type' => 'nullable|in:mobile,accessory,other',
            'status' => 'required|in:active,inactive'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        $product->update($data);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->saleItems()->count() > 0 || $product->purchaseItems()->count() > 0) {
            return redirect()->route('products.index')
                ->with('error', 'Cannot delete product with associated transactions.');
        }

        $product->delete();
        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
