<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Event;
use App\User;

class Attendee extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_id', 'user_id',
    ];

    /**
     * Attendance belongs to a user
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }


    /**
     * Attendance belongs to an event
     */
    public function event()
    {
        return $this->belongsTo('App\Event');
    }
}
