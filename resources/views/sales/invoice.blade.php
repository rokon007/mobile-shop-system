<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $sale->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 15px;
            color: #000;
            font-size: 12px;
            line-height: 1.4;
        }

        .invoice-container {
            max-width: 100%;
            margin: 0 auto;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000;
        }

        .company-info h1 {
            margin: 0 0 3px 0;
            font-size: 18px;
            font-weight: bold;
        }

        .company-info p {
            margin: 2px 0;
        }

        .invoice-title h2 {
            margin: 0 0 5px 0;
            font-size: 20px;
            text-align: right;
        }

        .invoice-title p {
            margin: 2px 0;
            text-align: right;
        }

        .billing-info {
            display: flex;
            margin-bottom: 15px;
            gap: 30px; /* Increased gap between sections */
        }

        .billing-section {
            flex: 1;
            padding: 10px 0;
        }

        .billing-section h3 {
            margin-top: 0;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .billing-section p {
            margin: 5px 0;
        }

        .billing-section .detail-row {
            display: flex;
            margin-bottom: 3px;
        }

        .billing-section .detail-label {
            font-weight: bold;
            min-width: 80px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .items-table th {
            padding: 8px 5px;
            text-align: left;
            font-weight: bold;
            border-bottom: 1px solid #000;
            text-transform: uppercase;
            font-size: 11px;
        }

        .items-table td {
            padding: 8px 5px;
            border-bottom: 1px solid #ddd;
        }

        .items-table .text-right {
            text-align: right;
        }

        .totals-section {
            width: 200px;
            margin-left: auto;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 6px 5px;
            border-bottom: 1px solid #ddd;
        }

        .totals-table .total-row {
            font-weight: bold;
            border-top: 1px solid #000;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            padding-top: 10px;
            border-top: 1px dashed #999;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            background-color: #fff !important;
            color: #000 !important;
            border: 1px solid #000;
            letter-spacing: 0.5px;
        }

        @media print {
            body {
                padding: 5px;
                font-size: 11px;
            }

            .no-print {
                display: none;
            }

            .invoice-header {
                page-break-after: avoid;
            }

            .items-table {
                page-break-inside: avoid;
            }

            .totals-section {
                page-break-before: avoid;
            }

            .footer {
                border-top: 1px solid #000;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <div class="company-info">
                <h1>{{ $settings['company_name'] ?? 'Mobile Shop' }}</h1>
                <p>{{ $settings['company_address'] ?? 'Company Address' }}</p>
                <p>Phone: {{ $settings['company_phone'] ?? 'N/A' }}</p>
            </div>
            <div class="invoice-title">
                <h2>INVOICE</h2>
                <p><strong>#{{ $sale->id }}</strong></p>
                <p>{{ $sale->sale_date->format('d/m/Y h:i A') }}</p>
            </div>
        </div>

        <div class="billing-info">
            <div class="billing-section">
                <h3>Bill To</h3>
                @if($sale->customer)
                    <p><strong>{{ $sale->customer->name }}</strong></p>
                    <div class="detail-row">
                        <span class="detail-label">Phone:</span>
                        <span>{{ $sale->customer->phone }}</span>
                    </div>
                    @if($sale->customer->email)
                    <div class="detail-row">
                        <span class="detail-label">Email:</span>
                        <span>{{ $sale->customer->email }}</span>
                    </div>
                    @endif
                    @if($sale->customer->address)
                    <div class="detail-row">
                        <span class="detail-label">Address:</span>
                        <span>{{ $sale->customer->address }}</span>
                    </div>
                    @endif
                @else
                    <p><strong>Walk-in Customer</strong></p>
                @endif
            </div>

            <div class="billing-section">
                <h3>Sale Details</h3>
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="badge">{{ ucfirst($sale->status) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Payment:</span>
                    <span class="badge">{{ ucfirst($sale->payment_status) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Method:</span>
                    <span>{{ ucfirst(str_replace('_', ' ', $sale->payment_method)) }}</span>
                </div>
                @if($sale->user)
                <div class="detail-row">
                    <span class="detail-label">Served By:</span>
                    <span>{{ $sale->user->name }}</span>
                </div>
                @endif
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>SKU</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->product->sku }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">৳{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">৳{{ number_format($item->total_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td>Subtotal:</td>
                    <td class="text-right">৳{{ number_format($sale->subtotal, 2) }}</td>
                </tr>
                @if($sale->tax_amount > 0)
                <tr>
                    <td>Tax ({{ $sale->tax_rate }}%):</td>
                    <td class="text-right">৳{{ number_format($sale->tax_amount, 2) }}</td>
                </tr>
                @endif
                @if($sale->discount_amount > 0)
                <tr>
                    <td>Discount:</td>
                    <td class="text-right">-৳{{ number_format($sale->discount_amount, 2) }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td>Total Amount:</td>
                    <td class="text-right">৳{{ number_format($sale->total_amount, 2) }}</td>
                </tr>
                <tr>
                    <td>Paid Amount:</td>
                    <td class="text-right">৳{{ number_format($sale->paid_amount, 2) }}</td>
                </tr>
                @if($sale->total_amount - $sale->paid_amount > 0)
                <tr>
                    <td><strong>Due Amount:</strong></td>
                    <td class="text-right"><strong>৳{{ number_format($sale->total_amount - $sale->paid_amount, 2) }}</strong></td>
                </tr>
                @endif
            </table>
        </div>

        @if($sale->notes)
        <div style="margin-top: 10px; padding: 8px; border: 1px dashed #999; border-radius: 3px;">
            <p style="margin: 0;"><strong>Notes:</strong> {{ $sale->notes }}</p>
        </div>
        @endif

        <div class="footer">
            <p>Thank you for your business!</p>
            <p>System developed by Rokon  | Contact: +8801717524792</p>
            <div class="no-print" style="margin-top: 10px;">
                <button onclick="window.print()" style="padding: 5px 15px; background-color: #000; color: white; border: none; cursor: pointer; border-radius: 3px;">
                    Print Invoice
                </button>
            </div>
        </div>
    </div>
</body>
</html>
