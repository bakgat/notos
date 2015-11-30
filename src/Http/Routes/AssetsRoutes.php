<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 18/11/15
 * Time: 13:52
 */

Route::get('/', 'AssetsController@index');
Route::get('/mime/{type}', 'AssetsController@ofType');