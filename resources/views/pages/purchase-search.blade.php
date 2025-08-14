@extends('layouts.app')
@section('title', 'Search Purchases')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">🔎 ক্রয় রেকর্ড সার্চ</h4>
        <a href="{{ route('purchase.new') }}" class="btn btn-primary">＋ নতুন ক্রয়</a>
    </div>
    @livewire('purchase-search')
@endsection
