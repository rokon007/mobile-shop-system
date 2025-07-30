@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Stock Transfers</h4>
                    <a href="{{ route('stock-transfers.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> New Transfer
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Transfer ID</th>
                                    <th>Date</th>
                                    <th>From Warehouse</th>
                                    <th>To Warehouse</th>
                                    <th>Items</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transfers as $transfer)
                                <tr>
                                    <td>{{ $transfer->id }}</td>
                                    <td>{{ $transfer->transfer_date->format('Y-m-d') }}</td>
                                    <td>{{ $transfer->fromWarehouse->name }}</td>
                                    <td>{{ $transfer->toWarehouse->name }}</td>
                                    <td>{{ $transfer->items->count() }}</td>
                                    <td>
                                        <span class="badge badge-{{ $transfer->status == 'completed' ? 'success' : ($transfer->status == 'pending' ? 'warning' : 'info') }}">
                                            {{ ucfirst($transfer->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $transfer->user->name }}</td>
                                    <td>
                                        <a href="{{ route('stock-transfers.show', $transfer) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($transfer->status == 'pending')
                                        <a href="{{ route('stock-transfers.edit', $transfer) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('stock-transfers.receive', $transfer) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-check"></i> Receive
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $transfers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
