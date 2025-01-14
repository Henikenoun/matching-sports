<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Demande;

class DemandeResponse extends Notification
{
    use Queueable;

    protected $demande;
    protected $responseType;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Demande $demande, $responseType)
    {
        $this->demande = $demande;
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
            ? 'Votre demande a été acceptée.' 
            : 'Votre demande a été refusée.';

        return [
            'demande_id' => $this->demande->ID,
            'message' => $message,
        ];
    }
}