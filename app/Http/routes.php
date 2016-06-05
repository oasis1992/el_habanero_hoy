<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::get('/', [
    'uses'  =>  'AddDataController@setInfo',
    'as'    =>  'set_info'
]);

Route::get('/mapa', [
    'uses'  =>  'AddDataController@map',
    'as'    =>  'map'
]);

Route::group(['prefix' => 'api'], function() {

    Route::get('/latitude/{lat}/longitude/{long}/now/{all?}', [
       'uses' => 'WebController@getByLocation',
        'as' => 'get_by_location'
    ]);
/*
    Route::get('/', [
        'uses' => '',
        'as' => ''
    ]);

    Route::get('/', [
        'uses' => '',
        'as' => ''
    ]);

    Route::get('/', [
        'uses' => '',
        'as' => ''
    ]);
*/

});




