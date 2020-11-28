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

/** TODO:: group routes */

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Event creation
Route::get('/events/create', 'EventController@create')->name('event.create');
Route::post('/events/create', 'EventController@store')->name('event.store');
Route::get('/events/{event}/created', 'EventController@created')->name('event.created');

// Update events
Route::get('events/{event}/edit', 'EventController@edit')->name('event.edit');
Route::post('events/{event}/edit', 'EventController@update')->name('event.update');

// View events
Route::get('/events', 'EventController@index')->name('event.index');
Route::get('events/{event}', 'EventController@view')->name('event.view');

// Invitations
Route::get('invitations/user/{user}', 'InvitationController@index')->name('invitation.index');

// Attendances
Route::get('user/{user}/attending', 'EventController@attending')->name('event.view-attending');

// Attend events
Route::post('events/{event}/{user}/attend', 'AttendeeController@create')->name('attendee.create');
Route::post('events/{event}/{user}/change-attend', 'AttendeeController@update')->name('attendee.update');

// Invitations
Route::get('events/{event}/invite-users', 'InvitationController@create')->name('invitation.create');
Route::post('events/{event}/invite-users/{username}', 'InvitationController@store')->name('invitation.store');

// User profile
Route::get('user/{user}', 'UserController@show')->name('user.show');
Route::get('user/{user}/events', 'UserController@myEvents')->name('user.events');

// Friendship
Route::get('friends', 'FriendsController@show')->name('friends.show');

