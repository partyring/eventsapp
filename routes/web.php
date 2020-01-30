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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/create-event', 'EventController@index')->name('createEvent');
Route::post('/create-event', 'EventController@create')->name('postEvent');
Route::get('/created/{event}', 'EventController@created')->name('eventCreated');

Route::get('/events', 'EventController@view')->name('allEvents');
Route::get('events/{event}', 'EventController@show')->name('showEvent');
