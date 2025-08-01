@extends('layouts.app')

@section('title', 'Employee')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h3>{{$employee->name}} Details</h3>
    </div>
    <div class="page-header-actions mb-3">
        {{-- <a href="{{ route('employees.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Add New Employee
        </a> --}}
    </div>
</div>
<div class="row layout-top-spacing">
    <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
        <div class="widget-content widget-content-area br-8">

        </div>
    </div>
</div>

@endsection
