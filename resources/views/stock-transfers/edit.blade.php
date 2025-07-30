@extends('layouts.app')

@section('title', 'Edit Stock Transfer')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h3>Edit Stock Transfer</h3>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('stock-transfers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
        <a href="{{ route('stock-transfers.show', $stockTransfer) }}" class="btn btn-info">
            <i class="fas fa-eye"></i> View Details
        </a>
    </div>
</div>

<div class="row layout-top-spacing">
    <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
        <div class="widget-content widget-content-area br-8">
            <form action="{{ route('stock-transfers.update', $stockTransfer) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="transfer_no">Transfer No <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('transfer_no') is-invalid @enderror" 
                                   id="transfer_no" name="transfer_no" value="{{ old('transfer_no', $stockTransfer->transfer_no) }}" required>
                            @error('transfer_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="transfer_date">Transfer Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('transfer_date') is-invalid @enderror" 
                                   id="transfer_date" name="transfer_date" value="{{ old('transfer_date', $stockTransfer->transfer_date) }}" required>
                            @error('transfer_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="from_warehouse_id">From Warehouse <span class="text-danger">*</span></label>
                            <select class="form-control @error('from_warehouse_id') is-invalid @enderror" 
                                    id="from_warehouse_id" name="from_warehouse_id" required>
                                <option value="">Select From Warehouse</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" {{ old('from_warehouse_id', $stockTransfer->from_warehouse_id) == $warehouse->id ? 'selected' : '' }}>
                                        {{ $warehouse->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('from_warehouse_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="to_warehouse_id">To Warehouse <span class="text-danger">*</span></label>
                            <select class="form-control @error('to_warehouse_id') is-invalid @enderror" 
                                    id="to_warehouse_id" name="to_warehouse_id" required>
                                <option value="">Select To Warehouse</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" {{ old('to_warehouse_id', $stockTransfer->to_warehouse_id) == $warehouse->id ? 'selected' : '' }}>
                                        {{ $warehouse->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('to_warehouse_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select class="form-control @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="pending" {{ old('status', $stockTransfer->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ old('status', $stockTransfer->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status', $stockTransfer->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                              id="notes" name="notes" rows="3">{{ old('notes', $stockTransfer->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>Current Transfer Items</label>
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
                    <small class="text-muted">Note: To modify items, please create a new transfer.</small>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Transfer
                    </button>
                    <a href="{{ route('stock-transfers.show', $stockTransfer) }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
