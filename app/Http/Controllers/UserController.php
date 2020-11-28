<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Event;

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | User Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling users.
    |
    */

    /**
     * TODO: Show user profile
     * 
     * @param User $user
     */
    public function show(User $user)
    {
        $user = User::where('id', $user->id);

        dd($user);
    }


    // TODO :: move to events controller
    public function myEvents()
    {
        $user = Auth::user();

        $events = Event::createdBy(Auth::user())->get();

        return view('user/myEvents', ['events' => $events]);
    }

    // TODO: move to invitations controller
    public function viewEventInvitations(User $user)
    {
        $pendingInvitations = $user->pendingInvitations()->pluck('event_id');

        $events = Event::whereIn('id', $pendingInvitations)->get();
        $pendingInvitations = $events->count();

        return view ('events/invitedEvents', ['events' => $events, 'pendingInvitations' => $pendingInvitations]);
    }

}
