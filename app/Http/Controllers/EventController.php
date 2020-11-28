<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreEventRequest;
use Storage;
use Auth;
use Carbon\Carbon;
use App\Event;
use App\Tag;
use App\EventTag;
use App\User;
use App\Image;
use App\Attendee;


class EventController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Event Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling events.
    |
    */

    /**
     * Page for creating event
     */
    public function create() 
    {
        $tags = Tag::orderBy('name')->get();

        return view('events/create', ['tags' => $tags]);
    }


    /**
     * Page for viewing a created event
     * 
     * @param Request $request
     * @param Event $event
     */
    public function view(Request $request, Event $event)
    {
        // Reflash any messages from potential redirects
        $request->session()->reflash();

        if (!$event->canBeViewedBy(Auth::user())) {#
            abort(403);
        }

        $imageURL = $event->mainImageURL();

        if ($event->wasCreatedBy(Auth::user())) {
            $userIsCreator = true;
        } else {
            $userIsCreator = false;
        }

        $session = $request->session()->all();

        return view('events/view', [
            'event' => $event, 
            'imageURL' => $imageURL, 
            'userIsCreator' => $userIsCreator,
            'session' => $session
        ]);
    }

    
    /**
     * Show all events
     * 
     * @param Request $request
     */
    public function index(Request $request)
    {
        if (isSet($_GET['past'])) {
            $past = $_GET['past'];
        } else {
            $past = 0;
        }

        $user = Auth::user();

        $events = Event::viewableBy($user);

        // todo: show invitations
        
        $pendingInvitations = $user->pendingInvitations()->count();

        if ($past) {
            $events = $events->pastEventsOnly()
                ->orderBy('date_start', 'desc');
        } else {
            $events = $events->futureEventsOnly()
                ->orderBy('date_start', 'asc');
        }

        $events = $events->paginate(10);

        return view('events/viewAll', [
            'events' => $events, 
            'past' => $past,
            'pendingInvitations' => $pendingInvitations
        ]);
    }


    /**
     * Save an event in the db
     * 
     * @param StoreEventRequest $request
     */
    public function store(StoreEventRequest $request)
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
        // TODO : Figure out privacy of public/private events and their images
        $path = $data['coverImage']
            ->store('event_' . $event->id, ['disk' => 'public']);

        $image = new Image;
        $image->location = $path;

        $event->image()->save($image);

        // Make creator attend event
        $this->createAttendanceFor(Auth::user(), $event);

        return redirect()->route('event.created', ['event' => $event]);
    }


    /**
     * TODO: this really belongs in a different class and is not
     * directly relevant to this controller as it needs to be
     * used by the attendee controller and the potential seeder
     * 
     * @param User $user
     * @param Event $event
     */
    private function createAttendanceFor(User $user, Event $event)
    {
        return Attendee::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);
    }


    /**
     * Success page on creation
     * 
     * @param Event $event
     */
    public function created(Event $event)
    {
        return view('events/created', ['event' => $event]);
    }


    /**
     * Edit an event
     * 
     * @param Event $event
     */
    public function edit(Event $event)
    {
        $this->checkPermissionToEdit(Auth::user(), $event);

        $tags = Tag::all();
        $tagIDs = $event->tagIDs()->toArray();

        return view('events/edit', 
            ['event' => $event, 'tags' => $tags, 'tagIDs' => $tagIDs]
        );
    }


    /**
     * Update an event
     * 
     * @param Request $request
     * @param Event $event
     */
    public function update(Request $request, Event $event)
    {
        $this->checkPermissionToEdit(Auth::user(), $event);

        // TODO: make a custom form request for editing or update creation one

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
        
        return redirect()->route('event.view', 
            ['event' => $event, 'message' => 'Event updated.']
        );
    }


    /**
     * TODO - this can probably be relocated to some middleware?
     * Check if user has permission to edit.
     * 
     * @param User $user
     * @param Event $event
     */
    private function checkPermissionToEdit(User $user, Event $event)
    {
        if (!$user->canEditEvent($event)) {
            abort(403);
        }
    }

    
    /**
     * This currently sits in EventController (rather than AttendeeController)
     * because it shows a combination of both 'invited' events and 'attending'
     * events.
     * 
     * @param User $user
     */
    public function attending(User $user)
    {
        // dd($_GET['invitedOnly']);
        $attendanceEventIDs = $user->attendances()->pluck('event_id');
        $invitationEventIDs = $user->invitations()->pluck('event_id');

        $eventsAttending = Event::whereIn('id', $attendanceEventIDs)->get();
        $eventsInvitedTo = Event::whereIn('id', $invitationEventIDs)->get();

        return view('user/attendingEvents', 
            ['eventsAttending' => $eventsAttending, 'eventsInvitedTo' => $eventsInvitedTo]
        );
    }
}
