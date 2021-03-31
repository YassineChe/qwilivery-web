<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//Models
use App\Models\Restaurant;

class RestaurantController extends Controller
{

    //* Delete Restaurant
    public function deleteRestaurant(Request $request){
        try{
            $restaurant = Restaurant::where('id', $request->restaurant_id)->first();
            if ($restaurant){
                if( $restaurant->delete() )
                    return dataToResponse('success', 'Succès ', 'Supprimé avec succès 👍', true, 200);
            }
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }

    //* Block Restaurant
    public function blockRestaurant(Request $request){
        try{
            if( Restaurant::where('id', $request->restaurant_id)->update(['blocked_at' => \Carbon\Carbon::now()]) )
                return dataToResponse('success', 'Succès ', 'Bloquer avec succès ❌', true, 200);
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }

    //* unBlock Restaurant
    public function unblockRestaurant(Request $request){
        try{
            if( Restaurant::where('id', $request->restaurant_id)->update(['blocked_at' => null]) )
                return dataToResponse('success', 'Succès ', 'Débloquer avec succès ✅', true, 200);
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }

}
