<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Event;
use App\EventTAg;

class Tag extends Model
{
    /**
     * Tag is owned by many events
     */
    public function events()
    {
        return $this->belongsToMany('App\Event')->using('App\EventTag');
    }
}
