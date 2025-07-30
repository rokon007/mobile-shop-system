@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Loan Details #{{ $loan->id }}</h4>
                    <div>
                        <a href="{{ route('loans.edit', $loan) }}" class="btn btn-warning">Edit</a>
                        <a href="{{ route('loans.index') }}" class="btn btn-primary">Back to List</a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Loan Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Borrower:</strong></td>
                                    <td>{{ $loan->loanable->name }} ({{ class_basename($loan->loanable_type) }})</td>
                                </tr>
                                <tr>
                                    <td><strong>Loan Amount:</strong></td>
                                    <td>${{ number_format($loan->amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Interest Rate:</strong></td>
                                    <td>{{ $loan->interest_rate }}%</td>
                                </tr>
                                <tr>
                                    <td><strong>Loan Date:</strong></td>
                                    <td>{{ $loan->loan_date->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Due Date:</strong></td>
                                    <td>{{ $loan->due_date->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Paid Amount:</strong></td>
                                    <td>${{ number_format($loan->paid_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Remaining Amount:</strong></td>
                                    <td>${{ number_format($loan->amount - $loan->paid_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $loan->status === 'paid' ? 'success' : ($loan->status === 'overdue' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($loan->status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>Record Payment</h5>
                            @if($loan->amount > $loan->paid_amount)
                                <form action="{{ route('loans.payment', $loan) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="amount">Payment Amount</label>
                                        <input type="number" name="amount" id="amount" class="form-control" step="0.01" max="{{ $loan->amount - $loan->paid_amount }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="payment_date">Payment Date</label>
                                        <input type="date" name="payment_date" id="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="notes">Notes</label>
                                        <textarea name="notes" id="notes" class="form-control" rows="2"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success">Record Payment</button>
                                </form>
                            @else
                                <p class="text-success">This loan is fully paid.</p>
                            @endif
                        </div>
                    </div>
                    
                    @if($loan->notes)
                        <div class="mt-3">
                            <h5>Notes</h5>
                            <p>{{ $loan->notes }}</p>
                        </div>
                    @endif
                    
                    @if($loan->payments->count() > 0)
                        <hr>
                        <h5>Payment History</h5>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($loan->payments as $payment)
                                    <tr>
                                        <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                        <td>${{ number_format($payment->amount, 2) }}</td>
                                        <td>{{ $payment->notes }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
