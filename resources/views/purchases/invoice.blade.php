@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Purchase Invoice #{{ $purchase->id }}</h4>
                    <button onclick="window.print()" class="btn btn-primary">Print Invoice</button>
                </div>
                <div class="card-body">
                    <div class="invoice-header mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <h3>Mobile Shop System</h3>
                                <p>123 Business Street<br>
                                City, State 12345<br>
                                Phone: (555) 123-4567</p>
                            </div>
                            <div class="col-md-6 text-right">
                                <h4>PURCHASE INVOICE</h4>
                                <p><strong>Invoice #:</strong> {{ $purchase->id }}<br>
                                <strong>Date:</strong> {{ $purchase->purchase_date->format('M d, Y') }}<br>
                                <strong>Status:</strong> {{ ucfirst($purchase->status) }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="supplier-info mb-4">
                        <h5>Supplier Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>{{ $purchase->supplier->name }}</strong><br>
                                {{ $purchase->supplier->address }}<br>
                                Phone: {{ $purchase->supplier->phone }}<br>
                                Email: {{ $purchase->supplier->email }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchase->items as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>${{ number_format($item->unit_price, 2) }}</td>
                                    <td>${{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3">Total Amount</th>
                                    <th>${{ number_format($purchase->total_amount, 2) }}</th>
                                </tr>
                                <tr>
                                    <th colspan="3">Paid Amount</th>
                                    <th>${{ number_format($purchase->paid_amount, 2) }}</th>
                                </tr>
                                <tr>
                                    <th colspan="3">Due Amount</th>
                                    <th>${{ number_format($purchase->total_amount - $purchase->paid_amount, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    @if($purchase->notes)
                        <div class="mt-3">
                            <h5>Notes</h5>
                            <p>{{ $purchase->notes }}</p>
                        </div>
                    @endif
                    
                    <div class="text-center mt-4">
                        <p>Thank you for your business!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .card-header, .btn, .navbar, .sidebar {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>
@endsection
