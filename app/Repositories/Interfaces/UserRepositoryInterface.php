<?php

namespace App\Repositories\Interfaces;

use Illuminate\Http\Request;

interface UserRepositoryInterface
{
    public function getFriends();
    public function getDetailConversation(Request $request);
    public function updateTimeLastActive(Request $request);
    public function searchFast(Request $request);
}
