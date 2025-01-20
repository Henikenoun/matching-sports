<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Demande;
use Illuminate\Support\Facades\Log;

class DemandeResponse extends Notification
{
    use Queueable;

    protected $demande;
    protected $responseType;

    public function __construct(Demande $demande, $responseType)
    {
        $this->demande = $demande;
        $this->responseType = $responseType;

        // Log the responseType to verify it is correctly assigned
        Log::info('DemandeResponse created with responseType: ' . $responseType);
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $message = $this->responseType === 'acceptée' 
            ? "Votre demande pour rejoindre l'équipe {$this->demande->equipe->nom} a été acceptée." 
            : "Votre demande pour rejoindre l'équipe {$this->demande->equipe->nom} a été refusée.";

        // Log the message to verify the correct message is being generated
        Log::info('DemandeResponse toArray message: ' . $message);

        return [
            'demande_id' => $this->demande->id,
            'equipe_id' => $this->demande->equipe ? $this->demande->equipe->id : null,
            'equipe' => $this->demande->equipe ? $this->demande->equipe->name : null,
            'user' => $this->demande->user ? $this->demande->user->name : null,
            'message' => $message, // Ajout du message personnalisé
        ];
    }
}