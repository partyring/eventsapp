<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\Event;

class EventController extends Controller
{

    // /**
    //  * Get a validator for an incoming registration request.
    //  *
    //  * @param  array  $data
    //  * @return \Illuminate\Contracts\Validation\Validator
    //  */
    // protected function validator(array $data)
    // {
    //     return Validator::make($data, [
    //         'name' => ['required', 'string', 'max:255'],
    //         'description' => ['required', 'string', 'max:255']
    //     ]);
    // }

    public function index() 
    {
        return view('events/create');
    }

    public function view(Event $event)
    {
        return view('events/view', ['event' => $event]);
    }

    public function viewAll()
    {
        $events = Event::all();

        return view('events/viewAll', ['events' => $events]);
    }

    public function create(Request $request)
    {
        $data = $request->all();

        $dateStart = $data['dateStart'];
        $timeStart = $data['timeStart'];

        $event = Event::create([
            'name' => $data['eventName'],
            'user_id' => Auth::id(),
            'description' => $data['description'],
            'date_start' => $data['dateStart'],
            'date_end' => $data['dateEnd']
        ]);


        return redirect()->route('eventCreated', ['event' => $event]);
    }


    public function created(Event $event)
    {
        return view('events/created', ['event' => $event]);
    }



    public function edit(Event $event)
    {
        return view('events/edit', ['event' => $event]);
    
    }


    public function update(Request $request, Event $event)
    {
        if (!Auth::user()->canEditEvent($event)) {
            dd('403');
        }

        $data = $request->all();

        $dateStart = $data['dateStart'];
        $timeStart = $data['timeStart'];

        $event->name = $data['eventName'];
        $event->description = $data['description'];
        $event->date_start = $data['dateStart'];
        $event->date_end = $data['dateEnd'];
        $event->save();
 

        return redirect()->route('viewEvent', ['event' => $event, 'message' => 'Event updated.']);
    }

    

}
