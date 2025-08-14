<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\PurchaseItem;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with(['supplier', 'creator'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::where('status', 'active')->get();
        $products = Product::where('status', 'active')->get();

        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.unit_price' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Generate purchase number
            $lastPurchase = Purchase::latest()->first();
            $purchaseNo = 'PUR-' . date('Y') . '-' . str_pad(($lastPurchase ? $lastPurchase->id + 1 : 1), 6, '0', STR_PAD_LEFT);

            // Calculate totals
            $subtotal = 0;
            foreach ($request->products as $product) {
                $subtotal += $product['quantity'] * $product['unit_price'];
            }

            $taxAmount = $request->tax_amount ?? 0;
            $discountAmount = $request->discount_amount ?? 0;
            $shippingCost = $request->shipping_cost ?? 0;
            $totalAmount = $subtotal + $taxAmount + $shippingCost - $discountAmount;

            // Create purchase
            $purchase = Purchase::create([
                'purchase_no' => $purchaseNo,
                'supplier_id' => $request->supplier_id,
                'purchase_date' => $request->purchase_date,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'shipping_cost' => $shippingCost,
                'total_amount' => $totalAmount,
                'paid_amount' => $request->paid_amount ?? 0,
                'due_amount' => $totalAmount - ($request->paid_amount ?? 0),
                'payment_status' => $this->getPaymentStatus($totalAmount, $request->paid_amount ?? 0),
                'payment_method' => $request->payment_method,
                'note' => $request->note,
                'status' => $request->status ?? 'pending',
                'created_by' => Auth::id(),
            ]);

            // Create purchase items
            // foreach ($request->products as $product) {
            //     PurchaseItem::create([
            //         'purchase_id' => $purchase->id,
            //         'product_id' => $product['product_id'],
            //         'quantity' => $product['quantity'],
            //         'unit_price' => $product['unit_price'],
            //         'total_price' => $product['quantity'] * $product['unit_price'],
            //     ]);

            //     // Update product stock if purchase is received
            //     if ($request->status === 'received') {
            //         $productModel = Product::find($product['product_id']);
            //         $productModel->increment('stock_quantity', $product['quantity']);
            //     }


            // store মেথডে নিচের কোড যোগ করুন
            foreach ($request->products as $product) {
                $purchaseItem = PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'unit_price' => $product['unit_price'],
                    'total_price' => $product['quantity'] * $product['unit_price'],
                ]);

                // Save inventories if exists
                if (!empty($product['inventories'])) {
                    $inventories = json_decode($product['inventories'], true);

                    foreach ($inventories as $inventory) {
                        Inventory::create([
                            'product_id' => $product['product_id'],
                            'purchase_item_id' => $purchaseItem->id,
                            'sku' => $inventory['sku'],
                            'imei' => $inventory['imei'] ?? null,
                            'serial_number' => $inventory['serial_number'] ?? null,
                            'quantity' => $inventory['quantity'],
                            'purchase_price' => $inventory['purchase_price'],
                            'selling_price' => $inventory['selling_price'],
                            'attribute_combination' => json_encode($inventory['filters']),
                            'status' => 'in_stock',
                        ]);
                    }
                }

                // Update product stock if purchase is received
                // if ($request->status === 'received') {
                //     $productModel = Product::find($product['product_id']);
                //     $productModel->increment('stock_quantity', $product['quantity']);
                // }

            }

            DB::commit();
            return redirect()->route('purchases.index')->with('success', 'Purchase created successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error creating purchase: ' . $e->getMessage());
        }


    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'items.product', 'creator']);
        return view('purchases.show', compact('purchase'));
    }

    public function edit(Purchase $purchase)
    {
        $suppliers = Supplier::where('status', 'active')->get();
        $products = Product::where('status', 'active')->get();
        $purchase->load(['supplier', 'items.product']);

        return view('purchases.edit', compact('purchase', 'suppliers', 'products'));
    }

    public function update(Request $request, Purchase $purchase)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Calculate totals
            $subtotal = 0;
            foreach ($request->products as $product) {
                $subtotal += $product['quantity'] * $product['unit_price'];
            }

            $taxAmount = $request->tax_amount ?? 0;
            $discountAmount = $request->discount_amount ?? 0;
            $shippingCost = $request->shipping_cost ?? 0;
            $totalAmount = $subtotal + $taxAmount + $shippingCost - $discountAmount;

            // Update purchase
            $purchase->update([
                'supplier_id' => $request->supplier_id,
                'purchase_date' => $request->purchase_date,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'shipping_cost' => $shippingCost,
                'total_amount' => $totalAmount,
                'paid_amount' => $request->paid_amount ?? 0,
                'due_amount' => $totalAmount - ($request->paid_amount ?? 0),
                'payment_status' => $this->getPaymentStatus($totalAmount, $request->paid_amount ?? 0),
                'payment_method' => $request->payment_method,
                'note' => $request->note,
                'status' => $request->status ?? 'pending',
            ]);

            // Delete old items
            $purchase->items()->delete();

            // Create new purchase items
            foreach ($request->products as $product) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'unit_price' => $product['unit_price'],
                    'total_price' => $product['quantity'] * $product['unit_price'],
                ]);
            }

            DB::commit();
            return redirect()->route('purchases.index')->with('success', 'Purchase updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error updating purchase: ' . $e->getMessage());
        }
    }

    public function destroy(Purchase $purchase)
    {
        try {
            $purchase->items()->delete();
            $purchase->delete();
            return redirect()->route('purchases.index')->with('success', 'Purchase deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting purchase: ' . $e->getMessage());
        }
    }

    public function invoice(Purchase $purchase)
    {
        $purchase->load(['supplier', 'items.product', 'creator']);
        return view('purchases.invoice', compact('purchase'));
    }

    public function payment(Request $request, Purchase $purchase)
    {
        $request->validate([
            'payment_amount' => 'required|numeric|min:0|max:' . $purchase->due_amount,
            'payment_method' => 'required|string',
        ]);

        $purchase->update([
            'paid_amount' => $purchase->paid_amount + $request->payment_amount,
            'due_amount' => $purchase->due_amount - $request->payment_amount,
            'payment_status' => $this->getPaymentStatus($purchase->total_amount, $purchase->paid_amount + $request->payment_amount),
            'payment_method' => $request->payment_method,
        ]);

        return back()->with('success', 'Payment recorded successfully!');
    }

    private function getPaymentStatus($totalAmount, $paidAmount)
    {
        if ($paidAmount == 0) {
            return 'pending';
        } elseif ($paidAmount < $totalAmount) {
            return 'partial';
        } else {
            return 'paid';
        }
    }
}
