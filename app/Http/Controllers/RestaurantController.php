<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Restaurant;

class RestaurantController extends Controller
{

    //* Edit Restaurant
    public function editRestaurant(Request $request)
    {
        if (
            Restaurant::where('id', (int)$request->id)->update([
                'name'         => $request->name,
                'email'        => $request->email,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'rate'    => $request->rate,
                'lat'     => $request->lat,
                'lng'     => $request->lng,
            ])
        )
            return dataToResponse('success', 'Succès ', 'Modifié avec succès 👍', true, 200);
    }

    //* Delete Restaurant
    public function deleteRestaurant(Request $request)
    {
        try {
            $restaurant = Restaurant::where('id', $request->restaurant_id)->first();
            if ($restaurant) {
                if ($restaurant->delete())
                    return dataToResponse('success', 'Succès ', 'Supprimé avec succès 👍', true, 200);
            }
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }

    //* Block Restaurant
    public function blockRestaurant(Request $request)
    {
        try {
            if (Restaurant::where('id', $request->restaurant_id)->update(['blocked_at' => \Carbon\Carbon::now()]))
                return dataToResponse('success', 'Succès ', 'Bloquer avec succès ❌', true, 200);
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }

    //* unBlock Restaurant
    public function unblockRestaurant(Request $request)
    {
        try {
            if (Restaurant::where('id', $request->restaurant_id)->update(['blocked_at' => null]))
                return dataToResponse('success', 'Succès ', 'Débloquer avec succès ✅', true, 200);
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }
}
