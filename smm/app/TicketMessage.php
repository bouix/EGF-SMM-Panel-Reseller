<?php
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */
namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TicketMessage extends Model
{
    protected $fillable = [
        'content',
        'user_id',
        'ticket_id',
        'is_read'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCreatedAtAttribute($date)
    {
        return is_null($date)
            ? ''
            : Carbon::createFromFormat('Y-m-d H:i:s', $date)->timezone(config('app.timezone'))->diffForHumans();
    }

    public function getUpdatedAtAttribute($date)
    {
        return is_null($date)
            ? ''
            : Carbon::createFromFormat('Y-m-d H:i:s', $date)->timezone(config('app.timezone'))->diffForHumans();
    }

}
