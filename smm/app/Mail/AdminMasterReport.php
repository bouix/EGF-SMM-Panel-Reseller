<?php
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */
namespace App\Mail;

use App\Order;
use App\Ticket;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class AdminMasterReport extends Mailable
{
    use Queueable, SerializesModels;

    public $today = [];
    public $month = [];
    public $lifetime = [];

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $this->today['orders']['COMPLETED'] = Order::whereDate('created_at', Carbon::now()->format('Y-m-d'))->whereIn('status', ['COMPLETED', 'PARTIAL'])->count();
        $this->today['orders']['PENDING'] = Order::whereDate('created_at', Carbon::now()->format('Y-m-d'))->whereIn('status', ['PENDING'])->count();
        $this->today['orders']['CANCELLED'] = Order::whereDate('created_at', Carbon::now()->format('Y-m-d'))->whereIn('status', ['CANCELLED'])->count();
        $this->today['orders']['INPROGRESS'] = Order::whereDate('created_at', Carbon::now()->format('Y-m-d'))->whereIn('status', ['INPROGRESS'])->count();

        $this->month['orders']['COMPLETED'] = Order::whereMonth('created_at', Carbon::now()->format('m'))->whereIn('status', ['COMPLETED', 'PARTIAL'])->count();
        $this->month['orders']['PENDING'] = Order::whereMonth('created_at', Carbon::now()->format('m'))->whereIn('status', ['PENDING'])->count();
        $this->month['orders']['CANCELLED'] = Order::whereMonth('created_at', Carbon::now()->format('m'))->whereIn('status', ['CANCELLED'])->count();
        $this->month['orders']['INPROGRESS'] = Order::whereMonth('created_at', Carbon::now()->format('m'))->whereIn('status', ['INPROGRESS'])->count();

        $this->lifetime['orders']['COMPLETED'] = Order::whereIn('status', ['COMPLETED', 'PARTIAL'])->count();
        $this->lifetime['orders']['PENDING'] = Order::whereIn('status', ['PENDING'])->count();
        $this->lifetime['orders']['CANCELLED'] = Order::whereIn('status', ['CANCELLED'])->count();
        $this->lifetime['orders']['INPROGRESS'] = Order::whereIn('status', ['INPROGRESS'])->count();

        $this->today['tickets']['OPEN'] = Ticket::whereDate('created_at', Carbon::now()->format('Y-m-d'))->whereIn('status', ['OPEN'])->count();
        $this->today['tickets']['CLOSED'] = Ticket::whereDate('created_at', Carbon::now()->format('Y-m-d'))->whereIn('status', ['CLOSED'])->count();

        $this->month['tickets']['OPEN'] = Ticket::whereMonth('created_at', Carbon::now()->format('m'))->whereIn('status', ['OPEN'])->count();
        $this->month['tickets']['CLOSED'] = Ticket::whereMonth('created_at', Carbon::now()->format('m'))->whereIn('status', ['CLOSED'])->count();

        $this->lifetime['tickets']['OPEN'] = Ticket::whereIn('status', ['OPEN'])->count();
        $this->lifetime['tickets']['CLOSED'] = Ticket::whereIn('status', ['CLOSED'])->count();

        $this->today['users']['new'] = User::whereDate('created_at', Carbon::now()->format('Y-m-d'))->count();
        $this->month['users']['new'] = User::whereMonth('created_at', Carbon::now()->format('m'))->count();

        $this->lifetime['users']['total'] = User::count();


        return $this->subject(__('mail.system_status_report'))
            ->markdown('mail.admin-master-report');
    }
}
