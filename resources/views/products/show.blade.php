@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Product Details</h4>
                    <div class="float-end">
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded" alt="{{ $product->name }}">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 300px;">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Product Name:</th>
                                    <td>{{ $product->name }}</td>
                                </tr>
                                <tr>
                                    <th>SKU:</th>
                                    <td>{{ $product->sku }}</td>
                                </tr>
                                <tr>
                                    <th>Barcode:</th>
                                    <td>{{ $product->barcode ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Category:</th>
                                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Brand:</th>
                                    <td>{{ $product->brand->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Unit:</th>
                                    <td>{{ $product->unit }}</td>
                                </tr>
                                <tr>
                                    <th>Purchase Price:</th>
                                    <td>৳{{ number_format($product->purchase_price, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Selling Price:</th>
                                    <td>৳{{ number_format($product->selling_price, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Stock Quantity:</th>
                                    <td>
                                        <span class="badge {{ $product->stock_quantity > $product->alert_quantity ? 'bg-success' : 'bg-warning' }}">
                                            {{ $product->stock_quantity }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Alert Quantity:</th>
                                    <td>{{ $product->alert_quantity }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge {{ $product->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                            {{ ucfirst($product->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tax Rate:</th>
                                    <td>{{ $product->tax_rate }}%</td>
                                </tr>
                                <tr>
                                    <th>Tax Method:</th>
                                    <td>{{ ucfirst($product->tax_method) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($product->description)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Description</h5>
                            <p>{{ $product->description }}</p>
                        </div>
                    </div>
                    @endif

                    @if($product->serials && $product->serials->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Serial Numbers / IMEI</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Serial Number</th>
                                            <th>Status</th>
                                            <th>Sale ID</th>
                                            <th>Date Added</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($product->serials as $serial)
                                        <tr>
                                            <td>{{ $serial->serial_number }}</td>
                                            <td>
                                                <span class="badge {{ $serial->status == 'available' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ ucfirst($serial->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $serial->sale_id ?? 'N/A' }}</td>
                                            <td>{{ $serial->created_at->format('d M Y') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Stock Movement History</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Quantity</th>
                                            <th>Reference</th>
                                            <th>Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($product->stockMovements ?? [] as $movement)
                                        <tr>
                                            <td>{{ $movement->created_at->format('d M Y') }}</td>
                                            <td>
                                                <span class="badge {{ $movement->type == 'in' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ ucfirst($movement->type) }}
                                                </span>
                                            </td>
                                            <td>{{ $movement->quantity }}</td>
                                            <td>{{ $movement->reference }}</td>
                                            <td>{{ $movement->notes ?? 'N/A' }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No stock movement history available</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
