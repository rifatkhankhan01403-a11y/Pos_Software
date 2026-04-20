<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class ProductController extends Controller
{

    function ProductPage(): View {
        return view('pages.dashboard.product-page');
    }


    // ✅ CREATE PRODUCT
    function CreateProduct(Request $request)
    {
        $user_id = $request->header('id');

        $img_url = null;

        if ($request->hasFile('img')) {
            $img = $request->file('img');
            $t = time();
            $file_name = $img->getClientOriginalName();
            $img_name = "{$user_id}-{$t}-{$file_name}";
            $img_url = "uploads/{$img_name}";
            $img->move(public_path('uploads'), $img_name);
        }

        return Product::create([
            'name' => $request->input('name'),
           // 'unit' => $request->input('unit'),
            'quantity' => $request->input('quantity'),
            'buy_price' => $request->input('buy_price'),
            'sell_price' => $request->input('sell_price'),
            'note' => $request->input('note'),
            'img_url' => $img_url,
            'category_id' => $request->input('category_id'),
            'subcategory_id' => $request->input('subcategory_id'),
            'user_id' => $user_id
        ]);
    }


    // ✅ DELETE PRODUCT
    function DeleteProduct(Request $request)
    {
        $user_id = $request->header('id');
        $product_id = $request->input('id');

        // get product first
        $product = Product::where('id', $product_id)
            ->where('user_id', $user_id)
            ->first();

        if ($product) {
            // delete image if exists
            if ($product->img_url && File::exists(public_path($product->img_url))) {
                File::delete(public_path($product->img_url));
            }

            return $product->delete();
        }

        return 0;
    }


    // ✅ GET SINGLE PRODUCT
    function ProductByID(Request $request)
    {
        $user_id = $request->header('id');
        $product_id = $request->input('id');

        return Product::where('id', $product_id)
            ->where('user_id', $user_id)
            ->first();
    }


    // ✅ PRODUCT LIST
    function ProductList(Request $request)
    {
        $user_id = $request->header('id');

    return Product::with('category')
    ->where('user_id', $user_id)
    ->get();
    }


    // ✅ UPDATE PRODUCT
    function UpdateProduct(Request $request)
    {
        $user_id = $request->header('id');
        $product_id = $request->input('id');

        $product = Product::where('id', $product_id)
            ->where('user_id', $user_id)
            ->first();

        if (!$product) {
            return 0;
        }

        $img_url = $product->img_url;

        // if new image uploaded
        if ($request->hasFile('img')) {

            $img = $request->file('img');
            $t = time();
            $file_name = $img->getClientOriginalName();
            $img_name = "{$user_id}-{$t}-{$file_name}";
            $img_url = "uploads/{$img_name}";
            $img->move(public_path('uploads'), $img_name);

            // delete old image
            if ($product->img_url && File::exists(public_path($product->img_url))) {
                File::delete(public_path($product->img_url));
            }
        }

        return Product::where('id', $product_id)
            ->where('user_id', $user_id)
            ->update([
                'name' => $request->input('name'),
               // 'unit' => $request->input('unit'),
                'quantity' => $request->input('quantity'),
                'buy_price' => $request->input('buy_price'),
                'sell_price' => $request->input('sell_price'),
                'note' => $request->input('note'),
                'img_url' => $img_url,
                'category_id' => $request->input('category_id'),
                'subcategory_id' => $request->input('subcategory_id'),
            ]);
    }
}
