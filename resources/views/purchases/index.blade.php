@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Purchase Management</h4>
                    <a href="{{ route('purchases.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Purchase
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Supplier</th>
                                    <th>Date</th>
                                    <th>Total Amount</th>
                                    <th>Paid Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchases as $purchase)
                                <tr>
                                    <td>{{ $purchase->id }}</td>
                                    <td>{{ $purchase->supplier->name }}</td>
                                    <td>{{ $purchase->purchase_date->format('Y-m-d') }}</td>
                                    <td>${{ number_format($purchase->total_amount, 2) }}</td>
                                    <td>${{ number_format($purchase->paid_amount, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $purchase->status === 'completed' ? 'success' : ($purchase->status === 'pending' ? 'warning' : 'info') }}">
                                            {{ ucfirst($purchase->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('purchases.show', $purchase) }}" class="btn btn-sm btn-info">View</a>
                                        <a href="{{ route('purchases.edit', $purchase) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="{{ route('purchases.invoice', $purchase) }}" class="btn btn-sm btn-secondary">Invoice</a>
                                        <form action="{{ route('purchases.destroy', $purchase) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    {{ $purchases->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
