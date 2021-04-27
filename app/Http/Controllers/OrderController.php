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

    //* Orders (This function is common between Restaurabt and admin with instruction)
    public function fetchOrderByPreOrderID($pre_order_id){
        try{
            switch(getConnectedGuard()){
                case 'restaurant':
                    $isAuthorized = PreOrder::select('id')->where('id', $pre_order_id)
                                            ->where('restaurant_id', authIdFromGuard('restaurant'))
                                            ->first();
                    if ($isAuthorized)
                        $orders = Order::where('pre_order_id', (int)$pre_order_id)->with('variant')->get();
                break;
                case 'admin': 
                    $orders = Order::where('pre_order_id', (int)$pre_order_id)->with('variant')->get();
                break;
            }

            return response($orders, 200);
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
    public function deleteOrder($order_id){
        try{
            switch (getConnectedGuard()) {
                case 'restaurant':
                        $delete = PreOrder::where('id', (int)$order_id)
                                    ->where('restaurant_id', authIdFromGuard('restaurant'))
                                    ->delete();
                break;
                
                case 'admin':
                    $delete = PreOrder::where('id', (int)$order_id)->forceDelete();
                    if ($delete)
                        Order::where('pre_order_id', (int)$order_id)->delete();
                break;
            }

            if ($delete)
                return dataToResponse('success', 'Succès', 'Commande supprimée avec succès ❌', true, 200); 

        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }

    //* Fetch order historic
    public function fetchHistoric(){
        try{
            return
                response(PreOrder::with(['restaurant', 'orders', 'delivery'])
                                ->withTrashed()
                                ->orderBy('id', 'DESC')
                                ->get()
                                , 200
                );
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }
}
