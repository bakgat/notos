<?php
/**
* Created by PhpStorm.
* User: karlvaniseghem
* Date: 18/09/15
* Time: 11:55
*/

Route::get('/', 'CalendarController@index');
Route::get('/{id}', 'CalendarController@edit');
Route::put('/{id}', 'CalendarController@update');
Route::post('/', 'CalendarController@store');
Route::delete('/{id}', 'CalendarController@destroy');

Route::get('/between/{start}/{end}', 'CalendarController@between');
Route::get('/group/{groupId}', 'CalendarController@ofGroup');

