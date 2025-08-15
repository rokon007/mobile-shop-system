<div>
    <div class="row mb-3">
        <div class="col-md-6">
            <input type="text" class="form-control" placeholder="Search by name, phone, IMEI, brand..."
                   wire:model.live="search">
        </div>
        <div class="col-md-2">
            <select class="form-control" wire:model.live="perPage">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Seller</th>
                    <th>Phone</th>
                    <th>IMEI</th>
                    <th>Purchase Date</th>
                    <th>Price</th>
                    <th>Documents</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchases as $purchase)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            {{ $purchase->seller->name ?? 'N/A' }}<br>
                            <small class="text-muted">{{ $purchase->seller->phone ?? '' }}</small>
                        </td>
                        <td>
                            {{ $purchase->phone->brand ?? 'N/A' }}<br>
                            <small class="text-muted">{{ $purchase->phone->model ?? '' }}</small>
                        </td>
                        <td>{{ $purchase->phone->imei ?? 'N/A' }}</td>
                        <td>{{ $purchase->purchase_date->format('d M Y') }}</td>
                        <td>{{ number_format($purchase->purchase_price, 2) }}</td>
                        <td>
                            @if($purchase->seller->purchase_receipt_path ?? false)
                                <a href="{{ asset('storage/'.$purchase->seller->purchase_receipt_path) }}"
                                   target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-file-invoice"></i> Receipt
                                </a>
                            @endif
                            @if($purchase->seller->nid_photo_path ?? false)
                                <a href="{{ asset('storage/'.$purchase->seller->nid_photo_path) }}"
                                   target="_blank" class="btn btn-sm btn-secondary mt-1">
                                    <i class="fas fa-id-card"></i> NID
                                </a>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <button wire:click="viewDetails({{ $purchase->id }})"
                                        class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <a href="{{ route('phone.purchase.edit', $purchase->id) }}"
                                   class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('purchase.pinvoice', $purchase->id) }}"
                                   target="_blank" class="btn btn-sm btn-success">
                                    <i class="bi bi-file-pdf"></i>
                                </a>
                                <button wire:click="confirmDelete({{ $purchase->id }})"
                                        class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">No records found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $purchases->links() }}
    </div>

    <!-- Enhanced View Details Modal -->
    @if($viewModal)
        <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-xl" role="document"> <!-- Changed to modal-xl for more space -->
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-info-circle mr-2"></i> Complete Purchase Details
                            <small class="d-block text-light mt-1">Transaction #{{ $selectedPurchase->id }}</small>
                        </h5>
                        <div>
                            <button onclick="window.print()" class="btn btn-sm btn-light mr-2">
                                <i class="fas fa-print mr-1"></i> Print
                            </button>
                            <button wire:click="closeModal" type="button" class="close text-white">
                                <span>&times;</span>
                            </button>
                        </div>
                    </div>
                    <div class="modal-body" id="printableArea">
                        <!-- Summary Card -->
                        <div class="card border-primary mb-4">
                            <div class="card-header bg-light-primary">
                                <h6 class="mb-0">
                                    <i class="fas fa-clipboard-list mr-2"></i>Transaction Summary
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>Purchase Date:</strong>
                                            <span class="badge bg-primary">
                                                {{ $selectedPurchase->purchase_date->format('d M Y') }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>Purchase Price:</strong>
                                            <span class="badge bg-success">
                                                ৳{{ number_format($selectedPurchase->purchase_price, 2) }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>IMEI:</strong>
                                            <span class="badge bg-info text-dark">
                                                {{ $selectedPurchase->phone->imei ?? 'N/A' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Seller Information Column -->
                            <div class="col-lg-6">
                                <div class="card mb-4 h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-user-tie mr-2"></i>Seller Details
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 text-center mb-3">
                                                @if($selectedPurchase->seller->photo_path)
                                                    <img src="{{ asset('storage/public/'.$selectedPurchase->seller->photo_path) }}"
                                                        class="img-thumbnail rounded-circle" style="max-height: 150px;">
                                                @else
                                                    <div class="bg-light p-4 text-muted rounded-circle d-flex align-items-center justify-content-center" style="height: 150px; width: 150px;">
                                                        <i class="fas fa-user fa-3x"></i>
                                                    </div>
                                                @endif
                                                <div class="mt-2">
                                                    <span class="badge bg-primary">
                                                        {{ $selectedPurchase->seller->nid_number }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="seller-info">
                                                    <h5 class="text-primary">{{ $selectedPurchase->seller->name }}</h5>
                                                    <hr class="my-2">

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p class="mb-2">
                                                                <i class="fas fa-mobile-alt mr-2 text-muted"></i>
                                                                <strong>Phone:</strong> {{ $selectedPurchase->seller->phone }}
                                                            </p>
                                                            <p class="mb-2">
                                                                <i class="fas fa-envelope mr-2 text-muted"></i>
                                                                <strong>Email:</strong> {{ $selectedPurchase->seller->email ?? 'N/A' }}
                                                            </p>
                                                            <p class="mb-2">
                                                                <i class="fas fa-birthday-cake mr-2 text-muted"></i>
                                                                <strong>DOB:</strong> {{ $selectedPurchase->seller->dob ? \Carbon\Carbon::parse($selectedPurchase->seller->dob)->format('d M Y') : 'N/A' }}
                                                            </p>
                                                            <p class="mb-2">
                                                                <i class="fas fa-birthday-cake mr-2 text-muted"></i>
                                                                <strong>NID No:</strong> {{ $selectedPurchase->seller->nid_number }}
                                                            </p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-2">
                                                                <i class="fab fa-facebook mr-2 text-muted"></i>
                                                                <strong>Facebook:</strong>
                                                                @if($selectedPurchase->seller->facebook_id)
                                                                    <a href="{{ $selectedPurchase->seller->facebook_id }}" target="_blank">Profile</a>
                                                                @else
                                                                    N/A
                                                                @endif
                                                            </p>
                                                            <p class="mb-2">
                                                                <i class="fas fa-user-friends mr-2 text-muted"></i>
                                                                <strong>Father:</strong> {{ $selectedPurchase->seller->father_name }}
                                                            </p>
                                                            <p class="mb-2">
                                                                <i class="fas fa-user-friends mr-2 text-muted"></i>
                                                                <strong>Mother:</strong> {{ $selectedPurchase->seller->mother_name }}
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="mt-3">
                                                        <h6><i class="fas fa-map-marker-alt mr-2 text-muted"></i>Address</h6>
                                                        <div class="address-box bg-light p-2 rounded">
                                                            <p class="mb-1"><strong>Present:</strong> {{ $selectedPurchase->seller->present_address }}</p>
                                                            <p class="mb-0"><strong>Permanent:</strong> {{ $selectedPurchase->seller->permanent_address }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Phone Information Column -->
                            <div class="col-lg-6">
                                <div class="card mb-4 h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-mobile-alt mr-2"></i>Device Specifications
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 text-center mb-3">
                                                <div class="device-icon bg-light-primary p-4 rounded">
                                                    <i class="fas fa-mobile-screen fa-4x text-primary"></i>
                                                    <div class="mt-2">
                                                        <span class="badge bg-primary">
                                                            {{ $selectedPurchase->phone->manufacture_year ?? 'N/A' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h4 class="text-primary">
                                                    {{ $selectedPurchase->phone->brand }} {{ $selectedPurchase->phone->model }}
                                                </h4>
                                                <hr class="my-2">

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="spec-item mb-3">
                                                            <h6 class="text-muted mb-1">IMEI Number</h6>
                                                            <p class="font-weight-bold">{{ $selectedPurchase->phone->imei }}</p>
                                                        </div>
                                                        <div class="spec-item mb-3">
                                                            <h6 class="text-muted mb-1">Serial Number</h6>
                                                            <p class="font-weight-bold">{{ $selectedPurchase->phone->serial_number ?? 'N/A' }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="spec-item mb-3">
                                                            <h6 class="text-muted mb-1">Memory</h6>
                                                            <p class="font-weight-bold">
                                                                {{ $selectedPurchase->phone->ram }} RAM / {{ $selectedPurchase->phone->rom }} ROM
                                                            </p>
                                                        </div>
                                                        <div class="spec-item mb-3">
                                                            <h6 class="text-muted mb-1">Manufacture Year</h6>
                                                            <p class="font-weight-bold">{{ $selectedPurchase->phone->manufacture_year ?? 'N/A' }}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mt-3">
                                                    <h6><i class="fas fa-info-circle mr-2 text-muted"></i>Additional Info</h6>
                                                    <div class="bg-light p-2 rounded">
                                                        <p class="mb-0">Device purchased on {{ $selectedPurchase->purchase_date->format('jS F Y') }} for ৳{{ number_format($selectedPurchase->purchase_price, 2) }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Documents Section -->
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-file-alt mr-2"></i>Supporting Documents
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @if($selectedPurchase->seller->nid_photo_path)
                                    <div class="col-md-4 mb-3">
                                        <div class="document-card h-100">
                                            <div class="document-preview">
                                                <a href="{{ asset('storage/public/'.$selectedPurchase->seller->nid_photo_path) }}" target="_blank">
                                                    <img src="{{ asset('storage/public/'.$selectedPurchase->seller->nid_photo_path) }}"
                                                        class="img-thumbnail w-100" style="max-height: 200px;">
                                                </a>
                                            </div>
                                            <div class="document-info p-2">
                                                <h6 class="mb-1"><i class="fas fa-id-card mr-1"></i> NID Document</h6>
                                                <small class="text-muted">Uploaded on {{ \Carbon\Carbon::parse($selectedPurchase->created_at)->format('d M Y') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    @if($selectedPurchase->seller->purchase_receipt_path)
                                    <div class="col-md-4 mb-3">
                                        <div class="document-card h-100">
                                            <div class="document-preview">
                                                <a href="{{ asset('storage/public/'.$selectedPurchase->seller->purchase_receipt_path) }}" target="_blank">
                                                    <img src="{{ asset('storage/public/'.$selectedPurchase->seller->purchase_receipt_path) }}"
                                                        class="img-thumbnail w-100" style="max-height: 200px;">
                                                </a>
                                            </div>
                                            <div class="document-info p-2">
                                                <h6 class="mb-1"><i class="fas fa-file-invoice mr-1"></i> Purchase Receipt</h6>
                                                <small class="text-muted">Uploaded on {{ \Carbon\Carbon::parse($selectedPurchase->created_at)->format('d M Y') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    @if($selectedPurchase->seller->photo_path)
                                    <div class="col-md-4 mb-3">
                                        <div class="document-card h-100">
                                            <div class="document-preview">
                                                <a href="{{ asset('storage/public/'.$selectedPurchase->seller->photo_path) }}" target="_blank">
                                                    <img src="{{ asset('storage/public/'.$selectedPurchase->seller->photo_path) }}"
                                                        class="img-thumbnail w-100" style="max-height: 200px;">
                                                </a>
                                            </div>
                                            <div class="document-info p-2">
                                                <h6 class="mb-1"><i class="fas fa-camera mr-1"></i> Seller Photo</h6>
                                                <small class="text-muted">Uploaded on {{ \Carbon\Carbon::parse($selectedPurchase->created_at)->format('d M Y') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">
                            <i class="fas fa-times mr-1"></i> Close
                        </button>
                        <a href="{{ route('purchase.invoice', $selectedPurchase->id) }}"
                        target="_blank" class="btn btn-primary">
                            <i class="fas fa-file-pdf mr-1"></i> View Invoice
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @push('styles')
        <style>
            /* Custom styles for the modal */
            .modal-xl {
                max-width: 1200px;
            }
            .bg-light-primary {
                background-color: #e3f2fd;
            }
            .seller-info h5 {
                font-weight: 600;
            }
            .address-box {
                border-left: 3px solid #0d6efd;
            }
            .device-icon {
                text-align: center;
            }
            .spec-item {
                padding: 8px;
                border-radius: 5px;
            }
            .spec-item:hover {
                background-color: #f8f9fa;
            }
            .document-card {
                border: 1px solid #dee2e6;
                border-radius: 5px;
                overflow: hidden;
                transition: all 0.3s;
            }
            .document-card:hover {
                box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
                transform: translateY(-2px);
            }
            .document-info {
                background-color: #f8f9fa;
                border-top: 1px solid #dee2e6;
            }

            /* Print styles */
            @media print {
                body * {
                    visibility: hidden;
                }
                #printableArea, #printableArea * {
                    visibility: visible;
                }
                #printableArea {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    padding: 20px;
                }
                .modal-header, .modal-footer {
                    display: none !important;
                }
                .card {
                    border: 1px solid #ddd !important;
                    page-break-inside: avoid;
                }
                .document-card {
                    page-break-inside: avoid;
                }
            }
        </style>
    @endpush

    <!-- Delete Confirmation Modal -->
    @if($deleteModal)
        <div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Confirm Deletion</h5>
                        <button wire:click="closeDeleteModal" type="button" class="close text-white">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this purchase record? This action cannot be undone.</p>
                        <p><strong>IMEI:</strong> {{ $purchaseToDelete->phone->imei ?? '' }}</p>
                    </div>
                    <div class="modal-footer">
                        <button wire:click="closeDeleteModal" type="button" class="btn btn-secondary">
                            Cancel
                        </button>
                        <button wire:click="deletePurchase" type="button" class="btn btn-danger">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>

@push('styles')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #printableArea, #printableArea * {
                visibility: visible;
            }
            #printableArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .modal-header, .modal-footer {
                display: none !important;
            }
        }
    </style>
@endpush
