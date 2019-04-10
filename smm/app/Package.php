<?php namespace App;
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'name',
        'price_per_item',
        'minimum_quantity',
        'maximum_quantity',
        'description',
        'slug',
        'status',
        'service_id',
        'custom_comments',
        'preferred_api_id',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

}
