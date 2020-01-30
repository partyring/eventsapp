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



    public function event()
    {
        return $this->belongsTo('App\Event');
    }


    public function tag()
    {
        return $this->belongsTo('App\Tag');
    }
}
