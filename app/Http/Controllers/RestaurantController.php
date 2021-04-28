<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RequestRestaurant;

//Models
use App\Models\Restaurant;

use App\Notifications\NotifyRestaurantAccount;


class RestaurantController extends Controller
{
    //* Fetch Restaurants
    public function fetchRestaurants(){
        return
            response(
                Restaurant::orderBy('id', 'DESC')->get(),
                200
            );
    }

    //* Add Restaurant. 
    public function addRestaurant(RequestRestaurant $request){
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

    //* Edit Restaurant
    public function editRestaurant(RequestRestaurant $request){
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
    public function deleteRestaurant(Request $request){
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
    public function blockRestaurant(Request $request){
        try {
            if (Restaurant::where('id', $request->restaurant_id)->update(['blocked_at' => \Carbon\Carbon::now()]))
                return dataToResponse('success', 'Succès ', 'Bloquer avec succès ❌', true, 200);
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }

    //* unBlock Restaurant
    public function unblockRestaurant(Request $request){
        try {
            if (Restaurant::where('id', $request->restaurant_id)->update(['blocked_at' => null]))
                return dataToResponse('success', 'Succès ', 'Débloquer avec succès ✅', true, 200);
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }

    //* Edit password
    public function editPassword(Request $request){
        try{
            $restaurant = Restaurant::where('id', authIdFromGuard('restaurant'))->first();
            if ($restaurant){
                if (\Hash::check($request->old, $restaurant->makeVisible(['password'])->password)){
                    if ($request->new == $request->cfm){
                        $restaurant->update(['password' => \Hash::make($request->new)]);
                        return dataToResponse('success', 'Succès ', 'Mot de passe a été changé avec succès', false, 200);
                    }
                }
                return dataToResponse('error', 'Erreur ', 'L\'ancien mot de passe ne correspond pas', false, 422);
            }
        }catch(\Exception $e){
            handleLogs($e);
        }
    }

    //* Edit Profile Information
    public function editProfile(Request $request){
        try{
            //Avatar handler
            if (\Auth::guard('restaurant')->user()->logo != $request->logo){
                $logo =  storeUploaded(public_path() . '/images/restaurants_logo', $request->logo);
            }
            else{
                $logo = \Auth::guard('restaurant')->user()->logo;
            }
            
            if(
                Restaurant::where('id', authIdFromGuard('restaurant'))->update([
                    'name'         => $request->name,
                    'phone_number' => $request->phone_number,
                    'email'        => $request->email,
                    'address'      => $request->address,
                    'lat'          => $request->lat,
                    'lng'          => $request->lng,
                    'logo'        => $logo
                ])
            )
            return dataToResponse('success', 'Succès ', 'Mise à jour du profil réussie', false, 200);
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }
}
