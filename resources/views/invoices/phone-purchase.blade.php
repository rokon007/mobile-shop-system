<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Phone Purchase Invoice - {{ $settings['shop_name'] ?? 'Mobile Shop' }}</title>
    <style>
        :root {
            --primary-color: #4a6bff;
            --secondary-color: #f8f9fa;
            --accent-color: #ff6b4a;
            --text-color: #333;
            --light-text: #666;
            --border-color: #e0e0e0;
        }

        body {
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 20px;
            background: #f5f7fa;
            color: var(--text-color);
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            position: relative;
            z-index: 2;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            opacity: 0.05;
            z-index: 1;
            width: 60%;
            height: auto;
            filter: grayscale(100%);
        }

        .header {
            display: flex;
            align-items: center;
            padding: 25px 30px;
            background: linear-gradient(135deg, var(--primary-color), #3a56d6);
            color: black;
        }

        .header img {
            max-height: 80px;
            margin-right: 25px;
            border-radius: 8px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .shop-details h5 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .shop-details p {
            margin: 3px 0;
            font-size: 13px;
            color: black;
        }

        .invoice-title {
            padding: 20px 30px;
            background-color: var(--secondary-color);
            border-bottom: 1px solid var(--border-color);
        }

        .invoice-title h2 {
            margin: 0;
            color: var(--primary-color);
            text-transform: uppercase;
            font-size: 20px;
            letter-spacing: 1px;
        }

        .invoice-meta {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            font-size: 13px;
            color: var(--light-text);
        }

        .invoice-number {
            font-weight: 600;
            color: var(--primary-color);
        }

        h4 {
            margin: 25px 30px 15px;
            color: var(--primary-color);
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        h4:before {
            content: "";
            display: inline-block;
            width: 4px;
            height: 18px;
            background: var(--primary-color);
            border-radius: 2px;
        }

        .section-container {
            padding: 0 30px 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            position: relative;
            z-index: 3;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            font-size: 13px;
            border: 1px solid var(--border-color);
        }

        th {
            background-color: #f5f7fa;
            font-weight: 600;
            color: #555;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        tr:nth-child(even) {
            background-color: var(--secondary-color);
        }

        .highlight {
            font-weight: 600;
            color: var(--primary-color);
        }

        .footer {
            margin-top: 30px;
            padding: 20px 30px;
            font-size: 12px;
            text-align: center;
            color: var(--light-text);
            border-top: 1px solid var(--border-color);
            background-color: var(--secondary-color);
        }

        .signature-area {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding: 0 30px;
        }

        .signature-box {
            text-align: center;
            width: 45%;
        }

        .signature-line {
            border-top: 1px dashed var(--border-color);
            margin: 40px 0 10px;
            padding-top: 10px;
        }

        .qr-code {
            width: 100px;
            height: 100px;
            background: #f5f5f5;
            margin: 20px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            color: var(--light-text);
            font-size: 10px;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            background-color: #e3f7eb;
            color: #28a745;
        }

        @media print {
            body {
                background: none;
                padding: 0;
            }

            .invoice-container {
                box-shadow: none;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>

@php
    $logoPath = \App\Models\SystemSetting::where('key', 'shop_logo')->value('value');
    $absoluteLogoPath = $logoPath ? public_path('storage/' . $logoPath) : null;
@endphp

{{-- Watermark --}}
@if(!empty($logoPath) && file_exists($absoluteLogoPath))
    <img src="{{ $absoluteLogoPath }}" class="watermark" alt="Watermark Logo">
@endif

<div class="invoice-container">

    {{-- Header --}}
    <div class="header">
        @if(!empty($logoPath) && file_exists($absoluteLogoPath))
            <img src="{{ $absoluteLogoPath }}" alt="Shop Logo">
        @endif
        <div class="shop-details">
            <h5>{{ $settings['shop_name'] ?? 'Mobile Shop System' }}</h5>
            <p>{{ $settings['shop_address'] ?? 'Shop Address' }}</p>
            <p>Phone: {{ $settings['shop_phone'] ?? 'N/A' }} | Email: {{ $settings['shop_email'] ?? 'N/A' }}</p>
            @if(!empty($settings['shop_vat']))
                <p>VAT Reg No: {{ $settings['shop_vat'] }}</p>
            @endif
        </div>
    </div>

    {{-- Invoice title --}}
    <div class="invoice-title">
        <h2>Phone Purchase Invoice</h2>
        <div class="invoice-meta">
            <div>
                <span class="invoice-number">Invoice #{{ $purchase->id }}</span>
                | Date: {{ $purchase->created_at->format('d M, Y') }}
                | Time: {{ $purchase->created_at->format('h:i A') }}
            </div>
            <div class="status-badge">Completed</div>
        </div>
    </div>

    <div class="section-container">
        {{-- Seller Information --}}
        <h4>Seller Information</h4>
        <table>
            <tr><th width="25%">Name</th><td>{{ $purchase->purchase->seller->name }}</td></tr>
            <tr><th>Contact Number</th><td>{{ $purchase->purchase->seller->phone }}</td></tr>
            <tr><th>Email</th><td>{{ $purchase->purchase->seller->email ?? 'N/A' }}</td></tr>
            <tr><th>Address</th><td>{{ $purchase->purchase->seller->present_address }}</td></tr>
            <tr><th>Seller ID</th><td>SELL-{{ str_pad($purchase->purchase->seller->id, 5, '0', STR_PAD_LEFT) }}</td></tr>
        </table>

        {{-- Phone Information --}}
        <h4>Device Details</h4>
        <table>
            <tr><th width="25%">Brand</th><td>{{ $purchase->brand }}</td></tr>
            <tr><th>Model</th><td>{{ $purchase->model }}</td></tr>
            <tr><th>Manufacture Year</th><td>{{ $purchase->manufacture_year }}</td></tr>
            <tr><th>IMEI</th><td class="highlight">{{ $purchase->imei }}</td></tr>
            <tr><th>Serial Number</th><td>{{ $purchase->serial_number ?? 'N/A' }}</td></tr>
            <tr><th>Specifications</th><td>{{ $purchase->ram }} RAM / {{ $purchase->rom }} Storage</td></tr>
            <tr><th>Purchase Price</th><td class="highlight">{{ number_format($purchase->purchase->purchase_price, 2) }} BDT</td></tr>
            <tr><th>Condition</th><td>Used (Good Condition)</td></tr>
        </table>

        {{-- Payment Summary --}}
        <h4>Payment Summary</h4>
        <table>
            <tr>
                <th width="25%">Subtotal</th>
                <td>{{ number_format($purchase->purchase->purchase_price, 2) }} BDT</td>
            </tr>
            {{-- <tr>
                <th>Tax/VAT (0%)</th>
                <td>0.00 BDT</td>
            </tr> --}}
            <tr>
                <th>Total Amount</th>
                <td class="highlight">{{ number_format($purchase->purchase->purchase_price, 2) }} BDT</td>
            </tr>
            {{-- <tr>
                <th>Payment Method</th>
                <td>Cash</td>
            </tr> --}}
            <tr>
                <th>Payment Status</th>
                <td><span class="status-badge">Paid</span></td>
            </tr>
        </table>
    </div>

    {{-- QR Code and Signatures --}}
    {{-- <div class="section-container">
        <div class="signature-area">
            <div class="signature-box">
                <div class="qr-code">
                    [QR Code]
                    <div>Scan to verify</div>
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-line">Seller's Signature</div>
            </div>
            <div class="signature-box">
                <div class="signature-line">Authorized Signature</div>
            </div>
        </div>
    </div> --}}

    {{-- Footer --}}
    <div class="footer">
        <p>{{ $settings['invoice_footer'] ?? 'Thank you for your business. Warranty provided as per company policy.' }}</p>
        <p>For any inquiries, please contact: {{ $settings['shop_phone'] ?? 'N/A' }} | {{ $settings['shop_email'] ?? 'N/A' }}</p>
        <p>System developed by <strong>Rokon</strong> | Contact: <strong>+8801717524792</strong></p>
    </div>
</div>
</body>
</html>
