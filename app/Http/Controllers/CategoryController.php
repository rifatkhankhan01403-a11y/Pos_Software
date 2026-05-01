<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    function CategoryPage()
    {
        return view('pages.dashboard.category-page');
    }

    // LIST
    function CategoryList(Request $request)
    {
        $shop_id = $request->auth_shop_id;

        return Category::where('shop_id', $shop_id)
            ->with('subCategories')
            ->get();
    }

    // CREATE CATEGORY + SUBCATEGORIES (ONE REQUEST)
    function CategoryCreate(Request $request){
        $shop_id = $request->auth_shop_id;

        // 1. Create Category
        $category = Category::create([
            'name' => $request->input('name'),
            'shop_id' => $shop_id
        ]);

        // 2. Get sub categories from request
        $subCategories = $request->input('sub_categories');

        // 3. Insert sub categories (if exists)
        if (!empty($subCategories)) {
            foreach ($subCategories as $sub) {
                SubCategory::create([
                    'name' => $sub,
                    'category_id' => $category->id,
                    'shop_id' => $shop_id
                ]);
            }
        }

        return response()->json($category, 201);
    }

    // DELETE CATEGORY
    function CategoryDelete(Request $request)
    {
        $shop_id = $request->auth_shop_id;

        return Category::where('id', $request->input('id'))
            ->where('shop_id', $shop_id)
            ->delete();
    }

    // GET BY ID
    function CategoryByID(Request $request)
    {
        $shop_id = $request->auth_shop_id;

        return Category::where('id', $request->input('id'))
            ->where('shop_id', $shop_id)
            ->with('subCategories')
            ->first();
    }

    // UPDATE CATEGORY ONLY
    function CategoryUpdate(Request $request)
    {
        $shop_id = $request->auth_shop_id;

        return Category::where('id', $request->input('id'))
            ->where('shop_id', $shop_id)
            ->update([
                'name' => $request->input('name')
            ]);
    }
}
