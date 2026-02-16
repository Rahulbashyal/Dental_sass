<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\InventoryCategory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = InventoryItem::with('category')->orderBy('name');
        
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $items = $query->paginate(15);
        $categories = InventoryCategory::orderBy('name')->get();

        return view('inventory::index', compact('items', 'categories'));
    }

    public function create()
    {
        $categories = InventoryCategory::orderBy('name')->get();
        return view('inventory::create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:inventory_categories,id',
            'sku' => 'nullable|string|unique:inventory_items,sku',
            'unit' => 'required|string',
            'min_stock_level' => 'required|numeric|min:0',
            'current_stock' => 'required|numeric|min:0',
            'unit_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $item = InventoryItem::create(array_merge($validated, [
            'clinic_id' => tenant()->clinic->id,
        ]));

        return redirect()->route('inventory.index')
            ->with('status', "Item '{$item->name}' added to inventory.");
    }
}
