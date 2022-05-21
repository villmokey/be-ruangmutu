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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('dashboard',[\App\Http\Controllers\Cms\Dashboard\DashboardController::class,'index'])->name('dashboard');
Route::resource('news',\App\Http\Controllers\Cms\Content\NewsController::class);

Route::get('data-news',[\App\Http\Controllers\Datatable\Content\NewsController::class,'getAll']);
