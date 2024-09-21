<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpressDelivery;
use App\Models\DeviceToken;
use GGInnovative\Larafirebase\Facades\Larafirebase;

class ExpressDeliveryController extends Controller
{
    //* Fetch express deliveries for admin
    public function fetchExpressDeliveriesAdmin()
    {
        try {
            return response(
                ExpressDelivery::orderBy('id', 'DESC')
                    ->with(['restaurant', 'delivery'])
                    ->whereNull('archived_at')
                    ->get(),
                200
            );
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }

    //* Admin delete express delivery
    public function deleteExpressDelivery(Request $request)
    {
        try {
            if (ExpressDelivery::where('id', $request->express_id)->delete()) {
                return dataToResponse('success', 'Succès', ['Supprimé avec succès'], 200);
            }
            return dataToResponse('error', 'Erreur', ['Something went wrong, Please try again'], 422);
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }

    //* Fetch express calls
    public function fetchExpressCalls()
    {
        try {
            return response(
                ExpressDelivery::whereNull('delivery_id')
                    ->whereNull('taken_at')
                    ->with('restaurant')
                    ->get(),
                200
            );
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }

    //* Take express
    public function takeExpress(Request $request)
    {
        try {
            // Get the express
            $express = ExpressDelivery::where('id', (int)$request->id)->whereNull('delivery_id')->first();

            if ($express) {
                // Assign the express to delivery
                $express->update([
                    'delivery_id' => authIdFromGuard('delivery'),
                    'taken_at'    => \Carbon\Carbon::now(),
                ]);

                return dataToResponse('success', 'Succès', ['Vous êtes en charge maintenant 😀'], 200);
            }

            return dataToResponse('error', 'Erreur !', ['Something went wrong!'], 422);
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }

    //* Teen historic
    public function teenHistoricExpress()
    {
        try {
            return response(
                ExpressDelivery::where('delivery_id', authIdFromGuard('delivery'))
                    ->whereNotNull('taken_at')
                    ->with('restaurant')
                    ->orderBy('id', 'DESC')
                    ->take(10)
                    ->get(),
                200
            );
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }

    //* Fetch global history for delivery
    public function historicExpress()
    {
        try {
            return response(
                ExpressDelivery::where('delivery_id', authIdFromGuard('delivery'))
                    ->whereNotNull('taken_at')
                    ->with('restaurant')
                    ->orderBy('id', 'DESC')
                    ->get(),
                200
            );
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }

    //* Assign delivery to express
    public function setDeliveryToExpress(Request $request)
    {
        try {
            $express = ExpressDelivery::where('id', (int)$request->express_id)
                ->whereNull('delivery_id')
                ->whereNull('taken_at')
                ->first();

            if ($express) {
                if (
                    $express->update([
                        'delivery_id' => (int)$request->delivery_id,
                        'taken_at'    => \Carbon\Carbon::now(),
                    ])
                ) {
                    $deliveryDeviceToken = DeviceToken::where('delivery_id', (int)$request->delivery_id)->first();
                    // If token exists, push notification to delivery
                    if ($deliveryDeviceToken) {
                        Larafirebase::withTitle('Express affecté à vous par Qwilivery')
                            ->withBody('Allez dans les historiques d\'Express pour plus d\'informations')
                            ->withClickAction('/expressClue')
                            ->withPriority('high')
                            ->sendNotification([$deliveryDeviceToken->token]);
                    }

                    return dataToResponse('success', 'Succès', ['Express a été affecté au livreur'], 200);
                }
            }

            return dataToResponse('error', 'Erreur', ['Something went wrong, Please try again'], 422);
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }
}
