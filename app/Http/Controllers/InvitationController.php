<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use App\User;
use App\Invitation;
use App\Attendee;

class InvitationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Invitation Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling invitations to events.
    |
    */

    /**
     * Create a new invitation for a given event
     * 
     * @param Request $request
     * @param Event $event
     */
    public function create(Request $request, Event $event)
    {
        // TODO: searching for users needs to be abstracted to a new function
        // and called by ajax
        $session = $request->session()->all();

        $username = (isSet($request->all()['username']) ? $request->all()['username'] : null);

        $users = [];

        if ($username) {
            $users = User::where('username', 'LIKE', "%{$username}%")->get();
        }

        $invitationUserIDs = Invitation::where('event_id', $event->id)
            ->pluck('user_id')
            ->toArray();

        $invitedUsers = User::whereIn('id', $invitationUserIDs)->get();

        return view('invitations.view', 
            [
                'event' => $event,
                'username' => $username,
                'users' => $users,
                'invitedUsers' => $invitedUsers,
                'session' => $session,
            ]
        );
    }


    /**
     * Send the invitation for a user for an event.
     * 
     * @param Request $request
     * @param Event $event
     * @param string $username
     */
    public function store(Request $request, Event $event, string $username)
    {
        $user = User::where('username', $username)->first();

        if (!$user) {
            $request->session()->flash('error', 'The invited user does not exist.');

            return redirect()->route('inviteUsers', ['event' => $event]);
        } 

        $invitation = Invitation::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        $attendee = Attendee::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();
        
        if ($attendee) {
            $request->session()->flash('error', $user->username . ' is already attending.');
        } elseif ($invitation) {
            $request->session()->flash('error', $user->username . ' is already invited.');
        } else {

            Invitation::create([
                'user_id' => $user->id,
                'event_id' => $event->id
            ]);

            $request->session()->flash('message', 'Invited ' . $user->username . '.');
        }

        // todo: dispatch email on queue
        return redirect()->route('inviteUsers', ['event' => $event]);
    }
}
