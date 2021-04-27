<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Delivery;
use App\Models\Restaurant;
use App\Models\PreOrder;
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
}