<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpressDelivery;

class ExpressDeliveryController extends Controller
{
    //* Fetch express deliveries for admin
    public function fetchExpressDeliveriesAdmin(){
        try{
            return response(
                ExpressDelivery::orderBy('id', 'DESC')->with('delivery')->get() 
                , 200
            );
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }

    //* Admin delete express delivery
    public function deleteExpressDelivery(Request $request){
        try{
            if(ExpressDelivery::where('id', $request->express_id)->delete())
                return dataToResponse('success', 'Succès', ['Supprimé avec succès'], 200);

            return dataToResponse('error', 'Erreur', ['Something went wrong, Please try again'], 422);
        }
        catch(\Exception $e){
            handleLogs($e);
        }    
    }
}
