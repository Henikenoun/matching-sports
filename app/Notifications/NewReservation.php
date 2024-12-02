<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewReservation extends Notification
{
    private $reservation;

    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    public function via($notifiable)
    {
        return ['database', 'mail']; // Canal pour les notifications
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nouvelle réservation')
            ->line("Une nouvelle réservation a été effectuée pour votre terrain.")
            ->line("Détails :")
            ->line("Utilisateur : {$this->reservation->User_Reserve}")
            ->line("Date : {$this->reservation->Date_Reservation}")
            ->action('Voir les détails', url("/reservations/{$this->reservation->id}"))
            ->line('Merci de vérifier la réservation.');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Nouvelle réservation pour votre terrain.',
            'reservation_id' => $this->reservation->id,
            'user' => $this->reservation->User_Reserve,
            'date' => $this->reservation->Date_Reservation,
        ];
    }
}
