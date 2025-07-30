<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleItem;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class PointOfSale extends Component
{
    public $search = '';
    public $cart = [];
    public $selectedCustomer = null;
    public $customerName = '';
    public $customerPhone = '';
    public $paymentMethod = 'cash';
    public $paidAmount = 0;
    public $discountAmount = 0;
    public $taxAmount = 0;
    public $note = '';

    public function addToCart($productId)
    {
        $product = Product::find($productId);
        
        if (!$product || $product->stock_quantity <= 0) {
            session()->flash('error', 'Product not available!');
            return;
        }

        if (isset($this->cart[$productId])) {
            if ($this->cart[$productId]['quantity'] >= $product->stock_quantity) {
                session()->flash('error', 'Insufficient stock!');
                return;
            }
            $this->cart[$productId]['quantity']++;
        } else {
            $this->cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->selling_price,
                'quantity' => 1,
                'stock' => $product->stock_quantity
            ];
        }
    }

    public function updateQuantity($productId, $quantity)
    {
        if ($quantity <= 0) {
            unset($this->cart[$productId]);
        } else {
            $this->cart[$productId]['quantity'] = $quantity;
        }
    }

    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
    }

    public function getSubtotalProperty()
    {
        return collect($this->cart)->sum(function($item) {
            return $item['price'] * $item['quantity'];
        });
    }

    public function getTotalProperty()
    {
        return $this->subtotal - $this->discountAmount + $this->taxAmount;
    }

    public function getDueAmountProperty()
    {
        return $this->total - $this->paidAmount;
    }

    public function processSale()
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Cart is empty!');
            return;
        }

        DB::beginTransaction();
        
        try {
            // Create sale
            $sale = Sale::create([
                'invoice_no' => 'INV-' . date('Ymd') . '-' . str_pad(Sale::count() + 1, 4, '0', STR_PAD_LEFT),
                'customer_id' => $this->selectedCustomer,
                'customer_name' => $this->customerName ?: null,
                'customer_phone' => $this->customerPhone ?: null,
                'sale_date' => now(),
                'subtotal' => $this->subtotal,
                'tax_amount' => $this->taxAmount,
                'discount_amount' => $this->discountAmount,
                'total_amount' => $this->total,
                'paid_amount' => $this->paidAmount,
                'due_amount' => $this->dueAmount,
                'payment_status' => $this->dueAmount <= 0 ? 'paid' : ($this->paidAmount > 0 ? 'partial' : 'pending'),
                'payment_method' => $this->paymentMethod,
                'note' => $this->note,
                'created_by' => auth()->id(),
            ]);

            // Create sale items and update stock
            foreach ($this->cart as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['price'] * $item['quantity'],
                ]);

                // Update product stock
                $product = Product::find($item['id']);
                $product->decrement('stock_quantity', $item['quantity']);
            }

            DB::commit();
            
            session()->flash('message', 'Sale completed successfully! Invoice: ' . $sale->invoice_no);
            $this->resetForm();
            
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Error processing sale: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->cart = [];
        $this->selectedCustomer = null;
        $this->customerName = '';
        $this->customerPhone = '';
        $this->paymentMethod = 'cash';
        $this->paidAmount = 0;
        $this->discountAmount = 0;
        $this->taxAmount = 0;
        $this->note = '';
    }

    public function render()
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
}
