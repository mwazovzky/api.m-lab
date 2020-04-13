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

Route::middleware('auth:api')->group(function () {
    Route::get('roles', 'RolesController@index');
    Route::get('users/profile', 'UsersController@profile');
    Route::apiResources([
        'users' => 'UsersController',
        'categories' => 'CategoriesController',
        'tags' => 'TagsController',
        'posts' => 'PostsController',
    ]);
    Route::get('users/{user}/photos', 'UsersPhotosController@show');
    Route::post('users/{user}/photos', 'UsersPhotosController@store');
    Route::delete('users/{user}/photos', 'UsersPhotosController@destroy');
    Route::get('users/{user}/phones', 'UsersPhonesController@show');
    Route::post('users/{user}/phones', 'UsersPhonesController@update');
});
