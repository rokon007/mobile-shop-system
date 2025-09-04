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
                    <!-- Search Box -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" id="searchInput" class="form-control" placeholder="Search by name or phone number...">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="fas fa-search"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select id="statusFilter" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select id="dueFilter" class="form-control">
                                    <option value="">All Customers</option>
                                    <option value="has_due">Has Due</option>
                                    <option value="no_due">No Due</option>
                                </select>
                            </div>
                        </div>
                    </div>

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
                            <tbody id="customersTableBody">
                                @foreach($customers as $customer)
                                <tr class="customer-row" data-name="{{ strtolower($customer->name) }}" data-phone="{{ $customer->phone }}" data-status="{{ $customer->status }}" data-due="{{ $customer->total_due > 0 ? 'has_due' : 'no_due' }}">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const dueFilter = document.getElementById('dueFilter');
    const customerRows = document.querySelectorAll('.customer-row');

    function filterCustomers() {
        const searchText = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const dueValue = dueFilter.value;

        customerRows.forEach(row => {
            const name = row.getAttribute('data-name');
            const phone = row.getAttribute('data-phone');
            const status = row.getAttribute('data-status');
            const due = row.getAttribute('data-due');

            const nameMatch = name.includes(searchText);
            const phoneMatch = phone.includes(searchText);
            const statusMatch = statusValue === '' || status === statusValue;
            const dueMatch = dueValue === '' || due === dueValue;

            if ((nameMatch || phoneMatch) && statusMatch && dueMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterCustomers);
    statusFilter.addEventListener('change', filterCustomers);
    dueFilter.addEventListener('change', filterCustomers);

    // Initialize filters based on URL parameters if any
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('search')) {
        searchInput.value = urlParams.get('search');
    }
    if (urlParams.has('status')) {
        statusFilter.value = urlParams.get('status');
    }
    if (urlParams.has('due')) {
        dueFilter.value = urlParams.get('due');
    }

    // Apply filters on page load
    filterCustomers();
});
</script>
@endpush
