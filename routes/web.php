<?php

use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth'])->group(function () {
  Route::get('/', [UserController::class, 'index']);
  Route::post('/load-detail-conversation', [UserController::class, 'getDetailConversation']);
  Route::get('/get-friends', [UserController::class, 'getFriends']);
  Route::post('/update-active', [UserController::class, 'uptimeLastActive']);
  Route::post('/send-message', [UserController::class, 'sendMessage']);
  Route::post('/load-more-messages', [UserController::class, 'loadMoreMessages']);
  Route::post('/search-fast-account', [UserController::class, 'searchFast']);
});



require __DIR__ . '/auth.php';
