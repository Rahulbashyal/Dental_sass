<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'role:clinic_admin|accountant']);
    }

    public function index(Request $request)
    {
        $query = PurchaseOrder::where('clinic_id', Auth::user()->clinic_id)
            ->with(['supplier']);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('supplier_id') && $request->supplier_id != '') {
            $query->where('supplier_id', $request->supplier_id);
        }

        $purchaseOrders = $query->latest()->paginate(15);
        $suppliers = Supplier::where('clinic_id', Auth::user()->clinic_id)->get();

        return view('purchase_orders.index', compact('purchaseOrders', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::where('clinic_id', Auth::user()->clinic_id)->get();
        $inventoryItems = InventoryItem::where('clinic_id', Auth::user()->clinic_id)->get();
        
        if ($suppliers->isEmpty()) {
            return redirect()->route('clinic.suppliers.create')->with('info', 'Please add a supplier before creating a purchase order.');
        }

        return view('purchase_orders.create', compact('suppliers', 'inventoryItems'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_number' => 'required|string|unique:purchase_orders,order_number',
            'items' => 'required|array|min:1',
            'items.*.inventory_item_id' => 'required|exists:inventory_items,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $purchaseOrder = PurchaseOrder::create([
                'clinic_id' => Auth::user()->clinic_id,
                'supplier_id' => $validated['supplier_id'],
                'order_number' => $validated['order_number'],
                'total_amount' => 0,
                'status' => 'draft',
            ]);

            $totalAmount = 0;
            foreach ($validated['items'] as $item) {
                $itemTotal = $item['quantity'] * $item['unit_price'];
                $totalAmount += $itemTotal;

                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'inventory_item_id' => $item['inventory_item_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $itemTotal,
                ]);
            }

            $purchaseOrder->update(['total_amount' => $totalAmount]);

            DB::commit();
            return redirect()->route('clinic.purchase-orders.show', $purchaseOrder)->with('success', 'Purchase order created.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create purchase order: ' . $e->getMessage());
        }
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $this->authorizeAccess($purchaseOrder);
        $purchaseOrder->load(['supplier', 'items.inventoryItem']);
        return view('purchase_orders.show', compact('purchaseOrder'));
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        $this->authorizeAccess($purchaseOrder);
        
        if ($purchaseOrder->status !== 'draft') {
            return back()->with('error', 'Only draft purchase orders can be edited.');
        }

        $suppliers = Supplier::where('clinic_id', Auth::user()->clinic_id)->get();
        $inventoryItems = InventoryItem::where('clinic_id', Auth::user()->clinic_id)->get();
        $purchaseOrder->load('items');

        return view('purchase_orders.edit', compact('purchaseOrder', 'suppliers', 'inventoryItems'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $this->authorizeAccess($purchaseOrder);

        if ($purchaseOrder->status !== 'draft') {
            return back()->with('error', 'Only draft purchase orders can be updated.');
        }

        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_number' => 'required|string|unique:purchase_orders,order_number,' . $purchaseOrder->id,
            'items' => 'required|array|min:1',
            'items.*.inventory_item_id' => 'required|exists:inventory_items,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $purchaseOrder->update([
                'supplier_id' => $validated['supplier_id'],
                'order_number' => $validated['order_number'],
            ]);

            $purchaseOrder->items()->delete();

            $totalAmount = 0;
            foreach ($validated['items'] as $item) {
                $itemTotal = $item['quantity'] * $item['unit_price'];
                $totalAmount += $itemTotal;

                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'inventory_item_id' => $item['inventory_item_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $itemTotal,
                ]);
            }

            $purchaseOrder->update(['total_amount' => $totalAmount]);

            DB::commit();
            return redirect()->route('clinic.purchase-orders.show', $purchaseOrder)->with('success', 'Purchase order updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update purchase order: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, PurchaseOrder $purchaseOrder)
    {
        $this->authorizeAccess($purchaseOrder);

        $validated = $request->validate([
            'status' => 'required|in:ordered,received,cancelled'
        ]);

        if ($purchaseOrder->status === 'received' || $purchaseOrder->status === 'cancelled') {
            return back()->with('error', 'Cannot change status of a received or cancelled order.');
        }

        DB::beginTransaction();
        try {
            $oldStatus = $purchaseOrder->status;
            $newStatus = $validated['status'];

            $updateData = ['status' => $newStatus];

            if ($newStatus === 'ordered' && $oldStatus === 'draft') {
                $updateData['ordered_at'] = now();
            }

            if ($newStatus === 'received') {
                $updateData['received_at'] = now();
                
                // Update inventory stock levels
                foreach ($purchaseOrder->items as $item) {
                    $inventoryItem = $item->inventoryItem;
                    $inventoryItem->increment('current_stock', $item->quantity);
                }
            }

            $purchaseOrder->update($updateData);

            DB::commit();
            return back()->with('success', 'Purchase order status updated to ' . $newStatus);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update status: ' . $e->getMessage());
        }
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        $this->authorizeAccess($purchaseOrder);

        if ($purchaseOrder->status !== 'draft') {
            return back()->with('error', 'Only draft purchase orders can be deleted.');
        }

        $purchaseOrder->delete();

        return redirect()->route('clinic.purchase-orders.index')->with('success', 'Purchase order deleted.');
    }

    protected function authorizeAccess(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }
    }
}
