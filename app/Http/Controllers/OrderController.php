<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\PreOrder;
//* Notifications
use App\Events\NewOrder;

class OrderController extends Controller
{
    public function addOrder(Request $request){
        try{
            $po = 
            PreOrder::create([
                'fullname'      => $request->fullname,
                'phone_number'  => $request->phone_number,
                'restaurant_id' => authIdFromGuard('restaurant'),
                'shipping_cost' => $request->shipping_cost,
                'tax'           => $request->tax,
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

                //All Good 👋 Let's notify deliveries! (No data will bind just for this moment!)
                event(new NewOrder());
            }
            
            return dataToResponse('success', 'Succès', ['La commande a été ajoutée 👍'], 200);
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

            $collection = PreOrder::where('id', (int)$pre_order_id)->with(['orders', 'orders.variant'])->first();

            $orders = [
                'pre_order' => [
                    'tax' => $collection->tax,
                    'shipping_cost' => $collection->shipping_cost,
                ],
                'variants' => [],
            ];

            if ($collection->orders != null){
                foreach ($collection->orders as $order) {
                    array_push($orders['variants'], $order);
                }
            }


            return response($orders, 200);

            // return response(Order::where('pre_order_id', (int)$pre_order_id)->with('variant', )->get(), 200);
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }
    
    //* Order To deliver (used now only for mobile)
    public function orderToDeliver(){
        try{
            return response()
                    ->json(
                        PreOrder::whereNull('delivered_at')->whereNull('delivery_id')
                        ->with(['orders', 'restaurant' => function($q){
                            $q->withTrashed();
                        }])
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
                return dataToResponse('success', 'Succès', ['Commande supprimée avec succès ❌'], 200); 

        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }

    //* Fetch order historic (Admin Guard)
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

    //* Fetch last five mission (For Delivery)
    public function fetchLastFiveMissions(){
        try{
            return response(
                PreOrder::where('delivery_id', authIdFromGuard('delivery'))
                    ->with(['restaurant' => function($q){
                        $q->select('id', 'name')->withTrashed();
                    }])
                    ->orderBy('id', 'ASC')
                    ->take(5)
                    ->get()
                , 
                200)
            ;
        }catch(\Exception $e){
            handleLogs($e);
        }
    }

    //* Fetch last five mission (For Delivery)
    public function fetchLastTeenMissions(){
        try{
            return response(
                PreOrder::where('restaurant_id', authIdFromGuard('restaurant'))
                    ->orderBy('id', 'ASC')
                    ->take(10)
                    ->get()
                , 
                200)
            ;
        }catch(\Exception $e){
            handleLogs($e);
        }
    }

    //* Fetch last five mission 
    public function fetchDeliveryHistoric(){
        try{
            return response(
                PreOrder::where('delivery_id', authIdFromGuard('delivery'))
                    ->with(['restaurant' => function($q){
                        $q->select('id', 'name')->withTrashed();
                    }])
                    ->orderBy('id', 'ASC')
                    ->take(5)
                    ->get()
                , 
                200)
            ;
        }catch(\Exception $e){
            handleLogs($e);
        }
    }

    //* Fetch Delivery boy historic
    public function fetchDeliveryHistory(){
        try{
            return response(
                PreOrder::where('delivery_id', authIdFromGuard('delivery'))
                    ->with('restaurant', 'orders')
                    ->orderBy('id', 'DESC')
                    ->get()
                ,
                200)
            ;
        }catch(\Exception $e){
            handleLogs($e);
        }
    }

    //* Take order in charge
    public function takeOrderInCharge(Request $request){
        try{

            $preorder = PreOrder::select('id')
                                ->where('id', (int)$request->id)
                                ->whereNull('delivery_id')
                                ->whereNull('delivered_at')
                                ->first();

            if ($preorder)
                if(PreOrder::where('id', (int)$request->id)->update(['delivery_id' => authIdFromGuard('delivery')]))
                    return dataToResponse('success', 'Succès', ['Effectué avec succès'], 200);

            return dataToResponse('error', 'Erreur', ['Cet ordre déjà pris'], 422);
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }

    //* Fetch current order
    public function fetchInprogressOrders(){
        try{
            return response(PreOrder::where('delivery_id', authIdFromGuard('delivery'))
                                ->whereNull('delivered_at')
                                ->with(['orders', 'restaurant' => function($q){
                                    return $q->withTrashed();
                                }])
                                ->get()
                        , 200
                    );
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }

    public function deliveredOrder(Request $request){
        try{
            if(
                PreOrder::where('id', (int)$request->id)
                                ->where('delivery_id', authIdFromGuard('delivery'))
                                ->update(['delivered_at' => \Carbon\Carbon::now()])
                )
                return dataToResponse('success', 'Succès', ['Commande livrée'], 200);
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    } 


    public function fetchDeliveredOrder(){
        try{
            return response(PreOrder::where('delivery_id', authIdFromGuard('delivery'))
                                ->whereNotNull('delivered_at')
                                ->with(['orders', 'restaurant'])
                                ->get()
                        , 200
                    );
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }
}
