<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockAdd;
use Carbon\Carbon;
  use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;



class PurchaseBookController extends Controller
{
    public function purchaseBook(Request $request)
    {
        $shopId = $request->auth_shop_id;

        $query = StockAdd::where('shop_id',$shopId)
            ->where('source', 'Purchase');

        // SEARCH (supplier name / phone)
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('supplier_name', 'like', "%$search%")
                  ->orWhere('supplier_phone', 'like', "%$search%");
            });
        }

        // DATE FILTER
        if ($request->filled('start_date') && $request->filled('end_date')) {

            $query->whereBetween('created_at', [
                $request->start_date . " 00:00:00",
                $request->end_date . " 23:59:59"
            ]);
        }

        // TOTAL PURCHASE (SHOP BASED)
        $totalPurchase = StockAdd::where('shop_id',$shopId)
            ->where('source', 'Purchase')
            ->sum('total_cost');

        $purchases = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.dashboard.purchase-book-page', compact('purchases', 'totalPurchase'));
    }


    public function deletePurchase(Request $request, $id)
    {
        $shopId = $request->auth_shop_id;

        $purchase = StockAdd::where('shop_id',$shopId)
            ->where('id',$id)
            ->firstOrFail();

        $purchase->delete();

        return redirect()->back()->with('success', 'Purchase deleted successfully');
    }



public function downloadPdf(Request $request)
{
    $shopId = $request->auth_shop_id;

    // SHOP INFO
    $shop = User::where('shop_id', $shopId)->first();

    // PURCHASE QUERY
    $query = StockAdd::where('shop_id', $shopId)
        ->where('source', 'Purchase');

    // SEARCH
    if ($request->filled('search')) {

        $search = $request->search;

        $query->where(function ($q) use ($search) {

            $q->where('supplier_name', 'like', "%{$search}%")
              ->orWhere('supplier_phone', 'like', "%{$search}%");

        });
    }

    // DATE FILTER
    if (
        $request->filled('start_date') &&
        $request->filled('end_date')
    ) {

        $query->whereBetween('created_at', [

            $request->start_date . ' 00:00:00',
            $request->end_date . ' 23:59:59'

        ]);
    }

    // GET DATA
    $purchases = $query
         ->oldest()
        ->get();

    // FORMAT DATE
    $purchases->transform(function ($item) {

        $item->formatted_created_at = Carbon::parse(
            $item->created_at
        )->format('d M Y, h:i A');

        return $item;
    });

    // SUMMARY
    $totalPurchase = $purchases->sum('total_cost');

    // PDF
    $pdf = Pdf::loadView('pdf.purchase', [

        'purchases'      => $purchases,
        'shop'           => $shop,
        'totalPurchase'  => $totalPurchase,
        'startDate'      => $request->start_date,
        'endDate'        => $request->end_date,
        'search'         => $request->search

    ])->setPaper('a4');

    return $pdf->download('purchase-report.pdf');
}
}
