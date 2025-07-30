<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Receipt #{{ $sale->id }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            margin: 0;
            padding: 10px;
            font-size: 12px;
            line-height: 1.4;
            width: 300px;
        }
        .receipt-header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .receipt-header h1 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }
        .receipt-info {
            margin-bottom: 10px;
        }
        .receipt-info div {
            display: flex;
            justify-content: space-between;
        }
        .items-section {
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .item-row {
            margin-bottom: 5px;
        }
        .item-name {
            font-weight: bold;
        }
        .item-details {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
        }
        .totals-section {
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }
        .grand-total {
            font-weight: bold;
            font-size: 14px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 10px;
        }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        @media print {
            body { width: auto; }
        }
    </style>
</head>
<body>
    <div class="receipt-header">
        <h1>{{ $settings['company_name'] ?? 'MOBILE SHOP' }}</h1>
        <div>{{ $settings['company_address'] ?? 'Shop Address' }}</div>
        <div>Tel: {{ $settings['company_phone'] ?? 'N/A' }}</div>
        <div>================================</div>
        <div class="bold">SALES RECEIPT</div>
    </div>

    <div class="receipt-info">
        <div><span>Receipt #:</span><span>{{ $sale->id }}</span></div>
        <div><span>Date:</span><span>{{ $sale->sale_date->format('d/m/Y H:i') }}</span></div>
        <div><span>Cashier:</span><span>{{ $sale->user->name ?? 'N/A' }}</span></div>
        @if($sale->customer)
        <div><span>Customer:</span><span>{{ $sale->customer->name }}</span></div>
        <div><span>Phone:</span><span>{{ $sale->customer->phone }}</span></div>
        @else
        <div><span>Customer:</span><span>Walk-in</span></div>
        @endif
    </div>

    <div class="items-section">
        <div class="center bold">ITEMS</div>
        <div>================================</div>
        @foreach($sale->items as $item)
        <div class="item-row">
            <div class="item-name">{{ $item->product->name }}</div>
            <div class="item-details">
                <span>{{ $item->quantity }} x ৳{{ number_format($item->unit_price, 2) }}</span>
                <span>৳{{ number_format($item->total_price, 2) }}</span>
            </div>
        </div>
        @endforeach
    </div>

    <div class="totals-section">
        <div class="total-row">
            <span>Subtotal:</span>
            <span>৳{{ number_format($sale->subtotal, 2) }}</span>
        </div>
        @if($sale->tax_amount > 0)
        <div class="total-row">
            <span>Tax ({{ $sale->tax_rate }}%):</span>
            <span>৳{{ number_format($sale->tax_amount, 2) }}</span>
        </div>
        @endif
        @if($sale->discount_amount > 0)
        <div class="total-row">
            <span>Discount:</span>
            <span>-৳{{ number_format($sale->discount_amount, 2) }}</span>
        </div>
        @endif
        <div class="total-row grand-total">
            <span>TOTAL:</span>
            <span>৳{{ number_format($sale->total_amount, 2) }}</span>
        </div>
        <div class="total-row">
            <span>Paid:</span>
            <span>৳{{ number_format($sale->paid_amount, 2) }}</span>
        </div>
        @if($sale->total_amount - $sale->paid_amount > 0)
        <div class="total-row bold">
            <span>Due:</span>
            <span>৳{{ number_format($sale->total_amount - $sale->paid_amount, 2) }}</span>
        </div>
        @else
        <div class="total-row">
            <span>Change:</span>
            <span>৳{{ number_format($sale->paid_amount - $sale->total_amount, 2) }}</span>
        </div>
        @endif
    </div>

    <div class="center">
        <div>Payment: {{ ucfirst(str_replace('_', ' ', $sale->payment_method)) }}</div>
        <div>Status: {{ ucfirst($sale->payment_status) }}</div>
    </div>

    @if($sale->notes)
    <div style="margin-top: 10px; border-top: 1px dashed #000; padding-top: 10px;">
        <div class="center bold">NOTES</div>
        <div>{{ $sale->notes }}</div>
    </div>
    @endif

    <div class="footer">
        <div>================================</div>
        <div>Thank you for your purchase!</div>
        <div>Please keep this receipt</div>
        <div>for warranty claims</div>
        <div>{{ now()->format('d/m/Y H:i:s') }}</div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
