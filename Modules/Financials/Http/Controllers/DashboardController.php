<?php

namespace Modules\Financials\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Expense;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', date('Y'));
        
        // Income vs Expenses Monthly
        $monthlyIncome = Invoice::where('status', 'paid')
            ->whereYear('paid_date', $year)
            ->select(
                DB::raw('MONTH(paid_date) as month'),
                DB::raw('SUM(total_amount) as amount')
            )
            ->groupBy('month')
            ->pluck('amount', 'month')
            ->toArray();

        $monthlyExpenses = Expense::whereYear('expense_date', $year)
            ->select(
                DB::raw('MONTH(expense_date) as month'),
                DB::raw('SUM(amount) as amount')
            )
            ->groupBy('month')
            ->pluck('amount', 'month')
            ->toArray();

        $incomeData = [];
        $expenseData = [];
        $labels = [];

        for ($m = 1; $m <= 12; $m++) {
            $labels[] = Carbon::create()->month($m)->format('M');
            $incomeData[] = $monthlyIncome[$m] ?? 0;
            $expenseData[] = $monthlyExpenses[$m] ?? 0;
        }

        $totalIncome = array_sum($incomeData);
        $totalExpenses = array_sum($expenseData);
        $netProfit = $totalIncome - $totalExpenses;

        return view('financials::dashboard', compact(
            'incomeData', 
            'expenseData', 
            'labels', 
            'totalIncome', 
            'totalExpenses', 
            'netProfit',
            'year'
        ));
    }
}
