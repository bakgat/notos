<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 9/09/15
 * Time: 15:28
 */

Route::group(['prefix' => '/api', 'namespace'=>'Bakgat\Notos\Http\Controllers'], function() {
    Route::group(['prefix'=>'/user'], function() {
        include_once __DIR__.'/Routes/UserRoutes.php';
    });
});