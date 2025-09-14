<!DOCTYPE html>
<html>
<head>
    <title>Sales Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
        }
        .date {
            font-size: 12px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
         td {
            font-size: 11px; /* Smaller font size for table cells */
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 12px;
            color: #666;
        }
        .summary {
            margin-top: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .product-details {
            font-size: 10px;
            color: #666;
        }
        .attribute-item {
            display: inline-block;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Sales Report</div>
        <div class="date">Generated on: {{ now()->format('M d, Y h:i A') }}</div>
    </div>

    @php
        $hideDateColumn = request()->start_date && request()->end_date && request()->start_date == request()->end_date;

        // Calculate total profit
        $totalProfit = 0;
        foreach($sales as $sale) {
            foreach($sale->items as $item) {
                $costPrice = $item->inventory->purchase_price ?? 0;
                $profit = ($item->unit_price - $costPrice) * $item->quantity;
                $totalProfit += $profit;
            }
        }
    @endphp

    @if(request()->start_date || request()->end_date || request()->customer_id)
    <div class="filters">
        <strong>Filters Applied:</strong><br>
        @if(request()->start_date)
        - Start Date: {{ request()->start_date }}<br>
        @endif
        @if(request()->end_date)
        - End Date: {{ request()->end_date }}<br>
        @endif
        @if(request()->customer_id)
        - Customer: {{ \App\Models\Customer::find(request()->customer_id)->name ?? 'N/A' }}<br>
        @endif
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Invoice #</th>
                @if(!$hideDateColumn)
                <th>Date</th>
                @endif
                <th>Customer</th>
                <th>Product Details</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
                @foreach($sale->items as $index => $item)
                <tr>
                    @if($index === 0)
                        <td rowspan="{{ $sale->items->count() }}">{{ $sale->invoice_no }}</td>
                        @if(!$hideDateColumn)
                        <td rowspan="{{ $sale->items->count() }}">{{ $sale->sale_date->format('M d, Y') }}</td>
                        @endif
                        <td rowspan="{{ $sale->items->count() }}">{{ $sale->customer->name ?? 'Walk-in Customer' }}</td>
                    @endif

                    <td>
                        <strong>{{ $item->product->name }}</strong>
                        <div class="product-details">
                            @php
                                $imeiNumbers = json_decode($item->imei_numbers, true) ?? [];
                                $serialNumbers = json_decode($item->serial_numbers, true) ?? [];
                            @endphp

                            @if(!empty($imeiNumbers) && array_filter($imeiNumbers))
                                @foreach(array_filter($imeiNumbers) as $imei)
                                    <div>IMEI: {{ $imei }}</div>
                                @endforeach
                            @endif

                            @if(!empty($serialNumbers) && array_filter($serialNumbers))
                                @foreach(array_filter($serialNumbers) as $serial)
                                    <div>Serial: {{ $serial }}</div>
                                @endforeach
                            @endif

                            @php
                                // Get attributes from sale item's attribute_data if available
                                $attributes = json_decode($item->attribute_data, true) ?? [];

                                // Fallback to inventory attributes if not in sale item
                                if (empty($attributes) && $item->inventory) {
                                    $attributes = json_decode($item->inventory->attribute_combination, true) ?? [];
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

                            @if(!empty($displayAttributes))
                                <div class="attributes">
                                    @foreach($displayAttributes as $name => $value)
                                        <span class="attribute-item">
                                            {{ $name }}: <strong>{{ $value }}</strong>
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </td>

                    @if($index === 0)
                        <td rowspan="{{ $sale->items->count() }}">Tk {{ number_format($sale->total_amount, 2) }}</td>
                    @endif
                </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <strong>Summary:</strong><br>
        Total Sales: {{ $sales->count() }}<br>
        Total Amount: Tk {{ number_format($sales->sum('total_amount'), 2) }}<br>
        Total Profit: Tk {{ number_format($totalProfit, 2) }}
    </div>

    <div class="footer">
        Generated by {{ config('app.name') }}
    </div>
</body>
</html>
