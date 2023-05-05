<?php

namespace App\Repositories;

use App\Models\Conversation;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface
{
    protected $userModel;
    protected $conversationModel;
    public function __construct(User $user, Conversation $conversation)
    {
        $this->userModel = $user;
        $this->conversationModel = $conversation;
    }
    public function getFriends()
    {
        try {
            $myAccount = $this->userModel->find(auth()->id());
            $myFriends  = $myAccount->friends()->get();
            return response()->json($myFriends);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    public function getDetailConversation(Request $request)
    {
        try {
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
                    $userChat  = $this->userModel->find($userId);
                    $friendChat = $this->userModel->find($friendId);
                    $conversation = $this->conversationModel->find($conversationId);
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
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    public function updateTimeLastActive(Request $request)
    {
        try {
            $this->userModel->find($request->user_id)->update([
                'last_active' => $request->last_active
            ]);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    public function searchFast(Request $request)
    {
        try {
            $key = $request->key;
            $result = $this->userModel->where(function ($query) use ($key) {
                $query->where('name', 'like', '%' . $key . '%');
                $query->orWhere('email', 'like', '%' . $key . '%');
            })->orderBy('name', 'asc')->take(10)->get();
            if ($result) {
                return response()->json(['success' => true, 'message' => $result]);
            }
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
