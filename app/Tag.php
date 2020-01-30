<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Event;
use App\EventTAg;

class Tag extends Model
{
    public function events()
    {
        return $this->belongsToMany('App\Event')->using('App\EventTag');
    }
}
