<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Phone;
use App\Models\SystemSetting;
use PDF;
use App\Models\PurchaseFhone;

class PurchaseInvoiceController extends Controller
{
    public function pinvoice($id)
    {
        $purchase = PurchaseFhone::with(['seller', 'phone'])->findOrFail($id);

        $settings = $this->getShopSettings();

        return view('invoices.purchase-invoice', compact('purchase', 'settings'));
    }

    private function getShopSettings()
    {
        $settingKeys = ['shop_name', 'shop_address', 'shop_phone', 'shop_email', 'shop_logo', 'invoice_footer'];
        $settings = [];

        foreach ($settingKeys as $key) {
            $settings[$key] = SystemSetting::where('key', $key)->value('value');
        }

        return $settings;
    }
}
