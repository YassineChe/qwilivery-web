<?php

namespace App\Http\Controllers;

//Models
use App\Models\Delivery;
use App\Models\PreOrder;
use App\Models\Admin;
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
            return dataToResponse('success', 'Succès ', 'Mise à jour du profil réussie', false, 200);
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
                        return dataToResponse('success', 'Succès ', 'Mot de passe a été changé avec succès', false, 200);
                    }
                }
                return dataToResponse('error', 'Erreur ', 'L\'ancien mot de passe ne correspond pas', false, 422);
            }
        }catch(\Exception $e){
            handleLogs($e);
        }
    }

    //* Approuve delivery man
    public function approvedDeliveryMan(Request $request){
        try {
            if (
                Delivery::where('id', $request->delivery_id)
                        ->update(['approved_at' => \Carbon\Carbon::now()])
                ) {
                return dataToResponse('success', 'Succès ', 'La mise à jour a réussi', false, 200);
            }
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }
    
    //! Delete delivery man (need a softdelete)
    public function deleteDeliveryMan(Request $request){
        try {
            if (Delivery::where('id', $request->delivery_id)->delete()) {
                return dataToResponse('success', 'Succès ', 'La suppression est un succès 👍', true, 200);
            }
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }

    //* Block delivery man
    public function blockDeliveryMan(Request $request){
        try {
            if (Delivery::where('id', $request->delivery_id)->update(["blocked_at" => \Carbon\Carbon::now()])) {
                return dataToResponse('success', 'Succès ', 'Livreur a été bloqué ❌', true, 200);
            }
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }

    //* Unblock delivery man
    public function unblockDeliveryMan(Request $request){
        try {
            if (Delivery::where('id', $request->delivery_id)->update(['blocked_at' => null]))
                return dataToResponse('success', 'Succès ', 'Débloquer avec succès ✅', true, 200);
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }

    //* Fetch delivery men.
    public function fetchDeliveries()
    {
        return
            response(
                Delivery::orderByRaw('created_at DESC')
                    ->get(),
                200
            );
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

    //* Add dilivery man to spefic order
    public function addDeliveryToOrder(Request $request){
        try{
            $preorder = PreOrder::select('id', 'delivery_id')
                                    ->whereNull('delivery_id')
                                    ->where('id', (int)$request->pre_order_id)->first();
            if ($preorder)
                if( $preorder->update(['delivery_id' => (int)$request->delivery_id]) )
                    return dataToResponse('success', 'Succès ', 'Livreur a été affecté avec succès 👍', true, 200);

            return dataToResponse('error', 'Erreur ', 'Un autre livreur s\'occupant de cette commande', true, 422);
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
        $fileName =  storeUploaded(public_path() . '/files', $request->permit);
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

        return dataToResponse('success', 'Succès ', 'Un E-mail a été envoyé au livreur avec les informations d\'identification 👍', true, 200);
    }

    //* Add delivery men.
    public function editDeliveryMan(Request $request){

        $delivery = Delivery::where('id', $request->id)->first();

        if ($request->permit != $delivery->permit) {
            try {
                $fileName =  storeUploaded(public_path() . '/files', $request->permit);
                // delete old permit 
                unlink("files/" . $delivery->permit);
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
            'password'     => \Hash::make($generetedPassword),
            'experience'   => $request->experience,
            'permit'       => $fileName,
            'phone_number' => $request->phone_number,
        ]);
        
        return dataToResponse('success', 'Succès ', [ "msg" => 'La modification a réussi ', "data" => $delivery], true, 200);
    }
}
