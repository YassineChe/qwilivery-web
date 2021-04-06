<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Variant;


class MenuController extends Controller
{
    //* Fetch menu of restarant
    public function fetchMenu(Request $request)
    {
        return Category::with(['variant'])->Where('id', authIdFromGuard(getConnectedGuard()))->get();
    }
    //* Create Category 
    public function createCategory(Request $request)
    {
        Category::Create([
            'restaurant_id' => authIdFromGuard(getConnectedGuard()),
            'name'          => $request->name
        ]);
    }

    //* Create Variant 
    public function createVariant(Request $request)
    {
        Variant::Create([
            'category_id' => authIdFromGuard(getConnectedGuard()),
            'name'        => $request->name,
            'size'        => $request->size,
            'price'       => $request->price,
        ]);
    }

    //* Update Category 

    public function updateCategory(Request $request)
    {
        Category::where('id', $request->id)->update([
            'name'        => $request->name,
            'size'        => $request->size,
            'price'       => $request->price,
        ]);
    }

    //* Update Variant 
    public function updateVariant(Request $request)
    {
        Variant::where('id', $request->id)->upadate([
            'name'        => $request->name,
            'size'        => $request->size,
            'price'       => $request->price,
        ]);
    }
    //* Delete Category 

    public function deleteCategory(Request $request)
    {
        Category::Where('id', $request->id)->delete();
    }

    //* Delete Variant 

    public function deleteVariant(Request $request)
    {
        Variant::Where('id', $request->id)->delete();
    }
}
