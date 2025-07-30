@extends('layouts.app')

@section('title', 'Sales Report')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h3>Sales Report</h3>
    </div>
    <div class="page-header-actions">
        <button class="btn btn-success" onclick="exportReport('excel')">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7,10 12,15 17,10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
            Export Excel
        </button>
        <button class="btn btn-danger" onclick="exportReport('pdf')">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14,2H6A2,2,0,0,0,4,4V20a2,2,0,0,0,2,2H18a2,2,0,0,0,2-2V8Z"></path><polyline points="14,2 14,8 20,8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10,9 9,9 8,9"></polyline></svg>
            Export PDF
        </button>
    </div>
</div>

<div class="row layout-top-spacing">
    <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
        <div class="widget-content widget-content-area br-8">
            <!-- Filter Form -->
            <form method="GET" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <label for="start_date">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" 
                               value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" 
                               value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3">
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
                    <div class="col-md-3">
                        <label>&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('reports.sales') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card widget-one">
                        <div class="widget-content">
                            <div class="w-numeric-value">
                                <div class="w-content">
                                    <span class="w-value">{{ $summary['total_sales'] }}</span>
                                    <span class="w-numeric-title">Total Sales</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card widget-two">
                        <div class="widget-content">
                            <div class="w-numeric-value">
                                <div class="w-content">
                                    <span class="w-value">৳{{ number_format($summary['total_amount'], 2) }}</span>
                                    <span class="w-numeric-title">Total Amount</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card widget-three">
                        <div class="widget-content">
                            <div class="w-numeric-value">
                                <div class="w-content">
                                    <span class="w-value">৳{{ number_format($summary['total_profit'], 2) }}</span>
                                    <span class="w-numeric-title">Total Profit</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card widget-four">
                        <div class="widget-content">
                            <div class="w-numeric-value">
                                <div class="w-content">
                                    <span class="w-value">৳{{ number_format($summary['avg_sale'], 2) }}</span>
                                    <span class="w-numeric-title">Average Sale</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sales Table -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Subtotal</th>
                            <th>Tax</th>
                            <th>Total</th>
                            <th>Profit</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                        <tr>
                            <td>{{ $sale->invoice_number }}</td>
                            <td>{{ $sale->sale_date->format('M d, Y') }}</td>
                            <td>{{ $sale->customer->name ?? 'Walk-in Customer' }}</td>
                            <td>{{ $sale->items->count() }}</td>
                            <td>৳{{ number_format($sale->subtotal, 2) }}</td>
                            <td>৳{{ number_format($sale->tax_amount, 2) }}</td>
                            <td>৳{{ number_format($sale->total_amount, 2) }}</td>
                            <td>৳{{ number_format($sale->profit, 2) }}</td>
                            <td>
                                <span class="badge badge-{{ $sale->status == 'completed' ? 'success' : ($sale->status == 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($sale->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">No sales found</td>
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

<script>
function exportReport(format) {
    const params = new URLSearchParams(window.location.search);
    params.set('export', format);
    window.location.href = '{{ route("reports.sales") }}?' + params.toString();
}
</script>
@endsection
