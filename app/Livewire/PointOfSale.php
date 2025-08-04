<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SystemSetting;
use App\Models\Inventory;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class PointOfSale extends Component
{
    public $search = '';
    public $cart = [];
    public $selectedCustomer = null;
    public $customerName = '';
    public $customerPhone = '';
    public $customerAddress = '';
    public $paymentMethod = 'cash';
    public $paidAmount = 0;
    public $discountAmount = 0;
    public $taxAmount = 0;
    public $note = '';
    public $showImeiModal = false;
    public $currentCartIndex = null;
    public $imeiNumbers = [];
    public $serialNumbers = [];

    // public function addToCart($productId)
    // {
    //     $product = Product::find($productId);

    //     if (!$product || $product->stock_quantity <= 0) {
    //         session()->flash('error', 'Product not available!');
    //         return;
    //     }

    //     $existingIndex = collect($this->cart)->search(function ($item) use ($productId) {
    //         return $item['product_id'] == $productId;
    //     });

    //     if ($existingIndex !== false) {
    //         if ($this->cart[$existingIndex]['quantity'] >= $product->stock_quantity) {
    //             session()->flash('error', 'Insufficient stock!');
    //             return;
    //         }
    //         $this->cart[$existingIndex]['quantity']++;

    //         // Add empty IMEI/Serial for new quantity
    //         // $newIndex = $this->cart[$existingIndex]['quantity'] - 1;
    //         // $this->cart[$existingIndex]['imei_numbers'][$newIndex] = '';
    //         // $this->cart[$existingIndex]['serial_numbers'][$newIndex] = '';
    //     } else {
    //         $this->cart[] = [
    //             'product_id' => $product->id,
    //             'name' => $product->name,
    //             'price' => $product->selling_price,
    //             'quantity' => 1,
    //             'stock' => $product->stock_quantity,
    //             'imei_numbers' => [''],
    //             'serial_numbers' => ['']
    //         ];
    //     }
    // }

    public function addToCart($inventoryId) // Changed parameter to accept inventory ID
    {
        $inventory = Inventory::with('product')->find($inventoryId);

        if (!$inventory || !$inventory->product || $inventory->quantity <= 0) {
            session()->flash('error', 'Product not available!');
            return;
        }

        $product = $inventory->product;
        $existingIndex = collect($this->cart)->search(function ($item) use ($inventoryId) {
            return $item['inventory_id'] == $inventoryId; // Changed to check inventory_id
        });

        $attributes = json_decode($inventory->attribute_combination, true) ?? [];

        // Get human-readable attribute values
        $attributeDisplay = [];
        foreach ($attributes as $filterId => $optionId) {
            $filter = \App\Models\Filter::find($filterId);
            $option = \App\Models\FilterOption::find($optionId);

            if ($filter && $option) {
                $attributeDisplay[$filter->name] = $option->value;
            }
        }

        if ($existingIndex !== false) {
            if ($this->cart[$existingIndex]['quantity'] >= $inventory->quantity) {
                session()->flash('error', 'Insufficient stock for this item!');
                return;
            }
            $this->cart[$existingIndex]['quantity']++;

            // Add the IMEI/serial for the new quantity
            $this->cart[$existingIndex]['imei_numbers'][] = $inventory->imei;
            $this->cart[$existingIndex]['serial_numbers'][] = $inventory->serial_number;
        } else {
            $this->cart[] = [
                'inventory_id' => $inventory->id, // Added inventory ID
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $inventory->selling_price, // Use inventory price if different
                'quantity' => 1,
                'stock' => $inventory->quantity, // Use inventory stock
                // 'imei_numbers' => [$inventory->imei], // Add actual IMEI
                // 'serial_numbers' => [$inventory->serial_number], // Add actual serial
                // 'attributes' => json_decode($inventory->attribute_combination, true) ?? [] // Add attributes

                'imei_numbers' => [$inventory->imei],
                'serial_numbers' => [$inventory->serial_number],
                'attributes' => $attributeDisplay, // Add formatted attributes
                'raw_attributes' => $attributes // Keep original for reference
            ];
        }
    }

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

        $oldQuantity = $this->cart[$index]['quantity'];
        $this->cart[$index]['quantity'] = $quantity;

        // Adjust IMEI/Serial arrays based on new quantity
        if ($quantity > $oldQuantity) {
            // Add empty entries for new items
            for ($i = $oldQuantity; $i < $quantity; $i++) {
                $this->cart[$index]['imei_numbers'][$i] = '';
                $this->cart[$index]['serial_numbers'][$i] = '';
            }
        } else {
            // Remove excess entries
            $this->cart[$index]['imei_numbers'] = array_slice($this->cart[$index]['imei_numbers'], 0, $quantity);
            $this->cart[$index]['serial_numbers'] = array_slice($this->cart[$index]['serial_numbers'], 0, $quantity);
        }
    }

    public function removeFromCart($index)
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
    }

    public function openImeiModal($index)
    {
        $this->currentCartIndex = $index;
        $this->imeiNumbers = $this->cart[$index]['imei_numbers'] ?? [];
        $this->serialNumbers = $this->cart[$index]['serial_numbers'] ?? [];

        // Ensure arrays have correct length
        $quantity = $this->cart[$index]['quantity'];
        for ($i = count($this->imeiNumbers); $i < $quantity; $i++) {
            $this->imeiNumbers[$i] = '';
        }
        for ($i = count($this->serialNumbers); $i < $quantity; $i++) {
            $this->serialNumbers[$i] = '';
        }

        $this->showImeiModal = true;
    }

    public function saveImeiSerial()
    {
        if ($this->currentCartIndex !== null) {
            $this->cart[$this->currentCartIndex]['imei_numbers'] = $this->imeiNumbers;
            $this->cart[$this->currentCartIndex]['serial_numbers'] = $this->serialNumbers;
        }
        $this->closeImeiModal();
        session()->flash('success', 'IMEI/Serial numbers saved successfully!');
    }

    public function closeImeiModal()
    {
        $this->showImeiModal = false;
        $this->currentCartIndex = null;
        $this->imeiNumbers = [];
        $this->serialNumbers = [];
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

        return $prefix . '-' . $currentYear . '-' . $currentMonth . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function processSale()
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Cart is empty!');
            return;
        }

        DB::beginTransaction();

        try {
            $customerId = $this->selectedCustomer;

            // Create new customer if not selected and customer details provided
            if (!$customerId && ($this->customerName || $this->customerPhone)) {
                if (!$this->customerName || !$this->customerPhone) {
                    session()->flash('error', 'Please provide both customer name and phone number for new customer!');
                    return;
                }

                $customer = Customer::create([
                    'name' => $this->customerName,
                    'phone' => $this->customerPhone,
                    'address' => $this->customerAddress,
                    'status' => 'active',
                    'opening_balance' => 0,
                    'current_balance' => 0,
                    'credit_limit' => 0,
                ]);

                $customerId = $customer->id;
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
                'payment_status' => $this->dueAmount <= 0 ? 'paid' : ($this->paidAmount > 0 ? 'partial' : 'pending'),
                'payment_method' => $this->paymentMethod,
                'note' => $this->note,
                'created_by' => auth()->id(),
            ]);

            // Create sale items and update stock
            foreach ($this->cart as $item) {
                // Filter out empty IMEI/Serial numbers
                $imeiNumbers = array_filter($item['imei_numbers'] ?? [], function($value) {
                    return !empty(trim($value));
                });
                $serialNumbers = array_filter($item['serial_numbers'] ?? [], function($value) {
                    return !empty(trim($value));
                });

                $saleItem = SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['price'] * $item['quantity'],
                    'imei_numbers' => json_encode($imeiNumbers),
                    'serial_numbers' => json_encode($serialNumbers),
                ]);

                // Debug: Log the saved data
                \Log::info('Sale Item Created:', [
                    'id' => $saleItem->id,
                    'imei_numbers' => $saleItem->imei_numbers,
                    'serial_numbers' => $saleItem->serial_numbers
                ]);

                // Update product stock
                $invtory = Inventory::find($item['inventory_id']);
                $invtory->decrement('quantity', $item['quantity']);
            }

            DB::commit();

            session()->flash('message', 'Sale completed successfully! Invoice: ' . $sale->invoice_no);

            // Redirect to invoice page
            return redirect()->route('sales.invoice', $sale->id);

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Error processing sale: ' . $e->getMessage());
            \Log::error('Sale Processing Error:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        }
    }

    public function resetForm()
    {
        $this->cart = [];
        $this->selectedCustomer = null;
        $this->customerName = '';
        $this->customerPhone = '';
        $this->customerAddress = '';
        $this->paymentMethod = 'cash';
        $this->paidAmount = 0;
        $this->discountAmount = 0;
        $this->taxAmount = 0;
        $this->note = '';
    }

    // public function render()
    // {
    //     $query = Product::with(['brand', 'category'])
    //         ->where('status', 'active')
    //         ->where('stock_quantity', '>', 0);

    //     if ($this->search) {
    //         $query->where(function($q) {
    //             $q->where('name', 'like', '%' . $this->search . '%')
    //               ->orWhere('model', 'like', '%' . $this->search . '%')
    //               ->orWhere('sku', 'like', '%' . $this->search . '%');
    //         });
    //     }

    //     $products = $query->get();
    //     $customers = Customer::where('status', 'active')->get();

    //     return view('livewire.point-of-sale', compact('products', 'customers'));
    // }

    public function render()
    {
        $query = Inventory::with(['product' => function($query) {
                $query->with(['brand', 'category'])
                    ->where('status', 'active');
            }])
            ->whereHas('product', function($q) {
                $q->where('status', 'active');
            });

        if ($this->search) {
            $query->where(function($q) {
                $q->whereHas('product', function($productQuery) {
                    $productQuery->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('model', 'like', '%' . $this->search . '%');
                })
                ->orWhere('imei', 'like', '%' . $this->search . '%')
                ->orWhere('serial_number', 'like', '%' . $this->search . '%');
            });
        }

        $inventories = $query->latest()->paginate(10);
        $customers = Customer::where('status', 'active')->get();

        return view('livewire.point-of-sale', [
            'inventories' => $inventories,
            'customers' => $customers
        ]);
    }
}
