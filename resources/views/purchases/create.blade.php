@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create Purchase</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('purchases.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="supplier_id">Supplier</label>
                                    <select name="supplier_id" id="supplier_id" class="form-control @error('supplier_id') is-invalid @enderror" required>
                                        <option value="">Select Supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('supplier_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="purchase_date">Purchase Date</label>
                                    <input type="date" name="purchase_date" id="purchase_date" class="form-control @error('purchase_date') is-invalid @enderror" value="{{ old('purchase_date', date('Y-m-d')) }}" required>
                                    @error('purchase_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                        </div>
                        
                        <h5>Purchase Items</h5>
                        <div id="purchase-items">
                            <div class="purchase-item row mb-3">
                                <div class="col-md-4">
                                    <select name="items[0][product_id]" class="form-control" required>
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="items[0][quantity]" placeholder="Quantity" class="form-control" min="1" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="items[0][unit_price]" placeholder="Unit Price" class="form-control" step="0.01" min="0" required>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger remove-item">Remove</button>
                                </div>
                            </div>
                        </div>
                        
                        <button type="button" id="add-item" class="btn btn-secondary mb-3">Add Item</button>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Create Purchase</button>
                            <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let itemIndex = 1;

document.getElementById('add-item').addEventListener('click', function() {
    const container = document.getElementById('purchase-items');
    const newItem = document.querySelector('.purchase-item').cloneNode(true);
    
    // Update input names
    newItem.querySelectorAll('select, input').forEach(element => {
        const name = element.getAttribute('name');
        if (name) {
            element.setAttribute('name', name.replace('[0]', `[${itemIndex}]`));
            element.value = '';
        }
    });
    
    container.appendChild(newItem);
    itemIndex++;
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-item')) {
        if (document.querySelectorAll('.purchase-item').length > 1) {
            e.target.closest('.purchase-item').remove();
        }
    }
});
</script>
@endsection
