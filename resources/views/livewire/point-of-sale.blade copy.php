<div class="container-fluid">
    <div class="row">
        <!-- Product Selection -->
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Products</h5>
                </div>
                <div class="card-body">
                    <!-- Search -->
                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="Search products by name, code, or barcode..."
                               wire:model.live="searchProduct">
                    </div>

                    <!-- Products Grid -->
                    <div class="row">
                        @forelse($products as $product)
                            <div class="col-lg-4 col-md-6 mb-3">
                                <div class="card h-100 product-card" style="cursor: pointer;" wire:click="addToCart({{ $product->id }})">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">{{ $product->name }}</h6>
                                        <p class="card-text">
                                            <small class="text-muted">{{ $product->brand->name }} - {{ $product->category->name }}</small><br>
                                            @if($product->model)
                                                <small class="text-muted">Model: {{ $product->model }}</small><br>
                                            @endif
                                            <strong class="text-primary">৳{{ number_format($product->selling_price, 2) }}</strong><br>
                                            <span class="badge bg-{{ $product->stock_quantity <= $product->min_stock_alert ? 'danger' : 'success' }}">
                                                Stock: {{ $product->stock_quantity }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                        <div class="col-12">
                            <div class="text-center py-4">
                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No products found</p>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Cart & Checkout -->
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Cart</h5>
                    @if(count($cart) > 0)
                        <button class="btn btn-sm btn-outline-danger float-end" wire:click="clearCart">
                            <i class="bi bi-trash-fill"></i> Clear
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    <!-- Customer Selection -->
                    <div class="mb-3">
                        <label class="form-label">Customer</label>
                        <select class="form-select" wire:model="selectedCustomer">
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->phone }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- New Customer Form -->
                    @if(!$selectedCustomer)
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">New Customer Details</label>
                        </div>
                        <div class="col-md-6 mb-2">
                            <input type="text" class="form-control form-control-sm"
                                   placeholder="Customer Name" wire:model="customerName">
                        </div>
                        <div class="col-md-6 mb-2">
                            <input type="text" class="form-control form-control-sm"
                                   placeholder="Phone Number" wire:model="customerPhone">
                        </div>
                        <div class="col-12">
                            <textarea class="form-control form-control-sm" rows="2"
                                      placeholder="Address (Optional)" wire:model="customerAddress"></textarea>
                        </div>
                    </div>
                    @endif

                    <!-- Cart Items -->
                    <div class="cart-items" style="max-height: 300px; overflow-y: auto;">
                        @forelse($cart as $index => $item)
                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $item['name'] }}</h6>
                                {{-- <small class="text-muted">{{ $item['model'] }} - ৳{{ number_format($item['selling_price'], 2) }}</small> --}}
                                <small class="text-muted">৳{{ number_format($item['price'], 2) }} each</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <input type="number" class="form-control form-control-sm me-2"
                                       style="width: 70px;" min="1" max="{{ $item['stock'] }}"
                                       value="{{ $item['quantity'] }}"
                                       wire:change="updateQuantity({{ $index }}, $event.target.value)">
                                <button class="btn btn-sm btn-outline-danger"
                                        wire:click="removeFromCart({{ $index }})">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <i class="fas fa-shopping-cart fa-2x text-muted mb-2"></i>
                            <p class="text-muted">Cart is empty</p>
                        </div>
                        @endforelse
                    </div>

                    @if(count($cart) > 0)
                    <!-- Discount & Tax -->
                    <hr>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label">Discount (%)</label>
                            <input type="number" class="form-control" min="0" max="100"
                                   step="0.01" wire:model.live="discount">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Tax (%)</label>
                            <input type="number" class="form-control" min="0" max="100"
                                   step="0.01" wire:model.live="tax">
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select class="form-select" wire:model="paymentMethod">
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>

                    <!-- Paid Amount -->
                    <div class="mb-3">
                        <label class="form-label">Paid Amount</label>
                        <input type="number" class="form-control" min="0" step="0.01"
                               wire:model.live="paidAmount">
                    </div>

                    <!-- Note -->
                    <div class="mb-3">
                        <label class="form-label">Note (Optional)</label>
                        <textarea class="form-control" rows="2" wire:model="note"></textarea>
                    </div>

                    <!-- Summary -->
                    {{-- <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Subtotal:</span>
                            <span>৳{{ number_format($this->subtotal, 2) }}</span>
                        </div>
                        @if($discount > 0)
                        <div class="d-flex justify-content-between mb-1 text-success">
                            <span>Discount ({{ $discount }}%):</span>
                            <span>-৳{{ number_format($this->discountAmount, 2) }}</span>
                        </div>
                        @endif
                        @if($tax > 0)
                        <div class="d-flex justify-content-between mb-1">
                            <span>Tax ({{ $tax }}%):</span>
                            <span>৳{{ number_format($this->taxAmount, 2) }}</span>
                        </div>
                        @endif
                        <div class="d-flex justify-content-between mb-2 fw-bold border-top pt-2">
                            <span>Total:</span>
                            <span>৳{{ number_format($this->total, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Paid:</span>
                            <span>৳{{ number_format($paidAmount, 2) }}</span>
                        </div>
                        @if($this->dueAmount > 0)
                        <div class="d-flex justify-content-between mb-3 text-danger">
                            <span>Due:</span>
                            <span>৳{{ number_format($this->dueAmount, 2) }}</span>
                        </div>
                        @endif
                    </div> --}}

                    <!-- Summary -->
                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Subtotal:</span>
                            <span>৳{{ number_format($this->subtotal, 2) }}</span>
                        </div>
                        @if($discount > 0)
                        <div class="d-flex justify-content-between mb-1 text-success">
                            <span>Discount ({{ $discount }}%):</span>
                            <span>-৳{{ number_format($this->discountAmount, 2) }}</span>
                        </div>
                        @endif
                        @if($tax > 0)
                        <div class="d-flex justify-content-between mb-1">
                            <span>Tax ({{ $tax }}%):</span>
                            <span>৳{{ number_format($this->taxAmount, 2) }}</span>
                        </div>
                        @endif
                        <div class="d-flex justify-content-between mb-2 fw-bold border-top pt-2">
                            <span>Total:</span>
                            <span>৳{{ number_format($this->total, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Paid:</span>
                            <span>৳{{ number_format($paidAmount, 2) }}</span>
                        </div>
                        @if($this->dueAmount > 0)
                        <div class="d-flex justify-content-between mb-3 text-danger">
                            <span>Due:</span>
                            <span>৳{{ number_format($this->dueAmount, 2) }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Process Sale Button -->
                    <button class="btn btn-primary w-100" wire:click="processSale"
                            {{ count($cart) == 0 ? 'disabled' : '' }}>
                        <i class="fas fa-check"></i> Process Sale
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif


    <style>
        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
    </style>
</div>


{{-- <div>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <h1 class="h2">Point of Sale (POS)</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button type="button" class="btn btn-danger" wire:click="resetForm">
                <i class="fas fa-refresh"></i> Reset Cart
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Products Section -->
        <div class="col-lg-8">
            <!-- Search -->
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search products by name, model, or IMEI..." wire:model.live="search">
                </div>
            </div>

            <!-- Products Grid -->
            <div class="row">
                @foreach($products as $product)
                <div class="col-lg-4 col-md-6 mb-3">
                    <div class="card h-100 product-card" style="cursor: pointer;" wire:click="addToCart({{ $product->id }})">
                        <div class="card-body">
                            <h6 class="card-title">{{ $product->name }}</h6>
                            <p class="card-text">
                                <small class="text-muted">{{ $product->brand->name }} - {{ $product->category->name }}</small><br>
                                @if($product->model)
                                    <small class="text-muted">Model: {{ $product->model }}</small><br>
                                @endif
                                <strong class="text-primary">৳{{ number_format($product->selling_price, 2) }}</strong><br>
                                <span class="badge bg-{{ $product->stock_quantity <= $product->min_stock_alert ? 'danger' : 'success' }}">
                                    Stock: {{ $product->stock_quantity }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if(count($products) === 0)
            <div class="text-center py-4">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No products found</h5>
                <p class="text-muted">Try adjusting your search criteria</p>
            </div>
            @endif
        </div>

        <!-- Cart Section -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Shopping Cart</h5>
                </div>
                <div class="card-body">
                    <!-- Customer Selection -->
                    <div class="mb-3">
                        <label class="form-label">Customer</label>
                        <select class="form-select" wire:model="selectedCustomer">
                            <option value="">Walk-in Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->phone }}</option>
                            @endforeach
                        </select>

                        @if(!$selectedCustomer)
                        <div class="row mt-2">
                            <div class="col-6">
                                <input type="text" class="form-control form-control-sm" placeholder="Customer Name" wire:model="customerName">
                            </div>
                            <div class="col-6">
                                <input type="text" class="form-control form-control-sm" placeholder="Phone Number" wire:model="customerPhone">
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Cart Items -->
                    <div class="cart-items" style="max-height: 300px; overflow-y: auto;">
                        @if(count($cart) > 0)
                            @foreach($cart as $productId => $item)
                            <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $item['name'] }}</h6>
                                    <small class="text-muted">৳{{ number_format($item['price'], 2) }} each</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="input-group input-group-sm" style="width: 100px;">
                                        <button class="btn btn-outline-secondary" type="button"
                                                wire:click="updateQuantity({{ $productId }}, {{ $item['quantity'] - 1 }})">-</button>
                                        <input type="text" class="form-control text-center" value="{{ $item['quantity'] }}" readonly>
                                        <button class="btn btn-outline-secondary" type="button"
                                                wire:click="updateQuantity({{ $productId }}, {{ $item['quantity'] + 1 }})">+</button>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger ms-2"
                                            wire:click="removeFromCart({{ $productId }})">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="text-end">
                                <strong>৳{{ number_format($item['price'] * $item['quantity'], 2) }}</strong>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-shopping-cart fa-2x text-muted mb-2"></i>
                                <p class="text-muted">Cart is empty</p>
                            </div>
                        @endif
                    </div>
                </div>

                @if(count($cart) > 0)
                <div class="card-footer">
                    <!-- Totals -->
                    <div class="mb-3">
                        <div class="row mb-2">
                            <div class="col-6">Subtotal:</div>
                            <div class="col-6 text-end">৳{{ number_format($this->subtotal, 2) }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6">
                                <label class="form-label form-label-sm">Discount:</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" wire:model.live="discountAmount">
                            </div>
                            <div class="col-6">
                                <label class="form-label form-label-sm">Tax:</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" wire:model.live="taxAmount">
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-2">
                            <div class="col-6"><strong>Total:</strong></div>
                            <div class="col-6 text-end"><strong>৳{{ number_format($this->total, 2) }}</strong></div>
                        </div>
                    </div>

                    <!-- Payment -->
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select class="form-select form-select-sm" wire:model="paymentMethod">
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="mobile_banking">Mobile Banking</option>
                            <option value="emi">EMI</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Paid Amount</label>
                        <input type="number" step="0.01" class="form-control" wire:model.live="paidAmount">
                        @if($this->dueAmount > 0)
                            <small class="text-muted">Due: ৳{{ number_format($this->dueAmount, 2) }}</small>
                        @elseif($this->dueAmount < 0)
                            <small class="text-success">Change: ৳{{ number_format(abs($this->dueAmount), 2) }}</small>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Note</label>
                        <textarea class="form-control form-control-sm" rows="2" wire:model="note"></textarea>
                    </div>

                    <button type="button" class="btn btn-success w-100" wire:click="processSale">
                        <i class="fas fa-cash-register"></i> Process Sale
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>


    <style>
        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
    </style>
</div> --}}
