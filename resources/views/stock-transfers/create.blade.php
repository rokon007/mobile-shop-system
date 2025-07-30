@extends('layouts.app')

@section('title', 'Create Stock Transfer')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Create Stock Transfer</h4>
                    <a href="{{ route('stock-transfers.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('stock-transfers.store') }}" method="POST" id="stockTransferForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="from_warehouse_id">From Warehouse <span class="text-danger">*</span></label>
                                    <select name="from_warehouse_id" id="from_warehouse_id" class="form-control @error('from_warehouse_id') is-invalid @enderror" required>
                                        <option value="">Select Warehouse</option>
                                        @foreach($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}" {{ old('from_warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                                {{ $warehouse->name }} ({{ $warehouse->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('from_warehouse_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="to_warehouse_id">To Warehouse <span class="text-danger">*</span></label>
                                    <select name="to_warehouse_id" id="to_warehouse_id" class="form-control @error('to_warehouse_id') is-invalid @enderror" required>
                                        <option value="">Select Warehouse</option>
                                        @foreach($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}" {{ old('to_warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                                {{ $warehouse->name }} ({{ $warehouse->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('to_warehouse_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="transfer_date">Transfer Date <span class="text-danger">*</span></label>
                                    <input type="date" name="transfer_date" id="transfer_date" class="form-control @error('transfer_date') is-invalid @enderror" value="{{ old('transfer_date', date('Y-m-d')) }}" required>
                                    @error('transfer_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="note">Note</label>
                                    <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror" rows="3">{{ old('note') }}</textarea>
                                    @error('note')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h5>Products</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="products-table">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Available Quantity</th>
                                                <th>Transfer Quantity</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="product-row-1">
                                                <td>
                                                    <select name="items[0][product_id]" class="form-control product-select" required>
                                                        <option value="">Select Product</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <span class="available-qty">0</span>
                                                </td>
                                                <td>
                                                    <input type="number" name="items[0][quantity]" class="form-control" min="1" value="1" required>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm remove-row" disabled>
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4">
                                                    <button type="button" class="btn btn-success btn-sm" id="add-product">
                                                        <i class="fas fa-plus"></i> Add Product
                                                    </button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Create Transfer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let rowCount = 1;
        
        // Load products when warehouse is selected
        $('#from_warehouse_id').change(function() {
            const warehouseId = $(this).val();
            if (warehouseId) {
                $.ajax({
                    url: `/api/warehouses/${warehouseId}/products`,
                    type: 'GET',
                    success: function(data) {
                        $('.product-select').empty().append('<option value="">Select Product</option>');
                        $.each(data, function(key, product) {
                            $('.product-select').append(`<option value="${product.id}" data-qty="${product.stock_quantity}">${product.name} (${product.code})</option>`);
                        });
                    }
                });
            }
        });
        
        // Update available quantity when product is selected
        $(document).on('change', '.product-select', function() {
            const selectedOption = $(this).find('option:selected');
            const availableQty = selectedOption.data('qty') || 0;
            $(this).closest('tr').find('.available-qty').text(availableQty);
        });
        
        // Add new row
        $('#add-product').click(function() {
            rowCount++;
            const newRow = `
                <tr id="product-row-${rowCount}">
                    <td>
                        <select name="items[${rowCount-1}][product_id]" class="form-control product-select" required>
                            <option value="">Select Product</option>
                            ${$('.product-select').first().html()}
                        </select>
                    </td>
                    <td>
                        <span class="available-qty">0</span>
                    </td>
                    <td>
                        <input type="number" name="items[${rowCount-1}][quantity]" class="form-control" min="1" value="1" required>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-row">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#products-table tbody').append(newRow);
        });
        
        // Remove row
        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
        });
        
        // Form validation
        $('#stockTransferForm').submit(function(e) {
            const fromWarehouse = $('#from_warehouse_id').val();
            const toWarehouse = $('#to_warehouse_id').val();
            
            if (fromWarehouse === toWarehouse && fromWarehouse !== '') {
                e.preventDefault();
                alert('Source and destination warehouses cannot be the same!');
                return false;
            }
            
            // Check if at least one product is selected
            if ($('.product-select').length === 0) {
                e.preventDefault();
                alert('Please add at least one product to transfer!');
                return false;
            }
            
            // Check for duplicate products
            const productIds = [];
            let hasDuplicates = false;
            
            $('.product-select').each(function() {
                const productId = $(this).val();
                if (productId && productIds.includes(productId)) {
                    hasDuplicates = true;
                    return false;
                }
                if (productId) {
                    productIds.push(productId);
                }
            });
            
            if (hasDuplicates) {
                e.preventDefault();
                alert('Duplicate products are not allowed!');
                return false;
            }
        });
    });
</script>
@endpush
