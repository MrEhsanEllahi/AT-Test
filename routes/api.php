<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('user-login', [UserController::class, 'loginForToken']);

Route::group(['middleware' => 'VerifyUserToken'], function () {
    Route::group(['prefix' => 'at'], function () {
        Route::post('register', [UserController::class, 'register']);
        Route::post('login', [UserController::class, 'login']);
        Route::get('logout', [UserController::class, 'logout']);
        Route::get('profile', [UserController::class, 'profile']);
        Route::post('update-profile', [UserController::class, 'updateProfile']);
    });
});
