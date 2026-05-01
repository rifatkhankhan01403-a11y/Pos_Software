<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceBilling;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;

class CodSaleController extends Controller
{
    /* =========================
       PAGE LOAD
    ========================= */
    public function index(): View
    {
        return view('pages.dashboard.condition-page');
    }


    /* =========================
       CREATE COD SALE
    ========================= */
    public function store(Request $request)
    {
        try {

            $shopId = $request->auth_shop_id;

            $totalQty = 0;
            $total = 0;
            $items = [];

            if (empty($request->items) || !is_array($request->items)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Items required'
                ]);
            }

            foreach ($request->items as $item) {

                $product = Product::where('shop_id', $shopId)
                    ->where('id', $item['product_id'])
                    ->first();

                if (!$product) continue;

                $qty = (float) $item['qty'];
                $price = (float) $item['price'];

                // reduce stock
                $product->quantity -= $qty;
                if ($product->quantity < 0) $product->quantity = 0;
                $product->save();

                $lineTotal = $qty * $price;

                $items[] = [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'qty' => $qty,
                    'price' => $price,
                    'total' => $lineTotal
                ];

                $totalQty += $qty;
                $total += $lineTotal;
            }

            $discount = (float) $request->discount;
            $courier = $request->courier;

            $final = $total - $discount;

            $invoice = InvoiceBilling::create([
                'shop_id' => $shopId,

                'customer_name' => $request->customer_name,
                'customer_mobile' => $request->customer_mobile,

                'items' => $items,

                'total' => $total,
                'discount' => $discount,
                'paid' => 0,
                'due' => $final,

                'courier' => $request->courier,
                'note' => $request->note,

                'invoice_date' => now(),
                'due_date' => $request->paid_date,

                'source' => 'Condition Sales'
            ]);

            return response()->json([
                'status' => true,
                'message' => 'COD Sale Created',
                'invoice_id' => $invoice->id
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    /* =========================
       LIST COD SALES
    ========================= */
 public function list(Request $request)
{
    $shopId = $request->auth_shop_id;

    if (!$shopId) {
        return response()->json([
            'error' => 'Shop ID missing'
        ], 401);
    }

    // ================= FILTERED QUERY (TABLE) =================
    $query = InvoiceBilling::where('shop_id', $shopId)
        ->where('source', 'Condition Sales');

    // SEARCH
    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('customer_name', 'like', '%' . $request->search . '%')
              ->orWhere('customer_mobile', 'like', '%' . $request->search . '%');
        });
    }

    // STATUS
    if ($request->status == 'pending') {
        $query->where('due', '>', 0);
    }

    if ($request->status == 'paid') {
        $query->where('due', 0);
    }

    // DATE FILTER (ONLY FOR TABLE)
    if ($request->start_date && $request->end_date) {
        $query->whereBetween('created_at', [
            $request->start_date . ' 00:00:00',
            $request->end_date . ' 23:59:59'
        ]);
    }

    // PAGINATED DATA (TABLE)
    $data = $query->orderBy('id', 'desc')->paginate(5);

    // ================= FLATTEN QTY =================
    $data->getCollection()->transform(function ($row) {

        $items = is_array($row->items)
            ? $row->items
            : json_decode($row->items, true);

        $totalQty = 0;

        if ($items) {
            foreach ($items as $item) {
                $totalQty += $item['qty'] ?? 0;
            }
        }

        $row->qty = $totalQty;

        return $row;
    });

    // ================= GLOBAL SUMMARY (NO FILTERS) =================
    $base = InvoiceBilling::where('shop_id', $shopId)
        ->where('source', 'Condition Sales');

    $summary = [
        'pending' => (clone $base)->sum('due'),
        'total_cod' => (clone $base)->sum('total'),
        'total_received' => (clone $base)->sum('paid'),
        'received_today' => (clone $base)
            ->whereDate('created_at', now()->toDateString())
            ->sum('paid'),
    ];

    // ================= RESPONSE =================
    return response()->json([
        'data' => $data,
        'summary' => $summary
    ]);
}

    /* =========================
       MARK AS PAID
    ========================= */
   public function markPaid($id)
{
    $invoice = InvoiceBilling::find($id);

    if (!$invoice) {
        return response()->json(['status' => false]);
    }

    $invoice->paid = $invoice->due;
    $invoice->due = 0;
    $invoice->due_date = now(); // ✅ paid date
    $invoice->save();

    return response()->json([
        'status' => true,
        'message' => 'Marked as paid'
    ]);
}


public function delete($id)
{
    $invoice = InvoiceBilling::find($id);

    if (!$invoice) {
        return response()->json(['status' => false]);
    }

    $invoice->delete();

    return response()->json([
        'status' => true,
        'message' => 'Deleted'
    ]);
}


public function downloadPdf(Request $request)
{
    $shopId = $request->auth_shop_id;

    $startDate = $request->start_date;
    $endDate = $request->end_date;

    $query = InvoiceBilling::where('shop_id', $shopId)
        ->where('source', 'Condition Sales');

    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('customer_name', 'like', '%' . $request->search . '%')
              ->orWhere('customer_mobile', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->status == 'pending') {
        $query->where('due', '>', 0);
    }

    if ($request->status == 'paid') {
        $query->where('due', 0);
    }

    if ($startDate && $endDate) {
        $query->whereBetween('created_at', [
            $startDate . ' 00:00:00',
            $endDate . ' 23:59:59'
        ]);
    }

    $sales = $query->orderBy('id', 'desc')->get();

    $sales->transform(function ($row, $index) {
        $items = is_array($row->items)
            ? $row->items
            : json_decode($row->items, true);

        $qty = 0;
        foreach ($items ?? [] as $item) {
            $qty += $item['qty'] ?? 0;
        }

        $row->sl = $index + 1;
        $row->qty = $qty;

        return $row;
    });
    $total = $sales->sum('total');
$totalPaid = $sales->sum('paid');
$totalDue = $sales->sum('due');

   $shop = User::where('shop_id', $shopId)
    ->where('role', 'owner')
    ->first();

   $pdf = PDF::loadView('pdf.cod-sale', [
    'sales' => $sales,
    'shop' => $shop,
    'startDate' => $startDate,
    'endDate' => $endDate,

    // ✅ ADD THESE
    'total' => $total,
    'totalPaid' => $totalPaid,
    'totalDue' => $totalDue
]);

    return $pdf->download('cod-sales.pdf');
}
}
