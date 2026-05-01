<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class ProductController extends Controller
{

    /* =========================
       PRODUCT PAGE
    ========================= */
    function ProductPage(): View {
        return view('pages.dashboard.product-page');
    }


    /* =========================
       CREATE PRODUCT
    ========================= */
    function CreateProduct(Request $request)
    {
        $shopId = $request->auth_shop_id;

        $img_url = null;

        if ($request->hasFile('img')) {
            $img = $request->file('img');
            $t = time();
            $file_name = $img->getClientOriginalName();
            $img_name = "{$t}-{$file_name}";
            $img_url = "uploads/{$img_name}";
            $img->move(public_path('uploads'), $img_name);
        }

        return Product::create([
            'shop_id' => $shopId,
            'name' => $request->input('name'),
            'quantity' => $request->input('quantity'),
            'buy_price' => $request->input('buy_price'),
            'sell_price' => $request->input('sell_price'),
            'note' => $request->input('note'),
            'img_url' => $img_url,
            'category_id' => $request->input('category_id'),
            'subcategory_id' => $request->input('subcategory_id'),
        ]);
    }


    /* =========================
       PRODUCT LIST
    ========================= */
    function ProductList(Request $request)
    {
        return Product::with('category')
            ->where('shop_id', $request->auth_shop_id)
            ->orderBy('id', 'desc')
            ->get();
    }


    /* =========================
       GET PRODUCT BY ID
    ========================= */
    function ProductByID(Request $request)
    {
        return Product::where('id', $request->id)
            ->where('shop_id', $request->auth_shop_id)
            ->first();
    }


    /* =========================
       UPDATE PRODUCT
    ========================= */
    function UpdateProduct(Request $request)
    {
        $product = Product::where('id', $request->id)
            ->where('shop_id', $request->auth_shop_id)
            ->first();

        if (!$product) {
            return 0;
        }

        $img_url = $product->img_url;

        if ($request->hasFile('img')) {

            $img = $request->file('img');
            $t = time();
            $file_name = $img->getClientOriginalName();
            $img_name = "{$t}-{$file_name}";
            $img_url = "uploads/{$img_name}";
            $img->move(public_path('uploads'), $img_name);

            if ($product->img_url && File::exists(public_path($product->img_url))) {
                File::delete(public_path($product->img_url));
            }
        }

        return Product::where('id', $request->id)
            ->where('shop_id', $request->auth_shop_id)
            ->update([
                'name' => $request->input('name'),
                'quantity' => $request->input('quantity'),
                'buy_price' => $request->input('buy_price'),
                'sell_price' => $request->input('sell_price'),
                'note' => $request->input('note'),
                'img_url' => $img_url,
                'category_id' => $request->input('category_id'),
                'subcategory_id' => $request->input('subcategory_id'),
            ]);
    }


    /* =========================
       DELETE PRODUCT
    ========================= */
    function DeleteProduct(Request $request)
    {
        $product = Product::where('id', $request->id)
            ->where('shop_id', $request->auth_shop_id)
            ->first();

        if ($product) {

            if ($product->img_url && File::exists(public_path($product->img_url))) {
                File::delete(public_path($product->img_url));
            }

            return $product->delete();
        }

        return 0;
    }

  public function searchProduct(Request $request)
{
    $shopId = $request->auth_shop_id;
    $query = $request->get('keyword', '');

    $data = Product::where('shop_id', $shopId)
        ->where('name', 'like', "%{$query}%")
        ->limit(10)
        ->get()
        ->map(function($p){
            return [
                'id' => $p->id,
                'name' => $p->name,
                'quantity' => $p->quantity,
                'price' => $p->price ?? $p->sell_price ?? 0 // ✅ SAFE
            ];
        });

    return response()->json($data);
}
}
