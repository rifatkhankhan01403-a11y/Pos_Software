<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{

    function CustomerPage(): View
    {
        return view('pages.dashboard.customer-page');
    }


    // CREATE CUSTOMER
    function CustomerCreate(Request $request)
    {
        $customer = Customer::create([
            'name'   => $request->name,
            'email'  => $request->email,
            'mobile' => $request->mobile
        ]);

        return response()->json([
            'status' => 'success',
            'data'   => $customer
        ]);
    }


    // LIST CUSTOMERS (ONLY SAME SHOP)
    function CustomerList(Request $request)
    {
        return Customer::where('shop_id', $request->auth_shop_id)
            ->orderBy('id', 'desc')
            ->get();
    }


    // DELETE CUSTOMER
  function CustomerDelete(Request $request)
{
    $deleted = Customer::where('id', $request->id)
        ->where('shop_id', $request->auth_shop_id)
        ->delete();

    return response()->json([
        'status' => 'success',
        'deleted' => $deleted
    ]);
}


    // GET CUSTOMER BY ID
    function CustomerByID(Request $request)
    {
        return Customer::where('id', $request->id)
            ->where('shop_id', $request->auth_shop_id)
            ->first();
    }


    // UPDATE CUSTOMER
    function CustomerUpdate(Request $request)
    {
        return Customer::where('id', $request->id)
            ->where('shop_id', $request->auth_shop_id)
            ->update([
                'name'   => $request->name,
                'email'  => $request->email,
                'mobile' => $request->mobile
            ]);
    }
public function searchCustomer(Request $request)
{
    $shopId = $request->auth_shop_id;

    $query = $request->get('keyword', '');

    $data = Customer::where('shop_id', $shopId)
        ->where(function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('mobile', 'like', "%{$query}%");
        })
        ->limit(10)
        ->get();

    return response()->json($data);
}


}
