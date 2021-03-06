<?php

/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 16/09/15
 * Time: 11:26
 */
Route::get('/', 'WebsitesController@index');
Route::get('/full', 'WebsitesController@fullIndex');
Route::get('/{id}', 'WebsitesController@edit');
Route::post('/', 'WebsitesController@store');
Route::post('/suggest', 'WebsitesController@suggest');
Route::put('/{id}', 'WebsitesController@update');
Route::delete('/{id}', 'WebsitesController@destroy');

Route::post('/url', 'WebsitesController@ofUrl');

