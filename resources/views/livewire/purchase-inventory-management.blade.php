<div id="inventory-management-{{ $this->id }}">
    @if($product)
    <div class="card mt-3 border-primary">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Inventory Management for {{ $product->name }}</h5>
        </div>
        <div class="card-body">
            <!-- Add/Edit Inventory Form -->
            <form wire:submit.prevent="{{ $selectedInventoryIndex !== null ? 'updateInventory' : 'addInventory' }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">SKU</label>
                        <input type="text" wire:model="newInventory.sku" class="form-control @error('newInventory.sku') is-invalid @enderror" placeholder="SKU">
                        @error('newInventory.sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    @if($product->category_id == 1)
                    <div class="col-md-3">
                        <label class="form-label">IMEI</label>
                        <input type="text" wire:model="newInventory.imei" class="form-control @error('newInventory.imei') is-invalid @enderror" placeholder="IMEI">
                        @error('newInventory.imei') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    @else
                    <div class="col-md-3">
                        <label class="form-label">Serial Number</label>
                        <input type="text" wire:model="newInventory.serial_number" class="form-control @error('newInventory.serial_number') is-invalid @enderror" placeholder="Serial Number">
                        @error('newInventory.serial_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    @endif

                    <div class="col-md-2">
                        <label class="form-label">Quantity</label>
                        <input type="number" wire:model="newInventory.quantity" class="form-control @error('newInventory.quantity') is-invalid @enderror" min="1">
                        @error('newInventory.quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Purchase Price</label>
                        <input type="number" step="0.01" wire:model="newInventory.purchase_price" class="form-control @error('newInventory.purchase_price') is-invalid @enderror" min="0">
                        @error('newInventory.purchase_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Selling Price</label>
                        <input type="number" step="0.01" wire:model="newInventory.selling_price" class="form-control @error('newInventory.selling_price') is-invalid @enderror" min="0">
                        @error('newInventory.selling_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Filters -->
                @if($filtersData && count($filtersData) > 0)
                <div class="row mt-3 g-3">
                    @foreach($filtersData as $filter)
                    <div class="col-md-3">
                        <label class="form-label">{{ $filter->name }}</label>
                        <select wire:model="newInventory.filters.{{ $filter->id }}" class="form-select">
                            <option value="">Select {{ $filter->name }}</option>
                            @foreach($filter->options as $option)
                                <option value="{{ $option->id }}">{{ $option->value }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endforeach
                </div>
                @endif

                <div class="mt-3">
                    @if($selectedInventoryIndex !== null)
                        <button type="submit" class="btn btn-primary">
                            <span wire:loading wire:target="updateInventory" class="spinner-border spinner-border-sm"></span>
                            Update Inventory
                        </button>
                        <button type="button" wire:click="cancelEdit" class="btn btn-secondary">Cancel</button>
                    @else
                        <button type="submit" class="btn btn-success">
                            <span wire:loading wire:target="addInventory" class="spinner-border spinner-border-sm"></span>
                            Add Inventory
                        </button>
                    @endif
                </div>
            </form>

            <!-- Inventory List -->
            @if(count($inventories) > 0)
            <div class="mt-4">
                <h6>Added Inventories</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>SKU</th>
                                @if($product->category_id == 1)
                                    <th>IMEI</th>
                                @else
                                    <th>Serial No</th>
                                @endif
                                <th>Quantity</th>
                                <th>Purchase Price</th>
                                <th>Selling Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inventories as $index => $inventory)
                            <tr>
                                <td>{{ $inventory['sku'] }}</td>
                                @if($product->category_id == 1)
                                    <td>{{ $inventory['imei'] }}</td>
                                @else
                                    <td>{{ $inventory['serial_number'] }}</td>
                                @endif
                                <td>{{ $inventory['quantity'] }}</td>
                                <td>{{ number_format($inventory['purchase_price'], 2) }}</td>
                                <td>{{ number_format($inventory['selling_price'], 2) }}</td>
                                <td>
                                    <button wire:click="editInventory({{ $index }})" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button wire:click="deleteInventory({{ $index }})" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    Livewire.on('renderInventoryComponent', (containerId) => {
        const container = document.getElementById(containerId);
        const componentHtml = document.getElementById('inventory-management-{{ $this->id }}').innerHTML;
        container.innerHTML = componentHtml;

        // Reinitialize Livewire after dynamic injection
        Livewire.rescan();
    });

    // Listen for inventory updates to notify parent component
    Livewire.on('inventoryUpdated', (inventories) => {
        // This event will be handled by the parent component
        window.dispatchEvent(new CustomEvent('inventory-updated', {
            detail: { inventories: inventories }
        }));
    });
</script>
@endpush
