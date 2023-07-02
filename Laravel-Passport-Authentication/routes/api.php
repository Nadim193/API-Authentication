<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(UserController::class)-> Group(function(){

    Route::post('login', 'userlogin');
    Route::post('register', 'registersubmit');
});

Route::controller(UserController::class)-> Group(function(){

    Route::get('user', 'getuserdetails');
    Route::post('logout', 'userlogout');

})->middleware('auth:api');