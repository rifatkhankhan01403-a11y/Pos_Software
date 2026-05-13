<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\InvoiceBilling;
use App\Models\StockAdd;
use App\Models\Expense;
use Illuminate\View\View;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;

class CashboxController extends Controller
{

    public function cashBox(Request $request): View
    {
        // =========================
        // FILTER VALUES
        // =========================
        $type = $request->get('type');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);

        $shopId = $request->auth_shop_id;

        // =========================
        // BASE QUERIES
        // =========================
        $invoiceQuery = InvoiceBilling::where('shop_id', $shopId);
        $stockQuery = StockAdd::where('shop_id', $shopId);
        $expenseQuery = Expense::where('shop_id', $shopId);

        // ✅ FIXED DATE FILTER
        if ($startDate && $endDate) {

            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();

            $invoiceQuery->whereBetween('created_at', [$start, $end]);
            $stockQuery->whereBetween('created_at', [$start, $end]);
            $expenseQuery->whereBetween('created_at', [$start, $end]);
        }

        // =========================
      // CASH IN
// =========================
$cashInData = $invoiceQuery
    ->select('id','paid as amount', 'created_at', 'source')
    ->get()
    ->map(function ($item) {
        return [
            'id' => $item->id,
            'model' => 'invoice',

            'type' => 'Cash In',
            'date' => $item->created_at,
            'source' => $item->source ?? 'Cash In',
            'note' => '-',
            'amount' => $item->amount
        ];
    });


// =========================
// CASH OUT (STOCK)
// =========================
$stockOutData = $stockQuery
    ->select('id','paid_amount as amount', 'created_at', 'source', 'note')
    ->get()
    ->map(function ($item) {
        return [
            'id' => $item->id,
            'model' => 'stock',

            'type' => 'Cash Out',
            'date' => $item->created_at,
            'source' => $item->source ?? 'Cash Out',
            'note' => $item->note ?? '-',
            'amount' => $item->amount
        ];
    });


// =========================
// CASH OUT (EXPENSE)
// =========================
$expenseData = $expenseQuery
    ->select('id','amount', 'created_at', 'category', 'note')
    ->get()
    ->map(function ($item) {
        return [
            'id' => $item->id,
            'model' => 'expense',

            'type' => 'Cash Out',
            'date' => $item->created_at,
            'source' => 'Expense',
            'note' => $item->note ?? $item->category ?? '-',
            'amount' => $item->amount
        ];
    });

        // =========================
        // MERGE ALL
        // =========================
        $transactions = collect()
            ->merge($cashInData)
            ->merge($stockOutData)
            ->merge($expenseData)
            ->sortByDesc('date')
            ->values();

        // =========================
        // FILTER BY TYPE
        // =========================
        if ($type) {
            $transactions = $transactions->where('type', $type)->values();
        }

        // ❌ REMOVED duplicate date filtering (IMPORTANT)

