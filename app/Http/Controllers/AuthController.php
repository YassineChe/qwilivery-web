<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Restaurant;
use App\Models\Delivery;
use App\Models\Password_reset;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;



use App\Notifications\ResetEmail;

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
            if (Hash::check($request->password, $admin->password)) {
                return response([
                    'guard' => 'admin',
                    'token' => $admin->createToken('admin-api', ['admin-stuff'])->plainTextToken
                ]);
            }
            //Wrong password!
            return dataToResponse('error', 'Erreur!', 'Le mot de passe est erroné', false, 422);
        } else if ($delivery) {
            // If login Success login
            if (Hash::check($request->password, $admin->password)) {
                return response([
                    'guard' => 'delivery',
                    'token' => $delivery->createToken('admin-api', ['admin-stuff'])->plainTextToken
                ]);
            }
            //Wrong password!
            return dataToResponse('error', 'Erreur!', 'Le mot de passe est erroné', false, 422);
        } else if ($restaurant) {
            // If login Success login
            if (Hash::check($request->password, $admin->password)) {
                return response([
                    'guard' => 'restaurant',
                    'token' => $restaurant->createToken('admin-api', ['admin-stuff'])->plainTextToken
                ]);
            }
            //Wrong password!
            return dataToResponse('error', 'Erreur!', 'Le mot de passe est erroné', false, 422);
        }

        //No item found.
        return dataToResponse('error', 'Erreur!', 'Ces identifiants ne correspondent pas à nos enregistrements', false, 422);
    }

    // Send reset Code 
    function createRestLink(Request $request)
    {
        // Check if email exist in data
        $admin = Admin::where('email', $request->email)->first();
        if ($admin) {
            if (Password_reset::Where('email', $request->email)->delete());
            $admin = Password_reset::create([
                'email' => $request->email,
                'token' => Str::random(60),
            ]);
            // Send email 
            $admin->notify(new ResetEmail($admin->email, $admin->token));

            return response(['email' => $admin->email, 'status' => true]);
        }

        // If email does not exist
        return dataToResponse('error', 'Erreur!', 'Ces identifiants ne correspondent pas à nos enregistrements', false, 422);
    }

    //* Check if code exist
    public function restPassword(Request $request)
    {
        if ($request->password !== $request->confirm) {
            return dataToResponse('error', 'Erreur!', 'le mot de passe ne correspond pas', false, 422);
        }

        //Check if token exist
        $reset = Password_reset::Where('token', $request->token)->first();

        if (!$reset) {
            return dataToResponse('error', 'Erreur!', ' votre URL a expiré', false, 422);
        }

        $time = Carbon::parse($reset->created_at)->addMinutes(10);

        if (Carbon::now() <= $time) {

            $admin = Admin::where('email', $reset->email)->first();
            if ($admin) {
                $admin->update([
                    'password' => Hash::make($request->password)
                ]);
                $reset->delete();
                return dataToResponse('success', 'success!', 'le mot de passe a été mis à jour avec succès', false, 200);
            }
        }
    }
}
