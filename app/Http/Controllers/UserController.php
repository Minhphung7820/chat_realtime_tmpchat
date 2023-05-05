<?php

namespace App\Http\Controllers;

use App\Jobs\InsertMessage;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Conversation;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserController extends Controller
{
    private UserRepositoryInterface $userRepo;
    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }
    public function index()
    {
        return view('dashboard');
    }
    public function getFriends()
    {
        return $this->userRepo->getFriends();
    }
    public function getDetailConversation(Request $request)
    {
        return $this->userRepo->getDetailConversation($request);
    }
    public function uptimeLastActive(Request $request)
    {
        return $this->userRepo->updateTimeLastActive($request);
    }
    public function searchFast(Request $request)
    {
        return $this->userRepo->searchFast($request);
    }
}
