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

Route::get('/events/create', 'EventController@index')->name('createEvent');
Route::post('/events/create', 'EventController@create')->name('postEvent');
Route::get('/created/{event}', 'EventController@created')->name('eventCreated');

Route::get('/events', 'EventController@viewAll')->name('allEvents');
Route::get('events/{event}', 'EventController@view')->name('viewEvent');
Route::get('events/{event}/edit', 'EventController@edit')->name('editEvent');
Route::post('events/{event}/edit', 'EventController@update')->name('updateEvent');
Route::post('events/{event}/{user}/attend', 'AttendeeController@create')->name('attendEvent');
Route::post('events/{event}/{user}/change-attend', 'AttendeeController@update')->name('updateAttend');

Route::get('user/{user}', 'UserController@show')->name('showUser');
Route::get('user/{user}/events', 'UserController@myEvents')->name('viewMyEvents');

Route::get('friends', 'FriendsController@show')->name('showFriends');

