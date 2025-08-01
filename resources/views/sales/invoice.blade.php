@extends('layouts.app')

@section('title', 'Invoice - ' . $sale->invoice_no)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Invoice - {{ $sale->invoice_no }}</h4>
                    <div>
                        <a href="{{ route('sales.print', $sale->id) }}" class="btn btn-primary btn-sm" target="_blank">
                            <i class="fas fa-download"></i> Print
                        </a>
                        {{-- <button onclick="window.print()" class="btn btn-info btn-sm">
                            <i class="fas fa-print"></i> Print
                        </button> --}}
                        <a href="{{ route('sales.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Sales
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="invoice-content" id="invoice-content">
                        <!-- Company Header -->
                        <div class="row mb-4">
                            <div class="col-8">
                                <div class="d-flex align-items-center">
                                    @php
                                        $logoPath = \App\Models\SystemSetting::where('key', 'shop_logo')->value('value');
                                    @endphp
                                    @if($settings['shop_logo'])
                                        {{-- <img src="{{ asset('storage/' . $settings['shop_logo']) }}"
                                             alt="Shop Logo" class="me-3" style="max-height: 80px;"> --}}
                                             <img src="{{ asset('storage/app/public/' . $logoPath) }}"
                                             alt="Shop Logo" class="me-3" style="max-height: 80px;">
                                    @endif
                                    <div>
                                        <h5 class="text-dark mb-1">{{ $settings['shop_name'] ?? 'Mobile Shop System' }}</h5>
                                        <p class="mb-0">{{ $settings['shop_address'] ?? 'Shop Address' }}</p>
                                        <p class="mb-0">Phone: {{ $settings['shop_phone'] ?? 'N/A' }}</p>
                                        <p class="mb-0">Email: {{ $settings['shop_email'] ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <h5 class="text-dark">INVOICE</h5>
                                <p class="mb-1"><strong>Invoice No:</strong> {{ $sale->invoice_no }}</p>
                                <p class="mb-1"><strong>Date:</strong> {{ $sale->sale_date->format('d M Y') }}</p>
                                <p class="mb-0"><strong>Time:</strong> {{ $sale->sale_date->format('h:i A') }}</p>
                            </div>
                        </div>

                        <!-- Customer Info -->
                        <div class="row mb-4">
                            <div class="col-6">
                                <h6>Bill To:</h6>
                                @if($sale->customer)
                                    <p class="mb-1"><strong> Name: {{ $sale->customer->name }}</strong></p>
                                    <p class="mb-1">Mobile: {{ $sale->customer->phone }}</p>
                                    <p class="mb-1">{{ $sale->customer->email }}</p>
                                    <p class="mb-0">{{ $sale->customer->address }}</p>
                                @elseif($sale->customer_name)
                                    <p class="mb-1"><strong>{{ $sale->customer_name }}</strong></p>
                                    <p class="mb-0">{{ $sale->customer_phone }}</p>
                                @else
                                    <p class="mb-0">Walk-in Customer</p>
                                @endif
                            </div>
                            <div class="col-6 text-end">
                                <p class="mb-1"><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $sale->payment_method)) }}</p>
                                <p class="mb-1"><strong>Payment Status:</strong>
                                    <span class="badge bg-{{ $sale->payment_status == 'paid' ? 'success' : ($sale->payment_status == 'partial' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($sale->payment_status) }}
                                    </span>
                                </p>
                                <p class="mb-0"><strong>Served by:</strong> {{ $sale->createdBy->name ?? 'System' }}</p>
                            </div>
                        </div>

                        <!-- Items Table -->
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered">
                                <thead class="table-primary">
                                    <tr>
                                        <th>#</th>
                                        <th>Product</th>
                                        <th>Brand</th>
                                        <th>Model</th>
                                        <th>IMEI/Serial</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Unit Price</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sale->items as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->product->name }}</td>
                                        <td>{{ $item->product->brand->name ?? 'N/A' }}</td>
                                        <td>{{ $item->product->model ?? 'N/A' }}</td>
                                        <td>
                                            @php
                                                $imeiNumbers = json_decode($item->imei_numbers, true) ?? [];
                                                $serialNumbers = json_decode($item->serial_numbers, true) ?? [];
                                            @endphp
                                            @if(!empty($imeiNumbers) && array_filter($imeiNumbers))
                                                <small><strong>IMEI:</strong><br>
                                                @foreach(array_filter($imeiNumbers) as $imei)
                                                    {{ $imei }}<br>
                                                @endforeach
                                                </small>
                                            @endif
                                            @if(!empty($serialNumbers) && array_filter($serialNumbers))
                                                <small><strong>Serial:</strong><br>
                                                @foreach(array_filter($serialNumbers) as $serial)
                                                    {{ $serial }}<br>
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
                                <p class="mb-1"><strong>{{ $settings['invoice_footer'] ?? 'Thank you for your business!' }}</strong></p>
                                <p class="mb-0 text-muted">This is a computer generated invoice.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .card-header, .btn, .no-print {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    .card-body {
        padding: 0 !important;
    }
}
</style>

<script>
function printInvoice() {
    window.print();
}
</script>
@endsection
