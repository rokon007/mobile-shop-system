@extends('layouts.app')

@section('title', 'Inventory Management')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h3>Inventory Overview</h3>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('inventory.damaged') }}" class="btn btn-warning">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-triangle"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
            Damaged Products
        </a>
    </div>
</div>

<div class="row layout-top-spacing">
    <!-- Summary Cards -->
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
        <div class="widget widget-one">
            <div class="widget-content">
                <div class="w-numeric-value">
                    <div class="w-content">
                        <span class="w-value">{{ $summary['total_products'] }}</span>
                        <span class="w-numeric-title">Total Products</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
        <div class="widget widget-two">
            <div class="widget-content">
                <div class="w-numeric-value">
                    <div class="w-content">
                        <span class="w-value">{{ $summary['low_stock_products'] }}</span>
                        <span class="w-numeric-title">Low Stock Alert</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
        <div class="widget widget-three">
            <div class="widget-content">
                <div class="w-numeric-value">
                    <div class="w-content">
                        <span class="w-value">{{ $summary['out_of_stock'] }}</span>
                        <span class="w-numeric-title">Out of Stock</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
        <div class="widget widget-four">
            <div class="widget-content">
                <div class="w-numeric-value">
                    <div class="w-content">
                        <span class="w-value">৳{{ number_format($summary['total_value'], 2) }}</span>
                        <span class="w-numeric-title">Total Inventory Value</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row layout-top-spacing">
    <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
        <div class="widget-content widget-content-area br-8">
            <!-- Filter Form -->
            <form method="GET" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="search" placeholder="Search products..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" name="category_id">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" name="stock_status">
                            <option value="">All Stock Status</option>
                            <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                            <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                            <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Current Stock</th>
                            <th>Reorder Level</th>
                            <th>Unit Price</th>
                            <th>Total Value</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                                             class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                    @endif
                                    <div>
                                        <strong>{{ $product->name }}</strong>
                                        <br><small class="text-muted">{{ $product->brand->name ?? 'No Brand' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->category->name ?? 'Uncategorized' }}</td>
                            <td>
                                <span class="badge badge-{{ $product->quantity > $product->reorder_level ? 'success' : ($product->quantity > 0 ? 'warning' : 'danger') }}">
                                    {{ $product->quantity }}
                                </span>
                            </td>
                            <td>{{ $product->reorder_level }}</td>
                            <td>৳{{ number_format($product->selling_price, 2) }}</td>
                            <td>৳{{ number_format($product->quantity * $product->selling_price, 2) }}</td>
                            <td>
                                @if($product->quantity <= 0)
                                    <span class="badge badge-danger">Out of Stock</span>
                                @elseif($product->quantity <= $product->reorder_level)
                                    <span class="badge badge-warning">Low Stock</span>
                                @else
                                    <span class="badge badge-success">In Stock</span>
                                @endif
                            </td>
                            <td>{{ $product->updated_at->format('M d, Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">No products found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($products->hasPages())
            <div class="d-flex justify-content-center">
                {{ $products->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Stock Adjustment Modal -->
<div class="modal fade" id="stockAdjustmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adjust Stock</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="stockAdjustmentForm">
                <div class="modal-body">
                    <input type="hidden" id="product_id" name="product_id">
                    <div class="form-group">
                        <label>Current Stock</label>
                        <input type="number" class="form-control" id="current_stock" readonly>
                    </div>
                    <div class="form-group">
                        <label>Adjustment Type</label>
                        <select class="form-control" id="adjustment_type" name="adjustment_type" required>
                            <option value="add">Add Stock</option>
                            <option value="subtract">Subtract Stock</option>
                            <option value="set">Set Stock</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" required min="0">
                    </div>
                    <div class="form-group">
                        <label>Reason</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Adjust Stock</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function adjustStock(productId) {
    // Implementation for stock adjustment
    $('#stockAdjustmentModal').modal('show');
}

function markDamaged(productId) {
    // Implementation for marking product as damaged
    if(confirm('Mark this product as damaged?')) {
        // AJAX call to mark as damaged
    }
}

function filterInventory() {
    // Implementation for filtering inventory
}
</script>
@endsection
