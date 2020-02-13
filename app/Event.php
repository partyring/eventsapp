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


    public function user()
    {
        return $this->belongsTo('App\User');
    }


    public function tags()
    {
        return $this->belongsToMany('App\Tag')->using('App\EventTag')->orderBy('name');
    }


    public function invitedUsers()
    {
        return $this->belongsToMany('App\User', 'invited_users');
    }


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


    public function tagIDs()
    {
        return $this->tags()->pluck('tag_id');
    }


    public function pastEvents()
    {
        return Event::where('date_start', '<', Carbon::now());
    }


    public function scopeFutureEventsOnly($query)
    {
        return $query->where('date_start', '>', Carbon::now());
    }


    public function scopePastEventsOnly($query)
    {
        return $query->where('date_start', '<=', Carbon::now());
    }


    public function ongoingEvents()
    {
        $now = Carbon::now();

        return Event::where('date_start', '<', $now)
            ->where('date_end', '>', $now);
    }


    public function scopeCreatedBy($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }


    public function scopeViewableBy($query, User $user) 
    {
        // We only want events that are either
        // 1. Created by the Auth user
        // 2. The Auth user is invited to
        // 3. The event is public

        $id = $user->id;

        return $query->with('invitedUsers')
            ->where('user_id', $id)
            ->orWhere('private', 0)
            ->orWhereHas('invitedUsers', function ($q) use ($id) {
                $q->where('user_id', $id);
            });

    }


    public function canBeViewedBy(User $user)
    {
        if ($this->private == 0) {
            return true;
        }


        if ($this->user_id == $user->id) {
            return true;
        }

        $invitedUsers = $this->invitedUsers()->pluck('user_id')->toArray();

        if (in_array($user->id, $invitedUsers)) {
            return true;
        }


        return false;
    }


    public function isInFuture()
    {
        if ($this->date_start > Carbon::now())
        {
            return true;
        }

        return false;
    }


    /**
     * Human readable formatted date for start of event
     */
    public function dateStartFriendly()
    {
        return Carbon::parse($this->date_start)->format('d-m-Y');
    }


    /**
     * Retrieve the location for the event image as found in storage.
     * Used for display in Blade files.
     * 
     * TODO: remove generic image and think of a better idea
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


    public function numberOfAttendees()
    {
        return $this->attendees()->count();
    }

}
