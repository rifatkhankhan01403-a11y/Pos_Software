<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceBilling;

class SaleHistoryController extends Controller
{

public function saleHistory(Request $request)
{
    $query = InvoiceBilling::whereIn('source', ['Sell', 'Quick Sell']);

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

    $totalSales = InvoiceBilling::whereIn('source', ['Sell', 'Quick Sell'])
        ->sum('total');

    $sales = $query->latest()
        ->paginate(10)
        ->onEachSide(1)
        ->withQueryString();

    return view('pages.dashboard.sale-history-page', compact('sales', 'totalSales'));
}

public function deleteSale($id)
{
    InvoiceBilling::findOrFail($id)->delete();

    return redirect()->back()->with('success','Sale deleted successfully');
}

}
