<div class="">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-title">
            <h3>Dashboard</h3>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-4">
        <!-- Today's Sales Card -->
        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Today's Sales</p>
                            <h4 class="my-1">৳{{ number_format($todaySales, 2) }}</h4>
                            <p class="mb-0 font-13 text-success"><i class="bi bi-caret-up-fill"></i> 5% from yesterday</p>
                        </div>
                        <div class="widget-icon-large bg-gradient-purple text-white ms-auto">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Expenses Card -->
        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Today's Expenses</p>
                            <h4 class="my-1">৳{{ number_format($todayExpenses, 2) }}</h4>
                            <p class="mb-0 font-13 text-danger"><i class="bi bi-caret-down-fill"></i> 2% from yesterday</p>
                        </div>
                        <div class="widget-icon-large bg-gradient-danger text-white ms-auto">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Profit Card -->
        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Today's Profit</p>
                            <h4 class="my-1">৳{{ number_format($todayProfit, 2) }}</h4>
                            <p class="mb-0 font-13 text-success"><i class="bi bi-caret-up-fill"></i> 12% from yesterday</p>
                        </div>
                        <div class="widget-icon-large bg-gradient-success  ms-auto">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Alert Card -->
        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Low Stock Alert</p>
                            <h4 class="my-1">{{ $lowStockProducts }}</h4>
                            <p class="mb-0 font-13 text-warning"><i class="bi bi-exclamation-triangle-fill"></i> Needs attention</p>
                        </div>
                        <div class="widget-icon-large bg-gradient-warning text-white ms-auto">
                            <i class="bi bi-exclamation-octagon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-4 mt-3">
        <!-- Total Products -->
        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Products</p>
                            <h4 class="my-1">{{ $totalProducts }}</h4>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar bg-gradient-info" role="progressbar" style="width: 90%"></div>
                            </div>
                        </div>
                        <div class="widget-icon-large bg-gradient-info text-white ms-auto">
                            <i class="bi bi-box-seam"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Customers</p>
                            <h4 class="my-1">{{ $totalCustomers }}</h4>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar bg-gradient-pink" role="progressbar" style="width: 80%"></div>
                            </div>
                        </div>
                        <div class="widget-icon-large bg-pink text-white ms-auto">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Employees -->
        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Employees</p>
                            <h4 class="my-1">{{ $totalEmployees }}</h4>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar bg-gradient-orange" role="progressbar" style="width: 70%"></div>
                            </div>
                        </div>
                        <div class="widget-icon-large bg-orange text-white ms-auto">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Profit -->
        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Monthly Profit</p>
                            <h4 class="my-1">৳{{ number_format($monthlyProfit, 2) }}</h4>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar bg-gradient-success" role="progressbar" style="width: 95%"></div>
                            </div>
                        </div>
                        <div class="widget-icon-large bg-gradient-success text-white ms-auto">
                            ৳
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Sales and Top Products -->
    <div class="row mt-4">
        <!-- Recent Sales -->
        <div class="col-12 col-lg-6 d-flex">
            <div class="card radius-10 w-100">
                <div class="card-header bg-transparent">
                    <div class="row g-3 align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Recent Sales</h5>
                        </div>
                        <div class="col">
                            <div class="d-flex align-items-center justify-content-end">
                                <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-secondary">View All</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Invoice</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentSales as $sale)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="recent-product-img">
                                                <i class="bi bi-receipt font-24"></i>
                                            </div>
                                            <div class="ms-2">
                                                <h6 class="mb-1 font-14">{{ $sale->invoice_no }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $sale->customer->name ?? $sale->customer_name ?? 'Walk-in' }}</td>
                                    <td>৳{{ number_format($sale->total_amount, 2) }}</td>
                                    <td>{{ $sale->created_at->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Selling Products -->
        <div class="col-12 col-lg-6 d-flex">
            <div class="card radius-10 w-100">
                <div class="card-header bg-transparent">
                    <div class="row g-3 align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Top Selling Products</h5>
                        </div>
                        <div class="col">
                            <div class="d-flex align-items-center justify-content-end">
                                <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary">View All</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Brand</th>
                                    <th>Sales</th>
                                    <th>Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topProducts as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="recent-product-img">
                                                <img src="{{ $product->image ? asset('storage/'.$product->image) : asset('assets/images/products/default-product.png') }}" alt="">
                                            </div>
                                            <div class="ms-2">
                                                <h6 class="mb-1 font-14">{{ $product->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $product->brand->name ?? 'N/A' }}</td>
                                    <td>{{ $product->sale_items_count }}</td>
                                    <td>
                                        <span class="badge rounded-pill bg-{{ $product->stock_quantity <= $product->min_stock_alert ? 'danger' : 'success' }}">
                                            {{ $product->stock_quantity }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Alert -->
    {{-- @if($lowStockProducts > 0)
    <div class="row mt-4">
        <div class="col-12 d-flex">
            <div class="card radius-10 w-100">
                <div class="card-header bg-transparent">
                    <div class="row g-3 align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Low Stock Alert ({{ $lowStockProducts }} items)</h5>
                        </div>
                        <div class="col">
                            <div class="d-flex align-items-center justify-content-end">
                                <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary">View All</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Brand</th>
                                    <th>Category</th>
                                    <th>Current Stock</th>
                                    <th>Min Alert</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockItems as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->brand->name ?? 'N/A' }}</td>
                                    <td>{{ $item->category->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge rounded-pill bg-danger">{{ $item->stock_quantity }}</span>
                                    </td>
                                    <td>{{ $item->min_stock_alert }}</td>
                                    <td>
                                        <a href="{{ route('products.edit', $item->id) }}" class="btn btn-sm btn-primary">Restock</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif --}}
</div>
