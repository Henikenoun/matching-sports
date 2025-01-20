<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EventCancelled extends Notification
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
            ->subject('Événement annulé')
            ->line("L'événement suivant a été annulé : {$this->event->nom}.")
            ->line('Nous nous excusons pour tout inconvénient causé.');
    }

    public function toArray($notifiable)
{
    return [
        'message' => "L'événement suivant a été annulé : {$this->event->nom}.",
        'club' => $this->event->club->nom,
        'date_creation' => $this->event->created_at->format('Y-m-d H:i:s'),
        'event_id' => $this->event->id,
    ];
}
}