<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//Models
use App\Models\Category;
use App\Models\Variant;
//Request
use App\Http\Requests\MenuRequest;

class MenuController extends Controller
{
    //* Fetch Categories of restarant
    public function fetchCategories(Request $request){
        try{
            return Category::where('restaurant_id', authIdFromGuard(getConnectedGuard()))->get();
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }

    //* Fetch Variants of restarant
    public function fetchVariants(Request $request){
        try{
            return Variant::where('id', authIdFromGuard(getConnectedGuard()))->get();
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }

    //* Add Category 
    public function addCategory(MenuRequest $request)
    {
        try{
            if(
                Category::Create([
                    'restaurant_id' => authIdFromGuard(getConnectedGuard()),
                    'name'          => $request->name,
                    'description'   => $request->description,
                ])
            )
            return dataToResponse('success', 'Succès', ["msg" => 'La catégorie a été ajoutée avec succès 👍',], true, 200);
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }

    //* Add Variant 
    public function addVariant(Request $request)
    {
        try{
            Variant::Create([
                'category_id' => authIdFromGuard(getConnectedGuard()),
                'name'        => $request->name,
                'size'        => $request->size,
                'price'       => $request->price,
            ]); 
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }

    //* Update Category 

    public function editCategory(Request $request)
    {
        try{
            Category::where('id', $request->id)->update([
                'name'        => $request->name,
                'size'        => $request->size,
                'price'       => $request->price,
            ]);
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }

    //* Update Variant 
    public function editVariant(Request $request)
    {
        try{
            Variant::where('id', $request->id)->upadate([
                'name'        => $request->name,
                'size'        => $request->size,
                'price'       => $request->price,
            ]);
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }

    //* Delete Category 
    public function deleteCategory(Request $request){
        try{
            Category::where('id', $request->id)->delete();
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }

    //* Delete Variant 
    public function deleteVariant(Request $request){
        try{
            Variant::where('id', $request->id)->delete();
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }
}
