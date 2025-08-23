<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Seller;
use App\Models\Phone;
use App\Models\PurchaseFhone;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;

class PhonePurchaseForm extends Component
{
    use WithFileUploads;

    public $name, $father_name, $mother_name, $nid_number, $permanent_address, $present_address,
           $dob, $phone, $email, $facebook_id, $photo, $nid_photo, $purchase_receipt;
    public $brand, $model, $manufacture_year, $imei, $serial_number, $ram, $rom;
    public $purchase_price, $purchase_date;

    protected $rules = [
        'name' => 'required|string',
        'nid_number' => 'required|unique:sellers,nid_number',
        'phone' => 'required',
        'imei' => 'required|unique:phones,imei',
        'serial_number' => 'required|unique:phones,serial_number',
        'purchase_price' => 'required|numeric',
        'purchase_date' => 'required|date',
        'photo' => 'nullable|image',
        'nid_photo' => 'nullable|image',
        'purchase_receipt' => 'nullable|mimes:jpg,jpeg,png,pdf',
    ];

    public function save()
    {
        $this->validate();

        // Process and save photo
        $photoPath = null;
        if ($this->photo) {
            $photoPath = $this->resizeAndStoreImage($this->photo, 'sellers/photos', 800, 600);
        }

        // Process and save nid_photo
        $nidPhotoPath = null;
        if ($this->nid_photo) {
            $nidPhotoPath = $this->resizeAndStoreImage($this->nid_photo, 'sellers/nid', 1000, 800);
        }

        // Process and save purchase_receipt (handle both images and PDF)
        $purchaseReceiptPath = null;
        if ($this->purchase_receipt) {
            if ($this->purchase_receipt->getClientOriginalExtension() === 'pdf') {
                // Store PDF as is
                $purchaseReceiptPath = $this->purchase_receipt->store('sellers/receipts', 'public');
            } else {
                // Resize and store image
                $purchaseReceiptPath = $this->resizeAndStoreImage($this->purchase_receipt, 'sellers/receipts', 1200, 900);
            }
        }

        $seller = Seller::create([
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
            'photo_path' => $photoPath,
            'nid_photo_path' => $nidPhotoPath,
            'purchase_receipt_path' => $purchaseReceiptPath,
        ]);

        $phone = Phone::create([
            'brand' => $this->brand,
            'model' => $this->model,
            'manufacture_year' => $this->manufacture_year,
            'imei' => $this->imei,
            'serial_number' => $this->serial_number,
            'ram' => $this->ram,
            'rom' => $this->rom,
        ]);

        PurchaseFhone::create([
            'seller_id' => $seller->id,
            'phone_id' => $phone->id,
            'purchase_price' => $this->purchase_price,
            'purchase_date' => $this->purchase_date,
        ]);

        session()->flash('success', 'Phone purchase saved successfully!');
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
        return view('livewire.phone-purchase-form');
    }
}
