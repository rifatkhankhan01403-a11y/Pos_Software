<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\InvoiceBilling;
use App\Models\StockAdd;
use App\Models\Expense;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
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
            $sellQuery->whereBetween('created_at', [$start, $end]);
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


        /* CONDITION SELL TOTAL */
$conditionQuery = InvoiceBilling::where('shop_id', $shopId)
    ->where('source', 'Condition Sales');

if ($start && $end) {
    $conditionQuery->whereBetween('created_at', [$start, $end]);
}

$conditionSell = $conditionQuery->sum('due');


/* STOCK SOLD QTY */
$stockSellQuery = InvoiceBilling::where('shop_id', $shopId)
    ->whereIn('source', ['Sell', 'Condition Sales','Quick Sell']);

if ($start && $end) {
    $stockSellQuery->whereBetween('created_at', [$start, $end]);
}

$stockInvoices = $stockSellQuery->get();

$stockSoldQty = 0;

foreach ($stockInvoices as $invoice) {

    if (!empty($invoice->items)) {

        foreach ($invoice->items as $item) {

            $stockSoldQty += (float) ($item['qty'] ?? 0);

        }
    }
}


    return response()->json([
    'sell' => round($sell,2),
    'purchase' => round($purchase,2),
    'expense' => round($expense,2),
    'condition_sell' => round($conditionSell,2),
    'stock_sold_qty' => round($stockSoldQty,2)
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

//dashboar cart

       $customer = InvoiceBilling::where('shop_id', $shopId)
     ->whereIn('source', ['Sell', 'customer_due'])
    ->selectRaw('SUM(total) as total, SUM(paid) as paid')
    ->first();

$receivable = ($customer->total ?? 0) - ($customer->paid ?? 0);


$supplier = StockAdd::where('shop_id', $shopId)
       ->whereIn('source', ['Purchase', 'supplier_due'])
    ->selectRaw('SUM(total_cost) as total_cost, SUM(paid_amount) as paid_amount')
    ->first();

$payable = ($supplier->total_cost ?? 0) - ($supplier->paid_amount ?? 0);



        $expense = Expense::where('shop_id', $shopId)->sum('amount');


   $customer_due = InvoiceBilling::where('shop_id', $shopId)
   ->whereIn('source', ['customer_due'])->sum('due');
  $supplier_due = StockAdd::where('shop_id', $shopId)
   ->whereIn('source', ['supplier_due'])->sum('due_amount');
        // net balance calculation in dashboard

     $cashIn = InvoiceBilling::where('shop_id', $shopId)->sum('paid');

$cashOut = StockAdd::where('shop_id', $shopId)->sum('paid_amount')
           + Expense::where('shop_id', $shopId)->sum('amount');

$cash = $cashIn - $cashOut - $customer_due +   $supplier_due  ;

$net = $cash ;


        return response()->json([
            'receivable' => $receivable,
            'payable'    => $payable,
            'net'        => $net,
            'stock'      => $stock
        ]);
    }




    //pdf

 public function downloadDashboardPdf(Request $request)
{
    $filter = $request->filter ?? 'today';

    $shopId = $request->auth_shop_id;

    $shop = User::where('shop_id', $shopId)
        ->where('role', 'owner')
        ->first();

    /* DATE RANGE */

    if ($filter == 'today') {

        $start = Carbon::today()->startOfDay();
        $end   = Carbon::today()->endOfDay();

    } elseif ($filter == 'weekly') {

        $today = Carbon::today();

        $start = $today->copy()
            ->startOfWeek(Carbon::SATURDAY)
            ->startOfDay();

        $end = $start->copy()
            ->addDays(6)
            ->endOfDay();

    } elseif ($filter == 'monthly') {

        $start = Carbon::now()
            ->startOfMonth()
            ->startOfDay();

        $end = Carbon::now()
            ->endOfMonth()
            ->endOfDay();

    } elseif ($filter == 'yearly') {

        $start = Carbon::now()
            ->startOfYear()
            ->startOfDay();

        $end = Carbon::now()
            ->endOfYear()
            ->endOfDay();

    } else {

        $start = null;
        $end = null;
    }


    /* SELL */

    $sellQuery = InvoiceBilling::where('shop_id', $shopId)
        ->whereIn('source', ['Sell', 'Quick Sell']);

    if ($start && $end) {
        $sellQuery->whereBetween('created_at', [$start, $end]);
    }

    $sell = $sellQuery->sum('total');


    /* PURCHASE */

    $purchaseQuery = StockAdd::where('shop_id', $shopId)
        ->where('source', 'Purchase');

    if ($start && $end) {

        $purchaseQuery->whereBetween('purchase_date', [
            $start->toDateString(),
            $end->toDateString()
        ]);
    }

    $purchase = $purchaseQuery->sum('total_cost');


    /* EXPENSE */

    $expenseQuery = Expense::where('shop_id', $shopId);

    if ($start && $end) {

        $expenseQuery->whereBetween('date', [
            $start->toDateString(),
            $end->toDateString()
        ]);
    }

    $expense = $expenseQuery->sum('amount');


    /* CONDITION SALE */

    $conditionQuery = InvoiceBilling::where('shop_id', $shopId)
        ->where('source', 'Condition Sales');

    if ($start && $end) {
        $conditionQuery->whereBetween('created_at', [$start, $end]);
    }

    $conditionSell = $conditionQuery->sum('due');


    /* STOCK */

    $stock = Product::where('shop_id', $shopId)
        ->sum('quantity');

    /* STOCK SOLD QTY */

$stockSellQuery = InvoiceBilling::where('shop_id', $shopId)
    ->whereIn('source', ['Sell', 'Condition Sales', 'Quick Sell']);

if ($start && $end) {
    $stockSellQuery->whereBetween('created_at', [$start, $end]);
}

$stockInvoices = $stockSellQuery->get();

$stockSoldQty = 0;

foreach ($stockInvoices as $invoice) {

    if (!empty($invoice->items)) {

        foreach ($invoice->items as $item) {

            $stockSoldQty += (float) ($item['qty'] ?? 0);

        }
    }
}


/* RECEIVABLE */

$customer = InvoiceBilling::where('shop_id', $shopId)
    ->whereIn('source', ['Sell', 'customer_due'])
    ->selectRaw('SUM(total) as total, SUM(paid) as paid')
    ->first();

$receivable = ($customer->total ?? 0) - ($customer->paid ?? 0);


/* PAYABLE */

$supplier = StockAdd::where('shop_id', $shopId)
    ->whereIn('source', ['Purchase', 'supplier_due'])
    ->selectRaw('SUM(total_cost) as total_cost, SUM(paid_amount) as paid_amount')
    ->first();

$payable = ($supplier->total_cost ?? 0) - ($supplier->paid_amount ?? 0);


/* NET BALANCE */

$cashIn = InvoiceBilling::where('shop_id', $shopId)
    ->sum('paid');

$cashOut = StockAdd::where('shop_id', $shopId)
    ->sum('paid_amount')
    + Expense::where('shop_id', $shopId)->sum('amount');

$cash = $cashIn - $cashOut;

$net = $cash + $receivable - $payable;


    $pdf = Pdf::loadView('pdf.dashboard-report', [

        'shop' => $shop,
        'filter' => $filter,

      'sell' => $sell,
'purchase' => $purchase,
'expense' => $expense,
'conditionSell' => $conditionSell,
'stock' => $stock,
'stockSoldQty' => $stockSoldQty,
'receivable' => $receivable,
'payable' => $payable,
'net' => $net,

        'start' => $start,
        'end' => $end

    ])->setPaper('a4');

    return $pdf->download('dashboard-report.pdf');
}
}
