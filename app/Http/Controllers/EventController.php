<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Auth;
use Carbon\Carbon;
use App\Event;
use App\Tag;
use App\EventTag;
use App\User;

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
        $tags = Tag::orderBy('name')->get();
        return view('events/create', ['tags' => $tags]);
    }


    public function view(Event $event)
    {
        if ($event->canBeViewedBy(Auth::user())) {
            return view('events/view', ['event' => $event]);
        }
        

        return dd('403');
    }

    
    public function viewAll()
    {
        $events = Event::viewableBy(Auth::user())->get();

        return view('events/viewAll', ['events' => $events]);
    }


    public function create(Request $request)
    {
        $data = $request->all();   

        $dateStart = $data['dateStart'];
        $timeStart = $data['timeStart'];

        $private = 1;        

        if ($data['privacyType'] == "public") {
            $private = 0;
        }

        $event = Event::create([
            'name' => $data['eventName'],
            'user_id' => Auth::id(),
            'description' => $data['description'],
            'private' => $private,
            'date_start' => $data['dateStart'],
            'date_end' => $data['dateEnd']
        ]);

        if (isSet($data['tags'])) {
            $tags = $data['tags'];

            foreach ($tags as $tag) {
                $eventsTags = EventTag::create([
                    'event_id' => $event->id,
                    'tag_id' => $tag
                ]);
            }
        }


        return redirect()->route('eventCreated', ['event' => $event]);
    }


    public function created(Event $event)
    {
        return view('events/created', ['event' => $event]);
    }



    public function edit(Event $event)
    {
        $this->checkPermissionToEdit(Auth::user(), $event);

        $tags = Tag::all();
        $tagIDs = $event->tagIDs()->toArray();

        return view('events/edit', ['event' => $event, 'tags' => $tags, 'tagIDs' => $tagIDs]);
    
    }


    public function update(Request $request, Event $event)
    {
        $this->checkPermissionToEdit(Auth::user(), $event);

        $data = $request->all();

        $dateStart = $data['dateStart'];
        $timeStart = $data['timeStart'];


        $event->name = $data['eventName'];
        $event->description = $data['description'];
        $event->date_start = $data['dateStart'];
        $event->date_end = $data['dateEnd'];
        $event->save();

        // Remove tags that have been untagged
        // Tag new tags to event

        if (!isSet($data['tags'])) {
            $newTags = [];
        } else {
            $newTags = $data['tags'];
        }          

        $event->tags()->sync($newTags);
        
        return redirect()->route('viewEvent', ['event' => $event, 'message' => 'Event updated.']);
    }


    private function checkPermissionToEdit(User $user, Event $event)
    {
        if (!$user->canEditEvent($event)) {
            dd('403');
        }
    }

    

}
