@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Warehouses</h4>
                    <a href="{{ route('warehouses.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Warehouse
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Address</th>
                                    <th>Manager</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($warehouses as $warehouse)
                                <tr>
                                    <td>{{ $warehouse->id }}</td>
                                    <td>{{ $warehouse->name }}</td>
                                    <td>{{ $warehouse->code }}</td>
                                    <td>{{ $warehouse->address }}</td>
                                    <td>{{ $warehouse->manager_name }}</td>
                                    <td>{{ $warehouse->phone }}</td>
                                    <td>
                                        <span class="badge badge-{{ $warehouse->status == 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($warehouse->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('warehouses.show', $warehouse) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('warehouses.edit', $warehouse) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('warehouses.destroy', $warehouse) }}" method="POST" class="d-inline">
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
                    {{ $warehouses->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
