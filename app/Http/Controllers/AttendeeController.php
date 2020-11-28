<?php

namespace App\Http\Controllers;
use App\Attendee;
use App\Event;
use App\User;
use Illuminate\Http\Request;
class AttendeeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Attendee Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling the attendance of users to
    | an event 
    |
    */

    /**
     * Create new attendence
     * 
     * @param Request $request
     * @param Event $event
     * @param User $user
     */
    public function create(Request $request, Event $event, User $user)
    {
        if (!$user->hasPermissionToAttend($event)) {
            // TODO: move permissions checks to middleware
            abort(403);
        }

        // check to see if the user is already attending the event
        $attendee = Attendee::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();
        
        if (!$attendee) {
            // check to see if there are any invitations pending
            // if so, count this as invitation aecpted
            $invitation = $user->getInviteFor($event);
            if ($invitation) {
                $invitation->accepted = true;
                $invitation->save();
            }

            Attendee::create([
                'user_id' => $user->id,
                'event_id' => $event->id
            ]);

            $request->session()->flash('success', 'You are now attending this event!');
    
        } else {
            $request->session()->flash('error', 'You are already attending this event!');
        }

        return redirect()->route('event.view', ['event' => $event]);
    }
    

    /**
     * Update a user's attendance
     * 
     * @param Request $request
     * @param Event $event
     * @param User $user
     */
    public function update(Request $request, Event $event, User $user)
    {
        if (!$user->canRemoveAttendance($event)) {
            $request->session()->flash('error', 'You must attend your own event!');
        } else {

            $attendee = Attendee::where('user_id', $user->id)
                ->where('event_id', $event->id)
                ->first();

            if ($attendee) {
                $attendee->delete();

                // reverse invitation acceptance
                $invitation = $user->getInviteFor($event);
                if ($invitation) {
                    $invitation->accepted = false;
                    $invitation->save();
                }
            }

            $request->session()->flash('success', 'You are no longer attending this event.');

        }

        return redirect()->route('event.view', ['event' => $event]);
    }
}
