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


    /**
     * A user can have many events
     */
    public function events()
    {
        return $this->hasMany('App\Event');
    }


    /**
     * User can attend many events
     */
    public function attendances()
    {
        return $this->hasMany('App\Attendee');
    }


    /**
     * User can have many invitations
     */
    public function invitations()
    {
        return $this->hasMany('App\Invitation');
    }

    
    /**
     * Invitations not yet accepted
     */
    public function pendingInvitations()
    {
        return $this->invitations()
            ->where('accepted', 0);
    }


    /**
     * Check if user has permission to view event
     * 
     * @return bool
     */
    public function canViewEvent(Event $event): bool
    {
        // if the user is the author
        if ($event->user_id == $this->id) {

            return true;
        }
        
        // if event is public
        if (!$event->private) {

            return true;
        }

        // if user is invited
        if ($this->isInvitedTo($event)) {

            return true;
        }

        return false;
    }


    /**
     * Check if user is invited to event
     * 
     * @param Event $event 
     * @return bool
     */
    public function isInvitedTo(Event $event)
    {
        $invite = $this->getInviteFor($event);

        if ($invite) {
            return true;
        }

        return false;
    }


    /**
     * Errrr TODO: move this to invitation
     */
    public function getInviteFor(Event $event)
    {
        return Invitation::where('event_id', $event->id)
            ->where('user_id', $this->id)
            ->first();
    }


    /**
     * Check if user can edit event
     * TODO : In the future this can be expanded to include admins
     * TODO: this should actually be in event tbh
     * 
     * @param Event $event
     * @return bool
     */
    public function canEditEvent(Event $event): bool
    {
        if (Auth::id() == $event->user_id && $event->isInFuture()) {

            return true;
        }

        return false;
    }


    /**
     * Check if user is attending an event
     * Again, TODO: this should be in attendance
     * 
     * @param Event $event
     * @return bool
     */
    public function isAttendingEvent(Event $event): bool
    {
        $attending = Attendee::where('user_id', $this->id)
            ->where('event_id', $event->id)
            ->first();

        if ($attending) {

            return true;
        }

        return false;
    }


    /**
     * Check if user has permission to attend an event
     * 
     * @param Event $event
     */
    public function hasPermissionToAttend(Event $event)
    {
        if ($this->canViewEvent($event)) {

            return true;
        }

        return false;
    }


    /**
     * TODO: this seriously needs to sit in event
     * 
     * @param Event $event
     */
    public function canRemoveAttendance(Event $event) 
    {
        // You must attend your own event
        if ($event->user_id == $this->id) {
            return false;
        }

        return true;
    }
}
