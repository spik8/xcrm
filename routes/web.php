<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'PagesController@getIndex');
Route::get('/projekt', 'PagesController@getChoice');
Route::get('/form', 'PagesController@getForm');
Route::get('/badania', 'PagesController@getResearch');
Route::get('/wysylka', 'PagesController@getShipment');
Route::get('/getCity', 'PagesController@getCity');
Route::get('/storageResearch', 'PagesController@storageResearch');
Route::get('/gererateCSV', 'PagesController@gererateCSV');
Route::get('/searchFromData', 'PagesController@searchFromData');

