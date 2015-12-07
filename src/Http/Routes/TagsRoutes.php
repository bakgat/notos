<?php

/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 19/09/15
 * Time: 23:00
 */
Route::get('/', 'TagController@index');
Route::get('/type/{type}', 'TagController@index');