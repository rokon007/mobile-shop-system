@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Customer Management</h4>
                    <a href="{{ route('customers.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Customer
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    {{-- <th>Email</th> --}}
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Total Orders</th>
                                    <th>Total Spent</th>
                                    <th>Due</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customers as $customer)
                                <tr>
                                    <td>{{ $customer->id }}</td>
                                    <td>{{ $customer->name }}</td>
                                    {{-- <td>{{ $customer->email ?? 'N/A' }}</td> --}}
                                    <td>{{ $customer->phone }}</td>
                                    <td>{{ Str::limit($customer->address, 30) ?? 'N/A' }}</td>
                                    <td>{{ $customer->sales_count }}</td>
                                    <td>৳{{ number_format($customer->total_spent, 2) }}</td>
                                    <td>
                                        @if($customer->total_due > 0)
                                            <span class="badge bg-danger">৳{{ number_format($customer->total_due, 2) }}</span>
                                        @else
                                            <span class="badge bg-success">৳0.00</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $customer->status == 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($customer->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('customers.show', $customer) }}" class="btn btn-info btn-sm" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="{{ route('customers.payment-history', $customer) }}" class="btn btn-secondary btn-sm" title="Payment History">
                                                <i class="bi bi-clock"></i>
                                            </a>
                                            <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this customer?')" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
