<?php

namespace App\Mail;

use App\Models\Centre;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NouveauCentreMail extends Mailable
{
    use Queueable, SerializesModels;

    public $centre;

    public function __construct(Centre $centre)
    {
        $this->centre = $centre;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouveau centre en attente de validation - SportMap Bénin',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.nouveau-centre',
        );
    }
}