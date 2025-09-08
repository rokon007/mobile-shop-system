<div>
    <!-- Filter div -->
                <div class="mb-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-text bg-primary"><i class="fa fa-search text-white"></i></span>
                                <input type="text" class="form-control" placeholder="Search products by name, model, or SKU..." wire:model.live="search">
                                <button class="btn btn-primary" type="button">Search</button>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Summary Cards -->
                {{-- <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card radius-10 border-0 border-start border-tiffany border-3">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="">
                                        <p class="mb-1">Total Sales</p>
                                        <h4 class="mb-0 text-tiffany">{{ $summary['total_sales'] }}</h4>
                                    </div>
                                </div>
                                <div class="ms-auto widget-icon bg-tiffany text-white">
                                    <i class="bi bi-bag-check-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card radius-10 border-0 border-start border-tiffany border-3">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="">
                                        <p class="mb-1">Total Amount</p>
                                        <h4 class="mb-0 text-tiffany">৳{{ number_format($summary['total_amount'], 2) }}</h4>
                                    </div>
                                </div>
                                <div class="ms-auto widget-icon bg-tiffany text-white">
                                    <i class="bi bi-bag-check-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card radius-10 border-0 border-start border-tiffany border-3">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="">
                                        <p class="mb-1">Average Sale</p>
                                        <h4 class="mb-0 text-tiffany">৳{{ number_format($summary['avg_sale'], 2) }}</h4>
                                    </div>
                                </div>
                                <div class="ms-auto widget-icon bg-tiffany text-white">
                                    <i class="bi bi-bag-check-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}

                <!-- Sales Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-4">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Brand</th>
                                <th>IMEI</th>
                                <th>Confuguration</th>
                                <th>Purchase Price</th>
                                <th>Selling Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inventories as $inventory)
                                @php
                                    $attributes = json_decode($inventory->attribute_combination, true) ?? [];
                                    $stockCount = $inventory->quantity ?? 0; // Changed from count() to quantity
                                @endphp
                            <tr>
                                <td>{{ $inventory->product->name }}</td>
                                <td>{{ $inventory->product->brand->name ?? 'N/A' }}</td>
                                <td>{{ $inventory->imei }}</td>
                                <td>
                                    @foreach($attributes as $key => $value)
                                        @php
                                            $filter = App\Models\Filter::find($key);
                                            $optionValue = App\Models\FilterOption::find($value);
                                        @endphp

                                        @if($filter && $optionValue)
                                                <small class="text-muted">
                                                <strong style="font-size: 11px;" class="text-dark">
                                                    {{ $filter->name }}: {{ $optionValue->value }}&nbsp;
                                                </strong>
                                            </small>
                                        @endif
                                    @endforeach
                                </td>
                                <td>৳{{ number_format($inventory->purchase_price, 2) }}</td>
                                <td>৳{{ number_format($inventory->selling_price, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No sales found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- @if($sales->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $sales->appends(request()->query())->links() }}
                </div>
                @endif --}}
</div>
