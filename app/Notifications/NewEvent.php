<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewEvent extends Notification
{
    private $event;

    public function __construct($event)
    {
        $this->event = $event;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nouvel événement sportif')
            ->line("Un nouvel événement est organisé : {$this->event->name}.")
            ->action('Voir les détails', url("/evenements/{$this->event->id}"))
            ->line('Ne manquez pas cette opportunité !');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "Nouvel événement : {$this->event->name}.",
            'event_id' => $this->event->id,
        ];
    }
}
