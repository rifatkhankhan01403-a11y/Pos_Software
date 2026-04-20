<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceBilling;
use App\Models\Product;
use Illuminate\View\View;
class InvoiceBillingController extends Controller
{


 function SalePage(): View {
        return view('pages.dashboard.sale-page');
    }




 function createInvoice(Request $request)
    {
        try {

            $totalProfit = 0;
            $updatedItems = [];

            if (empty($request->items) || !is_array($request->items)) {
                return response()->json([
                    'status' => false,
                    'error' => 'Items are required'
                ]);
            }

            foreach ($request->items as $item) {

                $qty = (float) $item['qty'];
                $salePrice = (float) $item['sale_price']; // TOTAL line price

                $product = Product::find($item['product_id']);

                if ($product) {

                    // =========================
                    // ✔ FIX PROFIT CALCULATION
                    // =========================

                    $buyPrice = (float) $product->buy_price;
                    $sellPrice = (float) $product->sell_price;

                    // per unit profit × qty
                    $profit = ($sellPrice - $buyPrice) * $qty;

                    $totalProfit += $profit;

                    // =========================
                    // ✔ FIX STOCK UPDATE
                    // =========================

                    $product->quantity = (float) $product->quantity - $qty;

                    if ($product->quantity < 0) {
                        $product->quantity = 0;
                    }

                    $product->save();

                    // attach profit per item
                    $item['profit'] = round($profit, 2);
                }

                $updatedItems[] = $item;
            }

            // =========================
            // FRONTEND TOTALS ONLY
            // =========================

            $subtotal = (float) $request->total;
            $discount = (float) $request->discount;
            $vat = (float) $request->vat;
            $total = (float) $request->payable;

            $paid = (float) $request->paid;
            $due = (float) $request->due;

            // safety only
            if ($due < 0) $due = 0;

            // =========================
            // SAVE
            // =========================

            $invoice = InvoiceBilling::create([
                'customer_id' => $request->customer_id,
                'customer_name' => $request->customer_name,
                'customer_mobile' => $request->customer_mobile,

                'items' => $updatedItems,

                'subtotal' => $subtotal,
                'discount' => $discount,
                'vat' => $vat,
                'total' => $total,

                'paid' => $paid,
                'due' => $due,

                'profit' => round($totalProfit, 2),

                'invoice_date' => now(),
                'due_date' => $request->due_date,

                'source' => 'Sell'
            ]);

            return response()->json([
                'status' => true,
                'invoice_id' => $invoice->id,
                'message' => 'Invoice saved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ]);
        }
    }


    public function QuickSellStore(Request $request)
{
    try {

        $sell = InvoiceBilling::create([
            'customer_name' => $request->customer_name,
            'customer_mobile' => $request->customer_mobile,
            'paid' => $request->amount,
            'total'=>$request->amount,
            'profit' => $request->profit,
            'invoice_date' => $request->sell_date,
            'source' => 'Quick Sell'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Quick sell saved successfully'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'error' => $e->getMessage()
        ]);
    }
}
}
