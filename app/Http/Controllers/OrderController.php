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
                'restaurant_id' => authIdFromGuard('restaurant'),
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

    public function preOrders(){
        try{
            return 
                response(
                    PreOrder::where('restaurant_id', authIdFromGuard('restaurant'))
                    ->with('orders')
                    ->get()
                    , 200
                );
        }catch(\Exception $e){
            handleLogs($e);
        }
    }

    public function orderToDeliver(){
        try{
            return response(
                PreOrder::where('delivered', false)->with('orders')
                        ->get()
                        , 200
            );
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }
}
