<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\HasApiTokens;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');







#####################  Auth Api ##################################

Route::post('/signup', [AuthController::class, 'signup']);

Route::post('/signin', [AuthController::class, 'signin']);

Route::post('/forgot_password',[AuthController::class,'forgotPassword']);
