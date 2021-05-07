<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chatflow;
use App\Models\Conversation;
use App\Events\MessageSent;

class ChatController extends Controller
{
    //* Send message
    public function sendMsgFromMsger(Request $request){
        try{

            $conversation = Conversation::where('id', (int)$request->conversation_id)
                                        ->where('admin_id', authIdFromGuard('admin'))
                                        ->first();

            if($conversation){
                $chatflow = Chatflow::create([
                    'conversation_id' => $conversation->id,
                    'from' => 'admin',
                    'to'   => $request->to,
                    'message' => $request->message,
                ]);

                switch($request->to){
                    case 'restaurant':
                        $recipient_id = $conversation->restaurant_id;
                        $guard = 'restaurant';
                        break;
                    case 'delivery':
                        $recipient_id = $conversation->delivery_id;
                        $guard   = 'delivery';
                        break;
                }

                event(new MessageSent($chatflow, $recipient_id, $guard));
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
                
                $chatflow = Chatflow::create([
                    'conversation_id' => $conversation->id,
                    'from' => 'admin',
                    'to'   => $request->guard,
                    'message' => $request->message,
                ]);
                

                switch($chatflow->to){
                    case 'restaurant':
                        $recipient_id = $conversation->restaurant_id;
                        $guard = 'restaurant';
                        break;
                    case 'delivery':
                        $recipient_id = $conversation->delivery_id;
                        $guard   = 'delivery';
                        break;
                }

                event(new MessageSent($chatflow, $recipient_id, $guard));
                
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
            $chatflow = Chatflow::create([
                'conversation_id' => $conversation->id,
                'from' => 'delivery',
                'to'   => $request->to,
                'message' => $request->message,
            ]);

            switch($request->to){
                case 'restaurant':
                    $recipient_id = $conversation->restaurant_id;
                    $guard = 'restaurant';
                break;
                case 'admin':
                    $recipient_id = $conversation->admin_id;
                    $guard   = 'admin';
                break;
            }

            event(new MessageSent($chatflow, $recipient_id, $guard));

        }
    }

    //* Mark all as read
    public function markAllAsRead(Request $request){
        try{
            $cnv = Conversation::select('id')
                            ->where('id', (int)$request->conversation_id)
                            ->where(getConnectedGuard().'_id', authIdFromGuard(getConnectedGuard()))
                            ->first();

            Chatflow::where('conversation_id', $cnv->id)
                        ->where('to', getConnectedGuard())
                        ->update(['seen_at' => \Carbon\Carbon::now()]);
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }
}
