<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\InventoryCategory;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\LowStockAlert;
use Illuminate\Support\Facades\Notification;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index(Request $request)
    {
        $query = InventoryItem::where('clinic_id', Auth::user()->clinic_id)
            ->with(['category']);

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
        }

        $items = $query->orderBy('name')->paginate(15);
        $categories = InventoryCategory::where('clinic_id', Auth::user()->clinic_id)->get();

        return view('inventory.index', compact('items', 'categories'));
    }

    public function create()
    {
        $categories = InventoryCategory::where('clinic_id', Auth::user()->clinic_id)->get();
        $suppliers = Supplier::where('clinic_id', Auth::user()->clinic_id)->get();
        return view('inventory.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:50',
            'category_id' => 'nullable|exists:inventory_categories,id',
            'unit' => 'required|string|max:20',
            'current_stock' => 'required|numeric|min:0',
            'min_stock_level' => 'required|numeric|min:0',
            'unit_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:500'
        ]);

        $validated['clinic_id'] = Auth::user()->clinic_id;

        $item = InventoryItem::create($validated);

        // Check for low stock
        if ($item->current_stock <= $item->min_stock_level) {
            $clinic = Auth::user()->clinic;
            if ($clinic) {
                Notification::send($clinic->admins, new LowStockAlert($item));
            }
        }

        return redirect()->route('clinic.inventory.index')->with('success', 'Item added to inventory.');
    }

    public function edit(InventoryItem $inventory)
    {
        $this->authorizeAccess($inventory);
        $categories = InventoryCategory::where('clinic_id', Auth::user()->clinic_id)->get();
        return view('inventory.edit', compact('inventory', 'categories'));
    }

    public function update(Request $request, InventoryItem $inventory)
    {
        $this->authorizeAccess($inventory);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:50',
            'category_id' => 'nullable|exists:inventory_categories,id',
            'unit' => 'required|string|max:20',
            'current_stock' => 'required|numeric|min:0',
            'min_stock_level' => 'required|numeric|min:0',
            'unit_price' => 'nullable|numeric|min:0',
        ]);

        $inventory->update($validated);

        // Check for low stock alert on update
        if ($inventory->current_stock <= $inventory->min_stock_level) {
            $clinic = Auth::user()->clinic;
            if ($clinic) {
                Notification::send($clinic->admins, new LowStockAlert($inventory));
            }
        }

        return redirect()->route('clinic.inventory.index')->with('success', 'Inventory updated.');
    }

    public function destroy(InventoryItem $inventory)
    {
        $this->authorizeAccess($inventory);
        $inventory->delete();

        return redirect()->route('clinic.inventory.index')->with('success', 'Item removed from inventory.');
    }

    protected function authorizeAccess(InventoryItem $item)
    {
        if ($item->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }
    }
}
