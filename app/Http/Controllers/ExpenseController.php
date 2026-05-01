<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
class ExpenseController extends Controller
{

    /* =========================
       EXPENSE PAGE
    ========================= */
    function ExpensePage(){
        return view('pages.dashboard.expense-page');
    }


    /* =========================
       EXPENSE LIST
    ========================= */
   function ExpenseList(Request $request)
{
    $shopId = $request->auth_shop_id;

    $query = Expense::where('shop_id', $shopId);

    // ================= ONLY APPLY FILTER IF DATE EXISTS =================
    if ($request->start_date && $request->end_date) {

        $query->whereBetween('date', [
            $request->start_date,
            $request->end_date
        ]);
    }

    return $query->orderBy('id', 'desc')->get();
}


    /* =========================
       CREATE EXPENSE
    ========================= */
    function CreateExpense(Request $request){

        $shopId = $request->auth_shop_id;

        $data = Expense::create([
            'shop_id' => $shopId,
            'date' => $request->date,
            'category' => $request->category,
            'amount' => $request->amount,
            'note' => $request->note
        ]);

        return response()->json($data,201);

    }


    /* =========================
       EXPENSE BY ID
    ========================= */
    function ExpenseByID(Request $request){

        $shopId = $request->auth_shop_id;

        return Expense::where('shop_id',$shopId)
            ->where('id',$request->id)
            ->first();

    }


    /* =========================
       UPDATE EXPENSE
    ========================= */
    function UpdateExpense(Request $request){

        $shopId = $request->auth_shop_id;

        return Expense::where('shop_id',$shopId)
            ->where('id',$request->id)
            ->update([
                'date'=>$request->date,
                'category'=>$request->category,
                'amount'=>$request->amount,
                'note'=>$request->note
            ]);

    }


    /* =========================
       DELETE EXPENSE
    ========================= */
    function DeleteExpense(Request $request){

        $shopId = $request->auth_shop_id;

        return Expense::where('shop_id',$shopId)
            ->where('id',$request->id)
            ->delete();

    }




public function downloadExpensePdf(Request $request)
{
    $shopId = $request->auth_shop_id;

    $startDate = $request->start_date;
    $endDate = $request->end_date;

    // SHOP INFO
    $shop = User::where('shop_id', $shopId)
        ->where('role', 'owner')
        ->first();

    $query = Expense::where('shop_id', $shopId);

    if ($startDate && $endDate) {
        $query->whereBetween('date', [$startDate, $endDate]);
    }

    $expenses = $query->orderBy('date', 'desc')->get();

    // SUMMARY
    $total = $expenses->sum('amount');

    $pdf = Pdf::loadView('pdf.expense', [
        'expenses' => $expenses,
        'shop' => $shop,
        'total' => $total,
        'startDate' => $startDate,
        'endDate' => $endDate
    ])->setPaper('a4');

    return $pdf->download('expense-report.pdf');
}

}
