<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\InvoiceBilling;
use App\Models\StockAdd;
use App\Models\Expense;

class CashboxController extends Controller
{
 public function cashBox(Request $request)
{

    // =========================
    // FILTER VALUES
    // =========================
    $type = $request->get('type');
    $startDate = $request->get('start_date');
    $endDate = $request->get('end_date');
    $perPage = $request->get('per_page', 10);
    $page = $request->get('page', 1);


    // =========================
    // CASH IN
    // =========================
    $cashInData = InvoiceBilling::select(
        'paid as amount',
        'created_at',
        'source',
        'customer_name'
    )
    ->get()
    ->map(function ($item) {
        return [
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
    $stockOutData = StockAdd::select(
        'paid_amount as amount',
        'created_at',
        'source',
        'note'
    )
    ->get()
    ->map(function ($item) {
        return [
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
    $expenseData = Expense::select(
        'amount',
        'created_at',
        'category',
        'note'
    )
    ->get()
    ->map(function ($item) {
        return [
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


    // =========================
    // FILTER BY DATE RANGE
    // =========================
    if ($startDate && $endDate) {
        $transactions = $transactions->filter(function ($item) use ($startDate, $endDate) {
            $date = \Carbon\Carbon::parse($item['date'])->format('Y-m-d');
            return $date >= $startDate && $date <= $endDate;
        })->values();
    }


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
    $cashIn = InvoiceBilling::sum('paid');
    $cashOut = StockAdd::sum('paid_amount') + Expense::sum('amount');
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
        $purchase->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Cash Out Saved'
        ]);
    }
}
