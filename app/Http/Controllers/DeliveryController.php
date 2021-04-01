<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DeliveryRequest;


use App\Models\Delivery;

class DeliveryController extends Controller
{

    //* Update password
    public function updatePassword(Request $request)
    {

        $guard = Delivery::where('id', authIdFromGuard('delivery'))->first();

        try {
            //* Check if confirm password 
            if ($request->new == $request->confirm) {

                if (\Hash::check($request->old, $guard->makeVisible(['password'])->password)) {

                    $guard->update([
                        'password' => \Hash::make($request->new)
                    ]);
                    return response()->json(["status" => "success", "subMessage" => "Modifié avec succès.", 'redirect' => null]);
                }
                return response()->json(["status" => "errors", "subMessage" => "Ancien mot de passe incorrect.", 'redirect' => null]);
            }
            return response()->json(["status" => "errors", "subMessage" => "Confirmer que le mot de passe ne correspond pas.", 'redirect' => null]);
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }
    //* Download permit
    public function downloadFile()
    {
        $guard = Delivery::where('id', authIdFromGuard('delivery'))->first();
        return response()->json('files/' . $guard->permit);
    }

    //*  updateDelivery
    public function updateDelivery(Request $request)
    {
        return $request;
        $delivery = Delivery::where('id', authIdFromGuard('delivery'))->first();
        // Retrieving necessary data
        $file = $request->Permit;
        //file upload 
        $to = time() . '.' . $file->extension();
        // Move picture to server
        $file->move(public_path() . '/files', $to);
        unlink("/files/" . $delivery->permit);

        $delivery->update([
            "first_name" => $request->first_name,
            "last_name"  => $request->last_name,
            "avatar"     => "avatar.png",
            "email"      => $request->email,
            "experience" => $request->experience,
            "permit"    => $to,
            "phone_number"      => $request->phone_number,
        ]);
        return $request;
    }
}
