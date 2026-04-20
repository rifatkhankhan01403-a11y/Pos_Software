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
        $sellQuery = InvoiceBilling::whereIn('source', ['Sell', 'Quick Sell']);

        if ($start && $end) {
            $sellQuery->whereBetween('invoice_date', [$start, $end]);
        }

        $sell = $sellQuery->sum('total');

        /* PURCHASE TOTAL (purchase_date - date) */
        $purchaseQuery = StockAdd::where('source', 'Purchase');

        if ($start && $end) {
            $purchaseQuery->whereBetween('purchase_date', [
                $start->toDateString(),
                $end->toDateString()
            ]);
        }

        $purchase = $purchaseQuery->sum('total_cost');

        /* EXPENSE TOTAL (date - date) */
        $expenseQuery = Expense::query();

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
    // =====================
    // CUSTOMER RECEIVABLE (same logic as partyList)
    // =====================
    $customer = InvoiceBilling::selectRaw('
        SUM(total) as total_amount,
        SUM(due) as total_due,
        SUM(paid) as total_paid
    ')->first();

    $receivable = ($customer->total_amount ?? 0)- ($customer->total_paid?? 0);

    // =====================
    // SUPPLIER PAYABLE (same logic as partyList)
    // =====================
    $supplier = StockAdd::selectRaw('
        SUM(paid_amount) as total_paid,
        SUM(total_cost) as total_amount
    ')->first();

    $payable = ($supplier->total_amount ?? 0) - ($supplier->total_paid ?? 0);


    // =====================
    // STOCK
    // =====================
    $stock = Product::sum('quantity');

  $receivable = InvoiceBilling::sum('total') - InvoiceBilling::sum('paid');

$payable = StockAdd::sum('total_cost') - StockAdd::sum('paid_amount');

$expense = Expense::sum('amount');

$net = ($receivable - $payable) - $expense;


    return response()->json([
        'receivable' => $receivable,
        'payable'    => $payable,
        'net'        => $net,
        'stock'      => $stock
    ]);
}
}
