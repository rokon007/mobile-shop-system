@extends('layouts.app')

@section('title', 'Invoice - ' . $sale->invoice_no)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center no-print">
                    <h4 class="card-title mb-0">Invoice - {{ $sale->invoice_no }}</h4>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div>
                            <button onclick="window.print()" class="btn btn-primary">
                                Print Invoice
                            </button>
                        </div>
                        {{-- Uncomment দিলে print link বাটনও পাশাপাশি আসবে --}}
                        {{--
                        <a href="{{ route('sales.print', $sale->id) }}" class="btn btn-primary btn-sm" target="_blank">
                            <i class="fas fa-download"></i> Print
                        </a>
                        --}}
                        <a href="{{ route('sales.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Sales
                        </a>
                    </div>
                </div>


                <div class="card-body invoice-container">
                    <div class="invoice-content" id="invoice-content">
                        @php
                            $logoPath = \App\Models\SystemSetting::where('key', 'shop_logo')->value('value');
                        @endphp
                         {{-- Watermark --}}
                        @if(!empty($logoPath))
                            <img src="{{ asset('storage/public/' . $logoPath) }}" class="watermark" alt="Watermark Logo">
                        @endif

                        <!-- Company Header -->
                        <div class="row mb-4">
                            <div class="col-8">
                                <div class="d-flex align-items-center">

                                    @if($settings['shop_logo'])
                                        <img src="{{ asset('storage/public/' . $logoPath) }}" alt="Shop Logo" class="me-3" style="max-height: 80px;">
                                    @endif
                                    <div>
                                        <h5 class="text-dark mb-1">{{ $settings['shop_name'] ?? 'Mobile Shop System' }}</h5>
                                        <p class="mb-0 fsize">{{ $settings['shop_address'] ?? 'Shop Address' }}</p>
                                        <p class="mb-0 fsize">Phone: {{ $settings['shop_phone'] ?? 'N/A' }}</p>
                                        <p class="mb-0 fsize">Email: {{ $settings['shop_email'] ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <h5 class="text-dark">INVOICE</h5>
                                <p class="mb-1 fsize"><strong>Invoice No:</strong> {{ $sale->invoice_no }}</p>
                                <p class="mb-1 fsize"><strong>Date:</strong> {{ $sale->sale_date->format('d M Y') }}</p>
                                <p class="mb-0 fsize"><strong>Time:</strong> {{ $sale->sale_date->format('h:i A') }}</p>
                            </div>
                        </div>

                        <!-- Customer Info -->
                        <div class="row mb-4">
                            <div class="col-6">
                                <h6>Bill To:</h6>
                                @if($sale->customer)
                                    <p class="mb-1 fsize"><strong>Name: {{ $sale->customer->name }}</strong></p>
                                    <p class="mb-1 fsize">Mobile: {{ $sale->customer->phone }}</p>
                                    <p class="mb-1 fsize">{{ $sale->customer->email }}</p>
                                    <p class="mb-0 fsize">{{ $sale->customer->address }}</p>
                                @elseif($sale->customer_name)
                                    <p class="mb-1 fsize"><strong>{{ $sale->customer_name }}</strong></p>
                                    <p class="mb-0 fsize">{{ $sale->customer_phone }}</p>
                                @else
                                    <p class="mb-0 fsize">Walk-in Customer</p>
                                @endif
                            </div>
                            <div class="col-6 text-end">
                                <p class="mb-1 fsize"><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $sale->payment_method)) }}</p>
                                <p class="mb-1 fsize"><strong>Payment Status:</strong>
                                    <span class="badge bg-{{ $sale->payment_status == 'paid' ? 'success' : ($sale->payment_status == 'partial' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($sale->payment_status) }}
                                    </span>
                                </p>
                                <p class="mb-0 fsize"><strong>Served by:</strong> {{ $sale->createdBy->name ?? 'System' }}</p>
                            </div>
                        </div>

                        <!-- Items Table -->
                        <div class="table-container">
                            <table class="invoice-table">
                                <thead>
                                    <tr>
                                        <th class="col-no">#</th>
                                        <th class="col-product">Product</th>
                                        <th class="col-brand">Brand</th>
                                        <th class="col-model">Model</th>
                                        <th class="col-imei">IMEI/Serial</th>
                                        <th class="col-qty">Qty</th>
                                        <th class="col-price">Unit Price</th>
                                        <th class="col-total">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sale->items as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            {{ $item->product->name }}<br>
                                            <div class="item-attributes">
                                                @php
                                                    $attributes = json_decode($item->attribute_data, true) ?? [];

                                                    if (empty($attributes) && $item->product->inventory) {
                                                        $attributes = json_decode($item->product->inventory->attribute_combination, true) ?? [];
                                                    }

                                                    $displayAttributes = [];
                                                    foreach ($attributes as $filterId => $optionId) {
                                                        $filter = App\Models\Filter::find($filterId);
                                                        $option = App\Models\FilterOption::find($optionId);
                                                        if ($filter && $option) {
                                                            $displayAttributes[$filter->name] = $option->value;
                                                        }
                                                    }
                                                @endphp

                                                @foreach($displayAttributes as $name => $value)
                                                    <small class="text-muted">{{ $name }}: <strong>{{ $value }}</strong></small>,
                                                @endforeach
                                            </div>
                                        </td>
                                        <td>{{ $item->product->brand->name ?? 'N/A' }}</td>
                                        <td>{{ $item->product->model ?? 'N/A' }}</td>
                                        <td>
                                            @php
                                                $imeiNumbers = json_decode($item->imei_numbers, true) ?? [];
                                                $serialNumbers = json_decode($item->serial_numbers, true) ?? [];
                                            @endphp
                                            @if(!empty($imeiNumbers) && array_filter($imeiNumbers))
                                                <small>
                                                    @foreach(array_filter($imeiNumbers) as $imei)
                                                        <strong>IMEI: </strong>{{ $imei }}<br>
                                                    @endforeach
                                                </small>
                                            @endif
                                            @if(!empty($serialNumbers) && array_filter($serialNumbers))
                                                <small>
                                                    @foreach(array_filter($serialNumbers) as $serial)
                                                        <strong>SL: </strong>{{ $serial }}<br>
                                                    @endforeach
                                                </small>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">৳{{ number_format($item->unit_price, 2) }}</td>
                                        <td class="text-end">৳{{ number_format($item->total_price, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Totals -->
                        <div class="row">
                            <div class="col-6">
                                @if($sale->note)
                                <div class="mb-3">
                                    <strong>Note:</strong>
                                    <p class="mb-0">{{ $sale->note }}</p>
                                </div>
                                @endif
                            </div>
                            <div class="col-6">
                                <table class="table table-sm">
                                    <tr>
                                        <td class="text-end"><strong>Subtotal:</strong></td>
                                        <td class="text-end">৳{{ number_format($sale->subtotal, 2) }}</td>
                                    </tr>
                                    @if($sale->discount_amount > 0)
                                    <tr>
                                        <td class="text-end"><strong>Discount:</strong></td>
                                        <td class="text-end">-৳{{ number_format($sale->discount_amount, 2) }}</td>
                                    </tr>
                                    @endif
                                    @if($sale->tax_amount > 0)
                                    <tr>
                                        <td class="text-end"><strong>Tax:</strong></td>
                                        <td class="text-end">৳{{ number_format($sale->tax_amount, 2) }}</td>
                                    </tr>
                                    @endif
                                    <tr class="table-primary">
                                        <td class="text-end"><strong>Total Amount:</strong></td>
                                        <td class="text-end"><strong>৳{{ number_format($sale->total_amount, 2) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-end"><strong>Paid Amount:</strong></td>
                                        <td class="text-end">৳{{ number_format($sale->paid_amount, 2) }}</td>
                                    </tr>
                                    @if($sale->due_amount > 0)
                                    <tr class="table-warning">
                                        <td class="text-end"><strong>Due Amount:</strong></td>
                                        <td class="text-end"><strong>৳{{ number_format($sale->due_amount, 2) }}</strong></td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="row mt-4">
                            <div class="col-12 text-center">
                                <hr>
                                <p class="mb-1 fsize"><strong>{{ $settings['invoice_footer'] ?? 'Thank you for your business!' }}</strong></p>
                                <p class="mb-0 text-muted fsize">This is a computer generated invoice.</p>
                                <p class="mb-0 sm-text text-muted" style="font-size:11px;">System developed by Rokon | Contact: +8801717524792.</p>
                            </div>
                        </div>

                    </div> <!-- invoice-content -->
                </div> <!-- card-body -->

            </div> <!-- card -->

        </div>
    </div>
</div>

{{-- <style>
    /* Invoice specific styles */
    .invoice-container {
        max-width: 210mm;
        height: 297mm;
        margin: 0 auto;
        padding: 15mm;
        background: #fff;
        box-sizing: border-box;
        box-shadow: none;
        page-break-after: always;
        position: relative;
    }

    .watermark {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        opacity: 0.1;
        width: 60%;
        max-width: 300px;
        pointer-events: none;
        z-index: 0;
    }

    .invoice-content {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 14px;
        line-height: 1.5;
        color: #212529;
        background: #fff;
    }

    .invoice-content h4,
    .invoice-content h5,
    .invoice-content h6 {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #343a40;
    }

    .invoice-content p,
    .invoice-content td,
    .invoice-content th {
        font-size: 14px;
        color: #212529;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    table, th, td {
        border: 1px solid #dee2e6;
    }

    th, td {
        padding: 8px 10px;
        vertical-align: middle;
    }

    thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        text-align: center;
    }

    .text-end {
        text-align: right !important;
    }

    .text-center {
        text-align: center !important;
    }

    .no-print {
        display: block;
    }

    /* Print Styles */
    @page {
        size: A4 portrait;
        margin: 15mm;
    }

    @media print {
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 0;
            opacity: 0.1;
            width: 60%;
            max-width: 300px;
        }

        body * {
            visibility: hidden;
        }
        .invoice-container,
        .invoice-container * {
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
            font-size: 14px;
            line-height: 1.5;
        }
        .no-print,
        .card-header,
        .btn {
            display: none !important;
        }
    }
</style> --}}

<style>
    .fsize {
        font-size: 12px;
    }
    /* Invoice container styles */
    .invoice-container {
        width: 210mm;
        min-height: 297mm;
        margin: 0 auto;
        padding: 15mm;
        background: #fff;
        box-sizing: border-box;
        position: relative;
    }

    /* Watermark styles */
    .watermark {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        opacity: 0.1;
        max-width: 300px;
        pointer-events: none;
        z-index: 0;
    }

    /* Table container to prevent overflow */
    .table-container {
        width: 100%;
        overflow: hidden;
        margin: 15px 0;
    }

    /* Table styles - optimized for full page fit */
    .invoice-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        table-layout: fixed; /* Changed back to fixed for consistent columns */
    }

    .invoice-table thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        padding: 10px;
        border: 1px solid #dee2e6;
        text-align: left;
    }

    .invoice-table tbody td {
        padding: 8px 10px;
        border: 1px solid #dee2e6;
        vertical-align: top;
        line-height: 1.4;
    }

    /* Column width adjustments for perfect fit */
    .invoice-table .col-no {
        width: 5%;
        min-width: 30px;
        max-width: 40px;
        text-align: center;
    }

    .invoice-table .col-product {
        width: 22%;
        min-width: 125px;
        word-break: break-word;
    }

    .invoice-table .col-brand {
        width: 10%;
        min-width: 80px;
        word-break: break-word;
    }

    .invoice-table .col-model {
        width: 10%;
        min-width: 80px;
        word-break: break-word;
    }

    .invoice-table .col-imei {
        width: 18%;
        min-width: 100px;
        word-break: break-all;
    }

    .invoice-table .col-qty {
        width: 8%;
        min-width: 50px;
        max-width: 60px;
        text-align: center;
    }

    .invoice-table .col-price {
        width: 14%;
        min-width: 90px;
        text-align: right;
        white-space: nowrap;
    }

    .invoice-table .col-total {
        width: 13%;
        min-width: 90px;
        text-align: right;
        white-space: nowrap;
    }

    /* For small tables */
    .invoice-table.table-sm thead th,
    .invoice-table.table-sm tbody td {
        padding: 6px 8px;
        font-size: 12px;
    }

    /* Print styles */
    @media print {
        body * {
            visibility: hidden;
        }

        .invoice-container,
        .invoice-container * {
            visibility: visible;
        }

        .invoice-container {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            padding: 15mm;
            margin: 0;
            background: #fff;
            font-size: 12px;
        }

        .invoice-table {
            page-break-inside: avoid;
        }

        .no-print {
            display: none !important;
        }
    }
</style>

<script>
    function printInvoice() {
        window.print();
    }
</script>
@endsection
