@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Edit Sale - #{{ $sale->id }}</h4>
                    <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('sales.update', $sale) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer_id">Customer</label>
                                    <select class="form-control" id="customer_id" name="customer_id">
                                        <option value="">Walk-in Customer</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ $sale->customer_id == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }} - {{ $customer->phone }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sale_date">Sale Date</label>
                                    <input type="datetime-local" class="form-control" id="sale_date" name="sale_date"
                                           value="{{ $sale->sale_date->format('Y-m-d\TH:i') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="payment_method">Payment Method</label>
                                    <select class="form-control" id="payment_method" name="payment_method" required>
                                        <option value="cash" {{ $sale->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="card" {{ $sale->payment_method == 'card' ? 'selected' : '' }}>Card</option>
                                        <option value="mobile_banking" {{ $sale->payment_method == 'mobile_banking' ? 'selected' : '' }}>Mobile Banking</option>
                                        <option value="bank_transfer" {{ $sale->payment_method == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="pending" {{ $sale->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="completed" {{ $sale->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ $sale->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="payment_status">Payment Status</label>
                                    <select class="form-control" id="payment_status" name="payment_status" required>
                                        <option value="pending" {{ $sale->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="partial" {{ $sale->payment_status == 'partial' ? 'selected' : '' }}>Partial</option>
                                        <option value="paid" {{ $sale->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <h5>Sale Items</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="sale-items-table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sale->items as $index => $item)
                                    <tr>
                                        <td>
                                            <select class="form-control product-select" name="items[{{ $index }}][product_id]" required>
                                                @foreach($inventories as $inventory)
                                                    <option value="{{ $inventory->id }}"
                                                            data-price="{{ $inventory->selling_price }}"
                                                            {{ $item->product_id == $inventory->id ? 'selected' : '' }}>
                                                        {{ $inventory->product->name }} @if($inventory->imei) ({{ $inventory->imei }}) @else ({{ $inventory->serial_number }}) @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control quantity-input"
                                                   name="items[{{ $index }}][quantity]"
                                                   value="{{ $item->quantity }}" min="1" required>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control price-input"
                                                   name="items[{{ $index }}][unit_price]"
                                                   value="{{ $item->unit_price }}" step="0.01" required>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-input"
                                                   value="{{ $item->total_price }}" readonly>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm remove-item">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <button type="button" class="btn btn-success" id="add-item">
                            <i class="fas fa-plus"></i> Add Item
                        </button>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3">{{ $sale->notes }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <td><strong>Subtotal:</strong></td>
                                        <td class="text-right" id="subtotal">৳{{ number_format($sale->subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Tax Rate (%):</strong>
                                            <input type="number" class="form-control form-control-sm d-inline"
                                                   id="tax_rate" name="tax_rate" value="{{ $sale->tax_rate }}"
                                                   step="0.01" min="0" max="100" style="width: 80px;">
                                        </td>
                                        <td class="text-right" id="tax-amount">৳{{ number_format($sale->tax_amount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Discount:</strong>
                                            <input type="number" class="form-control form-control-sm d-inline"
                                                   id="discount_amount" name="discount_amount" value="{{ $sale->discount_amount }}"
                                                   step="0.01" min="0" style="width: 100px;">
                                        </td>
                                        <td class="text-right" id="discount-display">৳{{ number_format($sale->discount_amount, 2) }}</td>
                                    </tr>
                                    <tr class="table-active">
                                        <td><strong>Total:</strong></td>
                                        <td class="text-right" id="total-amount"><strong>৳{{ number_format($sale->total_amount, 2) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Paid Amount:</strong>
                                            <input type="number" class="form-control form-control-sm d-inline"
                                                   id="paid_amount" name="paid_amount" value="{{ $sale->paid_amount }}"
                                                   step="0.01" min="0" style="width: 120px;">
                                        </td>
                                        <td class="text-right" id="paid-display">৳{{ number_format($sale->paid_amount, 2) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Sale
                            </button>
                            <a href="{{ route('sales.show', $sale) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = {{ count($sale->items) }};

    // Add new item
    document.getElementById('add-item').addEventListener('click', function() {
        const tbody = document.querySelector('#sale-items-table tbody');
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>
                <select class="form-control product-select" name="items[${itemIndex}][product_id]" required>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}">
                            {{ $product->name }} ({{ $product->sku }})
                        </option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" class="form-control quantity-input" name="items[${itemIndex}][quantity]" value="1" min="1" required>
            </td>
            <td>
                <input type="number" class="form-control price-input" name="items[${itemIndex}][unit_price]" step="0.01" required>
            </td>
            <td>
                <input type="number" class="form-control total-input" readonly>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-item">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(newRow);
        itemIndex++;
        updateCalculations();
    });

    // Remove item
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item') || e.target.parentElement.classList.contains('remove-item')) {
            e.target.closest('tr').remove();
            updateCalculations();
        }
    });

    // Update calculations when inputs change
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('quantity-input') ||
            e.target.classList.contains('price-input') ||
            e.target.id === 'tax_rate' ||
            e.target.id === 'discount_amount') {
            updateCalculations();
        }
    });

    // Update price when product changes
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select')) {
            const selectedOption = e.target.options[e.target.selectedIndex];
            const price = selectedOption.getAttribute('data-price');
            const row = e.target.closest('tr');
            row.querySelector('.price-input').value = price;
            updateCalculations();
        }
    });

    function updateCalculations() {
        let subtotal = 0;

        // Calculate item totals and subtotal
        document.querySelectorAll('#sale-items-table tbody tr').forEach(function(row) {
            const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const total = quantity * price;

            row.querySelector('.total-input').value = total.toFixed(2);
            subtotal += total;
        });

        // Update subtotal
        document.getElementById('subtotal').textContent = '৳' + subtotal.toFixed(2);

        // Calculate tax
        const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
        const taxAmount = (subtotal * taxRate) / 100;
        document.getElementById('tax-amount').textContent = '৳' + taxAmount.toFixed(2);

        // Calculate discount
        const discountAmount = parseFloat(document.getElementById('discount_amount').value) || 0;
        document.getElementById('discount-display').textContent = '৳' + discountAmount.toFixed(2);

        // Calculate total
        const totalAmount = subtotal + taxAmount - discountAmount;
        document.getElementById('total-amount').innerHTML = '<strong>৳' + totalAmount.toFixed(2) + '</strong>';

        // Update paid amount display
        const paidAmount = parseFloat(document.getElementById('paid_amount').value) || 0;
        document.getElementById('paid-display').textContent = '৳' + paidAmount.toFixed(2);
    }

    // Initial calculation
    updateCalculations();
});
</script>
