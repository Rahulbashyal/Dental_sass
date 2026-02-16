<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Vendor;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index(Request $request)
    {
        $query = Expense::where('clinic_id', Auth::user()->clinic_id)
            ->with(['vendor', 'branch']);

        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $expenses = $query->latest('expense_date')->paginate(15);
        
        $totalExpenses = Expense::where('clinic_id', Auth::user()->clinic_id)
            ->where('status', 'paid')
            ->sum('amount');

        return view('expenses.index', compact('expenses', 'totalExpenses'));
    }

    public function create()
    {
        $vendors = Vendor::where('clinic_id', Auth::user()->clinic_id)->orderBy('name')->get();
        $branches = Branch::where('clinic_id', Auth::user()->clinic_id)->get();
        $categories = ['Salary', 'Rent', 'Supplies', 'Utilities', 'Marketing', 'Equipment', 'Maintenance', 'Insurance', 'Others'];

        return view('expenses.create', compact('vendors', 'branches', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_id' => 'nullable|exists:vendors,id',
            'branch_id' => 'nullable|exists:branches,id',
            'category' => 'required|string',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'status' => 'required|in:pending,paid',
            'reference_number' => 'nullable|string|max:50',
            'receipt' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $validated['clinic_id'] = Auth::user()->clinic_id;

        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('receipts', 'public');
            $validated['receipt_path'] = $path;
        }

        Expense::create($validated);

        return redirect()->route('clinic.expenses.index')->with('success', 'Expense recorded successfully.');
    }

    public function show(Expense $expense)
    {
        $this->authorizeAccess($expense);
        return view('expenses.show', compact('expense'));
    }

    public function destroy(Expense $expense)
    {
        $this->authorizeAccess($expense);
        
        if ($expense->receipt_path) {
            Storage::disk('public')->delete($expense->receipt_path);
        }
        
        $expense->delete();

        return redirect()->route('clinic.expenses.index')->with('success', 'Expense deleted.');
    }

    protected function authorizeAccess(Expense $expense)
    {
        if ($expense->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }
    }
}
