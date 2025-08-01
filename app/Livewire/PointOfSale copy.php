<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\DB;

class PointOfSale extends Component
{
    public $products = [];
    public $customers = [];
    public $cart = [];
    public $selectedCustomer = null;
    public $customerName = '';
    public $customerPhone = '';
    public $customerAddress = '';
    public $searchProduct = '';
    public $paymentMethod = 'cash';
    public $paidAmount = 0;
    public $discount = 0;
    public $tax = 0;
    public $note = '';


    protected $rules = [
        'cart' => 'required|array|min:1',
        'paymentMethod' => 'required|in:cash,card,bank_transfer',
        'paidAmount' => 'required|numeric|min:0',
    ];

    public function mount()
    {
        $this->products = Product::where('status', 'active')
            ->where('stock_quantity', '>', 0)
            ->with(['brand', 'category'])
            ->get();

        $this->customers = Customer::where('status', 'active')->get();
        $this->tax = SystemSetting::where('key', 'default_tax_rate')->value('value') ?? 0;
    }

    public function updatedSearchProduct()
    {
        if (strlen($this->searchProduct) >= 2) {
            $this->products = Product::where('status', 'active')
                ->where('stock_quantity', '>', 0)
                ->where(function($query) {
                    $query->where('name', 'like', '%' . $this->searchProduct . '%')
                          ->orWhere('model', 'like', '%' . $this->searchProduct . '%')
                          ->orWhere('imei', 'like', '%' . $this->searchProduct . '%');
                })
                ->with(['brand', 'category'])
                ->get();
        } else {
            $this->products = Product::where('status', 'active')
                ->where('stock_quantity', '>', 0)
                ->with(['brand', 'category'])
                ->get();
        }
    }

