<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Variant;

class MenuController extends Controller
{
    //* Fetch Categories of restarant
    public function fetchCategories(Request $request)
    {
        return Category::Where('id', authIdFromGuard(getConnectedGuard()))->get();
    }

    //* Fetch Variants of restarant
    public function fetchVariants(Request $request)
    {
        return Variant::Where('id', authIdFromGuard(getConnectedGuard()))->get();
    }

    //* Create Category 
    public function createCategory(Request $request)
    {
        $request->validate([
            'type' => "required",
            'name' => 'required|unique:categories|min:2|max:255',
            'description' => 'required|unique:categories|min:20|max:2550',
        ]);

        $category =  Category::Create([
            'restaurant_id' => authIdFromGuard(getConnectedGuard()),
            'name'          => $request->name,
            'description'   => $request->description,
            'type'          => $request->type,

        ]);

        return dataToResponse(
            'success',
            'Succès ',
            [
                "msg" => 'La catégorie a été ajoutée avec succès 👍',
                "data" => $category
            ],
            true,
            200
        );
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
