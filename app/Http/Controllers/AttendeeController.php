<?php

namespace App\Http\Controllers;
use App\Attendee;
use App\Event;
use App\User;

use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    public function create(Request $request, Event $event, User $user)
    {
        if (!$user->hasPermissionToAttend($event)) {
            dd('403');
        }

        $attendee = Attendee::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();
        
        if (!$attendee) {
            Attendee::create([
                'user_id' => $user->id,
                'event_id' => $event->id
            ]);

            $request->session()->flash('success', 'You are now attending this event!');
    
        } else {
            $request->session()->flash('error', 'You are already attending this event!');
        }

        return redirect()->route('viewEvent', ['event' => $event]);
    }


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
            }

            $request->session()->flash('success', 'You are no longer attending this event.');

        }

        return redirect()->route('viewEvent', ['event' => $event]);
    }
}
