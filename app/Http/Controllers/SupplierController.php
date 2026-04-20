<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SupplierController extends Controller
{
    // Show Supplier Page
    function SupplierPage() {
        return view('pages.dashboard.supplier-page');
    }

    // Create Supplier
    function CreateSupplier(Request $request) {

        // Backend validation: must
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
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'mobile' => $request->input('mobile'),
            'address' => $request->input('address'),
            'note' => $request->input('note'),
            'img_url' => $img_url
        ]);
    }

    // List Suppliers
    function ListSupplier(Request $request) {
        return Supplier::all();
    }

    // Get Supplier by ID
    function SupplierByID(Request $request) {
        return Supplier::where('id', $request->input('id'))->first();
    }

    // Update Supplier
    function UpdateSupplier(Request $request) {
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

            // Delete old image
            $old_path = $request->input('file_path');
            if ($old_path) File::delete(public_path($old_path));

            $data['img_url'] = $img_url;
        }

        return Supplier::where('id', $supplier_id)->update($data);
    }

    // Delete Supplier
    function DeleteSupplier(Request $request) {
        $supplier_id = $request->input('id');
        $file_path = $request->input('file_path');

        if ($file_path) File::delete(public_path($file_path));

        return Supplier::where('id', $supplier_id)->delete();
    }
}
