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
        $user_id = $request->header('id');

        return Category::where('user_id', $user_id)
            ->with('subCategories')
            ->get();
    }

    // CREATE CATEGORY + SUBCATEGORIES (ONE REQUEST)
  function CategoryCreate(Request $request){
    $user_id = $request->header('id');

    // 1. Create Category
    $category = Category::create([
        'name' => $request->input('name'),
        'user_id' => $user_id
    ]);

    // 2. Get sub categories from request
    $subCategories = $request->input('sub_categories');

    // 3. Insert sub categories (if exists)
    if (!empty($subCategories)) {
        foreach ($subCategories as $sub) {
            SubCategory::create([
                'name' => $sub,
                'category_id' => $category->id,
                'user_id' => $user_id
            ]);
        }
    }

    return response()->json($category, 201);
}

    // DELETE CATEGORY
    function CategoryDelete(Request $request)
    {
        $user_id = $request->header('id');

        return Category::where('id', $request->input('id'))
            ->where('user_id', $user_id)
            ->delete();
    }

    // GET BY ID
    function CategoryByID(Request $request)
    {
        $user_id = $request->header('id');

        return Category::where('id', $request->input('id'))
            ->where('user_id', $user_id)
            ->with('subCategories')
            ->first();
    }

    // UPDATE CATEGORY ONLY
    function CategoryUpdate(Request $request)
    {
        $user_id = $request->header('id');

        return Category::where('id', $request->input('id'))
            ->where('user_id', $user_id)
            ->update([
                'name' => $request->input('name')
            ]);
    }
}
