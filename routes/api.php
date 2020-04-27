<?php

use Illuminate\Support\Facades\Route;

Route::post('register/register', 'RegisterController@register');
Route::post('register/verify-email', 'RegisterController@verifyEmail');
Route::post('login', 'LoginController@login');
Route::post('logout', 'LoginController@logout')->middleware('auth:api');
Route::post('reset-password/send-code', 'ResetPasswordController@sendCode');
Route::post('reset-password/reset-password', 'ResetPasswordController@resetPassword');

Route::get('categories/tree', 'CategoriesController@tree');
Route::apiResource('categories', 'CategoriesController')->only(['index', 'show']);
Route::apiResource('tags', 'TagsController')->only(['index', 'show']);
Route::apiResource('photos', 'PhotosController')->only(['index', 'show']);
Route::apiResource('posts', 'PostsController')->only(['index', 'show']);
Route::post('feedback', 'FeedbackController@store');

Route::middleware('auth:api')->group(function () {
    Route::apiResource('roles', 'RolesController')->only(['index']);
    Route::apiResource('photos', 'PhotosController')->except(['index', 'show']);
    // Symfony bug - handling file validation on PUT requests
    Route::post('photos/{photo}', 'PhotosController@update');
    Route::post('favorites/{model}/{id}', 'FavoritesController@store');
    Route::delete('favorites/{model}/{id}', 'FavoritesController@destroy');
    Route::apiResource('posts', 'PostsController')->except(['index', 'show']);
});

Route::middleware('owner')->group(function () {
    Route::get('users/{user}/phones', 'UsersPhonesController@show');
    Route::post('users/{user}/phones', 'UsersPhonesController@update');
    Route::apiResource('users', 'UsersController')->only(['show', 'update', 'destroy']);
});

Route::middleware('admin')->group(function () {
    Route::apiResource('categories', 'CategoriesController')->except(['index', 'show']);
    Route::apiResource('tags', 'TagsController')->except(['index', 'show']);
    Route::apiResource('users', 'UsersController')->except(['show', 'update', 'destroy']);
});
