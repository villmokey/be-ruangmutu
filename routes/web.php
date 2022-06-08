<?php

use Illuminate\Support\Facades\Route;

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
    return response()->json(['status' => 'ok']);
});

Route::get('jwt',function(){
    Artisan::call("key:generate");
    Artisan::call("jwt:secret");
    Artisan::call("cache:clear");
    Artisan::call("config:clear");
});
