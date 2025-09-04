@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Payment History - {{ $customer->name }}</h4>
                    <div>
                        <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Customers
                        </a>
                        <a href="{{ route('customers.show', $customer) }}" class="btn btn-info">
                            <i class="fas fa-user"></i> Customer Details
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6>Total Orders</h6>
                                    <h3>{{ $customer->sales_count }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6>Total Spent</h6>
                                    <h3>৳{{ number_format($customer->total_spent, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6>Total Due</h6>
                                    <h3>৳{{ number_format($customer->total_due, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6>Status</h6>
                                    <h3>
                                        <span class="badge badge-{{ $customer->status == 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($customer->status) }}
                                        </span>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Invoice No</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Received By</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                <tr>
                                    <td>{{ $payment->payment_date->format('d M, Y h:i A') }}</td>
                                    <td>
                                        <a href="{{ route('sales.show', $payment->sale_id) }}">
                                            {{ $payment->sale->invoice_no }}
                                        </a>
                                    </td>
                                    <td>৳{{ number_format($payment->amount, 2) }}</td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                        </span>
                                    </td>
                                    <td>{{ $payment->receivedBy->name }}</td>
                                    <td>{{ $payment->notes }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No payment history found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $payments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
