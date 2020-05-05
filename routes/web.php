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

// Event creation
Route::get('/events/create', 'EventController@startCreation')->name('createEvent');
Route::post('/events/create', 'EventController@create')->name('postEvent');
Route::get('/created/{event}', 'EventController@created')->name('eventCreated');

// View events
Route::get('/events', 'EventController@index')->name('allEvents');
Route::get('events/{event}', 'EventController@view')->name('viewEvent');
Route::get('user/{user}/event-invitations', 'UserController@viewEventInvitations')->name('viewEventInvitations');

Route::get('{user}/attending', 'EventController@attending')->name('viewAttending');

// Update events
Route::get('events/{event}/edit', 'EventController@edit')->name('editEvent');
Route::post('events/{event}/edit', 'EventController@update')->name('updateEvent');

// Attend events
Route::post('events/{event}/{user}/attend', 'AttendeeController@create')->name('attendEvent');
Route::post('events/{event}/{user}/change-attend', 'AttendeeController@update')->name('updateAttend');

// Invitations
Route::get('events/{event}/invite-users', 'InvitationController@index')->name('inviteUsers');
Route::post('events/{event}/invite-users/{username}', 'InvitationController@create')->name('sendInvitation');

// User profile
Route::get('user/{user}', 'UserController@show')->name('showUser');
Route::get('user/{user}/events', 'UserController@myEvents')->name('viewMyEvents');

// Friendship
Route::get('friends', 'FriendsController@show')->name('showFriends');

