<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 9/09/15
 * Time: 15:30
 */

Route::get('/', 'UserController@index');
Route::get('/{id}', 'UserController@edit');
Route::put('/{userId}', 'UserController@update');
Route::patch('/{userId}/password', 'UserController@resetPassword');
Route::post('/', 'UserController@store');
Route::delete('/{userId}', 'UserController@destroy');