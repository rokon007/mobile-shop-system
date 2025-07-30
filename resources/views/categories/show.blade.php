@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Category Details</h4>
                    <div class="float-end">
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Name:</th>
                                    <td>{{ $category->name }}</td>
                                </tr>
                                <tr>
                                    <th>Slug:</th>
                                    <td>{{ $category->slug }}</td>
                                </tr>
                                <tr>
                                    <th>Parent Category:</th>
                                    <td>{{ $category->parent ? $category->parent->name : 'None' }}</td>
                                </tr>
                                <tr>
                                    <th>Sort Order:</th>
                                    <td>{{ $category->sort_order }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge bg-{{ $category->is_active ? 'success' : 'danger' }}">
                                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Products Count:</th>
                                    <td>
                                        <span class="badge bg-info">{{ $category->products->count() }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            @if($category->image)
                                <div class="mb-3">
                                    <strong>Category Image:</strong>
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" 
                                             class="img-fluid rounded" style="max-width: 200px;">
                                    </div>
                                </div>
                            @endif
                            
                            <div class="mb-3">
                                <strong>Description:</strong>
                                <p class="mt-2">{{ $category->description ?? 'No description available' }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Created:</strong>
                                <p class="mt-2">{{ $category->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Last Updated:</strong>
                                <p class="mt-2">{{ $category->updated_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Subcategories -->
    @if($category->children && $category->children->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Subcategories</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Products Count</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($category->children as $child)
                                <tr>
                                    <td>{{ $child->name }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $child->products->count() }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $child->is_active ? 'success' : 'danger' }}">
                                            {{ $child->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('categories.show', $child) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('categories.edit', $child) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
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
    @endif
    
    <!-- Products in this category -->
    @if($category->products && $category->products->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Products in this Category</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>SKU</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($category->products->take(10) as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->sku }}</td>
                                    <td>${{ number_format($product->selling_price, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $product->stock_quantity > 0 ? 'success' : 'danger' }}">
                                            {{ $product->stock_quantity }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $product->is_active ? 'success' : 'danger' }}">
                                            {{ $product->is_active ? 'Active' : 'Inactive' }}
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
                    @if($category->products->count() > 10)
                        <div class="text-center mt-3">
                            <a href="{{ route('products.index', ['category' => $category->id]) }}" class="btn btn-primary">
                                View All Products ({{ $category->products->count() }})
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
