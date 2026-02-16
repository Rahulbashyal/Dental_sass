<?php

namespace Modules\Financials\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category');
        $query = Expense::orderBy('expense_date', 'desc');

        if ($category) {
            $query->where('category', $category);
        }

        $expenses = $query->paginate(15);
        
        $stats = [
            'total' => Expense::sum('amount'),
            'this_month' => Expense::whereMonth('expense_date', Carbon::now()->month)->sum('amount'),
            'inventory' => Expense::where('category', 'Inventory')->sum('amount'),
            'salary' => Expense::where('category', 'Salary')->sum('amount'),
        ];

        return view('financials::expenses.index', compact('expenses', 'stats', 'category'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:50',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
        ]);

        Expense::create(array_merge($validated, [
            'clinic_id' => tenant()->clinic->id,
        ]));

        return redirect()->route('expenses.index')
            ->with('status', 'Expense recorded successfully.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return back()->with('status', 'Expense record deleted.');
    }
}
