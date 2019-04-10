<?php namespace App\Http\Controllers;
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */
use App\Ticket;
use App\TicketMessage;
use Illuminate\Http\Request;
use App\Mail\TicketSubmitted;
use App\Mail\TicketNewMessage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\VerifyModuleSupportEnabled;

class SupportController extends Controller
{

    public function __construct()
    {
        $this->middleware(VerifyModuleSupportEnabled::class);
    }

    public function index()
    {
        return view('support.ticket.index');
    }

    public function indexData()
    {
        $tickets = Ticket::where(['user_id' => Auth::user()->id]);
        return datatables()
            ->of($tickets)
            ->editColumn('subject','{{ str_limit($subject,50) }}')
            ->editColumn('description','{{ str_limit($description,50) }}')
            ->addColumn('action', 'support.ticket.index-action-buttons')
            ->toJson();
    }

    public function create()
    {
        return view('support.ticket.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'subject' => 'required',
            'description' => 'required'
        ]);

        $ticket = Ticket::create([
            'subject' => $request->input('subject'),
            'description' => $request->input('description'),
            'user_id' => Auth::user()->id
        ]);

        Mail::to(getOption('notify_email'))->send(new TicketSubmitted($ticket));
        return redirect('/support');
    }

    public function show($id)
    {
        $ticket = Ticket::where(['id' => $id, 'user_id' => Auth::user()->id])->firstOrFail();
        $ticketMessages = $ticket->messages;

        if (!$ticketMessages->isEmpty()) {
            foreach ($ticketMessages as $message) {
                if($message->user_id != Auth::user()->id){
                    $message->update(['is_read' => 1 ]);
                }
            }
        }

        return view('support.messages.index', compact(
            'ticket',
            'ticketMessages'
        ));
    }

    public function message(Request $request, $id)
    {
        $this->validate($request, [
            'content' => 'required'
        ]);

        $ticketMessage = TicketMessage::create([
            'content' => $request->input('content'),
            'ticket_id' => $id,
            'user_id' => Auth::user()->id
        ]);

        Mail::to(getOption('notify_email'))->send(new TicketNewMessage($ticketMessage));
        return redirect('/support/ticket/' . $id);
    }
}
