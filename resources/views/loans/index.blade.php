@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Loan Management</h4>
                    <a href="{{ route('loans.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Loan
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Borrower</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Paid Amount</th>
                                    <th>Interest Rate</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($loans as $loan)
                                <tr>
                                    <td>{{ $loan->id }}</td>
                                    <td>{{ $loan->loanable->name }}</td>
                                    <td>{{ class_basename($loan->loanable_type) }}</td>
                                    <td>${{ number_format($loan->amount, 2) }}</td>
                                    <td>${{ number_format($loan->paid_amount, 2) }}</td>
                                    <td>{{ $loan->interest_rate }}%</td>
                                    <td>{{ $loan->due_date->format('Y-m-d') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $loan->status === 'paid' ? 'success' : ($loan->status === 'overdue' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($loan->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('loans.show', $loan) }}" class="btn btn-sm btn-info">View</a>
                                        <a href="{{ route('loans.edit', $loan) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('loans.destroy', $loan) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    {{ $loans->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
