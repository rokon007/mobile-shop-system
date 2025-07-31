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
                        <div class="mt-2">
                            {{-- <img src="{{ asset('storage/' . $logoPath) }}" class="logo-icon" alt="shop logo" style="max-height: 100px;"> --}}
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
@endsection
