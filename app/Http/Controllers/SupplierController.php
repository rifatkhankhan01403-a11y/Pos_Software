<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SupplierController extends Controller
{
    // =========================
    // SUPPLIER PAGE
    // =========================
    function SupplierPage() {
        return view('pages.dashboard.supplier-page');
    }


    // =========================
    // CREATE SUPPLIER
    // =========================
    function CreateSupplier(Request $request) {

        $shopId = $request->auth_shop_id;

        $request->validate([
            'email' => 'nullable|email|max:255',
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:50',
            'address' => 'nullable|string',
            'note' => 'nullable|string',
            'img' => 'nullable|image|max:2048'
        ]);

        $img_url = null;

        if ($request->hasFile('img')) {
            $img = $request->file('img');
            $t = time();
            $file_name = $img->getClientOriginalName();
            $img_name = "{$t}-{$file_name}";
            $img_url = "uploads/suppliers/{$img_name}";
            $img->move(public_path('uploads/suppliers'), $img_name);
        }

        return Supplier::create([
            'shop_id' => $shopId,
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'mobile' => $request->input('mobile'),
            'address' => $request->input('address'),
            'note' => $request->input('note'),
            'img_url' => $img_url
        ]);
    }


    // =========================
    // LIST SUPPLIERS
    // =========================
    function ListSupplier(Request $request) {

        $shopId = $request->auth_shop_id;

        return Supplier::where('shop_id',$shopId)->get();
    }


    // =========================
    // GET SUPPLIER BY ID
    // =========================
    function SupplierByID(Request $request) {

        $shopId = $request->auth_shop_id;

        return Supplier::where('shop_id',$shopId)
            ->where('id', $request->input('id'))
            ->first();
    }


    // =========================
    // UPDATE SUPPLIER
    // =========================
    function UpdateSupplier(Request $request) {

        $shopId = $request->auth_shop_id;

        $request->validate([
            'id' => 'required|integer|exists:suppliers,id',
            'email' => 'nullable|email|max:255',
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:50',
            'address' => 'nullable|string',
            'note' => 'nullable|string',
            'img' => 'nullable|image|max:2048'
        ]);

        $supplier_id = $request->input('id');

        $supplier = Supplier::where('shop_id',$shopId)
            ->where('id',$supplier_id)
            ->firstOrFail();

        $data = [
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'mobile' => $request->input('mobile'),
            'address' => $request->input('address'),
            'note' => $request->input('note')
        ];

        if ($request->hasFile('img')) {

            $img = $request->file('img');
            $t = time();
            $file_name = $img->getClientOriginalName();
            $img_name = "{$t}-{$file_name}";
            $img_url = "uploads/suppliers/{$img_name}";
            $img->move(public_path('uploads/suppliers'), $img_name);

            // delete old image
            if ($supplier->img_url) {
                File::delete(public_path($supplier->img_url));
            }

            $data['img_url'] = $img_url;
        }

        return Supplier::where('shop_id',$shopId)
            ->where('id',$supplier_id)
            ->update($data);
    }


    // =========================
    // DELETE SUPPLIER
    // =========================
    function DeleteSupplier(Request $request) {

        $shopId = $request->auth_shop_id;

        $supplier_id = $request->input('id');

        $supplier = Supplier::where('shop_id',$shopId)
            ->where('id',$supplier_id)
            ->firstOrFail();

        if ($supplier->img_url) {
            File::delete(public_path($supplier->img_url));
        }

        return $supplier->delete();
    }
}
