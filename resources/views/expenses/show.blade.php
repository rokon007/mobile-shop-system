@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Expense Details #{{ $expense->id }}</h4>
                    <div>
                        <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-warning">Edit</a>
                        <a href="{{ route('expenses.index') }}" class="btn btn-primary">Back to List</a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Title:</strong></td>
                                    <td>{{ $expense->title }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Category:</strong></td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $expense->category)) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Amount:</strong></td>
                                    <td>${{ number_format($expense->amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Date:</strong></td>
                                    <td>{{ $expense->expense_date->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $expense->status === 'approved' ? 'success' : 'warning' }}">
                                            {{ ucfirst($expense->status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            @if($expense->status === 'pending')
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Actions</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('expenses.approve', $expense) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success">Approve Expense</button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    @if($expense->description)
                        <div class="mt-3">
                            <h5>Description</h5>
                            <p>{{ $expense->description }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
