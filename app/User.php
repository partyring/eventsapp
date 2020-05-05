<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function events()
    {
        return $this->hasMany('App\Event');
    }


    public function attendances()
    {
        return $this->hasMany('App\Attendee');
    }


    public function invitations()
    {
        return $this->hasMany('App\Invitation');
    }

    
    public function pendingInvitations()
    {
        return $this->invitations()
            ->where('accepted', 0);
    }


    public function canViewEvent(Event $event) 
    {
        // if the user is the author
        if ($event->user_id == $this->id) {
            return true;
        }
        
        // if event is public
        if ($event->private == 0) {
            return true;
        }

        // if user is invited
        if ($this->isInvitedTo($event)) {
            return true;
        }

        return false;
    }


    public function isInvitedTo(Event $event)
    {
        $invite = $this->getInviteFor($event);

        if ($invite) {
            return true;
        }

        return false;
    }


    public function getInviteFor(Event $event)
    {
        return Invitation::where('event_id', $event->id)
            ->where('user_id', $this->id)
            ->first();
    }


    /**
     * In the future this can be expanded to include admins
     * 
     */
    public function canEditEvent(Event $event)
    {
        if (Auth::id() == $event->user_id && $event->isInFuture()) {
            return true;
        }

        return false;
    }


    public function isAttendingEvent(Event $event)
    {
        $attending = Attendee::where('user_id', $this->id)
            ->where('event_id', $event->id)
            ->first();

        if ($attending) {
            return true;
        }

        return false;
    }


    public function hasPermissionToAttend(Event $event)
    {
        if ($this->canViewEvent($event)) {
            return true;
        }

        return false;
    }


    public function canRemoveAttendance(Event $event) 
    {
        // You must attend your own event
        if ($event->user_id == $this->id) {
            return false;
        }

        return true;
    }
}
