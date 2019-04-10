<?php
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPackagePrice extends Model
{
    protected $fillable = [
        'user_id',
        'package_id',
        'price_per_item',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
