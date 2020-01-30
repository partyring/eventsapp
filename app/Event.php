<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'user_id', 'description', 'date_start', 'date_end',
    ];


    public function pastEvents()
    {
        return Event::where('date_start', '<', Carbon::now());
    }


    public function futureEvents()
    {
        return Event::where('date_start', '>', Carbon::now());
    }


    public function ongoingEvents()
    {
        $now = Carbon::now();

        return Event::where('date_start', '<', $now)
            ->where('date_end', '>', $now);
    }


    public function eventsCreatedByMe()
    {
        return Event::where('user_id', Auth::id());
    }


    public function eventsIAmAttending()
    {
        //
    }

}
