<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 22/09/15
 * Time: 17:00
 */
Route::get('/', 'MealsController@index');
Route::get('/prices', 'MealsController@prices');