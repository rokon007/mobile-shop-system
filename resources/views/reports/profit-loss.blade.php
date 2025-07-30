@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Profit & Loss Report</h4>
                    <div>
                        <button onclick="window.print()" class="btn btn-secondary">
                            <i class="fas fa-print"></i> Print
                        </button>
                        <a href="{{ route('reports.export', ['type' => 'profit-loss']) }}" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <input type="date" class="form-control" id="start_date" value="{{ $startDate }}">
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" id="end_date" value="{{ $endDate }}">
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary" onclick="filterReport()">Filter</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5>Total Revenue</h5>
                                    <h3>৳{{ number_format($totalRevenue, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5>Total Expenses</h5>
                                    <h3>৳{{ number_format($totalExpenses, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5>Cost of Goods Sold</h5>
                                    <h3>৳{{ number_format($costOfGoodsSold, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-{{ $netProfit >= 0 ? 'success' : 'danger' }} text-white">
                                <div class="card-body">
                                    <h5>Net Profit/Loss</h5>
                                    <h3>৳{{ number_format($netProfit, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h5>Monthly Breakdown</h5>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Revenue</th>
                                        <th>Expenses</th>
                                        <th>COGS</th>
                                        <th>Net Profit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($monthlyData as $data)
                                    <tr>
                                        <td>{{ $data['month'] }}</td>
                                        <td>৳{{ number_format($data['revenue'], 2) }}</td>
                                        <td>৳{{ number_format($data['expenses'], 2) }}</td>
                                        <td>৳{{ number_format($data['cogs'], 2) }}</td>
                                        <td class="text-{{ $data['profit'] >= 0 ? 'success' : 'danger' }}">
                                            ৳{{ number_format($data['profit'], 2) }}
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
</div>
@endsection
