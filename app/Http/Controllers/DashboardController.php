<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\InvoiceBilling;
use App\Models\StockAdd;
use App\Models\Expense;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function DashboardPage(): View
    {
        return view('pages.dashboard.dashboard-page');
    }

    public function getDashboardData(Request $request)
    {
        $filter = $request->filter ?? 'today';

        $shopId = $request->auth_shop_id;

        /* DATE RANGE */
        if ($filter == 'today') {

            $start = Carbon::today()->startOfDay();
            $end   = Carbon::today()->endOfDay();

        } elseif ($filter == 'weekly') {

            $today = Carbon::today();

            $start = $today->copy()->startOfWeek(Carbon::SATURDAY)->startOfDay();
            $end   = $start->copy()->addDays(6)->endOfDay();

        } elseif ($filter == 'monthly') {

            $start = Carbon::now()->startOfMonth()->startOfDay();
            $end   = Carbon::now()->endOfMonth()->endOfDay();

        } elseif ($filter == 'yearly') {

            $start = Carbon::now()->startOfYear()->startOfDay();
            $end   = Carbon::now()->endOfYear()->endOfDay();

        } else {

            $start = null;
            $end   = null;
        }

        /* SELL TOTAL (invoice_date - datetime) */
        $sellQuery = InvoiceBilling::where('shop_id', $shopId)
            ->whereIn('source', ['Sell', 'Quick Sell']);

        if ($start && $end) {
            $sellQuery->whereBetween('invoice_date', [$start, $end]);
        }

        $sell = $sellQuery->sum('total');

        /* PURCHASE TOTAL (purchase_date - date) */
        $purchaseQuery = StockAdd::where('shop_id', $shopId)
            ->where('source', 'Purchase');

        if ($start && $end) {
            $purchaseQuery->whereBetween('purchase_date', [
                $start->toDateString(),
                $end->toDateString()
            ]);
        }

        $purchase = $purchaseQuery->sum('total_cost');

        /* EXPENSE TOTAL (date - date) */
        $expenseQuery = Expense::where('shop_id', $shopId);

        if ($start && $end) {
            $expenseQuery->whereBetween('date', [
                $start->toDateString(),
                $end->toDateString()
            ]);
        }

        $expense = $expenseQuery->sum('amount');

        return response()->json([
            'sell' => $sell,
            'purchase' => $purchase,
            'expense' => $expense
        ]);
    }

    public function getDashboardSummary(Request $request)
    {

        $shopId = $request->auth_shop_id;

        // =====================
        // CUSTOMER RECEIVABLE
        // =====================
        $customer = InvoiceBilling::where('shop_id', $shopId)
            ->selectRaw('
                SUM(total) as total_amount,
                SUM(due) as total_due,
                SUM(paid) as total_paid
            ')->first();

        $receivable = ($customer->total_amount ?? 0) - ($customer->total_paid ?? 0);


        // =====================
        // SUPPLIER PAYABLE
        // =====================
        $supplier = StockAdd::where('shop_id', $shopId)
            ->selectRaw('
                SUM(paid_amount) as total_paid,
                SUM(total_cost) as total_amount
            ')->first();

        $payable = ($supplier->total_amount ?? 0) - ($supplier->total_paid ?? 0);


        // =====================
        // STOCK
        // =====================
        $stock = Product::where('shop_id', $shopId)->sum('quantity');


        $receivable = InvoiceBilling::where('shop_id', $shopId)->sum('total')
            - InvoiceBilling::where('shop_id', $shopId)->sum('paid');

        $payable = StockAdd::where('shop_id', $shopId)->sum('total_cost')
            - StockAdd::where('shop_id', $shopId)->sum('paid_amount');

        $expense = Expense::where('shop_id', $shopId)->sum('amount');


        // net balance calculation in dashboard

     $cashIn = InvoiceBilling::where('shop_id', $shopId)->sum('paid');

$cashOut = StockAdd::where('shop_id', $shopId)->sum('paid_amount')
           + Expense::where('shop_id', $shopId)->sum('amount');

$cash = $cashIn - $cashOut;

$net = $cash + $receivable - $payable;


        return response()->json([
            'receivable' => $receivable,
            'payable'    => $payable,
            'net'        => $net,
            'stock'      => $stock
        ]);
    }
}
