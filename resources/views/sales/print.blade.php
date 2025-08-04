<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $sale->invoice_no }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            body {
                background-color: white;
                font-size: 12px;
            }
            .container {
                width: 100%;
                max-width: 100%;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .table {
                page-break-inside: avoid;
            }
            .page-break {
                page-break-after: always;
            }
            .invoice-header {
                position: fixed;
                top: 0;
                width: 100%;
            }
            .invoice-footer {
                position: fixed;
                bottom: 0;
                width: 100%;
            }
            .watermark {
                display: block !important;
            }
        }
        .invoice-container {
            /* max-width: 800px; */
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            background: white;
            position: relative;
        }
        .invoice-header img {
            max-height: 60px;
        }
        .table th {
            white-space: nowrap;
        }
        .table-sm td, .table-sm th {
            padding: 0.3rem;
        }
        .text-primary {
            color: #7367f0 !important;
        }
        .badge {
            font-size: 85%;
            padding: 0.35em 0.5em;
        }
        .sm-text {
        font-size: 60%;
        font-weight: 400;
        }
        .watermark {
            position: fixed;
            opacity: 0.1;
            z-index: -1;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 120px;
            color: #7367f0;
            font-weight: bold;
            pointer-events: none;
            display: none;
        }
        .watermark-image {
            position: fixed;
            opacity: 0.08;
            z-index: -1;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-15deg);
            max-width: 60%;
            max-height: 60%;
            pointer-events: none;
            display: none;
        }
    </style>
