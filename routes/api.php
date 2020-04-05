<?php

use App\Http\Resources\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('/register')->group(function () {
    Route::post('register', 'RegisterController@register');
    Route::post('verify-email', 'RegisterController@verifyEmail');
});

Route::post('login', 'LoginController@login');

Route::middleware('auth:api')->group(function () {
    Route::post('logout', 'LoginController@logout');
});

Route::prefix('/reset-password')->group(function () {
    Route::post('send-code', 'ResetPasswordController@sendCode');
    Route::post('reset-password', 'ResetPasswordController@resetPassword');
});

Route::middleware('auth:api')->group(function () {
    Route::get('users/profile', 'UsersController@profile');

    Route::apiResources([
        'users' => 'UsersController',
    ]);
});
