<?php

Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'AuthController@login');
   // Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('payload', 'AuthController@payload');
    Route::post('signup', 'Auth\RegisterController@create');
    Route::post('signin', 'Auth\LoginController@login');
});


Route::middleware(['auth'])->group(function () {
    Route::get('user/get-products','UserController@getProducts');
    Route::get('user/get-ordered-products','UserController@getOrderedProducts');
    Route::get('logout', 'AuthController@logout');
    Route::resource('user', 'UserController'); // use Resoure routes, change routes, add all validations


    Route::middleware(['admin'])->group(function () {
        Route::resource('products', 'AdminController');
    });
});
