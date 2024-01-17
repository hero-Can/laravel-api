<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\AuthUserController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix'=>'user'],function(){
    Route::post('register',[AuthUserController::class,'register']);
    Route::post('login',[AuthUserController::class,'login']);
    Route::group(['middleware'=>'jwt.auth'],function(){
        Route::post('profile',function(){
          // return Auth::user(); // return authenticated user data
          return JWTAuth::parseToken()->getPayload(); // return payload
        //  $token = $request->header('auth-token');
        //  return $payload = JWTAuth::getPayload($token);
        });
     });

});
