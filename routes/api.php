<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('/register')->group(function () {
    Route::post('register', 'RegisterController@register');
    Route::post('verify-email', 'RegisterController@verifyEmail');
});

Route::post('login', 'LoginController@login');

Route::middleware('auth:api')->group(function () {
    Route::post('logout', 'LoginController@logout');
});
