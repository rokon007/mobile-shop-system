@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Customer Details</h4>
                    <div>
                        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('customers.index') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $customer->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $customer->email ?: 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Phone:</strong></td>
                                    <td>{{ $customer->phone }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Date of Birth:</strong></td>
                                    <td>{{ $customer->date_of_birth ? $customer->date_of_birth->format('F d, Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Gender:</strong></td>
                                    <td>{{ $customer->gender ? ucfirst($customer->gender) : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $customer->status == 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($customer->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Total Orders:</strong></td>
                                    <td>{{ $customer->sales->count() }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Spent:</strong></td>
                                    <td>৳{{ number_format($customer->sales->sum('total_amount'), 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Order:</strong></td>
                                    <td>{{ $customer->sales->first() ? $customer->sales->first()->sale_date->format('F d, Y') : 'No orders yet' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Joined:</strong></td>
                                    <td>{{ $customer->created_at->format('F d, Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($customer->address)
                    <div class="row">
                        <div class="col-12">
                            <h5>Address</h5>
                            <p>{{ $customer->address }}</p>
                        </div>
                    </div>
                    @endif

                    @if($customer->notes)
                    <div class="row">
                        <div class="col-12">
                            <h5>Notes</h5>
                            <p>{{ $customer->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Quick Stats</h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="mb-3">
                            <h3 class="text-primary">{{ $customer->sales->count() }}</h3>
                            <p class="text-muted">Total Orders</p>
                        </div>
                        <div class="mb-3">
                            <h3 class="text-success">৳{{ number_format($customer->sales->sum('total_amount'), 2) }}</h3>
                            <p class="text-muted">Total Spent</p>
                        </div>
                        <div class="mb-3">
                            <h3 class="text-info">৳{{ $customer->sales->count() > 0 ? number_format($customer->sales->avg('total_amount'), 2) : '0.00' }}</h3>
                            <p class="text-muted">Average Order</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('sales.create', ['customer_id' => $customer->id]) }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> New Sale
                        </a>
                        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit Customer
                        </a>
                        <button class="btn btn-info" onclick="sendSMS()">
                            <i class="fas fa-sms"></i> Send SMS
                        </button>
                        @if($customer->email)
                        <a href="mailto:{{ $customer->email }}" class="btn btn-secondary">
                            <i class="fas fa-envelope"></i> Send Email
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    @if($customer->sales->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Recent Orders</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Items</th>
                                    <th>Total Amount</th>
                                    <th>Payment Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customer->sales->take(10) as $sale)
                                <tr>
                                    <td>#{{ $sale->id }}</td>
                                    <td>{{ $sale->sale_date->format('M d, Y') }}</td>
                                    <td>{{ $sale->items->count() }}</td>
                                    <td>৳{{ number_format($sale->total_amount, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $sale->payment_status == 'paid' ? 'success' : ($sale->payment_status == 'partial' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($sale->payment_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('sales.invoice', $sale) }}" class="btn btn-sm btn-secondary" target="_blank">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($customer->sales->count() > 10)
                    <div class="text-center">
                        <a href="{{ route('sales.index', ['customer_id' => $customer->id]) }}" class="btn btn-primary">
                            View All Orders
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
function sendSMS() {
    const phone = '{{ $customer->phone }}';
    const message = 'Hello {{ $customer->name }}, thank you for being our valued customer!';
    
    // You can integrate with SMS API here
    alert('SMS functionality would be implemented here.\nPhone: ' + phone + '\nMessage: ' + message);
}
</script>
@endsection
