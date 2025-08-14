@extends('layouts.app')
@section('title', 'Used Phone Purchase')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">📱 ইউজড ফোন ক্রয়</h4>
        <a href="{{ route('purchase.search') }}" class="btn btn-outline-secondary">🔎 সার্চ</a>
    </div>
    @livewire('phone-purchase-form')
@endsection
