<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('product')->group(function() {
	Route::get('/', 'ProductController@index');
	Route::get('/get/{id}', 'ProductController@get');
	Route::post('/add', 'ProductController@create');
	Route::put('/update/{id}', 'ProductController@update');
	Route::delete('/delete/{id}', 'ProductController@destroy');
});

Route::prefix('transaction')->group(function() {
	Route::get('/', 'TransactionController@index');
	Route::post('/add', 'TransactionController@create');
});

Route::prefix('auth')->group(function() {
	Route::post('/login', 'AuthController@login');
	Route::post('/register', 'AuthController@register');
});