        // =========================
        // PAGINATION
        // =========================
        $transactionsPaginated = new LengthAwarePaginator(
            $transactions->forPage($page, $perPage),
            $transactions->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query()
            ]
        );

        // =========================
        // SUMMARY CARDS
        // =========================
        $cashIn = InvoiceBilling::where('shop_id', $shopId)->sum('paid');

        $cashOut =
            StockAdd::where('shop_id', $shopId)->sum('paid_amount')
            +
            Expense::where('shop_id', $shopId)->sum('amount');

        $balance = $cashIn - $cashOut;

        // =========================
        // TABLE TOTALS
        // =========================
        $totalTransactions = $transactions->count();
        $totalAmount = $transactions->sum('amount');

        return view('pages.dashboard.cashbox-page', [
            'transactions' => $transactionsPaginated,
            'cashIn' => $cashIn,
            'cashOut' => $cashOut,
            'balance' => $balance,
            'totalTransactions' => $totalTransactions,
            'totalAmount' => $totalAmount,
        ]);
    }


    // =========================
    // CASH IN
    // =========================
    public function cashIn(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'note' => 'nullable|string'
        ]);

        $invoice = new InvoiceBilling();

        $invoice->paid = $request->amount;
        $invoice->invoice_date = now();
        $invoice->source = 'Cash In';
        $invoice->note = $request->note;
        $invoice->shop_id = $request->auth_shop_id;

        $invoice->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Cash In Saved'
        ]);
    }


    // =========================
    // CASH OUT
    // =========================
    public function cashOut(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'note' => 'nullable|string'
        ]);

        $purchase = new StockAdd();

        $purchase->invoice_no = 'CASHOUT-' . time();
        $purchase->purchase_date = now();
        $purchase->paid_amount = $request->amount;
        $purchase->source = 'Cash Out';
        $purchase->note = $request->note;
        $purchase->shop_id = $request->auth_shop_id;

        $purchase->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Cash Out Saved'
        ]);
    }


    // =========================
    // DOWNLOAD PDF
    // =========================
    public function downloadPdf(Request $request)
    {
        $type = $request->get('type');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $shopId = $request->auth_shop_id;

        $shop = User::where('shop_id', $shopId)
            ->where('role', 'owner')
            ->first();

        $invoiceQuery = InvoiceBilling::where('shop_id', $shopId);
        $stockQuery = StockAdd::where('shop_id', $shopId);
        $expenseQuery = Expense::where('shop_id', $shopId);

        // ✅ FIXED DATE FILTER HERE ALSO
        if ($startDate && $endDate) {

            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();

            $invoiceQuery->whereBetween('created_at', [$start, $end]);
            $stockQuery->whereBetween('created_at', [$start, $end]);
            $expenseQuery->whereBetween('created_at', [$start, $end]);
        }

        $cashInData = $invoiceQuery->get()->map(function ($item) {
            return [
                'type' => 'Cash In',
                'date' => $item->created_at,
                'source' => $item->source,
                'note' => $item->note,
                'amount' => $item->paid
            ];
        });

        $stockOutData = $stockQuery->get()->map(function ($item) {
            return [
                'type' => 'Cash Out',
                'date' => $item->created_at,
                'source' => $item->source,
                'note' => $item->note,
                'amount' => $item->paid_amount
            ];
        });

        $expenseData = $expenseQuery->get()->map(function ($item) {
            return [
                'type' => 'Cash Out',
                'date' => $item->created_at,
                'source' => 'Expense',
                'note' => $item->note,
                'amount' => $item->amount
            ];
        });

        $transactions = collect()
            ->merge($cashInData)
            ->merge($stockOutData)
            ->merge($expenseData)
            ->sortBy('date')
            ->values();

        if ($type) {
            $transactions = $transactions->where('type', $type)->values();
        }

        $totalIn = $transactions->where('type', 'Cash In')->sum('amount');
        $totalOut = $transactions->where('type', 'Cash Out')->sum('amount');
        $balance = $totalIn - $totalOut;

        $pdf = Pdf::loadView('pdf.cashbox', [
            'transactions' => $transactions,
            'shop' => $shop,
            'totalIn' => $totalIn,
            'totalOut' => $totalOut,
            'balance' => $balance,
            'startDate' => $startDate,
            'endDate' => $endDate
        ])->setPaper('a4');

        return $pdf->download('cashbox-report.pdf');
    }


// =========================
// DELETE TRANSACTION
// =========================
public function deleteTransaction(Request $request)
{
    $shopId = $request->auth_shop_id;

    $id = $request->id;
    $model = $request->model;

    if ($model == 'invoice') {

        InvoiceBilling::where('id', $id)
            ->where('shop_id', $shopId)
            ->delete();

    } elseif ($model == 'stock') {

        StockAdd::where('id', $id)
            ->where('shop_id', $shopId)
            ->delete();

    } elseif ($model == 'expense') {

        Expense::where('id', $id)
            ->where('shop_id', $shopId)
            ->delete();
    }

    return response()->json([
        'status' => true,
        'message' => 'Transaction Deleted Successfully'
    ]);
}

}
