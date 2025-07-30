@extends('layouts.app')

@section('title', 'Warehouse Details')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h3>Warehouse Details</h3>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
        <a href="{{ route('warehouses.edit', $warehouse) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit
        </a>
    </div>
</div>

<div class="row layout-top-spacing">
    <div class="col-xl-8 col-lg-8 col-sm-12 layout-spacing">
        <div class="widget-content widget-content-area br-8">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Warehouse Name:</strong></label>
                        <p>{{ $warehouse->name }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Code:</strong></label>
                        <p>{{ $warehouse->code }}</p>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Phone:</strong></label>
                        <p>{{ $warehouse->phone ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Email:</strong></label>
                        <p>{{ $warehouse->email ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label><strong>Address:</strong></label>
                <p>{{ $warehouse->address }}</p>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label><strong>City:</strong></label>
                        <p>{{ $warehouse->city ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><strong>State:</strong></label>
                        <p>{{ $warehouse->state ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><strong>Postal Code:</strong></label>
                        <p>{{ $warehouse->postal_code ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label><strong>Status:</strong></label>
                <span class="badge badge-{{ $warehouse->status == 'active' ? 'success' : 'danger' }}">
                    {{ ucfirst($warehouse->status) }}
                </span>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Created At:</strong></label>
                        <p>{{ $warehouse->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Updated At:</strong></label>
                        <p>{{ $warehouse->updated_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-lg-4 col-sm-12 layout-spacing">
        <div class="widget-content widget-content-area br-8">
            <h5>Quick Actions</h5>
            <div class="list-group">
                <a href="{{ route('warehouses.edit', $warehouse) }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-edit"></i> Edit Warehouse
                </a>
                <a href="{{ route('stock-transfers.create') }}?from_warehouse={{ $warehouse->id }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-exchange-alt"></i> Transfer Stock From Here
                </a>
                <a href="{{ route('stock-transfers.create') }}?to_warehouse={{ $warehouse->id }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-download"></i> Transfer Stock To Here
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
