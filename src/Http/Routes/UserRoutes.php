<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 9/09/15
 * Time: 15:30
 */

Route::get('/', 'UserController@index');
Route::get('/{id}', 'UserController@edit');