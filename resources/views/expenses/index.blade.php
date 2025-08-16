@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Expense Management</h4>
                    <a href="{{ route('expenses.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Expense
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
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expenses as $expense)
                                <tr>
                                    <td>{{ $expense->id }}</td>
                                    <td>{{ $expense->title }}</td>
                                    <td>{{ $expense->category }}</td>
                                    <td>${{ number_format($expense->amount, 2) }}</td>
                                    <td>{{ $expense->expense_date->format('Y-m-d') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $expense->status === 'approved' ? 'success' : 'warning' }}">
                                            {{ ucfirst($expense->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('expenses.show', $expense) }}" class="btn btn-sm btn-info">View</a>
                                        <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-sm btn-warning">Edit</a>
                                        @if($expense->status === 'pending')
                                            <form action="{{ route('expenses.approve', $expense) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                            </form>
                                        @endif
                                        <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="d-inline">
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

                    {{ $expenses->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
