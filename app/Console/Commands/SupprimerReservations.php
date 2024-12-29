<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;

class SupprimerReservations extends Command
{
    protected $signature = 'reservations:supprimer';
    protected $description = 'Supprime les réservations non payées et âgées de plus de 24 heures';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $controller = new \App\Http\Controllers\ReservationController();
        $result = $controller->supprimerReservationsNonPayees();

        $this->info($result);
    }
}
