<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes TA-ITN-UP-Send
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('Auth')->prefix('auth')->group(function () {
    Route::post('register', 'RegisterController');
    Route::post('login', 'LoginController');
    Route::post('logout', 'LogoutController');
});

Route::namespace('Event')->prefix('event')->group(function() {
    // get all event
    Route::post('/', 'EventController@index');
    // create event
    Route::post('create', 'EventController@store');
    // update event
    Route::put('update', 'EventController@update');
    // show event by ID
    Route::get('show', 'EventController@show');
    // delete event
    Route::delete('delete', 'EventController@destroy');
    // search event
    Route::get('search', 'EventController@searchEvent');

    // registration to event
    Route::post('registration', 'RegisterEventController');
    // come to event
    Route::post('joined', 'JoinEventController');
   
    Route::prefix('participant')->group(function() {
        // list of participant registration on event
        Route::post('/', 'ParticipantController@registration');
        
        // list of participant coming on event
        Route::post('come', 'ParticipantController@come');
    });
});


