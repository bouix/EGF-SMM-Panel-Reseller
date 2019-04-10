<?php
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */
namespace App\Http\Controllers\Admin;

use App\Ticket;
use App\TicketMessage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SupportController extends Controller
{

    public function __construct()
    {
        $this->middleware('VerifyModuleSupportEnabled');
    }

    public function index(Request $request)
    {
        mpc_m_c($request->server('SERVER_NAME'));
        return view('admin.support.ticket.index');
    }

    public function indexData()
    {
        $tickets = Ticket::with('user');
        return datatables()
            ->of($tickets)
            ->addColumn('action', 'admin.support.ticket.index-buttons')
            ->toJson();
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticketMessages = $ticket->messages;

        if (!$ticketMessages->isEmpty()) {
            foreach ($ticketMessages as $message) {
                if ($message->user_id != Auth::user()->id) {
                    $message->update(['is_read' => 1]);
                }
            }
        }

        return view('admin.support.messages.index', compact(
            'ticket',
            'ticketMessages'
        ));
    }

    public function edit($id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('admin.support.ticket.edit', compact(
            'ticket'
        ));
    }

    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'subject' => 'required',
            'description' => 'required'
        ]);

        Ticket::where(['id' => $id])->update([
            'subject' => $request->input('subject'),
            'description' => $request->input('description'),
            'status' => $request->input('status')
        ]);

        Session::flash('alert', __('messages.updated'));
        Session::flash('alertClass', 'success');
        return redirect('/admin/support/tickets/'.$id.'/edit');

    }

    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        Session::flash('alert', __('messages.deleted'));
        Session::flash('alertClass', 'success');
        return redirect('/admin/support/tickets');
    }

    public function message(Request $request, $id)
    {
        $this->validate($request, [
            'content' => 'required'
        ]);

        TicketMessage::create([
            'content' => $request->input('content'),
            'ticket_id' => $id,
            'user_id' => Auth::user()->id
        ]);

        return redirect('/admin/support/tickets/' . $id);
    }
}
