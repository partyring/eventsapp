<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Tag;
use App\Image;
use App\EventTag;
use App\InvitedUser;
use Carbon\Carbon;
use Storage;

class Event extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'user_id', 'description', 'date_start', 'date_end', 'private',
    ];


    /**
     * An event belongs to a user
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }


    /**
     * An event has many tags
     */
    public function tags()
    {
        return $this->belongsToMany('App\Tag')
            ->using('App\EventTag')
            ->orderBy('name');
    }

    /**
     * An event has many invitations
     */
    public function invitations()
    {
        return $this->belongsToMany('App\User', 'invitations');
    }


    /**
     * An event has many attendees
     */
    public function attendees()
    {
        return $this->hasMany('App\Attendee');
    }


    /**
     * Get the event's image.
     */
    public function image()
    {
        return $this->morphOne('App\Image', 'imageable');
    }


    /**
     * Get event tag IDs
     */
    public function tagIDs()
    {
        return $this->tags()->pluck('tag_id');
    }


    /**
     * Get only future events
     */
    public function scopeFutureEventsOnly($query)
    {
        return $query->where('date_start', '>', Carbon::now());
    }


    /**
     * Get only past events
     */
    public function scopePastEventsOnly($query)
    {
        return $query->where('date_start', '<=', Carbon::now());
    }


    /**
     * Get events created by a user
     */
    public function scopeCreatedBy($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }


    /**
     * Events visible to a user
     */
    public function scopeViewableBy($query, User $user) 
    {
        // We only want events that are
        // 1. Created by the Auth user
        // 2. The Auth user is invited to
        // 3. The event is public

        $id = $user->id;

        return $query->with('invitations')
            ->where('user_id', $id)
            ->orWhere('private', 0)
            ->orWhereHas('invitations', function ($q) use ($id) {
                $q->where('user_id', $id);
            });
    }


    /**
     * Events which are happening now
     */
    public function ongoingEvents()
    {
        $now = Carbon::now();

        return Event::where('date_start', '<', $now)
            ->where('date_end', '>', $now);
    }


    /**
     * Check if event was created by the given user
     */
    public function wasCreatedBy(User $user): bool
    {
        if ($this->user_id == $user->id) {
            return true;
        }

        return false;
    }


    /**
     * If an event can be viewed by a user
     * 
     * @param User $user
     */
    public function canBeViewedBy(User $user)
    {
        if (!$this->private) {

            return true;
        }

        if ($this->user_id == $user->id) {

            return true;
        }

        $invitedUsers = $this->invitations()->pluck('user_id')->toArray();

        if (in_array($user->id, $invitedUsers)) {

            return true;
        }

        return false;
    }


    /**
     * Reusable function to check if event is in the future
     * 
     * @return bool
     */
    public function isInFuture(): bool
    {
        if ($this->date_start > Carbon::now())
        {
            return true;
        }

        return false;
    }


    /**
     * Human readable formatted date for start of event
     * 
     * @return string
     */
    public function dateStartFriendly(): string
    {
        return Carbon::parse($this->date_start)->format('d-m-Y');
    }


    /**
     * Retrieve the location for the event image as found in storage.
     * Used for display in Blade files.
     * 
     * TODO: remove generic image and think of a better idea
     * 
     * TODO: the relationship here is totally weird and this is excess code
     * fix that!
     */
    public function mainImageURL()
    {
        if ($this->image()->first()) {
            $imageLocation = $this->image()->first()->location;
        } else {
            // If the image cannot be displayed, show a generic image.
            $imageLocation = 'generic/generic1.jpg';
        }

       return Storage::url($imageLocation);
    }


    /**
     * Get the number of attending users
     * 
     * @return int
     */
    public function numberOfAttendees(): int
    {
        return $this->attendees()->count();
    }
}