    public function asd()
    {
        $query = Product::with(['brand', 'category'])
            ->where('status', 'active')
            ->where('stock_quantity', '>', 0);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('model', 'like', '%' . $this->search . '%')
                  ->orWhere('imei', 'like', '%' . $this->search . '%');
            });
        }

        $products = $query->get();
        $customers = Customer::where('status', 'active')->get();

        return view('livewire.point-of-sale', compact('products', 'customers'));
    }

    public function addToCart($productId)
    {
        $product = Product::find($productId);

        if (!$product || $product->stock_quantity <= 0) {
            session()->flash('error', 'Product not available or out of stock!');
            return;
        }

        $existingIndex = collect($this->cart)->search(function ($item) use ($productId) {
            return $item['product_id'] == $productId;
        });

        if ($existingIndex !== false) {
            if ($this->cart[$existingIndex]['quantity'] < $product->stock_quantity) {
                $this->cart[$existingIndex]['quantity']++;
                $this->cart[$existingIndex]['total'] = $this->cart[$existingIndex]['quantity'] * $this->cart[$existingIndex]['selling_price'];
            } else {
                session()->flash('error', 'Cannot add more items. Stock limit reached!');
            }
        } else {
            $this->cart[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                //'code' => $product->code,
                'price' => $product->selling_price,
                'quantity' => 1,
                'total' => $product->selling_price,
                'stock' => $product->stock_quantity
            ];
        }
    }

    // public function addToCart($productId)
    // {
    //     $product = Product::find($productId);

    //     if (!$product || $product->stock_quantity <= 0) {
    //         session()->flash('error', 'Product not available!');
    //         return;
    //     }

    //     if (isset($this->cart[$productId])) {
    //         if ($this->cart[$productId]['quantity'] >= $product->stock_quantity) {
    //             session()->flash('error', 'Insufficient stock!');
    //             return;
    //         }
    //         $this->cart[$productId]['quantity']++;
    //     } else {
    //         $this->cart[$productId] = [
    //             'id' => $product->id,
    //             'name' => $product->name,
    //             'price' => $product->selling_price,
    //             'quantity' => 1,
    //             'stock' => $product->stock_quantity
    //         ];
    //     }
    // }


    public function updateQuantity($index, $quantity)
    {
        if ($quantity <= 0) {
            unset($this->cart[$index]);
            $this->cart = array_values($this->cart);
            return;
        }

        if ($quantity > $this->cart[$index]['stock']) {
            session()->flash('error', 'Quantity cannot exceed stock limit!');
            return;
        }

        $this->cart[$index]['quantity'] = $quantity;
        $this->cart[$index]['total'] = $quantity * $this->cart[$index]['price'];

        // $this->cart[$index]['quantity'] = $quantity;
        // $this->cart[$index]['total'] = $quantity * $this->cart[$index]['price']; // quantity * price
    }

    public function removeFromCart($index)
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
    }

    public function clearCart()
    {
        $this->cart = [];
        $this->selectedCustomer = null;
        $this->customerName = '';
        $this->customerPhone = '';
        $this->customerAddress = '';
        $this->paidAmount = 0;
        $this->discount = 0;
        $this->note = '';
    }

    public function getSubtotalProperty()
    {
        return collect($this->cart)->sum('total');
    }

    public function getDiscountAmountProperty()
    {
        return ($this->subtotal * $this->discount) / 100;
    }

    public function getTaxAmountProperty()
    {
        return (($this->subtotal - $this->discountAmount) * $this->tax) / 100;
    }

    public function getTotalProperty()
    {
        return $this->subtotal - $this->discountAmount + $this->taxAmount;
    }

    public function getDueAmountProperty()
    {
        return max(0, $this->total - $this->paidAmount);
    }

    private function generateInvoiceNumber()
    {
        $prefix = SystemSetting::where('key', 'invoice_prefix')->value('value') ?? 'INV';
        $currentYear = date('Y');
        $currentMonth = date('m');

        // Get the last invoice number for current month
        $lastSale = Sale::whereYear('created_at', $currentYear)
                       ->whereMonth('created_at', $currentMonth)
                       ->orderBy('id', 'desc')
                       ->first();

        if ($lastSale && preg_match('/(\d+)$/', $lastSale->invoice_no, $matches)) {
            $lastNumber = intval($matches[1]);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // Format: INV-2024-01-0001
        return $prefix . '-' . $currentYear . '-' . $currentMonth . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function processSale()
    {
       $this->validate();
       //dd('ok');

        if (empty($this->cart)) {
            session()->flash('error', 'Cart is empty!');
            return;
        }

        DB::beginTransaction();

        try {
            $customerId = null;

            // Handle customer creation or selection
            if ($this->selectedCustomer) {
                $customerId = $this->selectedCustomer;
            } elseif ($this->customerName && $this->customerPhone) {
                // Create new customer
                $customer = Customer::create([
                    'name' => $this->customerName,
                    'phone' => $this->customerPhone,
                    'address' => $this->customerAddress,
                    'status' => 'active',
                    'created_by' => auth()->id()
                ]);
                $customerId = $customer->id;

                // Refresh customers list
                $this->customers = Customer::where('status', 'active')->get();
            }

            // Create sale
            $sale = Sale::create([
                'invoice_no' => $this->generateInvoiceNumber(),
                'customer_id' => $customerId,
                'customer_name' => $customerId ? null : $this->customerName,
                'customer_phone' => $customerId ? null : $this->customerPhone,
                'sale_date' => now(),
                'subtotal' => $this->subtotal,
                'tax_amount' => $this->taxAmount,
                'discount_amount' => $this->discountAmount,
                'total_amount' => $this->total,
                'paid_amount' => $this->paidAmount,
                'due_amount' => $this->dueAmount,
                'payment_status' => $this->dueAmount <= 0 ? 'paid' : 'partial',
                'payment_method' => $this->paymentMethod,
                'note' => $this->note,
                'sale_type' => 'retail',
                'created_by' => auth()->id()
            ]);

            // Create sale items and update stock
            foreach ($this->cart as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['total']
                ]);

                // Update product stock
                $product = Product::find($item['product_id']);
                $product->decrement('stock_quantity', $item['quantity']);
            }

            DB::commit();

            session()->flash('success', 'Sale completed successfully! Invoice: ' . $sale->invoice_no);

            // Clear cart and redirect to invoice
            $this->clearCart();

            return redirect()->route('sales.invoice', $sale->id);

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Error processing sale: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.point-of-sale');
    }
}
