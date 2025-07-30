<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $totalSales = Sale::sum('total_amount');
        $totalPurchases = Purchase::sum('total_amount');
        $totalExpenses = Expense::sum('amount');
        $totalProfit = $totalSales - $totalPurchases - $totalExpenses;

        return view('reports.index', compact('totalSales', 'totalPurchases', 'totalExpenses', 'totalProfit'));
    }

    public function sales(Request $request)
    {
        $query = Sale::with(['customer', 'items.product']);

        if ($request->start_date) {
            $query->whereDate('sale_date', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('sale_date', '<=', $request->end_date);
        }

        if ($request->customer_id) {
            $query->where('customer_id', $request->customer_id);
        }

        $sales = $query->latest()->paginate(15);

        $summary = [
            'total_sales' => $query->count(),
            'total_amount' => $query->sum('total_amount'),
            //'total_profit' => $query->sum('profit'),
            'avg_sale' => $query->avg('total_amount') ?? 0
        ];

        $customers = Customer::orderBy('name')->get();

        return view('reports.sales', compact('sales', 'summary', 'customers'));
    }

    public function profitLoss(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? now()->endOfMonth()->format('Y-m-d');

        $totalRevenue = Sale::whereBetween('sale_date', [$startDate, $endDate])->sum('total_amount');
        $costOfGoodsSold = Purchase::whereBetween('purchase_date', [$startDate, $endDate])->sum('total_amount');
        $totalExpenses = Expense::whereBetween('expense_date', [$startDate, $endDate])->sum('amount');

        $grossProfit = $totalRevenue - $costOfGoodsSold;
        $netProfit = $grossProfit - $totalExpenses;

        // Monthly breakdown
        $monthlyData = [];
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($start->lte($end)) {
            $monthStart = $start->copy()->startOfMonth();
            $monthEnd = $start->copy()->endOfMonth();

            $revenue = Sale::whereBetween('sale_date', [$monthStart, $monthEnd])->sum('total_amount');
            $cogs = Purchase::whereBetween('purchase_date', [$monthStart, $monthEnd])->sum('total_amount');
            $expenses = Expense::whereBetween('expense_date', [$monthStart, $monthEnd])->sum('amount');

            $monthlyData[] = [
                'month' => $start->format('M Y'),
                'revenue' => $revenue,
                'cogs' => $cogs,
                'expenses' => $expenses,
                'profit' => $revenue - $cogs - $expenses
            ];

            $start->addMonth();
        }

        return view('reports.profit-loss', compact(
            'totalRevenue', 'costOfGoodsSold', 'totalExpenses', 'netProfit',
            'startDate', 'endDate', 'monthlyData'
        ));
    }

    public function inventory()
    {
        $products = Product::with(['brand', 'category'])->get();
        $totalProducts = $products->count();
        $totalStockValue = $products->sum(function($product) {
            return $product->stock_quantity * $product->selling_price;
        });
        $lowStockProducts = $products->where('stock_quantity', '<=', 10);

        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('reports.inventory', compact('products', 'totalProducts', 'totalStockValue', 'lowStockProducts', 'brands', 'categories'));
    }

    public function customer()
    {
        $customers = Customer::withCount('sales')
            ->withSum('sales', 'total_amount')
            ->orderBy('sales_sum_total_amount', 'desc')
            ->paginate(15);

        return view('reports.customer', compact('customers'));
    }

    public function employee()
    {
        $employees = Employee::withCount(['attendances', 'leaves'])->paginate(15);
        return view('reports.employee', compact('employees'));
    }

    public function export(Request $request)
    {
        $type = $request->type;

        switch ($type) {
            case 'sales':
                return $this->exportSales($request);
            case 'inventory':
                return $this->exportInventory($request);
            case 'customer':
                return $this->exportCustomer($request);
            case 'profit-loss':
                return $this->exportProfitLoss($request);
            default:
                return back()->with('error', 'Invalid export type');
        }
    }

    private function exportSales($request)
    {
        // Implementation for sales export
        return back()->with('success', 'Sales report exported successfully');
    }

    private function exportInventory($request)
    {
        // Implementation for inventory export
        return back()->with('success', 'Inventory report exported successfully');
    }

    private function exportCustomer($request)
    {
        // Implementation for customer export
        return back()->with('success', 'Customer report exported successfully');
    }

    private function exportProfitLoss($request)
    {
        // Implementation for profit loss export
        return back()->with('success', 'Profit & Loss report exported successfully');
    }
}
