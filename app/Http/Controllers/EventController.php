<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreEventRequest;
use Auth;
use Carbon\Carbon;
use App\Event;
use App\Tag;
use App\EventTag;
use App\User;
use App\Image;


class EventController extends Controller
{

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

    
    public function viewAll(Request $request)
    {
        if (isSet($_GET['past'])) {
            $past = $_GET['past'];
        } else {
            $past = 0;
        }

        $events = Event::viewableBy(Auth::user());

        if ($past) {
            $events = $events->pastEventsOnly()
                ->orderBy('date_start', 'desc');
        } else {
            $events = $events->futureEventsOnly()
                ->orderBy('date_start', 'asc');
        }

        $events = $events->paginate(10);
                    

        return view('events/viewAll', ['events' => $events, 'past' => $past]);
    }


    public function create(StoreEventRequest $request)
    {
        $data = $request->validated();  

        // $dateStart = $data['dateStart'];
        // $timeStart = $data['timeStart'];

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

        // Upload image to directory matching event id
        $path = $data['coverImage']->store('event_' . $event->id);


        $image = new Image;
        $image->location = $path;

        $event->image()->save($image);


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

        // $data = $request->validated();
        $data = $request->all();

        // $dateStart = $data['dateStart'];
        // $timeStart = $data['timeStart'];


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
