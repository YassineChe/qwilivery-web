<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//Models
use App\Models\Category;
use App\Models\Variant;
//Request
use App\Http\Requests\MenuRequest;
use App\Http\Requests\VariantRequest;

class MenuController extends Controller
{
    //* Fetch Categories of restarant
    public function fetchCategories(Request $request)
    {
        try {
            return response(
                Category::where('restaurant_id', authIdFromGuard(getConnectedGuard()))
                    ->with(['variants'])
                    ->get()
                , 200
            );
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }

    //* Fetch Variants of restarant
    public function fetchVariants(Request $request)
    {
        try {
            return response(Variant::where('id', authIdFromGuard('restaurant'))->get(), 200);
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }

    //* Add Category 
    public function addCategory(MenuRequest $request)
    {
        try {
            if (
                Category::Create([
                    'restaurant_id' => authIdFromGuard(getConnectedGuard()),
                    'name'          => $request->name,
                    'description'   => $request->description,
                ])
            )
                return dataToResponse('success', 'Succès', ['La catégorie a été ajoutée avec succès 👍'], 200);
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }

    //* Add Variant 
    public function addVariant(VariantRequest $request)
    {
        try {

            $avatar = storeUploaded(public_path() . '/images/variants', $request->avatar);

            Variant::Create([
                'name'        => $request->name,
                'price'       => $request->price,
                'photo'       => $avatar,
                'description' => $request->description,
                'category_id' => $request->category_id,
            ]);

            return dataToResponse('success', 'Succès', ['L\'article a été ajoutée avec succès 👍'], 200);

        } catch (\Exception $e) {
            handleLogs($e);
        }
    }

    //* Update Category 

    public function editCategory(Request $request)
    {
        try {
            Category::where('id', $request->id)->update([
                'name'        => $request->name,
                'size'        => $request->size,
                'price'       => $request->price,
            ]);
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }

    //* Update Variant 
    public function editVariant(Request $request)
    {
        try {
            
            //Prepare data
            $dataToEdit = [
                'name'        => $request->name,
                'price'       => $request->price,
                'description' => $request->description,
            ];

            //Check is photo updated
            if ($request->avatar != '' && $request->avatar != 'undefined' ){
                $avatar = storeUploaded(public_path() . '/images/variants', $request->avatar);
                $dataToEdit['photo'] = $avatar;
            }
            
            if (Variant::where('id', $request->id)->update($dataToEdit))
                return dataToResponse('success', 'Succès', ['L\'article modifié avec succès 👍'], 200);

        } catch (\Exception $e) {
            handleLogs($e);
        }
    }

    //* Delete Category 
    public function deleteCategory($category_id)
    {
        try {
            Category::where('id', (int)$category_id)->delete();
            Variant::where('category_id', (int)$category_id)->delete();
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }

    //* Delete Variant 
    public function deleteVariant($variant_id)
    {
        try {
            if(Variant::where('id', $variant_id)->delete())
                return dataToResponse('success', 'Succès', ['L\'article supprimé avec succès'], 200);
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }
}
