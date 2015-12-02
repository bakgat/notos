<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 22/09/15
 * Time: 17:00
 */
Route::get('/', 'BooksController@index');
Route::get('/{id}', 'BooksController@edit');
Route::put('/{id}', 'BooksController@update');
Route::post('/', 'BooksController@store');
Route::delete('/{id}', 'BooksController@destroy');