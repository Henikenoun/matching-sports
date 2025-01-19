<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Reservation;

class ReservationResponse extends Notification
{
    use Queueable;

    protected $reservation;
    protected $responseType;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Reservation $reservation, $responseType)
    {
        $this->reservation = $reservation;
        $this->responseType = $responseType;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)            
    {
        $message = $this->responseType === 'accepted' 
            ? "Votre réservation pour le terrain {$this->reservation->Terrain} au club {$this->reservation->club->nom} à la date {$this->reservation->Date_Reservation} a été acceptée." 
            : "Votre réservation pour le terrain {$this->reservation->Terrain} au club {$this->reservation->club->nom} à la date {$this->reservation->Date_Reservation} a été refusée.";
    
        return [
            
            'message' => $message, // Ajout du message personnalisé
        ];
    }
    }