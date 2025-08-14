<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Phone;
use App\Models\SystemSetting;
use PDF;
use App\Models\PurchaseFhone;

class InvoiceController extends Controller
{
    public function generate($imei)
    {
        // Get phone purchase details by IMEI
        $purchase = Phone::where('imei', $imei)->firstOrFail();

        // Get shop settings
        $settings = $this->getShopSettings();

        // Render PDF view
        $pdf = PDF::loadView('invoices.phone-purchase', [
            'purchase' => $purchase,
            'settings' => $settings
        ])->setPaper('A4', 'portrait');

        // Download PDF
        return $pdf->download("invoice-{$purchase->imei}.pdf");
    }

    public function invoice($id)
    {
        dd('ok');
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
