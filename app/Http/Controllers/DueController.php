<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockAdd;
use App\Models\InvoiceBilling;
use App\Models\Customer;
use App\Models\Supplier;

class DueController extends Controller
{

/* =========================
   PARTY LIST
========================= */
public function partyList(Request $request)
{
    $type   = $request->type;
    $search = $request->search;
    $start  = $request->start_date;
    $end    = $request->end_date;

    /* =========================
       CUSTOMER LIST
    ========================= */
    if ($type === 'customer') {

        $query = Customer::query();

        if ($search) {
            $query->where('mobile', 'like', "%$search%");
        }

     $customers = Customer::query()
    ->select('customers.*')
    ->addSelect([
        'last_invoice' => InvoiceBilling::select('created_at')
            ->whereColumn('customer_mobile', 'customers.mobile')
            ->latest()
            ->limit(1)
    ])
    ->orderByDesc('last_invoice')
    ->paginate(7);
        // balance
        $balances = InvoiceBilling::selectRaw('
            customer_mobile,
            SUM(paid) as total_paid,
            SUM(due) as total_due
        ')
        ->when($start && $end, function ($q) use ($start, $end) {
            $q->whereBetween('invoice_date', [$start, $end]);
        })
        ->groupBy('customer_mobile')
        ->get()
        ->keyBy('customer_mobile');

        $data = $customers->getCollection()->map(function ($c) use ($balances) {

            $paid = $balances[$c->mobile]->total_paid ?? 0;
            $due  = $balances[$c->mobile]->total_due ?? 0;

            return [
                'id'     => $c->id,
                'name'   => $c->name,
                'mobile' => $c->mobile,
                'type'   => 'customer',
                'due'    => $paid - $due
            ];
        });

        return response()->json([
            'data' => $data,
            'current_page' => $customers->currentPage(),
            'last_page' => $customers->lastPage()
        ]);
    }

    /* =========================
       SUPPLIER LIST
    ========================= */
    if ($type === 'supplier') {

        $query = Supplier::query();

        if ($search) {
            $query->where('mobile', 'like', "%$search%");
        }
$suppliers = Supplier::query()
    ->select('suppliers.*')
    ->addSelect([
        'last_stock' => StockAdd::select('created_at')
            ->whereColumn('supplier_phone', 'suppliers.mobile')
            ->latest()
            ->limit(1)
    ])
    ->orderByDesc('last_stock')
    ->paginate(7);


        $balances = StockAdd::selectRaw('
            supplier_phone,
            SUM(paid_amount) as total_paid,
            SUM(due_amount) as total_due
        ')
        ->when($start && $end, function ($q) use ($start, $end) {
            $q->whereBetween('purchase_date', [$start, $end]);
        })
        ->groupBy('supplier_phone')
        ->get()
        ->keyBy('supplier_phone');

        $data = $suppliers->getCollection()->map(function ($s) use ($balances) {

            $paid = $balances[$s->mobile]->total_paid ?? 0;
            $due  = $balances[$s->mobile]->total_due ?? 0;

            return [
                'id'     => $s->id,
                'name'   => $s->name,
                'mobile' => $s->mobile,
                'type'   => 'supplier',
                'due'    => $paid - $due
            ];
        });

        return response()->json([
            'data' => $data,
            'current_page' => $suppliers->currentPage(),
            'last_page' => $suppliers->lastPage()
        ]);
    }

    return response()->json([]);
}


/* =========================
   LEDGER
========================= */
public function partyLedger(Request $request)
{
    $type = $request->type;
    $mobile = $request->mobile;

    /* =========================
       CUSTOMER LEDGER
    ========================= */
    if ($type === 'customer') {

        $customer = Customer::where('mobile', $mobile)->first();

        $transactions = InvoiceBilling::where('customer_mobile', $mobile)
            ->orderBy('created_at', 'asc')
            ->get();

        $running = 0;

        $data = $transactions->map(function ($t) use (&$running) {

            $running += ($t->paid - $t->due);

            return [
                'date'    => $t->created_at->format('d M Y, h:i A'),
                'payable' => $t->total,
                'paid'    => $t->paid,
                'due'     => $t->due,
                'balance' => $running
            ];
        });

        return response()->json([
    'name' => $customer->name ?? '',
    'mobile' => $mobile,
    'transactions' => $data,

    'total_payable' => $transactions->sum('total'),
    'total_paid'    => $transactions->sum('paid'),
    'total_due'     => $transactions->sum('due'),
]);
    }

    /* =========================
       SUPPLIER LEDGER
    ========================= */
    if ($type === 'supplier') {

        $supplier = Supplier::where('mobile', $mobile)->first();

        $transactions = StockAdd::where('supplier_phone', $mobile)
            ->orderBy('created_at', 'asc')
            ->get();

        $running = 0;

        $data = $transactions->map(function ($t) use (&$running) {

            $running += ($t->paid_amount - $t->due_amount);

            return [
                'date'    => $t->created_at->format('d M Y, h:i A'),
                 'payable' => $t->total_cost,
                'paid'    => $t->paid_amount,
                'due'     => $t->due_amount,
                'balance' => $running
            ];
        });

        return response()->json([
            'name' => $supplier->name ?? '',
            'mobile' => $mobile,
            'transactions' => $data,
            'total_payable' => $transactions->sum('total_cost'),
            'total_paid' => $transactions->sum('paid_amount'),
            'total_due'  => $transactions->sum('due_amount'),
        ]);
    }

    return response()->json([]);
}


/* =========================
   DASHBOARD
========================= */
public function dueBookPage()
{
    $customer = InvoiceBilling::selectRaw('SUM(total) as total_amount, SUM(paid) as paid')->first();
    $supplier = StockAdd::selectRaw('SUM(total_cost) as total_amount, SUM(paid_amount) as paid')->first();

    $receivable = ($customer->total_amount ?? 0) - ($customer->paid ?? 0);
    $payable    = ($supplier->total_amount ?? 0) - ($supplier->paid ?? 0);

    $net = $receivable - $payable;

    return view('pages.dashboard.due-book', compact(
        'receivable',
        'payable',
        'net'
    ));
}


/* =========================
   STORE DUE
========================= */
public function store(Request $request)
{
    try {

        $type     = $request->type;
        $taken_by = $request->taken_by;
        $name     = $request->name;
        $mobile   = $request->mobile;
        $amount   = (float) $request->amount;
        $date     = $request->date;
        $note     = $request->note;

        /* =========================
           CUSTOMER DUE
        ========================= */
      if ($taken_by == "Customer") {

    $customer = Customer::firstOrCreate(
        ['mobile' => $mobile],
        ['name' => $name]
    );

    InvoiceBilling::create([
        'customer_id' => $customer->id,
        'customer_name' => $name,
        'customer_mobile' => $mobile,

        'paid'  => $type == "Due Taken" ? $amount : 0,
        'due'   => $type == "Due Given" ? $amount : 0,
        'total' => $type == "Due Given" ? $amount : 0,

        'invoice_date' => $date,
        'source' => 'customer_due'
    ]);
}

        /* =========================
           SUPPLIER DUE
        ========================= */
        if ($taken_by == "Supplier") {

            $supplier = Supplier::firstOrCreate(
                ['mobile' => $mobile],
                ['name' => $name]
            );

            StockAdd::create([
                'supplier_id' => $supplier->id,
                'supplier_name' => $name,
                'supplier_phone' => $mobile,



                'paid_amount' => $type == "Due Taken" ? $amount : 0,
                'due_amount'  => $type == "Due Given" ? $amount : 0,
                'total_cost' => $type == "Due Given" ? $amount : 0,
                'purchase_date' => $date,
                'source' => 'supplier_due'
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Due saved successfully'
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'status' => false,
            'error' => $e->getMessage()
        ]);
    }
}

}
