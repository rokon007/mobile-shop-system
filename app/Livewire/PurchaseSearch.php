<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PurchaseFhone;
use Illuminate\Support\Facades\Storage;

class PurchaseSearch extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $viewModal = false;
    public $deleteModal = false;
    public $selectedPurchase;
    public $purchaseToDelete;

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function viewDetails($id)
    {
        $this->selectedPurchase = PurchaseFhone::with(['seller', 'phone'])->find($id);
        $this->viewModal = true;
    }

    public function closeModal()
    {
        $this->viewModal = false;
        $this->selectedPurchase = null;
    }

    public function confirmDelete($id)
    {
        $this->purchaseToDelete = PurchaseFhone::find($id);
        $this->deleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->deleteModal = false;
        $this->purchaseToDelete = null;
    }

    public function deletePurchase()
    {
        if ($this->purchaseToDelete) {
            // Delete related documents if needed
            $this->purchaseToDelete->delete();
            $this->deleteModal = false;
            session()->flash('message', 'Purchase record deleted successfully.');
        }
    }

    public function render()
    {
        $purchases = PurchaseFhone::query()
            ->with(['seller', 'phone'])
            ->where(function($query) {
                $query->whereHas('seller', function($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('phone', 'like', "%{$this->search}%");
                })
                ->orWhereHas('phone', function($q) {
                    $q->where('imei', 'like', "%{$this->search}%")
                      ->orWhere('brand', 'like', "%{$this->search}%")
                      ->orWhere('model', 'like', "%{$this->search}%");
                });
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.purchase-search', compact('purchases'));
    }
}
