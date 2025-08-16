<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Seller;
use App\Models\Phone;
use App\Models\PurchaseFhone;

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
        'photo' => 'nullable|image|max:2048',
        'nid_photo' => 'nullable|image|max:2048',
        'purchase_receipt' => 'nullable|mimes:jpg,jpeg,png,pdf|max:4096',
    ];

    public function save()
    {
        $this->validate();

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
            'photo_path' => $this->photo?->store('sellers/photos', 'public'),
            'nid_photo_path' => $this->nid_photo?->store('sellers/nid', 'public'),
            'purchase_receipt_path' => $this->purchase_receipt?->store('sellers/receipts', 'public'),
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
        //return redirect()->route('invoice.generate', ['imei' => $this->imei]);
        return redirect()->route('purchase.search');
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
