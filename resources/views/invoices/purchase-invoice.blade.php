@extends('layouts.app')

@section('content')
@php
    use Illuminate\Support\Facades\File;
    $logoPath = $settings['shop_logo'] ?? null;
    $absoluteLogoPath = $logoPath ? public_path('storage/public/' . $logoPath) : null;
@endphp

<style>
    .invoice-container {
        width: 210mm;
        height: 297mm;
        margin: 0 auto;
        padding: 15mm;
        box-sizing: border-box;
        background: #fff;
        box-shadow: none;
        page-break-after: always;
    }

    @page {
        size: A4 portrait;
        margin: 15mm;
    }

   @media print {
        body * {
            visibility: hidden;
        }
        .invoice-container, .invoice-container * {
            visibility: visible;
        }
        .invoice-container {
            position: fixed;
            left: 50%;
            top: 0;
            transform: translateX(-50%);
            width: 210mm;
            height: 297mm;
            padding: 15mm;
            margin: 0;
            box-shadow: none;
            background: #fff;
            box-sizing: border-box;
        }
        .no-print {
            display: none !important;
        }
    }




    /* আপনার আগের স্টাইল এখানে থাকবে */
    .header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }
    .header img {
        max-height: 80px;
        margin-right: 20px;
    }
    .shop-details h5 {
        margin: 0;
        font-weight: bold;
    }
    .shop-details p {
        margin: 2px 0;
    }
    .supplier-info p {
        margin: 2px 0;
    }
    .invoice-info p {
        margin: 2px 0;
    }
    .watermark {
        position: fixed;
        top: 30%;
        left: 30%;
        opacity: 0.1;
        width: 300px;
        pointer-events: none;
        z-index: -1;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    table, th, td {
        border: 1px solid #ccc;
    }
    th, td {
        padding: 8px;
        text-align: left;
    }
    tfoot th {
        text-align: right;
    }
    .text-right {
        text-align: right;
    }
    .notes {
        margin-top: 20px;
        padding: 10px;
        background: #f8f8f8;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
</style>


<div class="invoice-container">
    {{-- Watermark --}}
    @if(!empty($logoPath))
        <img src="{{ asset('storage/public/' . $logoPath) }}" class="watermark" alt="Watermark Logo">
    @endif

    {{-- Header --}}
    <div class="header">
        @if(!empty($logoPath))
            <img src="{{ asset('storage/public/' . $logoPath) }}" alt="Shop Logo">
        @endif
        <div class="shop-details">
            <h5>{{ $settings['shop_name'] ?? 'Mobile Shop System' }}</h5>
            <p>{{ $settings['shop_address'] ?? '123 Business Street, City, State' }}</p>
            <p>Phone: {{ $settings['shop_phone'] ?? '(555) 123-4567' }} | Email: {{ $settings['shop_email'] ?? 'info@example.com' }}</p>
            @if(!empty($settings['shop_vat']))
                <p>VAT Reg No: {{ $settings['shop_vat'] }}</p>
            @endif
        </div>
    </div>
    <div class="d-flex justify-content-between mb-4 flex-wrap">
        {{-- Supplier Info --}}
         <div class="supplier-info" style="flex: 1 1 45%; min-width: 280px;">
            <h5>Selar Information</h5>
            <p><strong>{{ $purchase->seller->name ?? 'N/A' }}</strong></p>
            <p>{{ $purchase->seller->permanent_address ?? 'N/A' }}</p>
            <p>Phone: {{ $purchase->seller->phone ?? 'N/A' }}</p>
            <p>Email: {{ $purchase->seller->email ?? 'N/A' }}</p>
        </div>
         {{-- Invoice Info --}}
        <div class="invoice-info" style="flex: 1 1 45%; min-width: 280px; text-align: right;">
            <h5>PURCHASE INVOICE</h5>
            <p><strong>Invoice #:</strong> {{ $purchase->id }}</p>
            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('M d, Y') }}</p>
            <p><strong>Status:</strong> {{ ucfirst($purchase->status ?? 'N/A') }}</p>
        </div>
    </div>

    {{-- Purchase Details Table --}}
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            {{-- Assuming one phone item per purchase here --}}
            <tr>
                <td>{{ $purchase->phone->brand }} {{ $purchase->phone->model }}</td>
                <td>1</td>
                <td>${{ number_format($purchase->purchase_price, 2) }}</td>
                <td>${{ number_format($purchase->purchase_price, 2) }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-right">Total Amount</th>
                <th>${{ number_format($purchase->purchase_price, 2) }}</th>
            </tr>
            {{-- Paid and Due amounts can be added here if applicable --}}
        </tfoot>
    </table>

    @if(!empty($purchase->notes))
        <div class="notes">
            <h5>Notes</h5>
            <p>{{ $purchase->notes }}</p>
        </div>
    @endif

    {{-- Invoice Footer --}}
    @if(!empty($settings['invoice_footer']))
        <div class="invoice-footer" style="margin-top: 40px; font-size: 0.9em; color: #555;">
            {!! nl2br(e($settings['invoice_footer'])) !!}
        </div>
    @endif

    <div style="margin-top: 40px; text-align: center;">
        <p>Thank you for your business!</p>
        <p class="mb-0 sm-text text-muted">System developed by Rokon  | Contact: +8801717524792.</p>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" class="btn btn-primary">Print Invoice</button>
    </div>
</div>
@endsection