</head>
<body>
    @php
        $logoPath = \App\Models\SystemSetting::where('key', 'shop_logo')->value('value');
    @endphp
    <div class="container invoice-container">
        <!-- Watermark -->
        @if($settings['shop_logo'])
        <img src="{{ asset('storage/app/public/' . $logoPath) }}" class="watermark-image" alt="Watermark">
        @else
        <div class="watermark">{{ $settings['shop_name'] ?? 'Mobile Shop' }}</div>
        @endif

        <!-- Print Button (Hidden when printing) -->
        <div class="text-center mb-3 no-print">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> Print Invoice
            </button>
        </div>

        <!-- Invoice Content -->
        <div class="invoice-content" id="invoice-content">
            <!-- Company Header -->
            <div class="row mb-3">
                <div class="col-8">
                    <div class="d-flex align-items-center">
                        @if($settings['shop_logo'])
                            {{-- <img src="{{ asset('storage/' . $settings['shop_logo']) }}"
                                 alt="Shop Logo" class="me-2" style="max-height: 60px;"> --}}
                                 <img src="{{ asset('storage/app/public/' . $logoPath) }}"
                                             alt="Shop Logo" class="me-3" style="max-height: 60px;">
                        @endif
                        <div>
                            <h5 class="text-dark mb-1">{{ $settings['shop_name'] ?? 'Mobile Shop System' }}</h5>
                            <p class="mb-0 small">{{ $settings['shop_address'] ?? 'Shop Address' }}</p>
                            <p class="mb-0 small">Phone: {{ $settings['shop_phone'] ?? 'N/A' }}</p>
                            <p class="mb-0 small">Email: {{ $settings['shop_email'] ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-4 text-right">
                    <h5 class="text-primary">INVOICE</h5>
                    <p class="mb-1 small"><strong>Invoice No:</strong> {{ $sale->invoice_no }}</p>
                    <p class="mb-1 small"><strong>Date:</strong> {{ $sale->sale_date->format('d M Y') }}</p>
                    <p class="mb-0 small"><strong>Time:</strong> {{ $sale->sale_date->format('h:i A') }}</p>
                </div>
            </div>

            <hr>

            <!-- Customer Info -->
            <div class="row mb-3">
                <div class="col-6">
                    <h6 class="text-primary">Bill To:</h6>
                    @if($sale->customer)
                        <p class="mb-1 small"><strong>{{ $sale->customer->name }}</strong></p>
                        <p class="mb-1 small">Mobile: {{ $sale->customer->phone }}</p>
                        <p class="mb-0 small">{{ $sale->customer->address }}</p>
                    @elseif($sale->customer_name)
                        <p class="mb-1 small"><strong>{{ $sale->customer_name }}</strong></p>
                        <p class="mb-0 small">{{ $sale->customer_phone }}</p>
                    @else
                        <p class="mb-0 small">Walk-in Customer</p>
                    @endif
                </div>
                <div class="col-6 text-right">
                    <p class="mb-1 small"><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $sale->payment_method)) }}</p>
                    <p class="mb-1 small"><strong>Status:</strong>
                        <span class="badge badge-{{ $sale->payment_status == 'paid' ? 'success' : ($sale->payment_status == 'partial' ? 'warning' : 'danger') }}">
                            {{ ucfirst($sale->payment_status) }}
                        </span>
                    </p>
                    <p class="mb-0 small"><strong>Served by:</strong> {{ $sale->createdBy->name ?? 'System' }}</p>
                </div>
            </div>

            <!-- Items Table -->
            <div class="table-responsive mb-3">
                <table class="table table-sm table-bordered">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th width="5%">#</th>
                            <th width="25%">Product</th>
                            <th width="15%">Brand</th>
                            <th width="15%">Model</th>
                            <th width="15%">IMEI/Serial</th>
                            <th width="5%" class="text-center">Qty</th>
                            <th width="10%" class="text-right">Unit Price</th>
                            <th width="10%" class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->product->name }}<br>
                                    <div class="item-attributes">
                                    @php
                                        // Get attributes from sale item's attribute_data if available
                                        $attributes = json_decode($item->attribute_data, true) ?? [];

                                        // Fallback to inventory attributes if not in sale item
                                        if (empty($attributes) && $item->product->inventory) {
                                            $attributes = json_decode($item->product->inventory->attribute_combination, true) ?? [];
                                        }

                                        // Convert to human-readable format if needed
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
                                        <small class="text-muted">
                                            {{ $name }}: <strong>{{ $value }}</strong>
                                        </small>,
                                    @endforeach
                                </div>
                            </td>
                            <td>{{ $item->product->brand->name ?? 'N/A' }}</td>
                            <td>{{ $item->product->model ?? 'N/A' }}</td>
                            <td class="small">
                                @php
                                    $imeiNumbers = json_decode($item->imei_numbers, true) ?? [];
                                    $serialNumbers = json_decode($item->serial_numbers, true) ?? [];
                                @endphp
                                @if(!empty($imeiNumbers) && array_filter($imeiNumbers))
                                    @foreach(array_filter($imeiNumbers) as $imei)
                                        <strong>IMEI: </strong>{{ $imei }}
                                    @endforeach
                                @endif
                                @if(!empty($serialNumbers) && array_filter($serialNumbers))

                                    @foreach(array_filter($serialNumbers) as $serial)
                                        <strong>Serial: </strong>{{ $serial }}
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-right">৳{{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-right">৳{{ number_format($item->total_price, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Totals -->
            <div class="row">
                <div class="col-6">
                    @if($sale->note)
                    <div class="mb-2">
                        <strong class="small">Note:</strong>
                        <p class="mb-0 small">{{ $sale->note }}</p>
                    </div>
                    @endif
                </div>
                <div class="col-6">
                    <table class="table table-sm">
                        <tr>
                            <td class="text-right"><strong>Subtotal:</strong></td>
                            <td class="text-right">৳{{ number_format($sale->subtotal, 2) }}</td>
                        </tr>
                        @if($sale->discount_amount > 0)
                        <tr>
                            <td class="text-right"><strong>Discount:</strong></td>
                            <td class="text-right">-৳{{ number_format($sale->discount_amount, 2) }}</td>
                        </tr>
                        @endif
                        @if($sale->tax_amount > 0)
                        <tr>
                            <td class="text-right"><strong>Tax:</strong></td>
                            <td class="text-right">৳{{ number_format($sale->tax_amount, 2) }}</td>
                        </tr>
                        @endif
                        <tr class="bg-light">
                            <td class="text-right"><strong>Total Amount:</strong></td>
                            <td class="text-right"><strong>৳{{ number_format($sale->total_amount, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-right"><strong>Paid Amount:</strong></td>
                            <td class="text-right">৳{{ number_format($sale->paid_amount, 2) }}</td>
                        </tr>
                        @if($sale->due_amount > 0)
                        <tr class="bg-warning-light">
                            <td class="text-right"><strong>Due Amount:</strong></td>
                            <td class="text-right"><strong>৳{{ number_format($sale->due_amount, 2) }}</strong></td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Footer -->
            <div class="row mt-3">
                <div class="col-12 text-center">
                    <hr>
                    <p class="mb-1 small"><strong>{{ $settings['invoice_footer'] ?? 'Thank you for your business!' }}</strong></p>
                    <p class="mb-1 small text-muted">This is a computer generated invoice.</p>
                    <p class="mb-0 sm-text text-muted">System developed by Rokon  | Contact: +8801717524792.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script>
        // Auto-print when page loads (optional)
       // window.onload = function() {
           // setTimeout(function() {
               // window.print();
           // }, 500);
       // };

        // Close window after printing (optional)
        window.onafterprint = function() {
            window.close();
        };
    </script>
</body>
</html>
