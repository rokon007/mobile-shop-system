@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Customer Report</h4>
                    <div>
                        <button onclick="window.print()" class="btn btn-secondary">
                            <i class="fas fa-print"></i> Print
                        </button>
                        <a href="{{ route('reports.export', ['type' => 'customer']) }}" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Export Excel
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
                            <button class="btn btn-primary" onclick="filterReport()">Filter</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Customer Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Total Orders</th>
                                    <th>Total Amount</th>
                                    <th>Last Order Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customers as $customer)
                                <tr>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->phone }}</td>
                                    <td>{{ $customer->sales_count }}</td>
                                    <td>৳{{ number_format($customer->total_amount, 2) }}</td>
                                    <td>{{ $customer->last_order_date ? $customer->last_order_date->format('Y-m-d') : 'N/A' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $customer->status == 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($customer->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
