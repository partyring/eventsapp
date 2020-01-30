<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Tag;
use App\EventTag;

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


    public function user()
    {
        return $this->belongsTo('App\User');
    }


    public function tags()
    {
        return $this->belongsToMany('App\Tag')->using('App\EventTag')->orderBy('name');
    }


    public function tagIDs()
    {
        return $this->tags()->pluck('tag_id');
    }


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


    public function scopeCreatedByUser($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }


}
