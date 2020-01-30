<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Event;

class UserController extends Controller
{
    public function show(User $user)
    {
        $user = User::where('id', $user->id);

        dd($user);
    }


    public function myEvents()
    {
        $user = Auth::user();

        $events = Event::createdByUser(Auth::user())->get();

        return view('user/myEvents', ['events' => $events]);
    }
}
