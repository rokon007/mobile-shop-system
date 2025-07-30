@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Purchase Details #{{ $purchase->id }}</h4>
                    <div>
                        <a href="{{ route('purchases.edit', $purchase) }}" class="btn btn-warning">Edit</a>
                        <a href="{{ route('purchases.invoice', $purchase) }}" class="btn btn-secondary">Invoice</a>
                        <a href="{{ route('purchases.index') }}" class="btn btn-primary">Back to List</a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Purchase Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Supplier:</strong></td>
                                    <td>{{ $purchase->supplier->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Date:</strong></td>
                                    <td>{{ $purchase->purchase_date->format('Y-m-d') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $purchase->status === 'completed' ? 'success' : ($purchase->status === 'pending' ? 'warning' : 'info') }}">
                                            {{ ucfirst($purchase->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Total Amount:</strong></td>
                                    <td>${{ number_format($purchase->total_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Paid Amount:</strong></td>
                                    <td>${{ number_format($purchase->paid_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Due Amount:</strong></td>
                                    <td>${{ number_format($purchase->total_amount - $purchase->paid_amount, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>Payment</h5>
                            @if($purchase->total_amount > $purchase->paid_amount)
                                <form action="{{ route('purchases.payment', $purchase) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="payment_amount">Payment Amount</label>
                                        <input type="number" name="payment_amount" id="payment_amount" class="form-control" step="0.01" max="{{ $purchase->total_amount - $purchase->paid_amount }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="payment_method">Payment Method</label>
                                        <select name="payment_method" id="payment_method" class="form-control" required>
                                            <option value="cash">Cash</option>
                                            <option value="bank">Bank Transfer</option>
                                            <option value="check">Check</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-success">Record Payment</button>
                                </form>
                            @else
                                <p class="text-success">This purchase is fully paid.</p>
                            @endif
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h5>Purchase Items</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchase->items as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>${{ number_format($item->unit_price, 2) }}</td>
                                    <td>${{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3">Total</th>
                                    <th>${{ number_format($purchase->total_amount, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    @if($purchase->notes)
                        <div class="mt-3">
                            <h5>Notes</h5>
                            <p>{{ $purchase->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
