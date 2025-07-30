<?php

namespace App\Livewire;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Purchase;
use App\Models\Employee;
use Livewire\Component;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $todaySales = 0;
    public $todayExpenses = 0;
    public $todayProfit = 0;
    public $monthlyProfit = 0;
    public $totalProducts = 0;
    public $lowStockProducts = 0;
    public $totalCustomers = 0;
    public $totalEmployees = 0;
    public $recentSales = [];
    public $topProducts = [];
    public $lowStockItems = [];
    public $monthlyData = [];

    public function mount()
    {
        $today = now()->toDateString();
        $thisMonth = now()->format('Y-m');
        
        // Today's statistics
        $this->todaySales = Sale::whereDate('sale_date', $today)->sum('total_amount');
        $this->todayExpenses = Expense::whereDate('expense_date', $today)->sum('amount');
        $this->todayProfit = $this->todaySales - $this->todayExpenses;
        
        // Monthly profit calculation
        $monthlySales = Sale::whereRaw("DATE_FORMAT(sale_date, '%Y-%m') = ?", [$thisMonth])->sum('total_amount');
        $monthlyExpenses = Expense::whereRaw("DATE_FORMAT(expense_date, '%Y-%m') = ?", [$thisMonth])->sum('amount');
        $this->monthlyProfit = $monthlySales - $monthlyExpenses;
        
        // Counts
        $this->totalProducts = Product::where('status', 'active')->count();
        $this->lowStockProducts = Product::lowStock()->count();
        $this->totalCustomers = Customer::where('status', 'active')->count();
        $this->totalEmployees = Employee::where('status', 'active')->count();
        
        // Recent sales
        $this->recentSales = Sale::with(['customer', 'createdBy'])
            ->latest()
            ->take(5)
            ->get();
            
        // Top selling products
        $this->topProducts = Product::withCount('saleItems')
            ->orderBy('sale_items_count', 'desc')
            ->take(5)
            ->get();
            
        // Low stock items
        $this->lowStockItems = Product::lowStock()
            ->with(['brand', 'category'])
            ->take(5)
            ->get();
            
        // Monthly data for chart
        $this->monthlyData = $this->getMonthlyData();
    }

    private function getMonthlyData()
    {
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthStr = $month->format('Y-m');
            
            $sales = Sale::whereRaw("DATE_FORMAT(sale_date, '%Y-%m') = ?", [$monthStr])->sum('total_amount');
            $expenses = Expense::whereRaw("DATE_FORMAT(expense_date, '%Y-%m') = ?", [$monthStr])->sum('amount');
            
            $data[] = [
                'month' => $month->format('M Y'),
                'sales' => $sales,
                'expenses' => $expenses,
                'profit' => $sales - $expenses
            ];
        }
        
        return $data;
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
