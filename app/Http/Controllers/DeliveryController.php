<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


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
}
