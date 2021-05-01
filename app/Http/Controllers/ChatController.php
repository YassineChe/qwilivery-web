<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chatflow;
use App\Models\Conversation;

class ChatController extends Controller
{
    //* Send message
    public function sendMessageByAdmin(Request $request){
        try{

            $cv = Conversation::where('admin_id', authIdFromGuard('admin'))
                    ->where('delivery_id', $request->delivery_id)
                    ->first();

            if (!$cv){
                $cv = Conversation::create([
                    'admin_id'    => authIdFromGuard('admin'),
                    'delivery_id' => (int)$request->delivery_id
                ]);
            }

            Chatflow::create([
                'conversation_id' => $cv->id,
                'from' => 'admin',
                'to'   => 'delivery',
                'message' => $request->message,
            ]);
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }

    public function fetchChatFlowByDeliveryID($delivery_id){
        try{
            //Get Conversation with chatflow
            $cnv = Conversation::where('admin_id', authIdFromGuard('admin'))
                    ->where('delivery_id', (int)$delivery_id)
                    ->with('chatflows')
                    ->first();

            if (!$cnv) return response([], 200);
            
            return response($cnv['chatflows'], 200);
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }
}
