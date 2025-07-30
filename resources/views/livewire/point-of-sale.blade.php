<div>
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
</div>
