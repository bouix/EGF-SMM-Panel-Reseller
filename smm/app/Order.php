<?php namespace App;
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'source',
        'status',
        'user_id',
        'link',
        'price',
        'package_id',
        'start_counter',
        'remains',
        'api_id',
        'api_order_id',
        'custom_comments',
        'quantity',
        'subscription_id',
    ];


    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function api()
    {
        return $this->belongsTo(API::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusAttribute($status)
    {
        return title_case($status);
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
