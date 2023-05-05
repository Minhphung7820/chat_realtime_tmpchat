<?php

namespace App\Repositories;

use App\Repositories\Interfaces\ChatRepositoryInterface;
use Illuminate\Http\Request;
use App\Jobs\InsertMessage;
use App\Models\Conversation;
use App\Models\User;
use Exception;

class ChatRepository implements ChatRepositoryInterface
{
    protected $userModel;
    protected $conversationModel;
    public function __construct(User $user, Conversation $conversation)
    {
        $this->userModel = $user;
        $this->conversationModel = $conversation;
    }
    public function sendMessage(Request $request)
    {
        try {
            InsertMessage::dispatch([
                'user_id' => $request->sender,
                'conversation_id' => $request->conversation,
                'message' => $request->message,
                'created_at' => now()
            ]);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    public function loadMoreMessages(Request $request)
    {
        try {
            $conversation = $this->conversationModel->find($request->conversation);
            $userChat  = $this->userModel->find($request->user);
            $friendChat = $this->userModel->find($request->friend);
            $messages = $conversation->getMessagesBetweenUsers($userChat, $friendChat, $request->idMinMessage);
            $result = array_reverse(collect($messages)->toArray());
            if (count($result) > 0) {
                return response()->json(["data" => true, 'result' => $result]);
            }
            return response()->json(["data" => false, 'result' => null]);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
