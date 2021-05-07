<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;

class ConversationController extends Controller
{
    //* Fetch admin conversation
    public function fetchConversations(){
        try{
            $connectedGuard = getConnectedGuard();
            return
                response(Conversation::where(getConnectedGuard().'_id', authIdFromGuard(getConnectedGuard()))
                            ->with([
                                'delivery', 
                                'restaurant', 
                                'admin',
                                'last_message', 
                                'unread_messages'
                            ])
                            ->withCount('unread_messages')
                            ->get(), 
                200);
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }
    
    //* Fetch Chatflow Conversation by its ID
    public function fetchChatflowAdminCnvId($conversation_id){
        try{
            $cnv = Conversation::where('id', (int)$conversation_id)
                    ->where(getConnectedGuard().'_id', authIdFromGuard(getConnectedGuard()))
                    ->with('chatflows')->first();

            return response($cnv ? $cnv->chatflows : [], 200);
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }
}
