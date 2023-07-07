<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::controller(ProductController::class)-> Group(function(){

    Route::get('create', 'create')->name('products.create');
    Route::get('index', 'index')->name('products.index');
    Route::post('submit', 'store')->name('products.submit');
});

Route::controller(ProductController::class)-> Group(function(){

    Route::put('edit/{id}', 'edit')->name('products.edit');
    Route::put('update/{id}', 'update')->name('products.update');
});

Route::controller(ProductController::class)-> Group(function(){

    Route::delete('delete/{id}', 'delete')->name('products.delete');
});