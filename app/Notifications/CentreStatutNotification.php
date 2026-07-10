<?php

namespace App\Notifications;

use App\Models\Centre;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CentreStatutNotification extends Notification
{
    use Queueable;

    protected $centre;
    protected $ancienStatut;
    protected $nouveauStatut;

    public function __construct(Centre $centre, $ancienStatut, $nouveauStatut)
    {
        $this->centre = $centre;
        $this->ancienStatut = $ancienStatut;
        $this->nouveauStatut = $nouveauStatut;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $statutLibelle = $this->nouveauStatut === 'publie' ? 'publié' : 'dépublié';

        return (new MailMessage)
            ->subject('Changement de statut du centre : ' . $this->centre->nom)
            ->line('Le centre "' . $this->centre->nom . '" a changé de statut.')
            ->line('Ancien statut : ' . ($this->ancienStatut ?? 'aucun'))
            ->line('Nouveau statut : ' . $this->nouveauStatut)
            ->action('Voir le centre', url('/admin/centres/' . $this->centre->id_centre))
            ->line('Merci d\'utiliser SportMap Bénin.');
    }
}