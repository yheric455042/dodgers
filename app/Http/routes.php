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

Route::get('/', function () {
    return view('welcome');
});



Route::post('/auth','UserController@auth');

Route::get('/allInfo', 'MapController@allInfo');
Route::post('/addData', 'MapController@addData');

Route::get('/findRoad/{city}/{town}', 'MapController@findRoad');
Route::get('/findTown/{city}/', 'MapController@findTown');
