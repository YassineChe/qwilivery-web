<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meal;

class MealController extends Controller
{
    public function fetchMeals(){
        try{
            return response(
                Meal::all(), 
            200);
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }
}
