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
                'fullname'      => $request->fullname,
                'phone_number'  => $request->phone_number,
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

            return dataToResponse('success', 'Succès', 'La commande a été ajoutée 👍', true, 200);
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }

    //* PreOrder
    public function fetchPreOrders(){
        try{
            return
                response(
                    PreOrder::where('restaurant_id', authIdFromGuard('restaurant'))
                    ->with('orders')
                    ->orderBy('id', 'DESC')
                    ->get()
                    , 200
                );
        }catch(\Exception $e){
            handleLogs($e);
        }
    }

    //* Orders
    public function fetchOrderByPreOrderID($pre_order_id){
        try{
            //Let make sure that Restaurant
            //Its able to request this json
            if(
            PreOrder::select('id')->where('id', $pre_order_id)
                                ->where('restaurant_id', authIdFromGuard('restaurant'))
                                ->first()
            )
            return
                response(
                    Order::where('pre_order_id', (int)$pre_order_id)
                            ->with('variant')
                            ->get()
                            , 200
                );
            
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }
    
    //* Order To deliver (used now only for mobile)
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

    //* This will set as SoftDelete will leave traceability for Qwilivery admin
    public function deleteOrderByRestaurant($order_id){
        try{
            if(
                PreOrder::where('id', (int)$order_id)
                        ->where('restaurant_id', authIdFromGuard('restaurant'))
                        ->delete()
            )
                return dataToResponse('success', 'Succès', 'Commande supprimée avec succès ❌', true, 200); 
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }
}
