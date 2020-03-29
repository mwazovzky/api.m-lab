<?php

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
