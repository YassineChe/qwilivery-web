<?php

namespace App\Http\Controllers;

//Models
use App\Models\Delivery;
use App\Models\Restaurant;
//Requests
use Illuminate\Http\Request;
use App\Http\Requests\RequestRestaurant;
//Notifications
use App\Notifications\NotifyRestaurantAccount;

class AdminController extends Controller
{
    //* Approuve.  
    public function approvedDeliveryMan(Request $request)
    {
        $delivery =  Delivery::where('id', $request->delivery_id)->first();
        if ($delivery) {
            $delivery->update(['status' => 1]);
            //Return data to front
            return dataToResponse('success', 'Succès ', 'La mise à jour a réussi', false, 200);
        }
    }
    //* Delete.
    public function deleteDeliveryMan(Request $request)
    {
        $delivery =  Delivery::where('id', $request->delivery_id)->first();
        if ($delivery) {
            $delivery->delete();
            return dataToResponse('success', 'Succès ', 'La suppression est un succès', true, 200);
        }
    }

    //* Block.
    public function blockDeliveryMan(Request $request)
    {
        $delivery =  Delivery::where('id', $request->delivery_id)->first();

        if ($delivery) {
            if ($delivery->blocked_at == null) {

                $delivery->update([
                    "blocked_at" => \Carbon\Carbon::now()
                ]);

                return dataToResponse('success', 'Succès ', 'Livreur a été bloqué', true, 200);
            }
            if ($delivery->blocked_at != null) {
                $delivery->update([
                    "blocked_at" => null
                ]);
                return dataToResponse('success', 'Succès ', 'Livreur a été débloqué', true, 200);
            }
        }
    }

    //* Fetch Restaurants
    public function fetchRestaurants()
    {
        return
            response(
                Restaurant::orderBy('id', 'DESC')->get(),
                200
            );
    }

    //* Add Restaurant. 
    public function addRestaurant(RequestRestaurant $request)
    {
        try {
            //Generate random password
            $generetedPassword = \Str::random(6);
            //Store data
            $restaurant = Restaurant::Create([
                'name'         => $request->name,
                'email'        => $request->email,
                'password'     => \Hash::make($generetedPassword),
                'phone_number' => $request->phone_number,
                'address'      => $request->address,
                'rate'         => $request->rate,
                'lat'          => $request->lat,
                'lng'          => $request->lng,
            ]);

            //Notify Restau
            if ($restaurant)
                $restaurant->notify(new NotifyRestaurantAccount(["password" => $generetedPassword, "email" => $request->email]));

            return dataToResponse('success', 'Succès ', 'Un E-mail a été envoyé au restaurant avec les informations d\'identification 👍', true, 200);
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }

    //* Fetch delivery men.
    public function fetchDeliveries()
    {
        return
            response(
                Delivery::orderBy('id', 'DESC')
                    ->get(),
                200
            );
    }
}
