{{-- <div>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <h1 class="h2">Product Management</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button type="button" class="btn btn-primary" wire:click="create">
                <i class="fas fa-plus"></i> Add New Product
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" class="form-control" placeholder="Search products..." wire:model.live="search">
            </div>
        </div>
        <div class="col-md-3">
            <select class="form-select" wire:model.live="selectedBrand">
                <option value="">All Brands</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select" wire:model.live="selectedCategory">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Brand</th>
                            <th>Category</th>
                            <th>Model</th>
                            <th>Purchase Price</th>
                            <th>Selling Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <div class="fw-bold">{{ $product->name }}</div>
                                        @if($product->imei)
                                            <small class="text-muted">IMEI: {{ $product->imei }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $product->brand->name }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td>{{ $product->model }}</td>
                            <td>৳{{ number_format($product->purchase_price, 2) }}</td>
                            <td>৳{{ number_format($product->selling_price, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $product->stock_quantity <= $product->min_stock_alert ? 'danger' : 'success' }}">
                                    {{ $product->stock_quantity }}
                                </span>
                                @if($product->stock_quantity <= $product->min_stock_alert)
                                    <i class="fas fa-exclamation-triangle text-warning ms-1" title="Low Stock"></i>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $product->status === 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($product->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" wire:click="edit({{ $product->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                            wire:click="delete({{ $product->id }})"
                                            onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            {{ $products->links() }}
        </div>
    </div>

    <!-- Product Modal -->
    @if($showModal)
    <div class="modal fade show" style="display: block;" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editMode ? 'Edit Product' : 'Add New Product' }}</h5>
                    <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Product Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model="name">
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Brand *</label>
                                    <select class="form-select @error('brand_id') is-invalid @enderror" wire:model="brand_id">
                                        <option value="">Select Brand</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('brand_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Category *</label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" wire:model="category_id">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Model</label>
                                    <input type="text" class="form-control" wire:model="model">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Purchase Price *</label>
                                    <input type="number" step="0.01" class="form-control @error('purchase_price') is-invalid @enderror" wire:model="purchase_price">
                                    @error('purchase_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Selling Price *</label>
                                    <input type="number" step="0.01" class="form-control @error('selling_price') is-invalid @enderror" wire:model="selling_price">
                                    @error('selling_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Stock Quantity *</label>
                                    <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror" wire:model="stock_quantity">
                                    @error('stock_quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Min Stock Alert *</label>
                                    <input type="number" class="form-control @error('min_stock_alert') is-invalid @enderror" wire:model="min_stock_alert">
                                    @error('min_stock_alert') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Warranty (Months)</label>
                                    <input type="number" class="form-control" wire:model="warranty_months">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">IMEI Number</label>
                                    <input type="text" class="form-control" wire:model="imei">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Serial Number</label>
                                    <input type="text" class="form-control" wire:model="serial_number">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" rows="3" wire:model="description"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" wire:model="status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$set('showModal', false)">Cancel</button>
                        <button type="submit" class="btn btn-primary">{{ $editMode ? 'Update' : 'Save' }} Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
</div> --}}
<div>
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <div class="page-pretitle">Inventory Management</div>
                <h2 class="page-title">Product Management</h2>
            </div>
            <div class="col-auto ms-auto">
                <div class="btn-list">
                    <button wire:click="create" class="btn btn-primary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                        Add Product
                    </button>
                    <button wire:click="create" class="btn btn-primary d-sm-none btn-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card mb-3">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-md-4">
                    <div class="input-icon">
                        <input type="text" class="form-control" placeholder="Search products..." wire:model.live.debounce.300ms="search">
                        {{-- <span class="input-icon-addon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <circle cx="10" cy="10" r="7" />
                                <line x1="21" y1="21" x2="15" y2="15" />
                            </svg>
                        </span> --}}
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" wire:model.live="selectedBrand">
                        <option value="">All Brands</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" wire:model.live="selectedCategory">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" wire:model.live="statusFilter">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table Card -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Products</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th wire:click="sortBy('id')" class="cursor-pointer">
                                ID
                                @if($sortField === 'id')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <line x1="12" y1="5" x2="12" y2="19" />
                                        <line x1="18" y1="{{ $sortDirection === 'asc' ? '11' : '13' }}" x2="12" y2="{{ $sortDirection === 'asc' ? '5' : '19' }}" />
                                        <line x1="6" y1="{{ $sortDirection === 'asc' ? '11' : '13' }}" x2="12" y2="{{ $sortDirection === 'asc' ? '5' : '19' }}" />
                                    </svg>
                                @endif
                            </th>
                            <th wire:click="sortBy('name')" class="cursor-pointer">Product</th>
                            <th>Brand</th>
                            <th>Category</th>
                            <th class="text-end">Prices</th>
                            <th class="text-end">Stock</th>
                            <th>Status</th>
                            <th class="w-1">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td class="text-muted">{{ $product->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="avatar avatar-sm me-2" style="background-image: url({{ $product->image_url ?? asset('static/product-placeholder.jpg') }})"></span>
                                    <div>
                                        <div>{{ $product->name }}</div>
                                        <div class="text-muted text-small">
                                            @if($product->imei)
                                                IMEI: {{ $product->imei }}
                                            @elseif($product->model)
                                                Model: {{ $product->model }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $product->brand->name }}</span>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $product->category->name }}</span>
                            </td>
                            <td class="text-end">
                                <div class="text-success">৳{{ number_format($product->selling_price, 2) }}</div>
                                <div class="text-muted text-small">৳{{ number_format($product->purchase_price, 2) }}</div>
                            </td>
                            <td class="text-end">
                                @if($product->stock_quantity <= $product->min_stock_alert)
                                    <span class="badge bg-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alert-circle" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <circle cx="12" cy="12" r="9" />
                                            <line x1="12" y1="8" x2="12" y2="12" />
                                            <line x1="12" y1="16" x2="12.01" y2="16" />
                                        </svg>
                                        {{ $product->stock_quantity }}
                                    </span>
                                @else
                                    <span class="badge bg-success">{{ $product->stock_quantity }}</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusColorMap = [
                                        'active' => 'success',
                                        'inactive' => 'danger',
                                        'out_of_stock' => 'warning',
                                        'discontinued' => 'primary'
                                    ];
                                    $status = strtolower($product->status);
                                    $color = $statusColorMap[$status] ?? 'gray';
                                @endphp
                                <span class="badge bg-{{ $color }}">
                                    <span class="badge-dot bg-{{ $color }}"></span>
                                    {{ ucfirst($product->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <button wire:click="edit({{ $product->id }})" class="btn btn-sm btn-icon" aria-label="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3" />
                                            <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3" />
                                            <line x1="16" y1="5" x2="19" y2="8" />
                                        </svg>
                                    </button>
                                    <button wire:click="delete({{ $product->id }})"
                                            onclick="return confirm('Are you sure you want to delete this product?')"
                                            class="btn btn-sm btn-icon" aria-label="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-danger" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <line x1="4" y1="7" x2="20" y2="7" />
                                            <line x1="10" y1="11" x2="10" y2="17" />
                                            <line x1="14" y1="11" x2="14" y2="17" />
                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="empty">
                                    <div class="empty-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M15 8h.01" />
                                            <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
                                            <path d="M3.5 15.5l4.5 -4.5c.928 -.893 2.072 -.893 3 0l5 5" />
                                            <path d="M14 14l1 -1c.928 -.893 2.072 -.893 3 0l2.5 2.5" />
                                        </svg>
                                    </div>
                                    <p class="empty-title">No products found</p>
                                    <p class="empty-subtitle text-muted">
                                        Try adjusting your search or filter to find what you're looking for
                                    </p>
                                    <div class="empty-action">
                                        <button wire:click="create" class="btn btn-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <line x1="12" y1="5" x2="12" y2="19" />
                                                <line x1="5" y1="12" x2="19" y2="12" />
                                            </svg>
                                            Add your first product
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer d-flex align-items-center">
            {{-- <p class="m-0 text-muted">Showing <span>{{ $products->firstItem() }}</span> to <span>{{ $products->lastItem() }}</span> of <span>{{ $products->total() }}</span> entries</p> --}}
            <div class="ms-auto">
                {{ $products->links() }}
            </div>
        </div>
    </div>

    <!-- Product Modal -->
    @if($showModal)
        <!-- Modal content remains the same as your original -->
        <!-- Just update the styling to match Skodash theme -->
         <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $editMode ? 'Edit Product' : 'Add New Product' }}</h5>
                        <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                    </div>
                    <form wire:submit.prevent="save">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Product Name *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model="name">
                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Brand *</label>
                                        <select class="form-select @error('brand_id') is-invalid @enderror" wire:model="brand_id">
                                            <option value="">Select Brand</option>
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('brand_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Category *</label>
                                        <select class="form-select @error('category_id') is-invalid @enderror" wire:model="category_id">
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Model</label>
                                        <input type="text" class="form-control" wire:model="model">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Purchase Price *</label>
                                        <input type="number" step="0.01" class="form-control @error('purchase_price') is-invalid @enderror" wire:model="purchase_price">
                                        @error('purchase_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Selling Price *</label>
                                        <input type="number" step="0.01" class="form-control @error('selling_price') is-invalid @enderror" wire:model="selling_price">
                                        @error('selling_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Stock Quantity *</label>
                                        <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror" wire:model="stock_quantity">
                                        @error('stock_quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Min Stock Alert *</label>
                                        <input type="number" class="form-control @error('min_stock_alert') is-invalid @enderror" wire:model="min_stock_alert">
                                        @error('min_stock_alert') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Warranty (Months)</label>
                                        <input type="number" class="form-control" wire:model="warranty_months">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">IMEI Number</label>
                                        <input type="text" class="form-control" wire:model="imei">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Serial Number</label>
                                        <input type="text" class="form-control" wire:model="serial_number">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" rows="3" wire:model="description"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" wire:model="status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="$set('showModal', false)">Cancel</button>
                            <button type="submit" class="btn btn-primary">{{ $editMode ? 'Update' : 'Save' }} Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
