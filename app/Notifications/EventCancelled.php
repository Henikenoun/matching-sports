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
            ->line("L'événement suivant a été annulé : {$this->event->name}.")
            ->line('Nous nous excusons pour tout inconvénient causé.');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "L'événement suivant a été annulé : {$this->event->name}.",
            'event_id' => $this->event->id,
        ];
    }
}