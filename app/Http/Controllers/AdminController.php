<?php

namespace App\Http\Controllers;

//Models
use App\Models\Delivery;
use App\Models\Restaurant;
//Requests
use Illuminate\Http\Request;
use App\Http\Requests\DeliveryRequest;
//Notifications
use App\Notifications\NotifyDeliveryAccount;

class AdminController extends Controller
{
    //* Approuve.  
    public function approvedDeliveryMan(Request $request)
    {
        try {
            if (Delivery::where('id', $request->delivery_id)->update(['status' => 1])) {
                //Return data to front
                return dataToResponse('success', 'Succès ', 'La mise à jour a réussi', false, 200);
            }
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }
    //* Delete.
    public function deleteDeliveryMan(Request $request)
    {
        try {
            if (Delivery::where('id', $request->delivery_id)->delete()) {
                return dataToResponse('success', 'Succès ', 'La suppression est un succès 👍', true, 200);
            }
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }

    //* Block.
    public function blockDeliveryMan(Request $request)
    {
        try {
            if (Delivery::where('id', $request->delivery_id)->update(["blocked_at" => \Carbon\Carbon::now()])) {
                return dataToResponse('success', 'Succès ', 'Livreur a été bloqué ❌', true, 200);
            }
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }

    // *Unblock DeliveryMan
    public function unblockDeliveryMan(Request $request)
    {
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
    //* Add delivery men.
    public function addDeliveryMan(DeliveryRequest $request)
    {

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

        return dataToResponse('success', 'Succès ', [
            "msg" => 'Un E-mail a été envoyé au livreur avec les informations d\'identification 👍',
            "data" => $delivery
        ], true, 200);
    }
}
