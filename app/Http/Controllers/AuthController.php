<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Restaurant;
use App\Models\Delivery;
use App\Models\Password_reset;
use App\Http\Requests\DeliveryRequest;
//Notifications
use App\Notifications\ResetEmail;
use App\Notifications\ConfirmAccountMail;

class AuthController extends Controller
{

    //* This will authenticated only delivery man (FOR MOBILE)
    public function deliveryLogin(Request $request){
        $delivery = Delivery::where('email', $request->email)->first();
        if ($delivery){
            if (\Hash::check($request->password, $delivery->password)) {

                if ($delivery->approved_at == null)
                    return dataToResponse('error', 'Erreur!', 'Votre compte n\'a pas encore été approuvé', false, 422);

                return response([
                    'first_name' => $delivery->first_name,
                    'last_name'  => $delivery->last_name,
                    'guard' => 'delivery',
                    'token' => $delivery->createToken('delivery-api', ['delivery-stuff'])->plainTextToken
                ], 200);
            }
            return dataToResponse('error', 'Erreur!', 'Le mot de passe est erroné', false, 422);
        }
        return dataToResponse('error', 'Erreur!', 'Ces identifiants ne correspondent pas à nos enregistrements', false, 422);
    }

    //* Login Admin (WEB PART)
    public function login(Request $request)
    {
        $delivery = Delivery::where('email', $request->email)->first();
        $admin = Admin::where('email', $request->email)->first();
        $restaurant = Restaurant::where('email', $request->email)->first();

        if ($admin) {
            if (\Hash::check($request->password, $admin->password)) {
                return response([
                    'guard' => 'admin',
                    'token' => $admin->createToken('admin-api', ['admin-stuff'])->plainTextToken
                ]);
            }
            //Wrong password!
            return dataToResponse('error', 'Erreur!', 'Le mot de passe est erroné', false, 422);
        } else if ($delivery) {
            if (\Hash::check($request->password, $delivery->password)) {

                if ($delivery->approved_at == null)
                    return dataToResponse('error', 'Erreur!', 'Votre compte n\'a pas encore été approuvé', false, 422);

                return response([
                    'guard' => 'delivery',
                    'token' => $delivery->createToken('admin-api', ['admin-stuff'])->plainTextToken
                ]);
            }
            //Wrong password!
            return dataToResponse('error', 'Erreur!', 'Le mot de passe est erroné', false, 422);
        } else if ($restaurant) {
            if (\Hash::check($request->password, $restaurant->password)) {
                return response([
                    'guard' => 'restaurant',
                    'token' => $restaurant->createToken('restaurant-api', ['restaurant-stuff'])->plainTextToken
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
                'token' => \Str::random(60),
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
            return dataToResponse('error', 'Erreur!', 'Le mot de passe ne correspond pas', false, 422);
        }

        //Check if token exist
        $reset = Password_reset::Where('token', $request->token)->first();

        if (!$reset) 
            return dataToResponse('error', 'Erreur!', 'Votre URL a expiré', false, 422);

        $time = \Carbon\Carbon::parse($reset->created_at)->addMinutes(10);

        if (\Carbon\Carbon::now() <= $time) {

            $admin = Admin::where('email', $reset->email)->first();
            if ($admin) {
                $admin->update(['password' => \Hash::make($request->password)]);
                $reset->delete();
                return dataToResponse('success', 'success!', 'le mot de passe a été mis à jour avec succès', false, 200);
            }
        }
    }

    //* Create new Delivery  (DeliveryRequest)
    public function regiterDelivery(DeliveryRequest $request)
    {
        $request->validate([
            'password'   => 'required|min:6',
        ]);
        // Check if password is much
        if ($request->password !== $request->confirm) {
            return dataToResponse('error', 'Erreur!', [['le mot de passe ne correspond pas']], false, 422);
        }
        // Retrieving necessary data
        $fileName =  storeUploaded(public_path() . '/files', $request->permit);

        $delivery =  Delivery::create([
            "first_name"   => $request->first_name,
            "last_name"    => $request->last_name,
            "avatar"       => "avatar.png",
            "email"        => $request->email,
            "password"     => \hash::make($request->password),
            "experience"   => $request->experience,
            "permit"       => $fileName,
            "phone_number" => $request->phone_number,
        ]);
        // send email.
        $delivery->notify(new ConfirmAccountMail($delivery->email, $delivery->token));

        return dataToResponse('success', 'success!', 'Votre compte a été créé avec succès', false, 200);
    }
}
