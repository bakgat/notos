<?php

/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 16/09/15
 * Time: 11:26
 */
Route::get('/', 'WebsitesController@index');
Route::get('/{id}', 'WebsitesController@edit');


