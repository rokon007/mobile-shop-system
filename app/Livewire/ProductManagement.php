<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class ProductManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedBrand = '';
    public $selectedCategory = '';
    public $showModal = false;
    public $editMode = false;
    public $productId = null;

    // Form properties
    public $name = '';
    public $brand_id = '';
    public $category_id = '';
    public $model = '';
    public $description = '';
    public $purchase_price = '';
    public $selling_price = '';
    public $stock_quantity = '';
    public $min_stock_alert = 5;
    public $imei = '';
    public $serial_number = '';
    public $warranty_months = 0;
    public $status = 'active';

    protected $rules = [
        'name' => 'required|string|max:255',
        'brand_id' => 'required|exists:brands,id',
        'category_id' => 'required|exists:categories,id',
        'purchase_price' => 'required|numeric|min:0',
        'selling_price' => 'required|numeric|min:0',
        'stock_quantity' => 'required|integer|min:0',
        'min_stock_alert' => 'required|integer|min:1',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedBrand()
    {
        $this->resetPage();
    }

    public function updatingSelectedCategory()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->editMode = false;
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->productId = $id;
        $this->name = $product->name;
        $this->brand_id = $product->brand_id;
        $this->category_id = $product->category_id;
        $this->model = $product->model;
        $this->description = $product->description;
        $this->purchase_price = $product->purchase_price;
        $this->selling_price = $product->selling_price;
        $this->stock_quantity = $product->stock_quantity;
        $this->min_stock_alert = $product->min_stock_alert;
        $this->imei = $product->imei;
        $this->serial_number = $product->serial_number;
        $this->warranty_months = $product->warranty_months;
        $this->status = $product->status;
        
        $this->showModal = true;
        $this->editMode = true;
    }

    public function save()
    {
        $this->validate();
        
        $data = [
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'brand_id' => $this->brand_id,
            'category_id' => $this->category_id,
            'model' => $this->model,
            'description' => $this->description,
            'purchase_price' => $this->purchase_price,
            'selling_price' => $this->selling_price,
            'stock_quantity' => $this->stock_quantity,
            'min_stock_alert' => $this->min_stock_alert,
            'imei' => $this->imei,
            'serial_number' => $this->serial_number,
            'warranty_months' => $this->warranty_months,
            'status' => $this->status,
        ];
        
        if ($this->editMode) {
            Product::findOrFail($this->productId)->update($data);
            session()->flash('message', 'Product updated successfully!');
        } else {
            Product::create($data);
            session()->flash('message', 'Product created successfully!');
        }
        
        $this->showModal = false;
        $this->resetForm();
    }

    public function delete($id)
    {
        Product::findOrFail($id)->delete();
        session()->flash('message', 'Product deleted successfully!');
    }

    public function resetForm()
    {
        $this->name = '';
        $this->brand_id = '';
        $this->category_id = '';
        $this->model = '';
        $this->description = '';
        $this->purchase_price = '';
        $this->selling_price = '';
        $this->stock_quantity = '';
        $this->min_stock_alert = 5;
        $this->imei = '';
        $this->serial_number = '';
        $this->warranty_months = 0;
        $this->status = 'active';
        $this->productId = null;
    }

    public function render()
    {
        $query = Product::with(['brand', 'category']);
        
        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('model', 'like', '%' . $this->search . '%')
                  ->orWhere('imei', 'like', '%' . $this->search . '%');
        }
        
        if ($this->selectedBrand) {
            $query->where('brand_id', $this->selectedBrand);
        }
        
        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }
        
        $products = $query->latest()->paginate(10);
        $brands = Brand::where('status', 'active')->get();
        $categories = Category::where('status', 'active')->get();

        return view('livewire.product-management', compact('products', 'brands', 'categories'));
    }
}
