@extends('layouts.app')

@section('content')
<div class="">
    <div class="row">
        <div class="col-12">
            <div class="card radius-10">
                <div class="card-header bg-transparent">
                    <div class="row g-3 align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Sales Management</h5>
                        </div>
                        <div class="col">
                            <div class="d-flex align-items-center justify-content-end gap-3">
                                <a href="{{ route('pos.index') }}" class="btn btn-success btn-sm">
                                    <i class="bi bi-cash-stack"></i> POS
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3 g-3">
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <input type="date" class="form-control form-control-sm" id="start_date" placeholder="Start Date">
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <input type="date" class="form-control form-control-sm" id="end_date" placeholder="End Date">
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <select class="form-select form-select-sm" id="customer_filter">
                                <option value="">All Customers</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <button type="button" class="btn btn-primary btn-sm w-100" id="filterSalesBtn">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Items</th>
                                    <th>Subtotal</th>
                                    <th>Tax</th>
                                    <th>Discount</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sales as $sale)
                                <tr>
                                    <td>{{ $sale->id }}</td>
                                    <td>{{ $sale->sale_date->format('d M Y, h:i A') }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($sale->customer && $sale->customer->photo)
                                            <div class="recent-product-img">
                                                <img src="{{ asset('storage/'.$sale->customer->photo) }}" class="rounded-circle" width="32" alt="">
                                            </div>
                                            @endif
                                            <div class="ms-2">
                                                <h6 class="mb-0 font-14">{{ $sale->customer->name ?? 'Walk-in Customer' }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $sale->items->count() }}</td>
                                    <td>৳{{ number_format($sale->subtotal, 2) }}</td>
                                    <td>৳{{ number_format($sale->tax_amount, 2) }}</td>
                                    <td>৳{{ number_format($sale->discount_amount, 2) }}</td>
                                    <td><strong>৳{{ number_format($sale->total_amount, 2) }}</strong></td>
                                    <td>
                                        <span class="badge rounded-pill bg-{{ $sale->payment_status == 'paid' ? 'success' : ($sale->payment_status == 'partial' ? 'warning' : 'danger') }} px-3 py-2">
                                            <i class="bi bi-{{ $sale->payment_status == 'paid' ? 'check-circle' : ($sale->payment_status == 'partial' ? 'clock-history' : 'x-circle') }} me-1"></i>
                                            {{ ucfirst($sale->payment_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3 fs-6">
                                            <a href="{{ route('sales.show', $sale) }}" class="text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="View">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                            <a href="{{ route('sales.invoice', $sale) }}" class="text-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Invoice" target="_blank">
                                                <i class="bi bi-file-earmark-pdf-fill"></i>
                                            </a>
                                            @if($sale->payment_status != 'paid')
                                            <a href="{{ route('sales.edit', $sale) }}" class="text-warning" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            @endif
                                            <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-danger border-0 bg-transparent" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete" onclick="return confirm('Are you sure?')">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $sales->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Filter button event listener
    document.getElementById('filterSalesBtn').addEventListener('click', filterSales);
});

function filterSales() {
    try {
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        const customerId = document.getElementById('customer_filter').value;

        // Get current URL parameters
        const url = new URL(window.location.href);
        const params = new URLSearchParams(url.search);

        // Update parameters
        if (startDate) params.set('start_date', startDate);
        else params.delete('start_date');

        if (endDate) params.set('end_date', endDate);
        else params.delete('end_date');

        if (customerId) params.set('customer_id', customerId);
        else params.delete('customer_id');

        // Remove pagination parameter
        params.delete('page');

        // Reload page with new parameters
        window.location.href = `${url.pathname}?${params.toString()}`;

    } catch (error) {
        console.error('Filter error:', error);
        const toast = new bootstrap.Toast(document.getElementById('errorToast'));
        document.getElementById('errorToastMessage').innerText = 'Error applying filters';
        toast.show();
    }
}
</script>

<!-- Toast Notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="errorToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-danger text-white">
            <strong class="me-auto">Error</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="errorToastMessage"></div>
    </div>
</div>
@endsection
