<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RequestRestaurant;
use App\Models\Restaurant;
use App\Models\ExpressDelivery;
use App\Models\DeviceToken;
use App\Models\AppSetting;
use App\Events\NewExpressDelivery;
use App\Notifications\NotifyAccountApproved;
use App\Notifications\NotifyRestaurantAccount;
use GGInnovative\Larafirebase\Facades\Larafirebase;


class RestaurantController extends Controller
{
    //* Fetch Restaurants
    public function fetchRestaurants()
    {
        return
            response(
                Restaurant::orderBy('id', 'DESC')->get(),
                200
            );
    }
    //* Add Restaurant
    public function addRestaurant(RequestRestaurant $request)
    {
        try {
            // Generate random password
            $generatedPassword = \Str::random(6);
            // Store restaurant data
            $restaurant = Restaurant::create([
                'name'         => $request->name,
                'email'        => $request->email,
                'password'     => \Hash::make($generatedPassword),
                'phone_number' => $request->phone_number,
                'address'      => $request->address,
                'rate'         => $request->rate,
                'lat'          => $request->lat,
                'lng'          => $request->lng,
            ]);

            // Notify the restaurant
            if ($restaurant) {
                $restaurant->notify(new NotifyRestaurantAccount([
                    "password" => $generatedPassword,
                    "email"    => $request->email,
                ]));
            }

            return dataToResponse('success', 'Succès', ['Un E-mail a été envoyé au restaurant avec les informations d\'identification 👍'], 200);
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }

    //* Edit Restaurant
    public function editRestaurant(RequestRestaurant $request)
    {
        if (
            Restaurant::where('id', (int)$request->id)->update([
                'name'         => $request->name,
                'email'        => $request->email,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'rate'    => $request->rate,
                'lat'     => $request->lat,
                'lng'     => $request->lng,
            ])
        )
            return dataToResponse('success', 'Succès ', ['Modifié avec succès 👍'], 200);
    }

    //* Delete Restaurant
    public function deleteRestaurant(Request $request)
    {
        try {
            $restaurant = Restaurant::where('id', $request->restaurant_id)->first();
            if ($restaurant) {
                if ($restaurant->delete())
                    return dataToResponse('success', 'Succès ', ['Supprimé avec succès 👍'], 200);
            }
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }

    //* Block Restaurant
    public function blockRestaurant(Request $request)
    {
        try {
            if (Restaurant::where('id', $request->restaurant_id)->update(['blocked_at' => \Carbon\Carbon::now()]))
                return dataToResponse('success', 'Succès ', ['Bloquer avec succès ❌'], 200);
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }

    //* unBlock Restaurant
    public function unblockRestaurant(Request $request)
    {
        try {
            if (Restaurant::where('id', $request->restaurant_id)->update(['blocked_at' => null]))
                return dataToResponse('success', 'Succès ', ['Débloquer avec succès ✅'], 200);
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }

    //* Edit password
    public function editPassword(Request $request)
    {
        try {
            $restaurant = Restaurant::where('id', authIdFromGuard('restaurant'))->first();
            if ($restaurant) {
                if (\Hash::check($request->old, $restaurant->makeVisible(['password'])->password)) {
                    if ($request->new == $request->cfm) {
                        $restaurant->update(['password' => \Hash::make($request->new)]);
                        return dataToResponse('success', 'Succès ', ['Mot de passe a été changé avec succès'], 200);
                    }
                }
                return dataToResponse('error', 'Erreur ', ['L\'ancien mot de passe ne correspond pas'], 422);
            }
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }

    //* Approuve delivery man
    public function approveRestaurant(Request $request)
    {
        try {
            $restaurant = Restaurant::where('id', $request->restaurant_id)->first();
            if ($restaurant->update(['approved_at' => \Carbon\Carbon::now()])) {
                try {
                    $restaurant->notify(new NotifyAccountApproved($restaurant->name));
                } catch (\Exception $e) {
                    handleLogs($e);
                }
                return dataToResponse('success', 'Succès ', ['Approuvé avec succès'], 200);
            }
            return dataToResponse('error', 'Erreur ! ', ['Something went wrong!'], 422);
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }


    //* Edit Profile Information
    public function editProfile(Request $request)
    {
        try {
            //Avatar handler
            if (\Auth::guard('restaurant')->user()->avatar != $request->avatar) {
                $avatar =  storeUploaded(public_path() . '/images/avatars', $request->logo);
            } else {
                $avatar = \Auth::guard('restaurant')->user()->avatar;
            }

            if (
                Restaurant::where('id', authIdFromGuard('restaurant'))->update([
                    'name'         => $request->name,
                    'phone_number' => $request->phone_number,
                    'email'        => $request->email,
                    'address'      => $request->address,
                    'lat'          => $request->lat,
                    'lng'          => $request->lng,
                    'avatar'       => $avatar
                ])
            )
                return dataToResponse('success', 'Succès ', ['Mise à jour du profil réussie'], 200);
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }

    //* Call express delivery
    public function callExpressDelivery()
    {
        try {
            if (ExpressDelivery::create(['restaurant_id' => authIdFromGuard('restaurant')])) {
                // Dispatch a notification for web
                event(new NewExpressDelivery());

                // Get device tokens
                $tokenCollections = DeviceToken::select('token')->get();
                if ($tokenCollections) {
                    $tokens = [];
                    foreach ($tokenCollections as $deliveryToken) {
                        $tokens[] = $deliveryToken->token;
                    }

                    // Get notification content
                    $appSettings = AppSetting::select('express_title', 'express_body')->where('id', 1)->first();

                    if ($appSettings) {
                        Larafirebase::setTitle($appSettings->express_title)
                            ->setBody(guardData('restaurant')->name . ' ' . $appSettings->express_body)
                            ->setClickAction('/expressClue')
                            ->setPriority('high')
                            ->sendNotification($tokens);
                    }
                }

                return dataToResponse('success', 'Succès', ['Un livreur arrivera dans instants.'], 200);
            }

            return dataToResponse('error', 'Erreur', ['Something went wrong!'], 422);
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }

    //* Fetch express delivery calls
    public function fetchExpressDelivery()
    {
        try {
            return response(
                ExpressDelivery::with('delivery')
                    ->where('restaurant_id', authIdFromGuard('restaurant'))
                    ->orderBy('id', 'DESC')->get(),
                200
            );
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }
}
