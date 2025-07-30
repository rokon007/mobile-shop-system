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
            padding: 20px;
            color: #333;
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }
        .company-info h1 {
            margin: 0;
            color: #007bff;
        }
        .invoice-info {
            text-align: right;
        }
        .invoice-info h2 {
            margin: 0;
            color: #007bff;
        }
        .billing-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .billing-section {
            width: 48%;
        }
        .billing-section h3 {
            margin-bottom: 10px;
            color: #007bff;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .items-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .items-table .text-right {
            text-align: right;
        }
        .totals-section {
            width: 300px;
            margin-left: auto;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        .totals-table .total-row {
            font-weight: bold;
            background-color: #f8f9fa;
            border-top: 2px solid #007bff;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-completed { background-color: #d4edda; color: #155724; }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-cancelled { background-color: #f8d7da; color: #721c24; }
        .payment-paid { background-color: #d4edda; color: #155724; }
        .payment-partial { background-color: #fff3cd; color: #856404; }
        .payment-pending { background-color: #f8d7da; color: #721c24; }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="invoice-header">
        <div class="company-info">
            <h1>{{ $settings['company_name'] ?? 'Mobile Shop' }}</h1>
            <p>{{ $settings['company_address'] ?? 'Company Address' }}</p>
            <p>Phone: {{ $settings['company_phone'] ?? 'N/A' }} | Email: {{ $settings['company_email'] ?? 'N/A' }}</p>
        </div>
        <div class="invoice-info">
            <h2>INVOICE</h2>
            <p><strong>Invoice #:</strong> {{ $sale->id }}</p>
            <p><strong>Date:</strong> {{ $sale->sale_date->format('F d, Y') }}</p>
            <p><strong>Time:</strong> {{ $sale->sale_date->format('h:i A') }}</p>
        </div>
    </div>

    <div class="billing-info">
        <div class="billing-section">
            <h3>Bill To:</h3>
            @if($sale->customer)
                <p><strong>{{ $sale->customer->name }}</strong></p>
                <p>{{ $sale->customer->email }}</p>
                <p>{{ $sale->customer->phone }}</p>
                <p>{{ $sale->customer->address }}</p>
            @else
                <p><strong>Walk-in Customer</strong></p>
            @endif
        </div>
        <div class="billing-section">
            <h3>Sale Details:</h3>
            <p><strong>Status:</strong> 
                <span class="status-badge status-{{ $sale->status }}">{{ ucfirst($sale->status) }}</span>
            </p>
            <p><strong>Payment Status:</strong> 
                <span class="status-badge payment-{{ $sale->payment_status }}">{{ ucfirst($sale->payment_status) }}</span>
            </p>
            <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $sale->payment_method)) }}</p>
            <p><strong>Served By:</strong> {{ $sale->user->name ?? 'N/A' }}</p>
        </div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Item</th>
                <th>SKU</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Unit Price</th>
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
    <div style="margin-top: 30px;">
        <h3>Notes:</h3>
        <p>{{ $sale->notes }}</p>
    </div>
    @endif

    <div class="footer">
        <p>Thank you for your business!</p>
        <p>This is a computer generated invoice.</p>
        <div class="no-print" style="margin-top: 20px;">
            <button onclick="window.print()" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
                Print Invoice
            </button>
            <button onclick="window.close()" style="padding: 10px 20px; background-color: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">
                Close
            </button>
        </div>
    </div>
</body>
</html>
