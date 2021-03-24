<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Restaurant;
use App\Models\Delivery;

class AuthController extends Controller
{

    //* Login Admin
    public function login(Request $request)
    {

        $admin = Admin::where('email', $request->email)->first();
        $restaurant = Restaurant::where('email', $request->email)->first();
        $delivery = Delivery::where('email', $request->email)->first();

        if ($admin) {
            // If login Success login
            if (\Hash::check($request->password, $admin->password)) {
                return response([
                    'guard' => 'admin',
                    'token' => $admin->createToken('admin-api', ['admin-stuff'])->plainTextToken
                ]);
            }
            //Wrong password!
            return dataToResponse('error', 'Erreur!', ['Le mot de passe est erroné'], false, 422);
        } else if ($delivery) {
            // If login Success login
            if (\Hash::check($request->password, $admin->password)) {
                return response([
                    'guard' => 'delivery',
                    'token' => $delivery->createToken('admin-api', ['admin-stuff'])->plainTextToken
                ]);
            }
            //Wrong password!
            return dataToResponse('error', 'Erreur!', ['Le mot de passe est erroné'], false, 422);
        } else if ($restaurant) {
            // If login Success login
            if (\Hash::check($request->password, $admin->password)) {
                return response([
                    'guard' => 'restaurant',
                    'token' => $restaurant->createToken('admin-api', ['admin-stuff'])->plainTextToken
                ]);
            }
            //Wrong password!
            return dataToResponse('error', 'Erreur!', ['Le mot de passe est erroné'], false, 422);
        }

        //No item found.
        return dataToResponse('error', 'Erreur!', ['Ces identifiants ne correspondent pas à nos enregistrements'], false, 422);
    }
}
