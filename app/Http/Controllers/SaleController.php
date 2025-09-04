<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['customer', 'items.product', 'createdBy']);

        // Search by invoice ID
        if ($request->filled('invoice_search')) {
            $query->where('invoice_no', 'like', '%' . $request->invoice_search . '%');
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('sale_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('sale_date', '<=', $request->end_date);
        }

        // Filter by customer
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $sales = $query->latest()->paginate(20);
        $customers = Customer::where('status', 'active')->get();

        return view('sales.index', compact('sales', 'customers'));
    }

    public function create()
    {
        $products = Product::where('status', 'active')->get();
        $customers = Customer::where('status', 'active')->get();

        return view('sales.create', compact('products', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,bank_transfer',
            'paid_amount' => 'required|numeric|min:0',
        ]);

        $sale = Sale::create([
            'invoice_no' => $this->generateInvoiceNumber(),
            'customer_id' => $request->customer_id,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'sale_date' => now(),
            'subtotal' => $request->subtotal,
            'tax_amount' => $request->tax_amount ?? 0,
            'discount_amount' => $request->discount_amount ?? 0,
            'total_amount' => $request->total_amount,
            'paid_amount' => $request->paid_amount,
            'due_amount' => $request->total_amount - $request->paid_amount,
            'payment_status' => ($request->total_amount - $request->paid_amount) <= 0 ? 'paid' : 'partial',
            'payment_method' => $request->payment_method,
            'note' => $request->note,
            'created_by' => auth()->id(),
        ]);

        foreach ($request->items as $item) {
            $sale->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['quantity'] * $item['unit_price'],
                'imei_numbers' => $item['imei_numbers'] ?? [],
                'serial_numbers' => $item['serial_numbers'] ?? [],
            ]);

            // Update product stock
            $product = Product::find($item['product_id']);
            $product->decrement('stock_quantity', $item['quantity']);
        }

        return redirect()->route('sales.invoice', $sale->id)
            ->with('success', 'Sale completed successfully!');
    }

    public function show(Sale $sale)
    {
        $sale->load(['customer', 'items.product.brand', 'items.product.category', 'createdBy']);
        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        // Load items with their product, inventory, and related product data
        $sale->load(['items.inventory.product', 'items.product']);
        //$products = Product::where('status', 'active')->get();
        $customers = Customer::where('status', 'active')->get();

        $query = Inventory::with(['product' => function($query) {
                $query->with(['brand', 'category'])
                    ->where('status', 'active');
            }])
            ->whereHas('product', function($q) {
                $q->where('status', 'active');
            });
            // ->where('quantity', '!=', 0);
        $inventories = $query->latest()->get();

        return view('sales.edit', compact('sale','inventories', 'customers'));
    }

    public function update(Request $request, Sale $sale)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'payment_method' => 'required|in:cash,card,bank_transfer',
            'paid_amount' => 'required|numeric|min:0',
        ]);

        $sale->update([
            'customer_id' => $request->customer_id,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'payment_method' => $request->payment_method,
            'paid_amount' => $request->paid_amount,
            'due_amount' => $sale->total_amount - $request->paid_amount,
            'payment_status' => ($sale->total_amount - $request->paid_amount) <= 0 ? 'paid' : 'partial',
            'note' => $request->note,
        ]);

        return redirect()->route('sales.index')
            ->with('success', 'Sale updated successfully!');
    }

    public function destroy(Sale $sale)
    {
        // Restore product stock
        foreach ($sale->items as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $product->increment('stock_quantity', $item->quantity);
            }
        }

        $sale->delete();

        return redirect()->route('sales.index')
            ->with('success', 'Sale deleted successfully!');
    }

    public function pos()
    {
        return view('sales.pos');
    }

    public function invoice(Sale $sale)
    {
        $sale->load(['customer', 'items.product.brand', 'items.product.category','items.product.inventory' ,'createdBy']);
        $settings = $this->getShopSettings();

        return view('sales.invoice', compact('sale', 'settings'));
    }

    public function print(Sale $sale)
    {
        $sale->load(['customer', 'items.product.brand', 'items.product.category','items.product.inventory' ,'createdBy']);
        $settings = $this->getShopSettings();

        // $pdf = Pdf::loadView('sales.print', compact('sale', 'settings'));

        // return $pdf->download('invoice-' . $sale->invoice_no . '.pdf');
        return view('sales.print', compact('sale', 'settings'));
    }

    // public function payment(Request $request, Sale $sale)
    // {
    //     $request->validate([
    //         'amount' => 'required|numeric|min:0|max:' . $sale->due_amount,
    //         'payment_method' => 'required|in:cash,card,bank_transfer',
    //     ]);

    //     $sale->increment('paid_amount', $request->amount);
    //     $sale->decrement('due_amount', $request->amount);

    //     if ($sale->due_amount <= 0) {
    //         $sale->update(['payment_status' => 'paid']);
    //     } else {
    //         $sale->update(['payment_status' => 'partial']);
    //     }

    //     return back()->with('success', 'Payment recorded successfully!');
    // }

    public function payment(Request $request, Sale $sale)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $sale->due_amount,
            'payment_method' => 'required|in:cash,card,bank_transfer,mobile_banking',
            'payment_date' => 'nullable|date'
        ]);

        try {
            DB::beginTransaction();

            $paymentAmount = $request->amount;

            $sale->paid_amount += $paymentAmount;
            $sale->due_amount -= $paymentAmount;

            if ($sale->due_amount <= 0) {
                $sale->payment_status = 'paid';
            } else {
                $sale->payment_status = 'partial';
            }

            $sale->save();

            // পেমেন্ট হিস্ট্রি রেকর্ড করতে চাইলে (যদি আপনার Payment মডেল থাকে)
            Payment::create([
                'sale_id' => $sale->id,
                'amount' => $paymentAmount,
                'payment_method' => $request->payment_method,
                'payment_date' => $request->payment_date ?? now(),
                'notes' => "Payment of ৳" . number_format($paymentAmount, 2) . " received against due amount of ৳" . number_format($sale->due_amount, 2),
                'received_by' => auth()->id()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment recorded successfully!',
                'new_paid_amount' => $sale->paid_amount,
                'new_due_amount' => $sale->due_amount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error recording payment: ' . $e->getMessage()
            ], 500);
        }
    }

    private function generateInvoiceNumber()
    {
        $prefix = SystemSetting::get('invoice_prefix', 'INV');
        $year = date('Y');
        $month = date('m');

        $lastSale = Sale::whereYear('created_at', $year)
                       ->whereMonth('created_at', $month)
                       ->orderBy('id', 'desc')
                       ->first();

        $sequence = $lastSale ? (int)substr($lastSale->invoice_no, -4) + 1 : 1;

        return $prefix . '-' . $year . $month . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    private function getShopSettings()
    {
        $settingKeys = ['shop_name', 'shop_address', 'shop_phone', 'shop_email', 'shop_logo', 'invoice_footer'];
        $settings = [];

        foreach ($settingKeys as $key) {
            $settings[$key] = SystemSetting::get($key);
        }

        return $settings;
    }
}
