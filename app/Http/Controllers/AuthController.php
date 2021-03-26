<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Restaurant;
use App\Models\Delivery;
use App\Models\Password_reset;
use Illuminate\Support\Str;
use Carbon\Carbon;

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
            if (\Hash::check($request->password, $admin->password)) {
                return response([
                    'guard' => 'admin',
                    'token' => $admin->createToken('admin-api', ['admin-stuff'])->plainTextToken
                ]);
            }
            //Wrong password!
            return dataToResponse('error', 'Erreur!', 'Le mot de passe est erroné', false, 422);
        } else if ($delivery) {
            // If login Success login
            if (\Hash::check($request->password, $admin->password)) {
                return response([
                    'guard' => 'delivery',
                    'token' => $delivery->createToken('admin-api', ['admin-stuff'])->plainTextToken
                ]);
            }
            //Wrong password!
            return dataToResponse('error', 'Erreur!', 'Le mot de passe est erroné', false, 422);
        } else if ($restaurant) {
            // If login Success login
            if (\Hash::check($request->password, $admin->password)) {
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
    function reset(Request $request)
    {

        // Check if email exist in data
        $admin = Admin::where('email', $request->email)->first();
        if ($admin) {
            if (Password_reset::Where('email', $request->email)->delete());
            $admin = Password_reset::create([
                'email' => $request->email,
                'token' => mt_rand(100000, 999999),
            ]);
            // Send email 
            $admin->notify(new ResetEmail($admin->email, $admin->token));

            return response(['email' => $admin->email, 'status' => true]);
        }

        // If email does not exist
        return dataToResponse('error', 'Erreur!', 'Ces identifiants ne correspondent pas à nos enregistrements', false, 422);
    }

    //! Check if code exist
    public function getCode($token)
    {
        //Check if token exist
        $t = DB::select('select * from password_resets where token = ?', [$token]);

        //
        if (count($t) > 0) {
            if ($t[0]->created_at > Carbon::now()) {
                return view('auth.password.reset', ['token' => $token]);
            } else return redirect('/forget-password');
        } else return redirect('/forget-password');
    }

    //! Update Password
    public function updatePassword(Request $request)
    {
        $resetUser = DB::select('select * from password_resets where token = ?', [$request->token])[0];

        $updatePassword = DB::table('password_resets')
            ->where(['email' => $resetUser->email, 'token' => $resetUser->token])
            ->first();

        if (!$updatePassword)
            return back()->withInput()->with('error', 'Invalid token!');

        $user = User::where('email', $resetUser->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email' => $resetUser->email])->delete();

        return response()->json(['code' => 200, 'Message' => 'password update successfull']);
    }
}
