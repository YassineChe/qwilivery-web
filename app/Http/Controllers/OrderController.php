<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\PreOrder;

class OrderController extends Controller
{
    public function addOrder(Request $request){
        try{
            $po = 
            PreOrder::create([
                'fullname' => $request->fullname,
                'address'  => $request->address,
                'lat'      => $request->lat,
                'lng'      => $request->lng,
            ]);

            if ($po){
                foreach ($request->orders as $key => $order) {
                    Order::CREATE([
                        'pre_order_id' => $po->id,
                        'variant_id'   => $order['variant']['id'],
                        'qty'          => $order['qty']
                    ]);
                }
            }

            return dataToResponse('success', 'Succès', ['msg' => 'La commande a été ajoutée 👍'], true, 200);
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }
}
