<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected  $fillable = [
        'quantity',
        'user_id',
        'package_id',
        'posts',
        'price',
        'status',
        'link',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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
