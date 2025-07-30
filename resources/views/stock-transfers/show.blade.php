@extends('layouts.app')

@section('title', 'Stock Transfer Details')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h3>Stock Transfer Details</h3>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('stock-transfers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
        @if($stockTransfer->status == 'pending')
            <a href="{{ route('stock-transfers.edit', $stockTransfer) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit
            </a>
            <form action="{{ route('stock-transfers.receive', $stockTransfer) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to receive this transfer?')">
                    <i class="fas fa-check"></i> Receive Transfer
                </button>
            </form>
        @endif
    </div>
</div>

<div class="row layout-top-spacing">
    <div class="col-xl-8 col-lg-8 col-sm-12 layout-spacing">
        <div class="widget-content widget-content-area br-8">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Transfer No:</strong></label>
                        <p>{{ $stockTransfer->transfer_no }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Transfer Date:</strong></label>
                        <p>{{ \Carbon\Carbon::parse($stockTransfer->transfer_date)->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>From Warehouse:</strong></label>
                        <p>{{ $stockTransfer->fromWarehouse->name }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>To Warehouse:</strong></label>
                        <p>{{ $stockTransfer->toWarehouse->name }}</p>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label><strong>Status:</strong></label>
                <span class="badge badge-{{ $stockTransfer->status == 'completed' ? 'success' : ($stockTransfer->status == 'pending' ? 'warning' : 'danger') }}">
                    {{ ucfirst($stockTransfer->status) }}
                </span>
            </div>
            
            @if($stockTransfer->notes)
            <div class="form-group">
                <label><strong>Notes:</strong></label>
                <p>{{ $stockTransfer->notes }}</p>
            </div>
            @endif
            
            <div class="form-group">
                <label><strong>Transfer Items:</strong></label>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stockTransfer->items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->quantity }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-lg-4 col-sm-12 layout-spacing">
        <div class="widget-content widget-content-area br-8">
            <h5>Transfer Information</h5>
            <div class="list-group">
                <div class="list-group-item">
                    <strong>Created At:</strong><br>
                    {{ $stockTransfer->created_at->format('M d, Y h:i A') }}
                </div>
                <div class="list-group-item">
                    <strong>Updated At:</strong><br>
                    {{ $stockTransfer->updated_at->format('M d, Y h:i A') }}
                </div>
                <div class="list-group-item">
                    <strong>Total Items:</strong><br>
                    {{ $stockTransfer->items->count() }}
                </div>
                <div class="list-group-item">
                    <strong>Total Quantity:</strong><br>
                    {{ $stockTransfer->items->sum('quantity') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
