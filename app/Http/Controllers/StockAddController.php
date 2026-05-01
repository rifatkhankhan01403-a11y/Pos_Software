<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Product;
use App\Models\StockAdd;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockAddController extends Controller
{
    // PAGE
    public function StockPurchasePage(): View
    {
        return view('pages.dashboard.stock-add-page');
    }


    // =========================
    // CREATE STOCK PURCHASE
    // =========================
    public function CreateStockPurchase(Request $request)
    {
        $shopId = $request->auth_shop_id;

        $items = json_decode($request->items, true) ?? [];
        $duePlan = json_decode($request->due_plan, true) ?? [];

        $totalQty = 0;
        $totalCost = 0;

        foreach ($items as $item) {

            $qty = (float) ($item['qty'] ?? 0);
            $buy = (float) ($item['buy_price'] ?? 0);

            $totalQty += $qty;
            $totalCost += $qty * $buy;
        }

        $paid = (float) $request->paid_amount;
        $due = $totalCost - $paid;

        // =========================
        // SUPPLIER SNAPSHOT (SHOP SAFE)
        // =========================
        $supplier = Supplier::where('shop_id',$shopId)
            ->where('id',$request->supplier_id)
            ->first();

        $purchase = new StockAdd();

        $purchase->shop_id = $shopId;

        $purchase->invoice_no = $request->invoice_no ?? 'INV-' . time();
        $purchase->supplier_id = $request->supplier_id;

        // snapshot
        $purchase->supplier_name = $supplier->name ?? '';
        $purchase->supplier_phone = $supplier->mobile ?? '';

        $purchase->purchase_date = $request->purchase_date;

        $purchase->items = $items;
        $purchase->due_plan = $duePlan;

        $purchase->total_qty = $totalQty;
        $purchase->total_cost = $totalCost;
        $purchase->paid_amount = $paid;
        $purchase->due_amount = $due < 0 ? 0 : $due;

        $purchase->source = 'Purchase';

        $purchase->save();

        // =========================
        // STOCK UPDATE (SHOP SAFE)
        // =========================
        foreach ($items as $item) {

            $product = Product::where('shop_id',$shopId)
                ->where('id',$item['product_id'])
                ->first();

            if ($product) {
                $product->quantity = (float)$product->quantity + (float)$item['qty'];
                $product->save();
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Stock Purchase Saved Successfully'
        ]);
    }
}
