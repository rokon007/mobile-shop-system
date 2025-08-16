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
use App\Models\PurchaseFhone;


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
    public $todayPhoneCount, $todayTotalAmount;
    public $salesChange, $expensesChange, $profitChange;
    public $maxProducts = 1000; //  ব্যবসায় সম্ভাব্য সর্বোচ্চ প্রোডাক্ট সংখ্যা
    public $maxCustomers = 500; // সম্ভাব্য সর্বোচ্চ কাস্টমার সংখ্যা
    public $maxEmployees = 50;  // সম্ভাব্য সর্বোচ্চ এমপ্লয়ী সংখ্যা
    public $maxMonthlyProfit = 500000; // সম্ভাব্য সর্বোচ্চ মাসিক লাভ (টাকায়)


    public function mount()
    {
        $today = now()->toDateString();
        $thisMonth = now()->format('Y-m');

        // Today's statistics
        $this->todaySales = Sale::whereDate('sale_date', $today)->sum('total_amount');
        $this->todayExpenses = Expense::whereDate('expense_date', $today)->sum('amount');
        // $this->todayProfit = $this->todaySales - $this->todayExpenses;

        $todaysSales = Sale::with(['items.product', 'items.inventory'])->whereDate('sale_date', $today)->get();

        $dailyProfit = $todaysSales->sum(function ($sale) {
            return $sale->items->sum(function ($item) {
                // যদি inventory রিলেশন থাকে এবং purchase_price পাওয়া যায়
                if ($item->inventory && $item->inventory->purchase_price) {
                    $profitPerItem = ($item->unit_price - $item->inventory->purchase_price) * $item->quantity;
                    return $profitPerItem;
                }
                // যদি product রিলেশন থাকে এবং purchase_price পাওয়া যায়
                elseif ($item->product && $item->product->purchase_price) {
                    $profitPerItem = ($item->unit_price - $item->product->purchase_price) * $item->quantity;
                    return $profitPerItem;
                }
                return 0;
            });
        });

        $this->todayProfit = $dailyProfit - $this->todayExpenses;

        // Monthly profit calculation
       // $monthlySales = Sale::whereRaw("DATE_FORMAT(sale_date, '%Y-%m') = ?", [$thisMonth])->sum('total_amount');
        $monthlyExpenses = Expense::whereRaw("DATE_FORMAT(expense_date, '%Y-%m') = ?", [$thisMonth])->sum('amount');
        // $this->monthlyProfit = $monthlySales - $monthlyExpenses;

        //dd($monthlyExpenses);
        $monthlySales = Sale::with(['items.inventory'])
                        ->where('sale_date', 'like', "$thisMonth%")
                        ->get();

        $monthlyProfit = $monthlySales->sum(function ($sale) {
            return $sale->items->sum(function ($item) {
                // Inventory থেকে purchase_price পেলে
                if ($item->inventory && isset($item->inventory->purchase_price)) {
                    return ($item->unit_price - $item->inventory->purchase_price) * $item->quantity;
                }

                return 0;
            });
        });

        $this->monthlyProfit = $monthlyProfit - $monthlyExpenses;

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


        // আজকের তারিখে কেনা ফোনের তথ্য
        $todayPurchases = PurchaseFhone::with('phone')
            ->whereDate('purchase_date', Carbon::today())
            ->get();

        // আজকের কেনা ফোনের সংখ্যা
        $this->todayPhoneCount = $todayPurchases->count();

        // আজকের মোট খরচ (purchase_price এর যোগফল)
        $this->todayTotalAmount = $todayPurchases->sum('purchase_price');



        // গতকালের ডাটা বের করুন
        $yesterday = Carbon::yesterday()->toDateString();

        // গতকালের বিক্রয়
        $yesterdaySales = Sale::whereDate('sale_date', $yesterday)->sum('total_amount');
        $this->salesChange = $this->getPercentageChange($this->todaySales, $yesterdaySales);

        // গতকালের খরচ
        $yesterdayExpenses = Expense::whereDate('expense_date', $yesterday)->sum('amount');
        $this->expensesChange = $this->getPercentageChange($this->todayExpenses, $yesterdayExpenses);

        // গতকালের লাভ
        $yesterdayProfit = Sale::with(['items.product', 'items.inventory'])
                            ->whereDate('sale_date', $yesterday)
                            ->get()
                            ->sum(function ($sale) {
                                return $sale->items->sum(function ($item) {
                                    if ($item->inventory && $item->inventory->purchase_price) {
                                        return ($item->unit_price - $item->inventory->purchase_price) * $item->quantity;
                                    }
                                    return 0;
                                });
                            }) - Expense::whereDate('expense_date', $yesterday)->sum('amount');

        $this->profitChange = $this->getPercentageChange($this->todayProfit, $yesterdayProfit);

    }


    private function getPercentageChange($todayValue, $yesterdayValue)
    {
        if ($yesterdayValue == 0) {
            return [
                'percentage' => 0,
                'direction' => 'no-change',
                'class' => 'text-secondary',
                'icon' => 'bi-dash'
            ];
        }

        $change = (($todayValue - $yesterdayValue) / $yesterdayValue) * 100;
        $rounded = round($change, 2);

        if ($change > 0) {
            return [
                'percentage' => $rounded,
                'direction' => 'up',
                'class' => 'text-success',
                'icon' => 'bi bi-caret-up-fill'
            ];
        } elseif ($change < 0) {
            return [
                'percentage' => abs($rounded),
                'direction' => 'down',
                'class' => 'text-danger',
                'icon' => 'bi bi-caret-down-fill'
            ];
        } else {
            return [
                'percentage' => 0,
                'direction' => 'no-change',
                'class' => 'text-secondary',
                'icon' => 'bi bi-dash'
            ];
        }

        // প্রগ্রেস বার ক্যালকুলেশন
        $this->maxProducts = max($this->totalProducts * 2, 100); // প্রোডাক্টের দ্বিগুণ বা ন্যূনতম 100
        $this->maxCustomers = max($this->totalCustomers * 2, 50); // কাস্টমারের দ্বিগুণ বা ন্যূনতম 50
        $this->maxEmployees = max($this->totalEmployees * 2, 10); // এমপ্লয়ীর দ্বিগুণ বা ন্যূনতম 10
        $this->maxMonthlyProfit = max($this->monthlyProfit * 2, 100000); // লাভের দ্বিগুণ বা ন্যূনতম ১ লাখ
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
