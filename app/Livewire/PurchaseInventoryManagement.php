<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Filter;
use Livewire\WithPagination;

class PurchaseInventoryManagement extends Component
{
    use WithPagination;

    public $productId;
    public $product;
    public $inventories = [];
    public $newInventory = [
        'sku' => '',
        'imei' => '',
        'serial_number' => '',
        'quantity' => 1,
        'purchase_price' => 0,
        'selling_price' => 0,
        'filters' => []
    ];
    public $filtersData = [];
    public $selectedInventoryIndex = null;

    protected $listeners = ['initializeInventoryManagement' => 'initComponent'];

    public function initComponent($productId, $containerId)
    {
        $this->productId = $productId;
        $this->mount($productId);
        $this->emitSelf('renderInventoryComponent', $containerId);
    }

    public function updatedInventories()
    {
        $this->emit('inventory-updated-'.$this->containerId, $this->inventories);
    }

    protected $rules = [
        'newInventory.sku' => 'required|unique:inventories,sku',
        'newInventory.imei' => 'nullable|unique:inventories,imei',
        'newInventory.serial_number' => 'nullable|unique:inventories,serial_number',
        'newInventory.quantity' => 'required|integer|min:1',
        'newInventory.purchase_price' => 'required|numeric|min:0',
        'newInventory.selling_price' => 'required|numeric|min:0',
    ];

    public function mount($productId)
    {
        $this->productId = $productId;
        $this->product = Product::with('category')->find($productId);

        if ($this->product && $this->product->category) {
            $this->filtersData = Filter::with('options')
                ->where('category_id', $this->product->category->id)
                ->where('is_active', 1)
                ->get();
        }

        $this->generateUniqueSKU();
    }

    public function generateUniqueSKU()
    {
        do {
            $sku = 'SKU-' . strtoupper(uniqid());
        } while (Inventory::where('sku', $sku)->exists());

        $this->newInventory['sku'] = $sku;
    }

    public function addInventory()
    {
        $this->validate();

        $this->inventories[] = [
            ...$this->newInventory,
            'filters' => $this->newInventory['filters'],
            'product_name' => $this->product->name
        ];

        $this->resetNewInventory();
        $this->generateUniqueSKU();
        $this->dispatch('inventory-added');
    }

    public function editInventory($index)
    {
        $this->selectedInventoryIndex = $index;
        $this->newInventory = $this->inventories[$index];
    }

    public function updateInventory()
    {
        $this->validate([
            'newInventory.sku' => 'required|unique:inventories,sku,' . ($this->inventories[$this->selectedInventoryIndex]['id'] ?? null),
            'newInventory.imei' => 'nullable|unique:inventories,imei,' . ($this->inventories[$this->selectedInventoryIndex]['id'] ?? null),
            'newInventory.serial_number' => 'nullable|unique:inventories,serial_number,' . ($this->inventories[$this->selectedInventoryIndex]['id'] ?? null),
            'newInventory.quantity' => 'required|integer|min:1',
            'newInventory.purchase_price' => 'required|numeric|min:0',
            'newInventory.selling_price' => 'required|numeric|min:0',
        ]);

        $this->inventories[$this->selectedInventoryIndex] = [
            ...$this->newInventory,
            'filters' => $this->newInventory['filters'],
            'product_name' => $this->product->name
        ];

        $this->cancelEdit();
        $this->dispatchBrowserEvent('inventory-updated');
    }

    public function deleteInventory($index)
    {
        unset($this->inventories[$index]);
        $this->inventories = array_values($this->inventories);
        $this->dispatchBrowserEvent('inventory-deleted');
    }

    public function cancelEdit()
    {
        $this->selectedInventoryIndex = null;
        $this->resetNewInventory();
        $this->generateUniqueSKU();
    }

    private function resetNewInventory()
    {
        $this->newInventory = [
            'sku' => '',
            'imei' => '',
            'serial_number' => '',
            'quantity' => 1,
            'purchase_price' => 0,
            'selling_price' => 0,
            'filters' => []
        ];
    }

    public function render()
    {
        return view('livewire.purchase-inventory-management');
    }
}
