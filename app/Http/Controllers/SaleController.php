<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Customer;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['customer', 'createdBy'])->latest()->paginate(20);
        $customerIds = $sales->pluck('customer_id')->unique();
        $customers = Customer::whereIn('id', $customerIds)->get();
        return view('sales.index', compact('sales','customers'));
    }

    public function pos()
    {
        return view('sales.pos');
    }

    public function invoice($id)
    {
        $sale = Sale::with(['customer', 'items.product', 'createdBy'])->findOrFail($id);
        return view('sales.invoice', compact('sale'));
    }

    public function print($id)
    {
        $sale = Sale::with(['customer', 'items.product'])->findOrFail($id);
        // PDF generation logic here
        return view('sales.print', compact('sale'));
    }
}
