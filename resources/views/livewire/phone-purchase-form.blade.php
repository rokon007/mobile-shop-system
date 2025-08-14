<!-- resources/views/livewire/phone-purchase-form.blade.php -->
<div class="card shadow-sm">
    <div class="card-body">
        @if(session()->has('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form wire:submit.prevent="save" enctype="multipart/form-data" class="row g-3">
            <!-- Seller Information -->
            <div class="col-12">
                <h5 class="fw-semibold mb-0">🧍 Seller Information</h5>
                <hr class="mt-2">
            </div>

            <div class="col-md-4">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control" wire:model.blur="name">
                @error('name') <small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Father's Name</label>
                <input type="text" class="form-control" wire:model.defer="father_name">
            </div>

            <div class="col-md-4">
                <label class="form-label">Mother's Name</label>
                <input type="text" class="form-control" wire:model.defer="mother_name">
            </div>

            <div class="col-md-4">
                <label class="form-label">NID Number</label>
                <input type="text" class="form-control" wire:model.blur="nid_number">
                @error('nid_number') <small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Permanent Address</label>
                <textarea class="form-control" rows="1" wire:model.defer="permanent_address"></textarea>
            </div>

            <div class="col-md-4">
                <label class="form-label">Present Address</label>
                <textarea class="form-control" rows="1" wire:model.defer="present_address"></textarea>
            </div>

            <div class="col-md-4">
                <label class="form-label">Date of Birth</label>
                <input type="date" class="form-control" wire:model.defer="dob">
            </div>

            <div class="col-md-4">
                <label class="form-label">Mobile Number</label>
                <input type="text" class="form-control" wire:model.defer="phone">
            </div>

            <div class="col-md-4">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" wire:model.defer="email">
            </div>

            <div class="col-md-4">
                <label class="form-label">Facebook Profile Link</label>
                <input type="text" class="form-control" wire:model.defer="facebook_id">
            </div>

            <!-- Document Upload with Preview -->
            <div class="col-12">
                <h5 class="fw-semibold mb-0 mt-4">📎 Document Upload</h5>
                <hr class="mt-2">
            </div>

            <div class="col-md-4">
                <label class="form-label">Seller Photo</label>
                <input type="file" class="form-control" wire:model="photo" id="photoUpload" accept="image/*">
                @error('photo') <small class="text-danger">{{ $message }}</small>@enderror
                @if($photo)
                    <div class="mt-2">
                        <img src="{{ $photo->temporaryUrl() }}" class="img-thumbnail" style="max-height: 150px;">
                        <button type="button" class="btn btn-sm btn-danger mt-1" wire:click="removePhoto">
                            <i class="fas fa-times"></i> Remove
                        </button>
                    </div>
                @endif
            </div>

            <div class="col-md-4">
                <label class="form-label">NID Copy</label>
                <input type="file" class="form-control" wire:model="nid_photo" id="nidUpload" accept="image/*">
                @error('nid_photo') <small class="text-danger">{{ $message }}</small>@enderror
                @if($nid_photo)
                    <div class="mt-2">
                        <img src="{{ $nid_photo->temporaryUrl() }}" class="img-thumbnail" style="max-height: 150px;">
                        <button type="button" class="btn btn-sm btn-danger mt-1" wire:click="removeNidPhoto">
                            <i class="fas fa-times"></i> Remove
                        </button>
                    </div>
                @endif
            </div>

            <div class="col-md-4">
                <label class="form-label">Purchase Receipt</label>
                <input type="file" class="form-control" wire:model="purchase_receipt" id="receiptUpload" accept="image/*,.pdf">
                @error('purchase_receipt') <small class="text-danger">{{ $message }}</small>@enderror
                @if($purchase_receipt)
                    <div class="mt-2">
                        @if(str_ends_with($purchase_receipt->getClientOriginalName(), '.pdf'))
                            <div class="bg-light p-3 text-center">
                                <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                <p class="mt-2 mb-0">{{ $purchase_receipt->getClientOriginalName() }}</p>
                            </div>
                        @else
                            <img src="{{ $purchase_receipt->temporaryUrl() }}" class="img-thumbnail" style="max-height: 150px;">
                        @endif
                        <button type="button" class="btn btn-sm btn-danger mt-1" wire:click="removeReceipt">
                            <i class="fas fa-times"></i> Remove
                        </button>
                    </div>
                @endif
            </div>

            <!-- Phone Information -->
            <div class="col-12">
                <h5 class="fw-semibold mb-0 mt-4">📱 Phone Information</h5>
                <hr class="mt-2">
            </div>

            <div class="col-md-4">
                <label class="form-label">Brand</label>
                <input type="text" class="form-control" wire:model.defer="brand">
                @error('brand') <small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Model</label>
                <input type="text" class="form-control" wire:model.defer="model">
            </div>

            <div class="col-md-4">
                <label class="form-label">Manufacture Year</label>
                <input type="number" class="form-control" wire:model.defer="manufacture_year">
            </div>

            <div class="col-md-4">
                <label class="form-label">IMEI Number</label>
                <input type="text" class="form-control" wire:model.defer="imei">
                @error('imei') <small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Serial Number</label>
                <input type="text" class="form-control" wire:model.defer="serial_number">
            </div>

            <div class="col-md-4">
                <label class="form-label">RAM</label>
                <input type="text" class="form-control" wire:model.defer="ram">
            </div>

            <div class="col-md-4">
                <label class="form-label">ROM</label>
                <input type="text" class="form-control" wire:model.defer="rom">
            </div>

            <div class="col-md-4">
                <label class="form-label">Purchase Price</label>
                <input type="number" step="0.01" class="form-control" wire:model.defer="purchase_price">
            </div>

            <div class="col-md-4">
                <label class="form-label">Purchase Date</label>
                <input type="date" class="form-control" wire:model.defer="purchase_date">
                @error('purchase_date') <small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <!-- Submit Button -->
            <div class="col-12 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Information
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Enhance file input with preview functionality
    document.addEventListener('livewire:load', function() {
        // Seller photo preview
        document.getElementById('photoUpload').addEventListener('change', function(e) {
            Livewire.emit('updatePhotoPreview', e.target.files[0]);
        });

        // NID photo preview
        document.getElementById('nidUpload').addEventListener('change', function(e) {
            Livewire.emit('updateNidPreview', e.target.files[0]);
        });

        // Receipt preview
        document.getElementById('receiptUpload').addEventListener('change', function(e) {
            Livewire.emit('updateReceiptPreview', e.target.files[0]);
        });
    });
</script>
@endpush
