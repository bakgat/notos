<?php

/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 25/09/15
 * Time: 11:26
 */
Route::get('/', 'BlogController@index');
Route::get('/{id}', 'BlogController@edit');
Route::put('/{id}', 'BlogController@update');
Route::post('/', 'BlogController@store');