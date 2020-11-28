<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Event;
use App\Tag;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EventTag extends Pivot
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_id', 'tag_id',
    ];


    /**
     * Event Tag belongs to an event
     */
    public function event()
    {
        return $this->belongsTo('App\Event');
    }


    /**
     * Event tag belongs to a tag
     */
    public function tag()
    {
        return $this->belongsTo('App\Tag');
    }
}
