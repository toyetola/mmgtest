<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\WalletController;
use \App\Http\Controllers\TransactionController;
use \App\Http\Controllers\AdminController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('/login', [AuthController::class, 'authenticate'])->name('login');
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:api')->group(function (){
    Route::get('/logout', [AuthController::class, 'logout']);

    Route::resource('wallets', WalletController::class)->except([
        'create', 'destroy'
    ]);

    Route::post('/fundWallet', [TransactionController::class, 'fundWallet']);
    Route::post('/send', [TransactionController::class, 'sendToAnotherWallet']);
    Route::get('/users', [AdminController::class, 'getUsers']);
    Route::get('/users/user/{id}', [AdminController::class, 'getUser']);
    Route::get('/stats', [AdminController::class, 'stats']);
});






