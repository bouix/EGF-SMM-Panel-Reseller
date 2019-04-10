<?php
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class API extends Model
{
    protected $table = 'apis';
    protected $fillable = [
        'name',
        'order_end_point',
        'order_success_response',
        'order_method',
        'status_end_point',
        'status_method',
        'status_success_response',
        'order_id_key',
        'start_counter_key',
        'status_key',
        'remains_key',
        'process_all_order',
    ];
}
