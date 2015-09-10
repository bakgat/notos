<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 9/09/15
 * Time: 15:28
 */

/* ------------------------
 * AUTHENTICATION
 * ---------------------- */
use Illuminate\Support\Facades\Auth;

Route::group(['prefix'=>'/auth', 'namespace' => 'Bakgat\Notos\Http\Controllers'], function() {
    Route::get('/login', [
        'uses' => 'Identity\AuthController@getLogin',
        'as' => 'login'
    ]);
    Route::post('/login', [
        'uses' => 'Identity\AuthController@postLogin',
        'as' => 'login.do'
    ]);
    Route::get('/logout', [
        'uses' => 'Identity\AuthController@getLogout',
        'as' => 'logout'
    ]);
});



//Reset routes
Route::get('password/reset', [
    'uses' => 'Auth\PasswordController@remind',
    'as' => 'password.remind'
]);
Route::post('password/reset', [
    'uses' => 'Auth\PasswordController@request',
    'as' => 'password.request'
]);


/* ------------------------
 * API
 * ---------------------- */
Route::group(['prefix' => '/api', 'namespace'=>'Bakgat\Notos\Http\Controllers'], function() {
    Route::group(['prefix'=>'/organization/{orgId}/user', 'namespace' => 'Identity'], function() {
        include_once __DIR__.'/Routes/UserRoutes.php';
    });
});