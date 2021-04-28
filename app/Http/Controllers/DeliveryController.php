<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DeliveryRequest;


use App\Models\Delivery;

class DeliveryController extends Controller
{

    //* Update password
    //* Edit password
    public function editPassword(Request $request){
        try{
            $delivery = delivery::where('id', authIdFromGuard('delivery'))->first();
            if ($delivery){
                if (\Hash::check($request->old, $delivery->makeVisible(['password'])->password)){
                    if ($request->new == $request->cfm){
                        $delivery->update(['password' => \Hash::make($request->new)]);
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
            if (\Auth::guard('delivery')->user()->avatar != $request->avatar){
                $avatar =  storeUploaded(public_path() . '/images/avatars', $request->avatar);
            }
            else{
                $avatar = \Auth::guard('delivery')->user()->avatar;
            }
            
            if(
                Delivery::where('id', authIdFromGuard('delivery'))->update([
                    'first_name'   => $request->first_name,
                    'last_name'    => $request->last_name,
                    'phone_number' => $request->phone_number,
                    'email'        => $request->email,
                    'experience'   => (int)$request->experience,
                    'avatar'       => $avatar
                ])
            )
            return dataToResponse('success', 'Succès ', 'Mise à jour du profil réussie', false, 200);
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }
}
