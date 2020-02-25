<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use App\User;
use App\Invitation;
use App\Attendee;

class InvitationController extends Controller
{

    public function index(Request $request, Event $event)
    {
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
     * This uses the username rather than the user ID to avoid link spamming.
     * Although this is protected by the CSRF token, I believe it is best to
     * not allow a user to manually just use the ID (e.g. 1, 2, 3) to invite users as it
     * would be very easy to spam this. In the future I think that IDs should be
     * changed so it is not 1, 2, 3 but a GUID.
     */
    public function create(Request $request, Event $event, $username)
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
        
        if ($invitation) {
            $request->session()->flash('error', $user->username . ' is already invited.');
        } elseif ($attendee) {
            $request->session()->flash('error', $user->username . ' is already attending.');
        } else {

            Invitation::create([
                'user_id' => $user->id,
                'event_id' => $event->id
            ]);

            $request->session()->flash('message', 'Invited ' . $user->username . '.');

        }
        
        return redirect()->route('inviteUsers', ['event' => $event]);
        // todo: dispatch email on queue
    }
}
