<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRestaurentRequest;
use Illuminate\Http\Request;
use App\Models\Delivery;
use App\Models\Restaurant;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;


use App\Notifications\RestaurantAccountEmail;

class AdminController extends Controller
{
    //* Approuve  
    public function approvedDeliveryMan(Request $request)
    {
        $delivery =  Delivery::Where('id', $request->id);
        if ($delivery) {
            $delivery->update([
                "status" => $request->status
            ]);
            return dataToResponse('success', 'Succès ', 'la mise à jour a réussi', false, 200);
        }
    }
    //* Delete
    public function deleteDeliveryMan(Request $request)
    {
        $delivery =  Delivery::Where('id', $request->id);
        if ($delivery) {
            $delivery->delete();
            return dataToResponse('success', 'Succès ', 'la suppression est un succès', true, 200);
        }
    }

    //*Block
    public function blockDeliveryMan(Request $request)
    {
        $delivery =  Delivery::Where('id', $request->id);
        if ($delivery) {
            if ($request->blocked_at == true) {
                $delivery->update([
                    "blocked_at" => Carbon::now()
                ]);
                return dataToResponse('success', 'Succès ', 'livreur a été bloqué', true, 200);
            }
            if ($request->blocked_at == false) {
                $delivery->update([
                    "blocked_at" => null
                ]);
                return dataToResponse('success', 'Succès ', 'livreur a été débloqué', true, 200);
            }
        }
    }

    //* Add Restaurant 
    public function addRestaurant(StoreRestaurentRequest $request)
    {
        $restaurant = Restaurant::Create([
            'restaurant_name' => $request->restaurant_name,
            'email'           => $request->email,
            'password'        => Hash::make($request->password),
            'phone'           => $request->phone,
            'address'         => $request->address,
            'tarif'           => $request->tarif,
        ]);

        $restaurant->notify(new RestaurantAccountEmail(["password" => $request->password, "email" => $request->email]));

        return dataToResponse('success', 'Succès ', [
            'Restaurent Ajouté avec succés',
            'Un couriel contenant le mot de passe du restaurateur son été envoye à son adresse'
        ], true, 200);
    }
}
