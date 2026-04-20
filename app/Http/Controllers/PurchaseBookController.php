<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockAdd;
use Carbon\Carbon;

class PurchaseBookController extends Controller
{
    public function purchaseBook(Request $request)
    {
        $query = StockAdd::where('source', 'Purchase');

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

        $totalPurchase = StockAdd::where('source', 'Purchase')->sum('total_cost');

        $purchases = $query->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.dashboard.purchase-book-page', compact('purchases', 'totalPurchase'));
    }

    public function deletePurchase($id)
    {
        StockAdd::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Purchase deleted successfully');
    }
}
