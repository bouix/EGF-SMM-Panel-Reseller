<?php
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'funds',
        'role',
        'status',
        'skype_id',
        'enabled_payment_methods',
        'api_token',
        'last_login',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }


    public function getStatusAttribute($status)
    {
        return title_case($status);
    }

    public function getlastLoginAttribute($date)
    {
        return is_null($date)
            ? ''
            : Carbon::createFromFormat('Y-m-d H:i:s', $date)->diffForHumans();
    }

    public function getCreatedAtAttribute($date)
    {
        return is_null($date)
            ? ''
            : Carbon::createFromFormat('Y-m-d H:i:s', $date)->timezone(config('app.timezone'))->toDateTimeString();
    }

    public function getUpdatedAtAttribute($date)
    {
        return is_null($date)
            ? ''
            : Carbon::createFromFormat('Y-m-d H:i:s', $date)->timezone(config('app.timezone'))->toDateTimeString();
    }


}
