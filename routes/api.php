<?php

Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('payload', 'AuthController@payload');
    Route::post('signup', 'Auth\RegisterController@create');
    Route::post('signin', 'Auth\LoginController@login');
});

//Route::group([
//    'middleware' => ['auth'],
//], function ($router) {
//    Route::post('admin', 'AdminController@index');
//    Route::get('logout', 'AuthController@logout');
//});

Route::middleware(['auth'])->group(function () {
    Route::get('user', 'UserController@index');
    Route::get('logout','AuthController@logout');
    Route::post('addProduct','ProductController@add');
    Route::get('getProducts','ProductController@get');
    Route::post('orderedProducts','OrderedProductsController@order');
    Route::get('getProductsFromCard','OrderedProductsController@get');
    Route::delete('deleteOrderedProduct/{id}','OrderedProductsController@delete');
});


Route::middleware(['admin'])->group(function () {
    Route::get('user', 'UserController@index');
});
