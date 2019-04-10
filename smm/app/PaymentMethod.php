<?php
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */
namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'status',
        'slug',
        'config_key',
        'config_value',
        'is_disabled_default',
    ];


    public function getStatusAttribute($status)
    {
        return title_case($status);
    }

}
