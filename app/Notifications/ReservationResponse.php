<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ReservationResponse extends Notification
{
    private $reservation;
    private $status;

    public function __construct($reservation, $status)
    {
        $this->reservation = $reservation;
        $this->status = $status; // acceptée ou refusée
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Réservation {$this->status}")
            ->line("Votre réservation pour le terrain a été {$this->status}.")
            ->action('Voir les détails', url("/reservations/{$this->reservation->id}"))
            ->line('Merci pour votre patience.');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "Votre réservation a été {$this->status}.",
            'reservation_id' => $this->reservation->id,
            'status' => $this->status,
        ];
    }
}
