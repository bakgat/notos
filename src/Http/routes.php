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

Route::get('/info', function () {
    return phpinfo();
});

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
    Route::group(['prefix' => '/organization/{orgId}'], function () {

        Route::group(['prefix' => '/upload'], function () {
            Route::get('/', 'Resource\AssetsController@index');
            Route::post('/', 'Resource\AssetsController@uploadFile');
            Route::delete('/file/{guid}', 'Resource\AssetsController@deleteFile');
            Route::post('/url', 'Resource\AssetsController@importUrl');
        });

        Route::group(['prefix' => '/assets', 'namespace' => 'Resource'], function () {
            require __DIR__ . '/Routes/AssetsRoutes.php';
        });

        Route::group(['prefix' => '/user', 'namespace' => 'Identity'], function () {
            require __DIR__ . '/Routes/UserRoutes.php';
        });
        Route::group(['prefix' => '/group', 'namespace' => 'Identity'], function () {
            require __DIR__ . '/Routes/GroupRoutes.php';
        });

        Route::group(['prefix' => '/blogs', 'namespace' => 'Location'], function () {
            require __DIR__ . '/Routes/BlogRoutes.php';
        });

        Route::group(['prefix' => '/books', 'namespace' => 'Resource'], function () {
            require __DIR__ . '/Routes/BooksRoutes.php';
        });
        Route::group(['prefix' => '/meals', 'namespace' => 'Resource'], function () {
            require __DIR__ . '/Routes/MealsRoutes.php';
        });

        Route::group(['prefix' => '/calendar', 'namespace' => 'Event'], function () {
            require __DIR__ . '/Routes/EventRoutes.php';
        });

        Route::get('/', 'Identity\OrganizationController@edit');

    });


    /*
     * GLOBAL ROUTES
     */

    Route::get('/group/levels', 'Identity\GroupController@indexLevels');


    Route::group(['prefix' => '/realm', 'namespace' => 'Identity'], function () {
        require __DIR__ . '/Routes/RealmRoutes.php';
    });


    Route::get('/websites/assets/mime/{mime}', 'Resource\AssetsController@imagesForWebsite');
    Route::group(['prefix' => '/websites', 'namespace' => 'Location'], function () {
        require __DIR__ . '/Routes/WebsitesRoutes.php';
    });


    Route::group(['prefix' => '/tags', 'namespace' => 'Descriptive'], function () {
        require __DIR__ . '/Routes/TagsRoutes.php';
    });
    Route::get('/publishers', 'Identity\PartyController@publishers');
    Route::get('/authors', 'Identity\PartyController@authors');


    Route::group(['prefix' => '/curricula/{course}', 'namespace' => 'Curricula'], function () {
        require __DIR__ . '/Routes/CurriculaRoutes.php';
    });

    Route::get('/user/profile', 'Identity\UserController@auth');

    Route::group(['prefix' => '/image', 'namespace' => 'Resource'], function () {
        require __DIR__ . '/Routes/ImageRoutes.php';
    });

    Route::post('/upload', 'Resource\AssetsController@uploadFile');
    Route::post('/upload/url', 'Resource\AssetsController@importUrl');
});

/* ------------------------
 * UPLOAD
 * ---------------------- */
/*Route::group(['prefix' => '/upload', 'namespace' => 'Bakgat\Notos\Http\Controllers'], function () {
    Route::get('/', 'Resource\AssetsController@index');
    Route::post('/file', 'Resource\AssetsController@uploadFile');
    Route::delete('/file/{guid}', 'Resource\AssetsController@deleteFile');
});*/