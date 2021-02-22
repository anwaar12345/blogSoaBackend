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
Route::group(['middleware' => ['ApiToken','auth:api']], function(){
	Route::get('/contacts', 'API\UserController@userContacts');
    Route::post('/create-contact','API\UserController@createContact');
    Route::get('/contact/{id}', 'API\UserController@getContactById');
    Route::put('update-contact/{id}','API\UserController@updateContactById');
    Route::get('userlogout','API\UserController@userLogout');
    Route::delete('delete-contact/{id}', 'API\UserController@deleteContactById');
});