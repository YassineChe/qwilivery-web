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

        // return $request;
        $delivery = Delivery::where('id', authIdFromGuard('delivery'))->first();
        $avatar = "";
        // return $request->avatar;
        if ($request->avatar !== $delivery->avatar && $request->avatar) {
            try {
                // Upload Image
                $avatar = storeUploaded(public_path() . '/Avatar', $request->avatar);
                unlink("Avatar/" . $delivery->avatar);
            } catch (\Throwable $th) {
                //throw $th;
            }
        } else $avatar = $delivery->avatar;

        // Check if pdf exist
        $fileName = "";
        if ($request->permit) {
            try {
                $fileName =  storeUploaded(public_path() . '/files', $request->permit);
                // delete old permit 
                unlink("files/" . $delivery->permit);
            } catch (\Exception $e) {
                handleLogs($e);
            }
        } else $fileName = $delivery->permit;

        $delivery->update([
            "first_name"    => $request->first_name,
            "last_name"     => $request->last_name,
            "avatar"        => $avatar,
            "email"         => $request->email,
            "experience"    => $request->experience,
            "permit"        => $fileName,
            "phone_number"  => $request->phone_number,
        ]);

        return dataToResponse('success', 'Succès ', $delivery, true, 200);
    }
}
