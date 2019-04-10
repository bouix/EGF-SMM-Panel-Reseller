<?php
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */
namespace App\Http\Controllers\Admin;

use App\Order;
use App\Package;
use App\Ticket;
use App\TicketMessage;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        mpc_m_c($request->server('SERVER_NAME'));
        $totalSell = Order::whereIn('status',['COMPLETED','PARTIAL'])->sum('price');
        $totalOrdersCompleted = Order::whereIn('status',['COMPLETED', 'PARTIAL'])->count();
        $totalOrdersPending = Order::where(['status' => 'PENDING'])->count();
        $totalOrdersCancelled = Order::where(['status' => 'CANCELLED'])->count();
        $totalOrdersInProgress = Order::where(['status' => 'INPROGRESS'])->count();
        $totalOrders = Order::count();
        $totalUsers = User::where('id', '<>', Auth::user()->id)->count();
        $supportTicketOpen = Ticket::where(['status' => 'OPEN'])->count();
        $unreadMessages = TicketMessage::where(['is_read' => 0])->whereNotIn('user_id', [Auth::user()->id])->count();
        return view('admin.dashboard', compact(
            'totalSell',
            'totalOrdersCompleted',
            'totalOrdersPending',
            'totalOrdersCancelled',
            'totalUsers',
            'supportTicketOpen',
            'unreadMessages',
            'totalOrdersInProgress',
            'totalOrders',
            'totalUsers'
        ));
    }

    public function saveNote(Request $request)
    {
        setOption('admin_note', $request->input('admin_note'));
        return redirect('/admin');
    }

    public function refreshSystem(Request $request)
    {
        $url = url('/admin');
        Artisan::call('config:cache');
        return redirect($url);
    }
}
