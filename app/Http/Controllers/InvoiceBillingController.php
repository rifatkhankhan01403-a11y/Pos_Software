<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceBilling;
use App\Models\Product;
use Illuminate\View\View;

class InvoiceBillingController extends Controller
{

    /* =========================
       SALE PAGE
    ========================= */
    function SalePage(): View {
        return view('pages.dashboard.sale-page');
    }


    /* =========================
       CREATE INVOICE
    ========================= */
    function createInvoice(Request $request)
    {
        try {

            $shopId = $request->auth_shop_id;

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
                $salePrice = (float) $item['sale_price'];

                // PRODUCT MUST BELONG TO SAME SHOP
                $product = Product::where('shop_id',$shopId)
                    ->where('id',$item['product_id'])
                    ->first();

                if ($product) {

                    // =========================
                    // PROFIT CALCULATION
                    // =========================
                    $buyPrice = (float) $product->buy_price;
                    $sellPrice = (float) $product->sell_price;

                    $profit = ($sellPrice - $buyPrice) * $qty;
                    $totalProfit += $profit;

                    // =========================
                    // STOCK UPDATE
                    // =========================
                    $product->quantity = (float) $product->quantity - $qty;

                    if ($product->quantity < 0) {
                        $product->quantity = 0;
                    }

                    $product->save();

                    $item['profit'] = round($profit,2);
                }

                $updatedItems[] = $item;
            }

            /* =========================
               FRONTEND TOTALS
            ========================= */

            $subtotal = (float) $request->total;
            $discount = (float) $request->discount;
            $vat = (float) $request->vat;
            $total = (float) $request->payable;

            $paid = (float) $request->paid;
            $due = (float) $request->due;

            if ($due < 0) $due = 0;

            /* =========================
               SAVE INVOICE
            ========================= */

            $invoice = InvoiceBilling::create([

                'shop_id' => $shopId,

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

                'profit' => round($totalProfit,2),

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


    /* =========================
       QUICK SELL
    ========================= */
    // public function QuickSellStore(Request $request)
    // {
    //     try {

    //         $shopId = $request->auth_shop_id;

    //         InvoiceBilling::create([

    //             'shop_id' => $shopId,

    //             'customer_name' => $request->customer_name,
    //             'customer_mobile' => $request->customer_mobile,

    //             'paid' => $request->amount,
    //             'total'=> $request->amount,
    //             'profit' => $request->profit,

    //             'invoice_date' => $request->sell_date,

    //             'source' => 'Quick Sell'
    //         ]);

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Quick sell saved successfully'
    //         ]);

    //     } catch (\Exception $e) {

    //         return response()->json([
    //             'status' => false,
    //             'error' => $e->getMessage()
    //         ]);

    //     }
    // }



public function QuickSellStore(Request $request)
{
    try {

        $shopId = $request->auth_shop_id;

        // =========================
        // VALIDATION: AMOUNT REQUIRED
        // =========================
        if (!$request->amount || $request->amount <= 0) {
            return response()->json([
                'status' => false,
                'message' => 'Amount is required'
            ]);
        }

        $product = null;
        $qty = (float) $request->qty;

        // =========================
        // IF PRODUCT SELECTED
        // =========================
        if ($request->product_id) {

            $product = Product::where('shop_id', $shopId)
                ->where('id', $request->product_id)
                ->first();

            if (!$product) {
                return response()->json([
                    'status' => false,
                    'message' => 'Product not found'
                ]);
            }

            // QTY REQUIRED WHEN PRODUCT EXISTS
            if ($qty < 1) {
                return response()->json([
                    'status' => false,
                    'message' => 'Quantity must be at least 1'
                ]);
            }

            // STOCK CHECK
            if ($qty > $product->quantity) {
                return response()->json([
                    'status' => false,
                    'message' => 'Insufficient stock'
                ]);
            }
        }

        // =========================
        // PROFIT (only if product exists)
        // =========================
        $profit = 0;

        if ($product) {
            $profit =
                ((float)$product->sell_price - (float)$product->buy_price) * $qty;

            // reduce stock
            $product->quantity -= $qty;
            $product->save();
        }

        // =========================
        // SAVE INVOICE
        // =========================
        InvoiceBilling::create([
            'shop_id' => $shopId,
            'customer_name' => $request->customer_name,
            'customer_mobile' => $request->customer_mobile,

            'items' => $product ? [[
                'product_id' => $product->id,
                'product_name' => $product->name,
                'qty' => $qty,
                'sale_price' => $product->sell_price
            ]] : [],

            'paid' => (float) $request->amount,
            'total' => (float) $request->amount,
            'profit' => round($profit, 2),
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

