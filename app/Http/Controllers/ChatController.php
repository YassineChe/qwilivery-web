<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chatflow;
use App\Models\Conversation;

class ChatController extends Controller
{
    //* Send message
    public function sendMsgFromMsger(Request $request){
        try{

            $conversation = Conversation::where('id', (int)$request->conversation_id)
                                        ->where('admin_id', authIdFromGuard('admin'))
                                        ->first();

            if($conversation){
                Chatflow::create([
                    'conversation_id' => $conversation->id,
                    'from' => 'admin',
                    'to'   => $request->to,
                    'message' => $request->message,
                ]);
            }
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }

    public function sendMsgOutMsger(Request $request){
        try{
            $conversation = Conversation::where('admin_id', authIdFromGuard('admin'))
                                ->where($request->guard.'_id', $request->guard_id)
                                ->first();

            if (!$conversation){
                $conversation = Conversation::create([
                    $request->guard.'_id' => $request->guard_id,
                    'admin_id' => authIdFromGuard('admin')
                ]);
            }

            if($conversation){
                Chatflow::create([
                    'conversation_id' => $conversation->id,
                    'from' => 'admin',
                    'to'   => $request->guard,
                    'message' => $request->message,
                ]);

                //Brodcasting event
                
                // Success message
                return dataToResponse('success', 'Succès !', "Message envoyé", false, 200);
            }
            
            return dataToResponse('error', 'Erreur !', "Quelque chose s'est mal passé, veuillez réessayer le plus tôt possible", false, 422);
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }

    public function fetchChatFlowByDeliveryID($delivery_id){
        try{
            //Get Conversation with chatflow
            $conversation = Conversation::where('admin_id', authIdFromGuard('admin'))
                    ->where('delivery_id', (int)$delivery_id)
                    ->with('chatflows')

                    ->first();

            if (!$conversation) return response([], 200);
            
            return response($conversation['chatflows'], 200);
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }

    //* Send message by delivery
    public function sendMsgFromMsgerDelivery(Request $request){

        $conversation = Conversation::where('delivery_id', authIdFromGuard('delivery'))
                        ->where('id', (int)$request->conversation_id)
                        ->first();

        if ($conversation){
            Chatflow::create([
                'conversation_id' => $conversation->id,
                'from' => 'delivery',
                'to'   => $request->to,
                'message' => $request->message,
            ]);
        }
    }
}
