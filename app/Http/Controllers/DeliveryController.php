<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DeliveryRequest;
use App\Models\Delivery;
use App\Models\PreOrder;
use App\Models\ExpressDelivery;
//Notifications
use App\Notifications\NotifyAccountApproved;
use App\Notifications\NotifyDeliveryAccount;

class DeliveryController extends Controller
{
    //* Edit password
    public function editPassword(Request $request){
        try{
            $delivery = Delivery::where('id', authIdFromGuard('delivery'))->first();
            if ($delivery){
                if (\Hash::check($request->old, $delivery->makeVisible(['password'])->password)){
                    if ($request->new == $request->cfm){
                        $delivery->update(['password' => \Hash::make($request->new)]);
                        return dataToResponse('success', 'Succès ', ['Mot de passe a été changé avec succès'], 200);
                    }
                }
                return dataToResponse('error', 'Erreur ', ['L\'ancien mot de passe ne correspond pas'], 422);
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
            return dataToResponse('success', 'Succès ', ['Mise à jour du profil réussie'], 200);
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }

    //* Add delivery men.
    public function addDeliveryMan(DeliveryRequest $request){
        //Generate random password.
        $generetedPassword = \Str::random(6);
        //Upload data to server.
        $fileName =  storeUploaded(public_path() . '/images/permits', $request->permit);
        //Store data
        $delivery = Delivery::Create([
            'first_name'   => $request->first_name,
            'last_name'    => $request->last_name,
            'email'        => $request->email,
            'password'     => \Hash::make($generetedPassword),
            'experience'   => $request->experience,
            'permit'       => $fileName,
            'phone_number' => $request->phone_number,
        ]);
        //Notify Delivery
        if ($delivery)
            $delivery->notify(new NotifyDeliveryAccount(["password" => $generetedPassword, "email" => $request->email]));

        return dataToResponse('success', 'Succès ', ['Un E-mail a été envoyé au livreur avec les informations d\'identification 👍'], 200);
    }

    //* Unblock delivery man
    public function unblockDeliveryMan(Request $request){
        try {
            if (Delivery::where('id', $request->delivery_id)->update(['blocked_at' => null]))
                return dataToResponse('success', 'Succès ', ['Débloquer avec succès ✅'], 200);
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }

    //* Block delivery man
    public function blockDeliveryMan(Request $request){
        try {
            if (Delivery::where('id', $request->delivery_id)->update(["blocked_at" => \Carbon\Carbon::now()])) {
                return dataToResponse('success', 'Succès ', ['Livreur a été bloqué ❌'], 200);
            }
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }

    //! Delete delivery man (need a softdelete)
    public function deleteDeliveryMan(Request $request){
        try {
            if (Delivery::where('id', $request->delivery_id)->delete()) {
                return dataToResponse('success', 'Succès ', ['La suppression est un succès 👍'], 200);
            }
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }

    //* Approuve delivery man
    public function approvedDeliveryMan(Request $request){
        try {
            $delivery = Delivery::where('id', $request->delivery_id)->first();
            if ($delivery->update(['approved_at' => \Carbon\Carbon::now()])) {
                try{
                    $delivery->notify(new NotifyAccountApproved($delivery->last_name));
                }
                catch(\Exception $e){
                    handleLogs($e);
                }
                //Return data!
                return dataToResponse('success', 'Succès ', ['Approuvé avec succès'], 200);
            }
            return dataToResponse('error', 'Erreur ! ', ['Something went wrong!'], 422);
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }

    //* Add dilivery man to spefic order
    public function addDeliveryToOrder(Request $request){
        try{
            $preorder = PreOrder::select('id', 'delivery_id')
                                    ->whereNull('delivery_id')
                                    ->where('id', (int)$request->pre_order_id)->first();
            if ($preorder)
                if( $preorder->update(['delivery_id' => (int)$request->delivery_id]) )
                    return dataToResponse('success', 'Succès ', ['Livreur a été affecté avec succès 👍'], 200);

            return dataToResponse('error', 'Erreur ', ['Un autre livreur s\'occupant de cette commande'], true, 422);
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }

    //* Fetch delivery men with more information like whos in service ? or not!
    public function fetchRestrictedDeliveries(){
        try{
            return  
                response(Delivery::whereNull('blocked_at')
                            ->with(['pre_order' => function($q){
                                $q->select('id', 'delivery_id');
                            }])
                            ->get()
                    , 200
                );
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }

    //* Fetch delivery men.
    public function fetchDeliveries()
    {
        return
            response(
                Delivery::orderBy('id', 'DESC')->get(),
                200
            );
    }

    //* Add delivery men.
    public function editDeliveryMan(Request $request){

        $delivery = Delivery::where('id', $request->id)->first();

        if ($request->permit != $delivery->permit) {
            try {
                $fileName =  storeUploaded(public_path() . '/images/permits', $request->permit);
                // delete old permit 
                unlink("images/permits/" . $delivery->permit);
            } catch (\Exception $e) {
                handleLogs($e);
            }
        } else $fileName = $delivery->permit;

        //Generate random password.
        $generetedPassword = \Str::random(6);
        //Store data
        $delivery = Delivery::where('id', $request->id)->update([
            'first_name'   => $request->first_name,
            'last_name'    => $request->last_name,
            'email'        => $request->email,
            'experience'   => $request->experience,
            'phone_number' => $request->phone_number,
        ]);
        
        return dataToResponse('success', 'Succès ', ['La modification a réussi'], 200);
    }

    //* Fetch express calls
    public function fetchExpressCalls(){
        try{
            return response(
                    ExpressDelivery::whereNull('delivery_id')
                        ->whereNull('taken_at')
                        ->with('restaurant')
                        ->get()
                , 200
            );
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }

    //* Take express
    public function takeExpress(Request $request){
        try{

            //Get the express
            $express = ExpressDelivery::where('id', (int)$request->id)->whereNull('delivery_id')->first();

            //Check the express
            if ($express){
                //Assign the express to delivery
                $express->update([
                    'delivery_id' => authIdFromGuard('delivery'),
                    'taken_at'    => \Carbon\Carbon::now()
                ]);

                //Response to font
                return dataToResponse('success', 'Succès', ['Vous êtes en charge maintenant 😀'], 200);
            }            

            //Incase something went wrong
            return dataToResponse('error', 'Erreur ! ', ['Something went wrong!'], 422);
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }

    //* Teen historic
    public function teenHistoricExpress(){
        try{
            return response(
                ExpressDelivery::where('delivery_id', authIdFromGuard('delivery'))
                    ->whereNotNull('taken_at')
                    ->with('restaurant')
                    ->orderBy('id', 'DESC')
                    ->take(10)
                    ->get()
            , 200
        );
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }
}
