<?php namespace App\Http\Controllers;
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */
use App\Ticket;
use App\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function index()
    {
        $spentAmount = 0;
        $ordersPending = 0;
        $ordersCancelled = 0;
        $ordersCompleted = 0;
        $ordersPartial = 0;
        $ordersInProgress = 0;
        $orders = Auth::user()->orders;
        $ticketIds = Ticket::where(['user_id' => Auth::user()->id])->get()->pluck('id')->toArray();
        $unreadMessages = TicketMessage::where(['is_read' => 0])->whereIn('ticket_id', $ticketIds)->whereNotIn('user_id', [Auth::user()->id])->count();
        $supportTicketOpen = Ticket::where(['status' => 'OPEN', 'user_id' => Auth::user()->id])->count();

        foreach ($orders as $order) {
            if (strtolower($order->status) == 'pending') {
                $spentAmount += $order->price;
                $ordersPending++;
            } elseif (strtolower($order->status) == 'cancelled') {
                $ordersCancelled++;
            } elseif (strtolower($order->status) == 'completed') {
                $spentAmount += $order->price;
                $ordersCompleted++;
            } elseif (strtolower($order->status) == 'partial') {
                $spentAmount += $order->price;
                $ordersCompleted++;
            } elseif (strtolower($order->status) == 'inprogress') {
                $ordersInProgress++;
            }
        }
        return view('dashboard', compact(
            'spentAmount',
            'ordersPending',
            'ordersCancelled',
            'ordersCompleted',
            'unreadMessages',
            'ordersPartial',
            'supportTicketOpen',
            'ordersInProgress'
        ));


    }
}
