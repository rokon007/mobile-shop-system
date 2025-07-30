@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Sale Details - #{{ $sale->id }}</h4>
                    <div>
                        <a href="{{ route('sales.invoice', $sale) }}" class="btn btn-secondary" target="_blank">
                            <i class="fas fa-file-pdf"></i> View Invoice
                        </a>
                        <a href="{{ route('sales.print', $sale) }}" class="btn btn-info" target="_blank">
                            <i class="fas fa-print"></i> Print
                        </a>
                        @if($sale->payment_status != 'paid')
                        <a href="{{ route('sales.edit', $sale) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        @endif
                        <a href="{{ route('sales.index') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Back to Sales
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Sale Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Sale ID:</strong></td>
                                    <td>{{ $sale->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Date:</strong></td>
                                    <td>{{ $sale->sale_date->format('F d, Y H:i A') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $sale->status == 'completed' ? 'success' : ($sale->status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($sale->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Status:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $sale->payment_status == 'paid' ? 'success' : ($sale->payment_status == 'partial' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($sale->payment_status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Method:</strong></td>
                                    <td>{{ ucfirst($sale->payment_method) }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Customer Information</h5>
                            @if($sale->customer)
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $sale->customer->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $sale->customer->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Phone:</strong></td>
                                    <td>{{ $sale->customer->phone }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Address:</strong></td>
                                    <td>{{ $sale->customer->address }}</td>
                                </tr>
                            </table>
                            @else
                            <p class="text-muted">Walk-in Customer</p>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <h5>Sale Items</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sale->items as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->product->sku }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>৳{{ number_format($item->unit_price, 2) }}</td>
                                    <td>৳{{ number_format($item->total_price, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-md-6 offset-md-6">
                            <table class="table">
                                <tr>
                                    <td><strong>Subtotal:</strong></td>
                                    <td class="text-right">৳{{ number_format($sale->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tax ({{ $sale->tax_rate }}%):</strong></td>
                                    <td class="text-right">৳{{ number_format($sale->tax_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Discount:</strong></td>
                                    <td class="text-right">৳{{ number_format($sale->discount_amount, 2) }}</td>
                                </tr>
                                <tr class="table-active">
                                    <td><strong>Total Amount:</strong></td>
                                    <td class="text-right"><strong>৳{{ number_format($sale->total_amount, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Paid Amount:</strong></td>
                                    <td class="text-right">৳{{ number_format($sale->paid_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Due Amount:</strong></td>
                                    <td class="text-right">৳{{ number_format($sale->total_amount - $sale->paid_amount, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($sale->notes)
                    <hr>
                    <h5>Notes</h5>
                    <p>{{ $sale->notes }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
