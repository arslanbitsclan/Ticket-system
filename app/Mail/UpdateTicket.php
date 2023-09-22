<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateTicket extends Mailable
{
    use Queueable, SerializesModels;
    protected $ticket;
    protected $message;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Ticket $ticket, $message)
    {
        $this->ticket = $ticket;
        $this->message = $message;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Update Ticket',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.ticket.update',
            with: [
                'uid' => $this->ticket->uid,
                'subject' => $this->ticket->subject,
                'type' => $this->ticket->ticketType->name,
                'status' => $this->ticket->status->name,
                'customer' => $this->ticket->user->full_name,
                'priority' => $this->ticket->priority->name,
                'department' => $this->ticket->department->name,
                'category' => $this->ticket->category->name,
                'update_msg' => $this->message,
                'url' => url("/ticket/".$this->ticket->id."/edit")
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
