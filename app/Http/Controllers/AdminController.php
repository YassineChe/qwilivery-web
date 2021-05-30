<?php

namespace App\Http\Controllers;

//Models
use App\Models\Delivery;
use App\Models\PreOrder;
use App\Models\Admin;
use App\Models\Restaurant;
//Requests
use Illuminate\Http\Request;
use App\Http\Requests\DeliveryRequest;
//Notifications
use App\Notifications\NotifyDeliveryAccount;

class AdminController extends Controller
{

    //* Edit Profile Information
    public function editProfile(Request $request){
        try{
            //Avatar handler
            if (\Auth::guard('admin')->user()->avatar != $request->avatar){
                $avatar =  storeUploaded(public_path() . '/images/avatars', $request->avatar);
            }
            else{
                $avatar = \Auth::guard('admin')->user()->avatar;
            }
            
            if(
                Admin::where('id', authIdFromGuard('admin'))->update([
                    'first_name'   => $request->first_name,
                    'last_name'    => $request->last_name,
                    'phone_number' => $request->phone_number,
                    'email'        => $request->email,
                    'avatar'       => $avatar
                ])
            )
            return dataToResponse('success', 'Succès ', ['Mise à jour du profil réussie'], 200);
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }

    //* Edit password
    public function editPassword(Request $request){
        try{
            $admin = Admin::where('id', authIdFromGuard('admin'))->first();
            if ($admin){
                if (\Hash::check($request->old, $admin->makeVisible(['password'])->password)){
                    if ($request->new == $request->cfm){
                        $admin->update(['password' => \Hash::make($request->new)]);
                        return dataToResponse('success', 'Succès ', ['Mot de passe a été changé avec succès'], 200);
                    }
                }
                return dataToResponse('error', 'Erreur ', ['L\'ancien mot de passe ne correspond pas'], 422);
            }
        }catch(\Exception $e){
            handleLogs($e);
        }
    }

    //* Fetch restaurant and deliveries
    public function fetchRestaurantsAndDeliveries(){
        try{
            $restaurants = collect(self::fetchRestaurants());
            $deliveries  = collect(self::fetchDeliveries());

            //Combiane them here
            return response($restaurants->merge($deliveries), 200);
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }

    public function fetchDeliveries(){
        try{
            return Delivery::select('id', 'first_name', 'last_name', 'avatar', \DB::raw("'delivery' as type"))
                    ->take(5)
                    ->get();
        }
        catch(\Exception $e){
            handleLogs($e);
        }              
    }

    public function fetchRestaurants(){
        try{
            return Restaurant::select('id', 'name', 'avatar', \DB::raw("'restaurant' as type"))
                    ->take(5)
                    ->get();
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }

}
