<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceBilling;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;

class SaleHistoryController extends Controller
{

    /* =========================
       SALE HISTORY LIST
    ========================= */
    public function saleHistory(Request $request)
    {
        $shopId = $request->auth_shop_id;

        $query = InvoiceBilling::where('shop_id',$shopId)
            ->whereIn('source', ['Sell', 'Quick Sell', 'Condition Sales']);

        // SEARCH
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%$search%")
                  ->orWhere('customer_mobile', 'like', "%$search%");
            });
        }

        // DATE RANGE FILTER
        if ($request->filled('start_date') && $request->filled('end_date')) {

            $query->whereBetween('created_at', [
                $request->start_date . " 00:00:00",
                $request->end_date . " 23:59:59"
            ]);
        }

        // TOTAL SALES (SHOP ONLY)
        $totalSales = InvoiceBilling::where('shop_id',$shopId)
            ->whereIn('source', ['Sell', 'Quick Sell'])
            ->sum('total');

        $sales = $query
            ->latest()
            ->paginate(10)
            ->onEachSide(1)
            ->withQueryString();

        return view('pages.dashboard.sale-history-page', compact('sales', 'totalSales'));
    }


    /* =========================
       DELETE SALE
    ========================= */
    public function deleteSale(Request $request, $id)
    {
        $shopId = $request->auth_shop_id;

        $sale = InvoiceBilling::where('shop_id',$shopId)
            ->where('id',$id)
            ->firstOrFail();

        $sale->delete();

        return redirect()->back()->with('success','Sale deleted successfully');
    }

public function downloadPdf(Request $request)
{
    $shopId = $request->auth_shop_id;

    $query = InvoiceBilling::where('shop_id', $shopId)
        ->whereIn('source', ['Sell', 'Quick Sell', 'Condition Sales']);

    // SEARCH
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('customer_name', 'like', "%$search%")
              ->orWhere('customer_mobile', 'like', "%$search%");
        });
    }

    // DATE FILTER
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('created_at', [
            $request->start_date . " 00:00:00",
            $request->end_date . " 23:59:59"
        ]);
    }

    $sales = $query->latest()->get();

    $shop = \App\Models\User::where('shop_id', $shopId)->first();

    $totalSales = 0;
    $totalQty = 0;

    $transactions = $sales->map(function ($item, $index) use (&$totalSales, &$totalQty) {

        $items = is_string($item->items)
            ? json_decode($item->items, true)
            : ($item->items ?? []);

        $rowQty = collect($items)->sum('qty');

        // accumulate totals
        $totalSales += $item->total;
        $totalQty += $rowQty;

        return [
            'sl' => $index + 1,
            'date' => Carbon::parse($item->created_at)->format('d M Y, h:i A'),
            'customer' => $item->customer_name,
            'mobile' => $item->customer_mobile,
            'items' => $items,
            'qty' => $rowQty,
            'amount' => $item->total,
            'source' => $item->source
        ];
    });

    $pdf = Pdf::loadView('pdf.sales-report', [
        'transactions' => $transactions,
        'shop' => $shop,
        'totalSales' => $totalSales,
        'totalQty' => $totalQty,
        'startDate' => $request->start_date,
        'endDate' => $request->end_date
    ])->setPaper('a4');

   return $pdf->download('sales-report.pdf');
}

}
