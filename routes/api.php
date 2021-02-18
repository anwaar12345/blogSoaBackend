<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');

Route::group(['middleware' => 'ApiToken'], function(){
	Route::get('/contacts', 'API\UserController@users');
    Route::post('/create-contact','API\UserController@createContact');
    Route::get('/contact/{detail}', 'API\UserController@getUserById');
    Route::put('update/{id}','API\UserController@updateUserById');
});