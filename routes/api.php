<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->namespace('Api')->group(function() {

    Route::prefix('search')->name('search.')->group(function() {
        Route::get('/', 'RealStateSearchController@index')->name('index');
        Route::get('/{id}', 'RealStateSearchController@show')->name('show');
    });

    Route::namespace('Auth')->name('api.')->group(function() {
        //login
        Route::post('/login', 'AuthController@login')->name('login');
        //logout
        Route::get('/logout', 'AuthController@logout')->name('logout');
        //refresh
        Route::get('/refresh', 'AuthController@refresh')->name('refresh');
    });
    
    Route::middleware(['api.auth'])->group(function() {        
        // real_states
        Route::resource('/real-states', 'RealStateController');

        // users
        Route::resource('/users', 'UserController');

        // categories
        Route::get('categories/{id}/real-states', 'CategoryController@realStates')
            ->name('categories.real-states');
        Route::resource('/categories', 'CategoryController');

        // photos 
        Route::prefix('photos')->name('photos.')->group(function() {
            Route::delete('/{id}', 'RealStatePhotoController@destroy')->name('destroy');
            Route::put('set-thumb/{id}', 'RealStatePhotoController@setThumb')->name('set-thumb');
        });
    });
});
