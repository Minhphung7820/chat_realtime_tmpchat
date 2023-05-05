<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\ChatRepositoryInterface;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    private ChatRepositoryInterface $chatRepo;
    public function __construct(ChatRepositoryInterface $chatRepo)
    {
        $this->chatRepo = $chatRepo;
    }
    public function sendMessage(Request $request)
    {
        return $this->chatRepo->sendMessage($request);
    }

    public function loadMoreMessages(Request $request)
    {
        return $this->chatRepo->loadMoreMessages($request);
    }
}
