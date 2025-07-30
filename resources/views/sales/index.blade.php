@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Sales Management</h4>
                    <div>
                        <a href="{{ route('sales.pos') }}" class="btn btn-success">
                            <i class="fas fa-cash-register"></i> POS
                        </a>
                        <a href="{{ route('sales.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> New Sale
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <input type="date" class="form-control" id="start_date" placeholder="Start Date">
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" id="end_date" placeholder="End Date">
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" id="customer_filter">
                                <option value="">All Customers</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary" onclick="filterSales()">Filter</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Sale ID</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Items</th>
                                    <th>Subtotal</th>
                                    <th>Tax</th>
                                    <th>Discount</th>
                                    <th>Total</th>
                                    <th>Payment Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sales as $sale)
                                <tr>
                                    <td>{{ $sale->id }}</td>
                                    <td>{{ $sale->sale_date->format('Y-m-d H:i') }}</td>
                                    <td>{{ $sale->customer->name ?? 'Walk-in Customer' }}</td>
                                    <td>{{ $sale->items->count() }}</td>
                                    <td>৳{{ number_format($sale->subtotal, 2) }}</td>
                                    <td>৳{{ number_format($sale->tax_amount, 2) }}</td>
                                    <td>৳{{ number_format($sale->discount_amount, 2) }}</td>
                                    <td>৳{{ number_format($sale->total_amount, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $sale->payment_status == 'paid' ? 'success' : ($sale->payment_status == 'partial' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($sale->payment_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('sales.show', $sale) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('sales.invoice', $sale) }}" class="btn btn-secondary btn-sm" target="_blank">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        @if($sale->payment_status != 'paid')
                                        <a href="{{ route('sales.edit', $sale) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endif
                                        <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $sales->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function filterSales() {
    // Implementation for filtering sales
}
</script>
@endsection
