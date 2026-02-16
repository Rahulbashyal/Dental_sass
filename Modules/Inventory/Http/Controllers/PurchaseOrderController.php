<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\InventoryItem;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $orders = PurchaseOrder::with('supplier')->orderBy('created_at', 'desc')->paginate(15);
        return view('inventory::purchase_orders.index', compact('orders'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $items = InventoryItem::orderBy('name')->get();
        return view('inventory::purchase_orders.create', compact('suppliers', 'items'));
    }

    public function store(Request $request)
    {
        // Simple implementation for now
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'status' => 'required|in:draft,ordered',
        ]);

        $order = PurchaseOrder::create(array_merge($validated, [
            'clinic_id' => tenant()->clinic->id,
            'order_number' => 'PO-' . strtoupper(uniqid()),
        ]));

        return redirect()->route('purchase-orders.index')
            ->with('status', "Purchase Order #{$order->order_number} created.");
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier', 'items.inventoryItem']);
        return view('inventory::purchase_orders.show', compact('purchaseOrder'));
    }
}
