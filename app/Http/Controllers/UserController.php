<?php

namespace App\Http\Controllers;

use App\Events\SendMessage;
use App\Jobs\InsertMessage;
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
                $messages = $conversation->getMessagesBetweenUsers($userChat, $friendChat, null);
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
        InsertMessage::dispatch([
            'user_id' => $request->sender,
            'conversation_id' => $request->conversation,
            'message' => $request->message,
            'created_at' => now()
        ]);
    }

    public function loadMoreMessages(Request $request)
    {
        $conversation = Conversation::find($request->conversation);
        $userChat  = User::find($request->user);
        $friendChat = User::find($request->friend);
        $messages = $conversation->getMessagesBetweenUsers($userChat, $friendChat, $request->idMinMessage);
        $result = array_reverse(collect($messages)->toArray());
        if (count($result) > 0) {
            return response()->json(["data" => true, 'result' => $result]);
        }
        return response()->json(["data" => false, 'result' => null]);
    }
    public function searchFast(Request $request)
    {
        $key = $request->key;
        $result = User::where(function ($query) use ($key) {
            $query->where('name', 'like', '%' . $key . '%');
            $query->orWhere('email', 'like', '%' . $key . '%');
        })->orderBy('name', 'asc')->take(10)->get();
        if ($result) {
            return response()->json(['success' => true, 'message' => $result]);
        }
    }
}
