<?php

namespace App\Http\Controllers;

use App\Models\StockTransfer;
use App\Models\StockTransferItem;
use App\Models\Warehouse;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockTransferController extends Controller
{
    public function index()
    {
        $transfers = StockTransfer::with(['fromWarehouse', 'toWarehouse', 'items.product'])->latest()->paginate(10);
        return view('stock-transfers.index', compact('transfers'));
    }

    public function create()
    {
        $warehouses = Warehouse::all();
        $products = Product::all();
        return view('stock-transfers.create', compact('warehouses', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id' => 'required|exists:warehouses,id|different:from_warehouse_id',
            'transfer_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $transfer = StockTransfer::create([
                'from_warehouse_id' => $request->from_warehouse_id,
                'to_warehouse_id' => $request->to_warehouse_id,
                'transfer_date' => $request->transfer_date,
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            foreach ($request->items as $item) {
                StockTransferItem::create([
                    'stock_transfer_id' => $transfer->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                ]);
            }
        });

        return redirect()->route('stock-transfers.index')->with('success', 'Stock transfer created successfully');
    }

    public function show(StockTransfer $stockTransfer)
    {
        $stockTransfer->load(['fromWarehouse', 'toWarehouse', 'items.product']);
        return view('stock-transfers.show', compact('stockTransfer'));
    }

    public function edit(StockTransfer $stockTransfer)
    {
        $warehouses = Warehouse::all();
        $products = Product::all();
        $stockTransfer->load(['items.product']);
        return view('stock-transfers.edit', compact('stockTransfer', 'warehouses', 'products'));
    }

    public function update(Request $request, StockTransfer $stockTransfer)
    {
        $request->validate([
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id' => 'required|exists:warehouses,id|different:from_warehouse_id',
            'transfer_date' => 'required|date',
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        $stockTransfer->update($request->only(['from_warehouse_id', 'to_warehouse_id', 'transfer_date', 'status', 'notes']));

        return redirect()->route('stock-transfers.index')->with('success', 'Stock transfer updated successfully');
    }

    public function destroy(StockTransfer $stockTransfer)
    {
        if ($stockTransfer->status === 'completed') {
            return back()->with('error', 'Cannot delete completed transfer');
        }

        $stockTransfer->delete();
        return redirect()->route('stock-transfers.index')->with('success', 'Stock transfer deleted successfully');
    }

    public function receive(StockTransfer $stockTransfer)
    {
        if ($stockTransfer->status !== 'pending') {
            return back()->with('error', 'Transfer is not pending');
        }

        $stockTransfer->update(['status' => 'completed']);
        return back()->with('success', 'Stock transfer received successfully');
    }
}
