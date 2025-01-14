<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Demande;

class NewDemande extends Notification
{
    use Queueable;

    protected $demande;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Demande $demande)
    {
        $this->demande = $demande;
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
        return [
            'demande_id' => $this->demande->ID,
            'user_demande' => $this->demande->User_Demande,
            'date_demande' => $this->demande->Date_Demande,
        ];
    }
}