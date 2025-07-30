<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\DamagedProduct;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'permission:inventory-management']);
    }

    public function index(Request $request)
    {
        $query = Product::with(['brand', 'category']);
        
        // Apply filters
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%')
                  ->orWhere('model', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->has('brand_id') && $request->brand_id) {
            $query->where('brand_id', $request->brand_id);
        }
        
        if ($request->has('stock_status') && $request->stock_status) {
            if ($request->stock_status === 'low_stock') {
                $query->whereRaw('stock_quantity <= min_stock_alert');
            } elseif ($request->stock_status === 'out_of_stock') {
                $query->where('stock_quantity', 0);
            } elseif ($request->stock_status === 'in_stock') {
                $query->where('stock_quantity', '>', 0);
            }
        }
        
        $products = $query->paginate(15);
        
        // Calculate summary
        $summary = [
            'total_products' => Product::count(),
            'low_stock_products' => Product::whereRaw('stock_quantity <= min_stock_alert')->count(),
            'out_of_stock' => Product::where('stock_quantity', 0)->count(),
            'total_value' => Product::sum(\DB::raw('stock_quantity * selling_price'))
        ];
        
        $categories = Category::where('status', 'active')->get();
        $brands = Brand::where('status', 'active')->get();
        
        return view('inventory.index', compact('products', 'summary', 'categories', 'brands'));
    }

    public function damaged()
    {
        $damagedProducts = DamagedProduct::with(['product'])->latest()->paginate(10);
        $products = Product::where('status', 'active')->get();
        return view('inventory.damaged', compact('damagedProducts', 'products'));
    }

    public function storeDamaged(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'damage_type' => 'required|string',
            'damage_date' => 'required|date',
            'description' => 'required|string',
            'loss_amount' => 'required|numeric|min:0',
        ]);

        $product = Product::findOrFail($request->product_id);
        
        // Check if quantity is valid
        if ($request->quantity > $product->stock_quantity) {
            return back()->with('error', 'Damaged quantity cannot exceed available stock');
        }

        // Create damaged product record
        $damaged = DamagedProduct::create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'damage_type' => $request->damage_type,
            'damage_date' => $request->damage_date,
            'description' => $request->description,
            'loss_amount' => $request->loss_amount,
            'action_taken' => $request->action_taken,
            'reported_by' => auth()->id(),
        ]);

        // Handle image upload if present
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('damaged-products', 'public');
            $damaged->update(['image' => $path]);
        }

        // Update product stock
        $product->decrement('stock_quantity', $request->quantity);

        return redirect()->route('inventory.damaged')->with('success', 'Damaged product recorded successfully');
    }
}
