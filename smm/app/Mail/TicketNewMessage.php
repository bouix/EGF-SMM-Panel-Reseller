<?php
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */
namespace App\Mail;

use App\TicketMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TicketNewMessage extends Mailable
{
    use Queueable, SerializesModels;

    public $ticketMessage;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(TicketMessage $ticketMessage)
    {
        $this->ticketMessage = $ticketMessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('mail.ticket_message_subject'))
            ->markdown('mail.ticket-new-message');
    }
}
