<?php

namespace App\Livewire\Filter;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Inventory;
//use App\Models\ProductImage;
use App\Models\Filter;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
//use Intervention\Image\ImageManager;
//use Intervention\Image\Drivers\Imagick\Driver;


class InventoryManagementComponent extends Component
{
    use WithPagination, WithFileUploads;
    public $prouctData;
    public $product_id, $sku, $purchase_price, $selling_price, $quantity, $photo,$imei,$serial_number;
    public $filtersData, $attribute_combination = [], $filters = [];
    public $editMode = false; // To toggle between Add and Edit
    public $inventoryId;
    public $selectedInventoryId;
    public $search = '';
    public $catId;



    protected $rules = [
        'sku' => 'required|unique:inventories,sku',
        'purchase_price' => 'required|numeric|min:0',
        'selling_price' => 'required|numeric',
        'quantity' => 'required|integer|min:0',
        'photo' => 'nullable|image|max:1024', // Max 1MB
        'imei' => 'nullable|unique',
        'serial_number' => 'nullable|unique',
    ];



    private function generateUniqueSKU()
    {
        do {
            $sku = 'SKU-' . strtoupper(uniqid());
        } while (Inventory::where('sku', $sku)->exists());

        return $sku;
    }






    public function mount($id)
    {
        $this->prouctData=Product::with(['category','inventory','inventories','brand',])
                            ->where('id',$id)->first();
        $category_id=$this->prouctData->category->id;
        $this->catId=$category_id;
        $this->filtersData=Filter::where('category_id',$category_id)->where('is_active',1)->get();
        $this->product_id=$id;
        $this->sku = $this->sku ?: $this->generateUniqueSKU();
        $this->quantity = 1;
        // $this->sku = $this->sku;
    }
    public function storeInventory()
    {
        //$this->validate();

        // Handle Image Upload
        // $imagePath = null;
        // if ($this->photo) {
        //     $imageName = uniqid() . '.' . $this->photo->getClientOriginalExtension();
        //     $img=$this->photo;
        //     $manager = new ImageManager(new Driver());
        //     $img1=$manager->read($img);
        //     $img1=$img1->resize(512, 512)->toJpeg(80);
        //     $filePath = "public/inventories/{$imageName}";
        //     Storage::put($filePath, $img1->__toString());
        //     $imagePath ="app/{$filePath}";
        // }

        // Store Inventory
        $inventory = Inventory::create([
            'product_id'=>$this->product_id,
            'sku' => $this->sku,
            'purchase_price' => $this->purchase_price,
            'selling_price' => $this->selling_price,
            'quantity' => $this->quantity,
            'attribute_combination' => json_encode($this->filters),
            'imei' => $this->imei,
            'serial_number' => $this->serial_number,
        ]);

        // if ($imagePath) {
        //     ProductImage::create([
        //         'product_id' => $this->product_id,
        //         'inventory_id' => $inventory->id,
        //         'image_path' => $imagePath,
        //     ]);
        // }

        session()->flash('message', 'Inventory added successfully.');
        $this->reset(['sku', 'purchase_price', 'quantity','selling_price', 'photo', 'filters','imei','serial_number']);
    }

    public function editInventory($id)
    {
        $inventory = Inventory::findOrFail($id);
        $this->selectedInventoryId = $id;
        $this->sku = $inventory->sku;
        $this->purchase_price = $inventory->purchase_price;
        $this->selling_price = $inventory->selling_price;
        $this->quantity = $inventory->quantity;
        $this->filters = json_decode($inventory->attribute_combination, true);
        $this->imei = $inventory->imei;
        $this->serial_number = $inventory->serial_number;

    }

    public function updateInventory()
    {
        $this->validate([
            'sku' => 'required|unique:inventories,sku,' . $this->selectedInventoryId,
            'purchase_price' => 'required|numeric',
            'selling_price' => 'nullable|numeric',
            'quantity' => 'required|integer',
            'photo' => 'nullable|image|max:1024', // Max 1MB
            'imei' => 'nullable',
            'serial_number' => 'nullable',
        ]);

        $inventory = Inventory::findOrFail($this->selectedInventoryId);

        // Handle Image Update
        // $imagePath = $inventory->productImage?->image_path;
        // if ($this->photo) {
        //     if ($imagePath) {
        //         Storage::delete('public/' . $imagePath);
        //     }
        //     $imageName = uniqid() . '.' . $this->photo->getClientOriginalExtension();
        //     $img=$this->photo;
        //     $manager = new ImageManager(new Driver());
        //     $img1=$manager->read($img);
        //     $img1=$img1->resize(512, 512)->toJpeg(80);
        //     $filePath = "public/inventories/{$imageName}";
        //     Storage::put($filePath, $img1->__toString());
        //     $imagePath ="app/{$filePath}";
        // }

        // Update Inventory
        $inventory->update([
            'sku' => $this->sku,
            'purchase_price' => $this->purchase_price,
            'selling_price' => $this->selling_price,
            'quantity' => $this->quantity,
            'attribute_combination' => json_encode($this->filters),
            'imei' => $this->imei,
            'serial_number' => $this->serial_number,
        ]);

        // if ($imagePath) {
        //     $inventory->images()->updateOrCreate(
        //         ['product_id' => $this->product_id, 'inventory_id' => $inventory->id], // Matching condition
        //         ['image_path' => $imagePath] // Data to update or insert
        //     );
        // }

        session()->flash('message', 'Inventory updated successfully.');
        $this->reset(['sku', 'selling_price', 'quantity','purchase_price', 'photo', 'filters', 'selectedInventoryId']);
    }

    public function deleteInventory($id)
    {
        $inventory = Inventory::findOrFail($id);
        if ($inventory->productImage) {
            Storage::delete('public/' . $inventory->productImage->image_path);
            $inventory->productImage->delete();
        }
        $inventory->delete();

        session()->flash('message', 'Inventory deleted successfully.');
    }

    public function render()
    {
        $filtersData = Filter::with('options')->get();
        // $inventories = Inventory::where('sku', 'like', '%' . $this->search . '%')
        //     ->orderBy('created_at', 'desc')
        //     ->with('images')
        //     ->paginate(10);

        $inventories = Inventory::query()
                    ->where('product_id',$this->product_id)
                    //->with('images') // Eager load the related images
                    ->when($this->search, function ($query) {
                        $query->where('sku', 'like', '%' . $this->search . '%');
                    })
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        return view('livewire.filter.inventory-management-component', [
            'inventories' => $inventories,
            'filtersData' => $filtersData,
        ])->layout('livewire.layout.app');
    }
}


