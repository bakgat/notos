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
use Intervention\Image\Facades\Image;

Route::group(['prefix' => '/auth', 'namespace' => 'Bakgat\Notos\Http\Controllers'], function () {
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
Route::group(['prefix' => '/api', 'namespace' => 'Bakgat\Notos\Http\Controllers'], function () {

    /*
     * ORGANIZATION SPECIFIC ROUTES
     */
    Route::group(['prefix' => '/organization/{orgId}'], function() {
        Route::group(['prefix' => '/user', 'namespace' => 'Identity'], function () {
            include_once __DIR__ . '/Routes/UserRoutes.php';
        });

        Route::group(['prefix' => '/blogs', 'namespace' => 'Location'], function () {
            include_once __DIR__ . '/Routes/BlogRoutes.php';
        });

        Route::group(['prefix' => '/books', 'namespace' => 'Resource'], function () {
            include_once __DIR__ . '/Routes/BooksRoutes.php';
        });
        Route::group(['prefix' => '/meals', 'namespace' => 'Resource'], function () {
            include_once __DIR__ . '/Routes/MealsRoutes.php';
        });

        Route::group(['prefix'=>'/calendar', 'namespace' => 'Event'], function() {
            include_once __DIR__ . '/Routes/EventRoutes.php';
        });
    });


    /*
     * GLOBAL ROUTES
     */
    Route::group(['prefix' => '/group', 'namespace' => 'Identity'], function () {
        include_once __DIR__ . '/Routes/GroupRoutes.php';
    });
    Route::group(['prefix' => '/realm', 'namespace' => 'Identity'], function() {
        include_once __DIR__ . '/Routes/RealmRoutes.php';
    });


    Route::group(['prefix' => '/websites', 'namespace' => 'Location'], function () {
        include_once __DIR__ . '/Routes/WebsitesRoutes.php';
    });
    Route::group(['prefix' => '/tags', 'namespace' => 'Descriptive'], function () {
        include_once __DIR__ . '/Routes/TagsRoutes.php';
    });

    Route::group(['prefix' => '/curricula/{course}', 'namespace' => 'Curricula'], function () {
        include_once __DIR__ . '/Routes/CurriculaRoutes.php';
    });



    Route::get('/user/profile', 'Identity\UserController@auth');
});