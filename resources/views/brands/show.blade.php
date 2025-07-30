@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Brand Details</h4>
                    <div>
                        <a href="{{ route('brands.edit', $brand) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('brands.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Name:</th>
                                    <td>{{ $brand->name }}</td>
                                </tr>
                                <tr>
                                    <th>Slug:</th>
                                    <td>{{ $brand->slug }}</td>
                                </tr>
                                <tr>
                                    <th>Description:</th>
                                    <td>{{ $brand->description ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge badge-{{ $brand->status === 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($brand->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td>{{ $brand->created_at->format('M d, Y h:i A') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated:</th>
                                    <td>{{ $brand->updated_at->format('M d, Y h:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            @if($brand->logo)
                                <div class="text-center">
                                    <img src="{{ asset('storage/' . $brand->logo) }}" 
                                         alt="{{ $brand->name }}" 
                                         class="img-fluid" 
                                         style="max-height: 200px;">
                                </div>
                            @else
                                <div class="text-center text-muted">
                                    <i class="fas fa-image fa-5x"></i>
                                    <p>No logo uploaded</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Section -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Products ({{ $brand->products->count() }})</h5>
                </div>
                <div class="card-body">
                    @if($brand->products->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Stock</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($brand->products as $product)
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->category->name ?? 'N/A' }}</td>
                                            <td>{{ $product->stock_quantity }}</td>
                                            <td>${{ number_format($product->selling_price, 2) }}</td>
                                            <td>
                                                <span class="badge badge-{{ $product->status === 'active' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($product->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted">
                            <i class="fas fa-box-open fa-3x"></i>
                            <p>No products found for this brand</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
