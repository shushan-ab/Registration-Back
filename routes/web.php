<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/regs', function () {

    $regs = App\Registration::all();
        //App\Registration::all();
    //dd($reg);
    return view('welcome',compact('regs'));
});

Route::get('/regs/{reg}', function ($id) {

    $reg = App\Registration::find($id);
    return view('show',compact('reg'));
});
