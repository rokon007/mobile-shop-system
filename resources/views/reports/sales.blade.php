@extends('layouts.app')

@section('title', 'Sales Report')

@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
            <div class="widget-content widget-content-area br-4">
                <div class="d-flex justify-content-between mb-4">
                    <h4 class="mb-0">Sales Report</h4>
                    <div>
                        <button class="btn btn-success" onclick="exportReport('excel')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7,10 12,15 17,10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                            Export Excel
                        </button>
                        <button class="btn btn-danger ml-2" onclick="exportReport('pdf')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text">
                                <path d="M14,2H6A2,2,0,0,0,4,4V20a2,2,0,0,0,2,2H18a2,2,0,0,0,2-2V8Z"></path>
                                <polyline points="14,2 14,8 20,8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10,9 9,9 8,9"></polyline>
                            </svg>
                            Export PDF
                        </button>
                    </div>
                </div>

                <!-- Filter Form -->
                <form method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="start_date">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date"
                                       value="{{ request('start_date') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="end_date">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date"
                                       value="{{ request('end_date') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="customer_id">Customer</label>
                                <select class="form-control" id="customer_id" name="customer_id">
                                    <option value="">All Customers</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-group w-100">
                                <button type="submit" class="btn btn-primary w-100">Filter</button>
                            </div>
                            <div class="form-group ml-2">
                                <a href="{{ route('reports.sales') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>


                <!-- Summary Cards -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card radius-10 border-0 border-start border-tiffany border-3">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="">
                                        <p class="mb-1">Total Sales</p>
                                        <h4 class="mb-0 text-tiffany">{{ $summary['total_sales'] }}</h4>
                                    </div>
                                </div>
                                <div class="ms-auto widget-icon bg-tiffany text-white">
                                    <i class="bi bi-bag-check-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card radius-10 border-0 border-start border-tiffany border-3">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="">
                                        <p class="mb-1">Total Amount</p>
                                        <h4 class="mb-0 text-tiffany">৳{{ number_format($summary['total_amount'], 2) }}</h4>
                                    </div>
                                </div>
                                <div class="ms-auto widget-icon bg-tiffany text-white">
                                    <i class="bi bi-bag-check-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card radius-10 border-0 border-start border-tiffany border-3">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="">
                                        <p class="mb-1">Average Sale</p>
                                        <h4 class="mb-0 text-tiffany">৳{{ number_format($summary['avg_sale'], 2) }}</h4>
                                    </div>
                                </div>
                                <div class="ms-auto widget-icon bg-tiffany text-white">
                                    <i class="bi bi-bag-check-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sales Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-4">
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Items</th>
                                <th>Subtotal</th>
                                <th>Tax</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $sale)
                            <tr>
                                <td>{{ $sale->invoice_no }}</td>
                                <td>{{ $sale->sale_date->format('M d, Y') }}</td>
                                <td>{{ $sale->customer->name ?? 'Walk-in Customer' }}</td>
                                <td>{{ $sale->items->count() }}</td>
                                <td>৳{{ number_format($sale->subtotal, 2) }}</td>
                                <td>৳{{ number_format($sale->tax_amount, 2) }}</td>
                                <td>৳{{ number_format($sale->total_amount, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No sales found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($sales->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $sales->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function exportReport(format) {
    // Get all current filter parameters
    const params = new URLSearchParams(window.location.search);

    // Add export format parameter
    params.set('export', format);

    // Submit a POST request to the export route
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("reports.export") }}';

    // Add CSRF token
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);

    // Add type parameter
    const typeInput = document.createElement('input');
    typeInput.type = 'hidden';
    typeInput.name = 'type';
    typeInput.value = 'sales';
    form.appendChild(typeInput);

    // Add all current filter parameters
    params.forEach((value, key) => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = value;
        form.appendChild(input);
    });

    document.body.appendChild(form);
    form.submit();
}
</script>
@endsection
