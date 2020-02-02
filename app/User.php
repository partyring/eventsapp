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


    public function canViewEvent(Event $event) 
    {
        if ($event->private == 0) {
            return true;
        }

        return false;
    }


    /**
     * In the future this can be expanded to include admins
     * 
     */
    public function canEditEvent(Event $event)
    {
        if (Auth::id() == $event->user_id)
        {
            return true;
        }

        return false;
    }
}
