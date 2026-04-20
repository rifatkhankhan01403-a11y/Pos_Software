<?php
namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{

    function ExpensePage(){
        return view('pages.dashboard.expense-page');
    }


    function ExpenseList(){

        return Expense::orderBy('id','desc')->get();

    }


    function CreateExpense(Request $request){

        $data=Expense::create([
            'date'=>$request->date,
            'category'=>$request->category,
            'amount'=>$request->amount,
            'note'=>$request->note
        ]);

        return response()->json($data,201);

    }


    function ExpenseByID(Request $request){

        return Expense::where('id',$request->id)->first();

    }


    function UpdateExpense(Request $request){

        return Expense::where('id',$request->id)->update([
            'date'=>$request->date,
            'category'=>$request->category,
            'amount'=>$request->amount,
            'note'=>$request->note
        ]);

    }


    function DeleteExpense(Request $request){

        return Expense::where('id',$request->id)->delete();

    }

}
