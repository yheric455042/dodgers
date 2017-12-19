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
Route::post('/createUser','UserController@createUser');
Route::get('/verifytoken/{token}','UserController@verifyToken');
Route::post('/logout','UserController@logout');

Route::get('/allInfo', 'MapController@allInfo');
Route::get('/getInfo/{lat}/{lng}/{distance}', 'MapController@getInfo');
Route::post('/addData', 'MapController@addData');

Route::get('/findRoad/{city}/{town}', 'MapController@findRoad');
Route::get('/findTown/{city}/', 'MapController@findTown');
