<aside class="sidebar-wrapper sidebar-theme" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ asset('assets/img/logo.svg') }}" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">SMS</h4>
        </div>
        <div class="toggle-icon ms-auto">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11,17 6,12 11,7"></polyline><polyline points="18,17 13,12 18,7"></polyline></svg>
        </div>
    </div>
    <!-- Navigation -->
    <ul class="metismenu menu-categories" id="menu">
        <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}">
                <div class="parent-icon"><i class="bi bi-house-door"></i></div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>
        @can('user-management')
        <li class="{{ request()->routeIs('users.*') || request()->routeIs('roles.*') ? 'active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-people"></i></div>
                <div class="menu-title">User Management</div>
            </a>
            <ul>
                <li class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <a href="{{ route('users.index') }}"><i class="bi bi-arrow-right-short"></i>Users</a>
                </li>
                <li class="{{ request()->routeIs('roles.*') ? 'active' : '' }}">
                    <a href="{{ route('roles.index') }}"><i class="bi bi-arrow-right-short"></i>Roles & Permissions</a>
                </li>
            </ul>
        </li>
        @endcan
        @can('product-management')
        <li class="{{ request()->routeIs('products.*') || request()->routeIs('brands.*') || request()->routeIs('categories.*') ? 'active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-box"></i></div>
                <div class="menu-title">Product Management</div>
            </a>
            <ul>
                <li class="{{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <a href="{{ route('products.index') }}"><i class="bi bi-arrow-right-short"></i>Products</a>
                </li>
                <li class="{{ request()->routeIs('brands.*') ? 'active' : '' }}">
                    <a href="{{ route('brands.index') }}"><i class="bi bi-arrow-right-short"></i>Brands</a>
                </li>
                <li class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <a href="{{ route('categories.index') }}"><i class="bi bi-arrow-right-short"></i>Categories</a>
                </li>
            </ul>
        </li>
        @endcan
        @can('purchase-management')
        <li class="{{ request()->routeIs('purchases.*') || request()->routeIs('suppliers.*') ? 'active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-cart"></i></div>
                <div class="menu-title">Purchase Management</div>
            </a>
            <ul>
                <li class="{{ request()->routeIs('purchases.*') ? 'active' : '' }}">
                    <a href="{{ route('purchases.index') }}"><i class="bi bi-arrow-right-short"></i>Purchases</a>
                </li>
                <li class="{{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                    <a href="{{ route('suppliers.index') }}"><i class="bi bi-arrow-right-short"></i>Suppliers</a>
                </li>
            </ul>
        </li>
        @endcan
        @can('sales-management')
        <li class="{{ request()->routeIs('sales.*') || request()->routeIs('pos.*') || request()->routeIs('customers.*') ? 'active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-cash"></i></div>
                <div class="menu-title">Sales Management</div>
            </a>
            <ul>
                <li class="{{ request()->routeIs('pos.*') ? 'active' : '' }}">
                    <a href="{{ route('pos.index') }}"><i class="bi bi-arrow-right-short"></i>POS System</a>
                </li>
                <li class="{{ request()->routeIs('sales.*') ? 'active' : '' }}">
                    <a href="{{ route('sales.index') }}"><i class="bi bi-arrow-right-short"></i>Sales</a>
                </li>
                <li class="{{ request()->routeIs('customers.*') ? 'active' : '' }}">
                    <a href="{{ route('customers.index') }}"><i class="bi bi-arrow-right-short"></i>Customers</a>
                </li>
            </ul>
        </li>
        @endcan
        @can('employee-management')
        <li class="{{ request()->routeIs('employees.*') || request()->routeIs('attendance.*') || request()->routeIs('leaves.*') ? 'active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-person-badge"></i></div>
                <div class="menu-title">Employee Management</div>
            </a>
            <ul>
                <li class="{{ request()->routeIs('employees.*') ? 'active' : '' }}">
                    <a href="{{ route('employees.index') }}"><i class="bi bi-arrow-right-short"></i>Employees</a>
                </li>
                <li class="{{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                    <a href="{{ route('attendance.index') }}"><i class="bi bi-arrow-right-short"></i>Attendance</a>
                </li>
                <li class="{{ request()->routeIs('leaves.*') ? 'active' : '' }}">
                    <a href="{{ route('leaves.index') }}"><i class="bi bi-arrow-right-short"></i>Leave Management</a>
                </li>
            </ul>
        </li>
        @endcan
        @can('finance-management')
        <li class="{{ request()->routeIs('expenses.*') || request()->routeIs('loans.*') ? 'active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-calculator"></i></div>
                <div class="menu-title">Finance & Accounting</div>
            </a>
            <ul>
                <li class="{{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                    <a href="{{ route('expenses.index') }}"><i class="bi bi-arrow-right-short"></i>Expenses</a>
                </li>
                <li class="{{ request()->routeIs('loans.*') ? 'active' : '' }}">
                    <a href="{{ route('loans.index') }}"><i class="bi bi-arrow-right-short"></i>Loan Management</a>
                </li>
            </ul>
        </li>
        @endcan
        @can('inventory-management')
        <li class="{{ request()->routeIs('inventory.*') || request()->routeIs('warehouses.*') || request()->routeIs('stock-transfers.*') ? 'active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-layers"></i></div>
                <div class="menu-title">Inventory Management</div>
            </a>
            <ul>
                <li class="{{ request()->routeIs('inventory.*') ? 'active' : '' }}">
                    <a href="{{ route('inventory.index') }}"><i class="bi bi-arrow-right-short"></i>Stock Overview</a>
                </li>
                <li class="{{ request()->routeIs('warehouses.*') ? 'active' : '' }}">
                    <a href="{{ route('warehouses.index') }}"><i class="bi bi-arrow-right-short"></i>Warehouses</a>
                </li>
                <li class="{{ request()->routeIs('stock-transfers.*') ? 'active' : '' }}">
                    <a href="{{ route('stock-transfers.index') }}"><i class="bi bi-arrow-right-short"></i>Stock Transfers</a>
                </li>
            </ul>
        </li>
        @endcan
        @can('reports-view')
        <li class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-bar-chart"></i></div>
                <div class="menu-title">Reports & Analytics</div>
            </a>
            <ul>
                <li class="{{ request()->routeIs('reports.sales') ? 'active' : '' }}">
                    <a href="{{ route('reports.sales') }}"><i class="bi bi-arrow-right-short"></i>Sales Report</a>
                </li>
                <li class="{{ request()->routeIs('reports.profit-loss') ? 'active' : '' }}">
                    <a href="{{ route('reports.profit-loss') }}"><i class="bi bi-arrow-right-short"></i>Profit & Loss</a>
                </li>
                <li class="{{ request()->routeIs('reports.inventory') ? 'active' : '' }}">
                    <a href="{{ route('reports.inventory') }}"><i class="bi bi-arrow-right-short"></i>Inventory Report</a>
                </li>
                <li class="{{ request()->routeIs('reports.customer') ? 'active' : '' }}">
                    <a href="{{ route('reports.customer') }}"><i class="bi bi-arrow-right-short"></i>Customer Report</a>
                </li>
            </ul>
        </li>
        @endcan
        @can('system-settings')
        <li class="{{ request()->routeIs('settings.*') ? 'active' : '' }}">
            <a href="{{ route('settings.index') }}">
                <div class="parent-icon"><i class="bi bi-gear"></i></div>
                <div class="menu-title">System Settings</div>
            </a>
        </li>
        @endcan
    </ul>
</aside>
