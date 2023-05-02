<?php

namespace App\Http\Controllers;

use App\Events\SendMessage;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Conversation;

class UserController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }
    public function getFriends()
    {
        $myAccount = User::find(auth()->id());
        $myFriends  = $myAccount->friends()->get();
        return response()->json($myFriends);
    }
    public function getDetailConversation(Request $request)
    {
        switch ($request->type) {
            case 'conversation':
                $userId =  $request->user_id;
                $friendId = $request->friend_id;
                $conversationId = DB::table('conversation_user')
                    ->select('conversation_id')
                    ->whereIn('user_id', [$userId, $friendId])
                    ->groupBy('conversation_id')
                    ->havingRaw('COUNT(*) = 2')
                    ->pluck('conversation_id')
                    ->first();
                $userChat  = User::find($userId);
                $friendChat = User::find($friendId);
                $conversation = Conversation::find($conversationId);
                if ($conversation === null) {
                    return response()->json(["data" => false, 'result' => null, 'conversation_id' => null]);
                }
                $messages = $conversation->getMessagesBetweenUsers($userChat, $friendChat);
                $result = array_reverse(collect($messages)->toArray());
                return response()->json(["data" => true, 'result' => $result, 'conversation_id' => $conversationId]);
                break;
            default:
                break;
        }
    }
    public function uptimeLastActive(Request $request)
    {
        User::find($request->user_id)->update([
            'last_active' => $request->last_active
        ]);
    }
    public function sendMessage(Request $request)
    {
        $userSender = User::find($request->sender);
        Message::create([
            'user_id' => $request->sender,
            'conversation_id' => $request->conversation,
            'message' => $request->message,
            'created_at' => now()
        ]);
        broadcast(new SendMessage($userSender, $request->message, $request->conversation));
        return response()->json("ĐÃ gửi thành công");
    }
}
