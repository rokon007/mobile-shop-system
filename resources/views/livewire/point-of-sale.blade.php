<div class="page-body">
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>Point of Sale (POS)</h3>
                </div>
                <div class="col-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> <i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item">Sales</li>
                        <li class="breadcrumb-item active">POS</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <!-- Products Section -->
            <div class="col-xl-6 col-lg-6">
                <div class="card shadow-none bg-light border">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <span class="input-group-text bg-primary"><i class="fa fa-search text-white"></i></span>
                                    <input type="text" class="form-control" placeholder="Search products by name, model, or SKU..." wire:model.live="search">
                                    <button class="btn btn-primary" type="button">Search</button>
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <button type="button" class="btn btn-danger btn-sm" wire:click="resetForm">
                                    <i class="fa fa-refresh me-2"></i> Reset Cart
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body shadow-none bg-light border">
                        <div class="row">
                            @foreach($products as $product)
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 mb-3">
                                <div class="card h-100 product-card" wire:click="addToCart({{ $product->id }})">
                                    <div class="product-ribbon ribbon ribbon-primary" style="{{ $product->stock_quantity <= $product->min_stock_alert ? 'display: block;' : 'display: none;' }}">
                                        <span>Low Stock</span>
                                    </div>
                                    <div class="card-body text-center">
                                        <h6 class="mt-2 mb-1">{{ $product->name }}</h6>
                                        <p class="mb-1">
                                            <small class="text-muted">{{ $product->brand->name ?? 'N/A' }} - {{ $product->category->name ?? 'N/A' }}</small>
                                        </p>
                                        <div class="mb-1">
                                            <strong class="text-primary">৳{{ number_format($product->selling_price, 2) }}</strong>
                                        </div>
                                        <div class="stock-info">
                                            <span class="badge bg-{{ $product->stock_quantity <= $product->min_stock_alert ? 'danger' : 'success' }}">
                                                Stock: {{ $product->stock_quantity }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        @if(count($products) === 0)
                        <div class="text-center py-4">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <h5 class="mt-3 text-muted">No products found</h5>
                            <p class="text-muted">Try adjusting your search criteria</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Cart Section - Made wider -->
            <div class="col-xl-6 col-lg-6">
                <div class="card cart-card">
                    <div class="card-header bg-primary">
                        <h5 class="text-white mb-0">Shopping Cart</h5>
                    </div>
                    <div class="card-body">
                        <!-- Customer Selection -->
                        <div class="mb-3">
                            <label class="form-label">Customer</label>
                             <div wire:ignore>
                                <select class="single-select" wire:model.live="selectedCustomer" id="customerSelect">
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->phone }}</option>
                                    @endforeach
                                </select>
                            </div>


                            @if(!$selectedCustomer)
                            <div class="mt-2">
                                <small class="text-muted">Or add new customer:</small>
                                <div class="row g-2 mt-2">
                                    <div class="col-12">
                                        <input type="text" class="form-control form-control-sm" placeholder="Customer Name" wire:model="customerName">
                                    </div>
                                    <div class="col-6">
                                        <input type="text" class="form-control form-control-sm" placeholder="Phone" wire:model="customerPhone">
                                    </div>
                                    <div class="col-6">
                                        <input type="text" class="form-control form-control-sm" placeholder="Address" wire:model="customerAddress">
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Cart Items - Removed scroll -->
                        <div class="cart-items mb-3">
                            @if(count($cart) > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <tbody>
                                            @foreach($cart as $index => $item)
                                            <tr class="cart-item">
                                                <td style="width: 40%">
                                                    <h6 class="mb-1 mt-2">{{ $item['name'] }}</h6>
                                                    <small class="text-muted">৳{{ number_format($item['price'], 2) }} each</small>
                                                </td>
                                                <td class="text-center" style="width: 30%">
                                                    <div class="input-group input-group-sm mt-2">
                                                        <button class="btn btn-outline-secondary" type="button"
                                                                wire:click="updateQuantity({{ $index }}, {{ $item['quantity'] - 1 }})">-</button>
                                                        <input type="text" class="form-control text-center" value="{{ $item['quantity'] }}" readonly>
                                                        <button class="btn btn-outline-secondary" type="button"
                                                                wire:click="updateQuantity({{ $index }}, {{ $item['quantity'] + 1 }})">+</button>
                                                    </div>
                                                    <!-- Added IMEI/Serial button here -->
                                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2 mb-2 w-100"
                                                            wire:click="openImeiModal({{ $index }})">
                                                        <i class="fa fa-barcode me-1"></i> IMEI/Serial
                                                    </button>
                                                </td>
                                                <td class="text-end" style="width: 20%">
                                                    <div class="mt-2">
                                                        <strong>৳{{ number_format($item['price'] * $item['quantity'], 2) }}</strong>
                                                    </div>
                                                </td>
                                                <td class="text-end" style="width: 10%">
                                                    <button type="button" class="btn btn-xs btn-outline-danger mt-2"
                                                            wire:click="removeFromCart({{ $index }})">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @if(!empty($item['imei_numbers']) && array_filter($item['imei_numbers']))
                                            <tr>
                                                <td colspan="4" class="p-0">
                                                    <div class="alert alert-success py-1 px-2 mb-0">
                                                        <small><i class="fa fa-check-circle"></i> IMEI/Serial Added</small>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mt-2">Your cart is empty</p>
                                </div>
                            @endif
                        </div>

                        @if(count($cart) > 0)
                        <div class="cart-summary">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>৳{{ number_format($this->subtotal, 2) }}</span>
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <label class="form-label">Discount:</label>
                                    <div class="input-group input-group-sm">
                                        <input type="number" step="0.01" class="form-control" wire:model.live="discountAmount">
                                        <span class="input-group-text">৳</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Tax:</label>
                                    <div class="input-group input-group-sm">
                                        <input type="number" step="0.01" class="form-control" wire:model.live="taxAmount">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Total:</strong>
                                <strong>৳{{ number_format($this->total, 2) }}</strong>
                            </div>

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
                                <div class="input-group">
                                    <span class="input-group-text">৳</span>
                                    <input type="number" step="0.01" class="form-control" wire:model.live="paidAmount">
                                </div>
                                @if($this->dueAmount > 0)
                                    <small class="text-danger">Due: ৳{{ number_format($this->dueAmount, 2) }}</small>
                                @elseif($this->dueAmount < 0)
                                    <small class="text-success">Change: ৳{{ number_format(abs($this->dueAmount), 2) }}</small>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Note</label>
                                <textarea class="form-control" rows="2" wire:model="note" placeholder="Any special instructions..."></textarea>
                            </div>

                            <button type="button" class="btn btn-success w-100" wire:click="processSale">
                                <i class="fa fa-check-circle me-2"></i> Complete Sale
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- IMEI/Serial Modal -->
    @if($showImeiModal)
    <div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Add IMEI/Serial Numbers</h5>
                    <button type="button" class="btn-close" wire:click="closeImeiModal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($currentCartIndex !== null && isset($cart[$currentCartIndex]))
                    <div class="mb-4">
                        <h6>Product: {{ $cart[$currentCartIndex]['name'] }}</h6>
                        <p>Quantity: {{ $cart[$currentCartIndex]['quantity'] }}</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">IMEI Numbers</h6>
                                </div>
                                <div class="card-body">
                                    @for($i = 0; $i < $cart[$currentCartIndex]['quantity']; $i++)
                                    <div class="mb-2">
                                        <label class="form-label">Item {{ $i + 1 }} IMEI:</label>
                                        <input type="text" class="form-control form-control-sm" wire:model="imeiNumbers.{{ $i }}" placeholder="Enter IMEI">
                                    </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Serial Numbers</h6>
                                </div>
                                <div class="card-body">
                                    @for($i = 0; $i < $cart[$currentCartIndex]['quantity']; $i++)
                                    <div class="mb-2">
                                        <label class="form-label">Item {{ $i + 1 }} Serial:</label>
                                        <input type="text" class="form-control form-control-sm" wire:model="serialNumbers.{{ $i }}" placeholder="Enter Serial">
                                    </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeImeiModal">Cancel</button>
                    <button type="button" class="btn btn-primary" wire:click="saveImeiSerial">Save Details</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif


    <style>
        .product-card {
            transition: all 0.3s ease;
            cursor: pointer;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #f1f1f1;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-color: var(--primary-color);
        }

        .cart-card {
            border: 1px solid #f1f1f1;
            border-radius: 8px;
        }

        .cart-summary {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
        }
        .cart-item {
            background-color: #fff;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ced4da;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .cart-item:hover {
            background-color: #f8f9fa;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-color: #7367f0;
        }

        .cart-item td {
            /* padding: 12px 15px; */
            vertical-align: middle;
        }

        .cart-item .input-group-sm {
            width: 120px;
            margin: 0 auto;
        }

        .cart-item .btn-xs {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        /* For the success alert showing IMEI/Serial added */
        .cart-item + tr[class*="alert"] td {
            padding: 0;
        }

        .cart-item + tr[class*="alert"] .alert {
            margin: 0 10px 10px;
            border-radius: 0 0 8px 8px;
        }

        /* .cart-item:hover {
            background-color: rgba(0, 123, 255, 0.05);
        } */

        .product-ribbon {
            position: absolute;
            top: 10px;
            right: -30px;
            width: 100px;
            transform: rotate(45deg);
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            color: white;
            padding: 2px 0;
        }

         /* Add Select2 styling to match Skodash theme */
        .select2-container--default .select2-selection--single {
            height: 38px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
            padding-left: 12px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #7367f0;
            color: white;
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #f8f8f8;
            color: #333;
        }

        .select2-container--default .select2-results__option[aria-selected=true]:hover {
            background-color: #7367f0;
            color: white;
        }

    </style>
    @push('scripts')

        <script>
              document.addEventListener('livewire:initialized', () => {
                function initSelect2() {
                    $('#customerSelect').select2({
                        theme: 'bootstrap4',
                        width: '100%',
                        placeholder: "Select Customer",
                        allowClear: true,
                        dropdownParent: $('.cart-card') // Ensure proper dropdown positioning
                    }).on('change', function() {
                        @this.set('selectedCustomer', $(this).val(), true);
                    });
                }

                // Initialize on load
                initSelect2();

                // Reinitialize when Livewire updates
                Livewire.on('update-customer-select', (value) => {
                    $('#customerSelect').val(value).trigger('change');

                    // Destroy and recreate Select2 to prevent styling issues
                    $('#customerSelect').select2('destroy');
                    initSelect2();
                });
            });
        </script>
    @endpush
</div>
