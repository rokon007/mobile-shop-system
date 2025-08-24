{{-- @extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h3>System Settings</h3>
    </div>
</div>

<div class="row layout-top-spacing">
    <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
        <div class="widget-content widget-content-area br-8">
            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf


                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label for="company_name">Company Name</label>
                            <input type="text" class="form-control" id="company_name" name="company_name"
                                   value="{{ $settings['company_name'] ?? '' }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label for="company_email">Company Email</label>
                            <input type="email" class="form-control" id="company_email" name="company_email"
                                   value="{{ $settings['company_email'] ?? '' }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label for="company_phone">Company Phone</label>
                            <input type="text" class="form-control" id="company_phone" name="company_phone"
                                   value="{{ $settings['company_phone'] ?? '' }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label for="currency">Currency</label>
                            <select class="form-control" id="currency" name="currency">
                                <option value="BDT" {{ ($settings['currency'] ?? '') == 'BDT' ? 'selected' : '' }}>BDT (৳)</option>
                                <option value="USD" {{ ($settings['currency'] ?? '') == 'USD' ? 'selected' : '' }}>USD ($)</option>
                                <option value="EUR" {{ ($settings['currency'] ?? '') == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="company_address">Company Address</label>
                    <textarea class="form-control" id="company_address" name="company_address" rows="3">{{ $settings['company_address'] ?? '' }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label for="tax_rate">Tax Rate (%)</label>
                            <input type="number" class="form-control" id="tax_rate" name="tax_rate"
                                   value="{{ $settings['tax_rate'] ?? '0' }}" step="0.01" min="0" max="100">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label for="low_stock_alert">Low Stock Alert Quantity</label>
                            <input type="number" class="form-control" id="low_stock_alert" name="low_stock_alert"
                                   value="{{ $settings['low_stock_alert'] ?? '10' }}" min="1">
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="company_logo">Company Logo</label>
                    <input type="file" class="form-control" id="company_logo" name="company_logo" accept="image/*">

                    @php
                        $logoPath = \App\Models\SystemSetting::where('key', 'shop_logo')->value('value');
                    @endphp

                    @if($logoPath && Storage::disk('public')->exists($logoPath))
                        <div class="mt-2">-}}
                            {{-- <img src="{{ asset('storage/' . $logoPath) }}" class="logo-icon" alt="shop logo" style="max-height: 100px;"> --}}
                            {{--<img src="{{ asset('storage/app/public/' . $logoPath) }}" class="logo-icon" alt="shop logo" style="max-height: 100px;">
                        </div>
                    @else
                        <div class="mt-2">
                            <img src="{{ asset('assets/img/logo.svg') }}" class="logo-icon" alt="default logo" style="max-height: 100px;">
                        </div>
                    @endif
                </div>

                <hr>

                <h5>Backup & Maintenance</h5>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label>Database Backup</label>
                            <div>
                                <a href="{{ route('settings.backup') }}" class="btn btn-info">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7,10 12,15 17,10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                    Create Backup
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label>Auto Backup</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="auto_backup" name="auto_backup"
                                       {{ ($settings['auto_backup'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="auto_backup">
                                    Enable automatic daily backup
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-save"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17,21 17,13 7,13 7,21"></polyline><polyline points="7,3 7,8 15,8"></polyline></svg>
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection --}}

@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h3>System Settings</h3>
    </div>
</div>

<div class="row layout-top-spacing">
    <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
        <div class="widget-content widget-content-area br-8">
            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                {{-- @method('PUT') --}}

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label for="shop_name">Shop Name</label>
                            <input type="text" class="form-control" id="shop_name" name="shop_name"
                                   value="{{ $settings['shop_name'] ?? '' }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label for="shop_email">Shop Email</label>
                            <input type="email" class="form-control" id="shop_email" name="shop_email"
                                   value="{{ $settings['shop_email'] ?? '' }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label for="shop_phone">Shop Phone</label>
                            <input type="text" class="form-control" id="shop_phone" name="shop_phone"
                                   value="{{ $settings['shop_phone'] ?? '' }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label for="currency">Currency</label>
                            <select class="form-control" id="currency" name="currency">
                                <option value="BDT" {{ ($settings['currency'] ?? '') == 'BDT' ? 'selected' : '' }}>BDT (৳)</option>
                                <option value="USD" {{ ($settings['currency'] ?? '') == 'USD' ? 'selected' : '' }}>USD ($)</option>
                                <option value="EUR" {{ ($settings['currency'] ?? '') == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="shop_address">Shop Address</label>
                    <textarea class="form-control" id="shop_address" name="shop_address" rows="3">{{ $settings['shop_address'] ?? '' }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label for="tax_rate">Tax Rate (%)</label>
                            <input type="number" class="form-control" id="tax_rate" name="tax_rate"
                                   value="{{ $settings['tax_rate'] ?? '0' }}" step="0.01" min="0" max="100">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label for="low_stock_alert">Low Stock Alert Quantity</label>
                            <input type="number" class="form-control" id="low_stock_alert" name="low_stock_alert"
                                   value="{{ $settings['low_stock_alert'] ?? '10' }}" min="1">
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="company_logo">Company Logo</label>
                    <input type="file" class="form-control" id="company_logo" name="company_logo" accept="image/*">

                    @php
                        $logoPath = \App\Models\SystemSetting::where('key', 'shop_logo')->value('value');
                    @endphp

                    @if($logoPath && Storage::disk('public')->exists($logoPath))
                        <div class="mt-2">
                            <img src="{{ asset('storage/app/public/' . $logoPath) }}" class="logo-icon" alt="shop logo" style="max-height: 100px;">
                        </div>
                    @else
                        <div class="mt-2">
                            <img src="{{ asset('assets/img/logo.svg') }}" class="logo-icon" alt="default logo" style="max-height: 100px;">
                        </div>
                    @endif
                </div>

                <hr>

                <h5>Backup & Maintenance</h5>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label>Auto Backup</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="auto_backup" name="auto_backup"
                                       {{ ($settings['auto_backup'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="auto_backup">
                                    Enable automatic daily backup
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-save"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17,21 17,13 7,13 7,21"></polyline><polyline points="7,3 7,8 15,8"></polyline></svg>
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Backup Files Section -->
<div class="row layout-top-spacing mt-3">
    <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
        <div class="widget-content widget-content-area br-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Available Backups</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>File Name</th>
                                    <th>Size</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($backupFiles as $file)

                                    <tr>
                                        <td>{{ $file['name'] }}</td>
                                        <td>{{ $file['size'] }} MB</td>
                                        <td>{{ $file['last_modified'] }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('settings.backup.download', ['file' => $file['name']]) }}"
                                                   class="btn btn-primary btn-sm" title="Download">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                                <button class="btn btn-danger btn-sm delete-backup"
                                                        data-filename="{{ $file['name'] }}" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No backup files found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-success" id="create-backup-btn">
                        <i class="fas fa-plus-circle"></i> Create New Backup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteBackupModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this backup file?</p>
                <p class="filename"><strong></strong></p>
            </div>
            <div class="modal-footer">
                <form id="deleteBackupForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Create backup button
    $('#create-backup-btn').click(function() {
        $.ajax({
            url: "{{ route('settings.backup') }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}"
            },
            beforeSend: function() {
                $('#create-backup-btn').prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin"></i> Creating...');
            },
            success: function(response) {
                toastr.success(response.message || 'Backup created successfully');
                setTimeout(() => location.reload(), 1500);
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON.message || 'Backup failed');
                $('#create-backup-btn').prop('disabled', false)
                    .html('<i class="fas fa-plus-circle"></i> Create New Backup');
            }
        });
    });

    // Delete backup button
    $('.delete-backup').click(function() {
        const filename = $(this).data('filename');
        $('#deleteBackupModal .filename strong').text(filename);
        $('#deleteBackupForm').attr('action', "{{ route('settings.backup.delete', '') }}/" + filename);
        $('#deleteBackupModal').modal('show');
    });
});
</script>
@endpush

@endsection
