<?php
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class ApiMapping extends Model
{
    protected $fillable = [
        'package_id',
        'api_package_id',
        'api_id',
    ];
}