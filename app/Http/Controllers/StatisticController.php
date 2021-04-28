<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Delivery;
use App\Models\Restaurant;
use App\Models\PreOrder;
use App\Models\Report;
use DB;

class StatisticController extends Controller
{
    //* Count delevery men
    public function countDeliveries(){
        try{
            return 
                response()->json([
                    'count_deliveries' => Delivery::select(DB::raw('count(*) count_deliveries'))->get()->pluck('count_deliveries')[0]
                ]);
        }catch(\Exception $e){
            return 0;
        }
    }

    //* Count restaurants
    public function countRestaurants(){
        try{
            return 
                response()->json([
                    'count_restaurants' => Restaurant::select(DB::raw('count(*) count_restaurants'))->get()->pluck('count_restaurants')[0]
                ]);
        }catch(\Exception $e){
            return 0;
        }
    }

    //* Count delivered menu
    public function countDelivered(){
        try{
            return
                response()->json([
                    'count_delivered' => PreOrder::select(DB::raw('count(*) count_delivered'))->whereNotNull('delivered_at')->get()->pluck('count_delivered')[0]
                ]);
        }catch(\Exception $e){
            return 0;
        }
    }

    //* Count delivered order for delivery guy gaurd
    public function countDelivedForDelivery(){
        try{
            return
                response()->json([
                    'count_delivered' 
                                => PreOrder::select(DB::raw('count(*) count_delivered'))
                                ->where('delivery_id', authIdFromGuard('delivery'))
                                ->whereNotNull('delivered_at')->get()
                                ->pluck('count_delivered')[0]
                ], 200);
        }catch(\Exception $e){
            return 0;
        }
    }

    //* Count delivered order for delivery guy gaurd
    public function countReportsUnseen(){
        try{
            return
                response()->json([
                    'count_unseen_reports' 
                                => Report::select(DB::raw('count(*) count_unseen_reports'))
                                ->whereNull('seen_at')->get()
                                ->pluck('count_unseen_reports')[0]
                ], 200);
        }catch(\Exception $e){
            return 0;
        }
    }
}