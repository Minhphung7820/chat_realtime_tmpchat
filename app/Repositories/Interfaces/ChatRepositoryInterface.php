<?php

namespace App\Repositories\Interfaces;

use Illuminate\Http\Request;

interface ChatRepositoryInterface
{
    public function loadMoreMessages(Request $request);
    public function sendMessage(Request $request);
}
