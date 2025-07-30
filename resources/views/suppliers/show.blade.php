@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Supplier Details</h4>
                    <div class="float-end">
                        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Name:</th>
                                    <td>{{ $supplier->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $supplier->email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{ $supplier->phone }}</td>
                                </tr>
                                <tr>
                                    <th>Company:</th>
                                    <td>{{ $supplier->company ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>City:</th>
                                    <td>{{ $supplier->city ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Country:</th>
                                    <td>{{ $supplier->country ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Address:</strong>
                                <p class="mt-2">{{ $supplier->address ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>Notes:</strong>
                                <p class="mt-2">{{ $supplier->notes ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>Created:</strong>
                                <p class="mt-2">{{ $supplier->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>Last Updated:</strong>
                                <p class="mt-2">{{ $supplier->updated_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Purchase History -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Purchase History</h5>
                </div>
                <div class="card-body">
                    @if($supplier->purchases && $supplier->purchases->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Purchase #</th>
                                        <th>Date</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($supplier->purchases->take(10) as $purchase)
                                    <tr>
                                        <td>{{ $purchase->purchase_number }}</td>
                                        <td>{{ $purchase->purchase_date->format('M d, Y') }}</td>
                                        <td>${{ number_format($purchase->total_amount, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $purchase->status == 'completed' ? 'success' : 'warning' }}">
                                                {{ ucfirst($purchase->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('purchases.show', $purchase) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No purchase history found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
