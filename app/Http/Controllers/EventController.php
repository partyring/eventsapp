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

    public function view()
    {
        $events = Event::all();

        return view('events/view', ['events' => $events]);
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
}
