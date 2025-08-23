<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Seller;
use App\Models\Phone;
use App\Models\PurchaseFhone;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class EditPhonePurchaseForm extends Component
{
    use WithFileUploads;
    public $seller_id;
    public $phone_id;
    public $purchase_phone_id;

    public $name, $father_name, $mother_name, $nid_number, $permanent_address, $present_address, $dob, $phone, $email, $facebook_id;
    public $brand, $model, $manufacture_year, $imei, $serial_number, $ram, $rom;
    public $purchase_price, $purchase_date;
    public $photo, $nid_photo, $purchase_receipt;

    public $photo_path;
    public $nid_photo_path;
    public $purchase_receipt_path;


    public function mount($purchase_phone_id)
    {
        $purchase = PurchaseFhone::with('seller', 'phone')->findOrFail($purchase_phone_id);

        $this->purchase_phone_id = $purchase->id;
        $this->seller_id = $purchase->seller->id;
        $this->phone_id = $purchase->phone->id;

        // Seller data
        $this->name = $purchase->seller->name;
        $this->father_name = $purchase->seller->father_name;
        $this->mother_name = $purchase->seller->mother_name;
        $this->nid_number = $purchase->seller->nid_number;
        $this->permanent_address = $purchase->seller->permanent_address;
        $this->present_address = $purchase->seller->present_address;
        $this->dob = $purchase->seller->dob ? date('Y-m-d', strtotime($purchase->seller->dob)) : null;
        $this->phone = $purchase->seller->phone;
        $this->email = $purchase->seller->email;
        $this->facebook_id = $purchase->seller->facebook_id;

        // Phone data
        $this->brand = $purchase->phone->brand;
        $this->model = $purchase->phone->model;
        $this->manufacture_year = $purchase->phone->manufacture_year;
        $this->imei = $purchase->phone->imei;
        $this->serial_number = $purchase->phone->serial_number;
        $this->ram = $purchase->phone->ram;
        $this->rom = $purchase->phone->rom;

        // Purchase data
        $this->purchase_price = $purchase->purchase_price;
        $this->purchase_date = $purchase->purchase_date ? date('Y-m-d', strtotime($purchase->purchase_date)) : null;

        $this->photo_path = $purchase->seller->photo_path; // Seller এর ছবি
        $this->nid_photo_path = $purchase->seller->nid_photo_path; // Seller এর NID এর ছবি
        $this->purchase_receipt_path = $purchase->seller->purchase_receipt_path; // Purchase receipt

    }

    public function rules()
    {
        return [
            'name' => 'required|string',
            'father_name' => 'required|string',
            'mother_name' => 'required|string',
            'nid_number' => 'required|unique:sellers,nid_number,' . $this->seller_id,
            'phone' => 'required',
            'brand' => 'required|string',
            'model' => 'required|string',
            'manufacture_year' => 'required|integer',
            'imei' => 'required|unique:phones,imei,' . $this->phone_id,
            'serial_number' => 'required|unique:phones,serial_number,' . $this->phone_id,
            'ram' => 'nullable|string',
            'rom' => 'nullable|string',
            'purchase_price' => 'required|numeric',
            'purchase_date' => 'required|date',
            'photo' => 'nullable|image|max:2048',
            'nid_photo' => 'nullable|image|max:2048',
            'purchase_receipt' => 'nullable|mimes:jpg,jpeg,png,pdf',
        ];
    }

    public function update()
    {
        $this->validate($this->rules());

        // Update Seller
        $seller = Seller::findOrFail($this->seller_id);

        // Process and update photo if provided
        if ($this->photo) {
            if ($seller->photo_path && Storage::disk('public')->exists($seller->photo_path)) {
                Storage::disk('public')->delete($seller->photo_path);
            }
            $photoPath = $this->resizeAndStoreImage($this->photo, 'sellers/photos', 800, 600);
            $seller->photo_path = $photoPath;
        }

        // Process and update nid_photo if provided
        if ($this->nid_photo) {
            if ($seller->nid_photo_path && Storage::disk('public')->exists($seller->nid_photo_path)) {
                Storage::disk('public')->delete($seller->nid_photo_path);
            }
            $nidPhotoPath = $this->resizeAndStoreImage($this->nid_photo, 'sellers/nid', 1000, 800);
            $seller->nid_photo_path = $nidPhotoPath;
        }

        // Process and update purchase_receipt if provided
        if ($this->purchase_receipt) {
            if ($seller->purchase_receipt_path && Storage::disk('public')->exists($seller->purchase_receipt_path)) {
                Storage::disk('public')->delete($seller->purchase_receipt_path);
            }

            if ($this->purchase_receipt->getClientOriginalExtension() === 'pdf') {
                // Store PDF as is
                $receiptPath = $this->purchase_receipt->store('sellers/receipts', 'public');
            } else {
                // Resize and store image
                $receiptPath = $this->resizeAndStoreImage($this->purchase_receipt, 'sellers/receipts', 1200, 900);
            }
            $seller->purchase_receipt_path = $receiptPath;
        }

        $seller->update([
            'name' => $this->name,
            'father_name' => $this->father_name,
            'mother_name' => $this->mother_name,
            'nid_number' => $this->nid_number,
            'permanent_address' => $this->permanent_address,
            'present_address' => $this->present_address,
            'dob' => $this->dob,
            'phone' => $this->phone,
            'email' => $this->email,
            'facebook_id' => $this->facebook_id,
        ]);

        // Update Phone
        $phone = Phone::findOrFail($this->phone_id);
        $phone->update([
            'brand' => $this->brand,
            'model' => $this->model,
            'manufacture_year' => $this->manufacture_year,
            'imei' => $this->imei,
            'serial_number' => $this->serial_number,
            'ram' => $this->ram,
            'rom' => $this->rom,
        ]);

        // Update Purchase
        $purchase = PurchaseFhone::findOrFail($this->purchase_phone_id);
        $purchase->update([
            'purchase_price' => $this->purchase_price,
            'purchase_date' => $this->purchase_date,
        ]);

        session()->flash('success', 'Phone purchase updated successfully!');
        return redirect()->route('purchase.search');
    }

    /**
     * Resize and store an image using Intervention Image
     *
     * @param mixed $file The uploaded file
     * @param string $directory The storage directory
     * @param int $width The target width
     * @param int $height The target height
     * @return string|null The file path or null if failed
     */
    private function resizeAndStoreImage($file, $directory, $width, $height)
    {
        try {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file->getRealPath());

            // Resize the image while maintaining aspect ratio
            $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            // Generate a unique filename
            $fileName = time() . '_' . uniqid() . '.jpg';
            $filePath = $directory . '/' . $fileName;

            // Save the image as JPG with 80% quality
            $image->toJpeg(80)->save(storage_path('app/public/' . $filePath));

            return $filePath;
        } catch (\Exception $e) {
            // Log error or handle it as needed
            return null;
        }
    }

    public function removePhoto()
    {
        $this->photo = null;
    }

    public function removeNidPhoto()
    {
        $this->nid_photo = null;
    }

    public function removeReceipt()
    {
        $this->purchase_receipt = null;
    }

    public function render()
    {
        return view('livewire.edit-phone-purchase-form')->layout('livewire.layout.app');
    }
}
