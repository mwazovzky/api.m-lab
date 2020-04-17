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
    // Symfony bug - handling file validation on PUT requests
    Route::post('photos/{photo}', 'PhotosController@update');
    Route::get('users/profile', 'UsersController@profile');
    Route::get('users/{user}/phones', 'UsersPhonesController@show');
    Route::post('users/{user}/phones', 'UsersPhonesController@update');
    Route::apiResources([
        'roles' => 'RolesController',
        'users' => 'UsersController',
        'categories' => 'CategoriesController',
        'tags' => 'TagsController',
        'photos' => 'PhotosController',
        'posts' => 'PostsController',
    ]);
    Route::post('favorites/{model}/{id}', 'FavoritesController@store');
    Route::delete('favorites/{model}/{id}', 'FavoritesController@destroy');
});
