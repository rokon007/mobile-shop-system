<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Inventory;


class ProductReport extends Component
{
    public $search = '';

    public function render()
    {
        $query = Inventory::with(['product' => function($query) {
                $query->with(['brand', 'category'])
                    ->where('status', 'active');
            }])
            ->whereHas('product', function($q) {
                $q->where('status', 'active');
            })
            ->where('quantity', '!=', 0);

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
        return view('livewire.product-report', ['inventories' => $inventories]);
    }
}
