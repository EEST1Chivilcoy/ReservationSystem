<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Reservation;
use DateTime;

class HomeController extends Controller
{
    public function index()
    {
        // Lógica de Navidad (Legacy)
        $today = new DateTime();
        $month = (int) $today->format('m');
        $day = (int) $today->format('d');
        $isChristmasWeek = $month === 12 && $day >= 20 && $day <= 26;

        // Comprobar si hay reservas para decidir qué mostrar
        $reservationModel = new Reservation();
        $hasReservations = $reservationModel->hasReservations();

        $data = [
            'isChristmasWeek' => $isChristmasWeek,
            'hasReservations' => $hasReservations,
            'loggedIn' => $_SESSION['loggedIn'] ?? false,
            'isAdmin' => $_SESSION['EsAdmin'] ?? false,
            'userName' => $_SESSION['nombreyapellido'] ?? null
        ];

        $this->view('home/index', $data);
    }
    
    public function getEvents()
    {
        $reservationModel = new Reservation();
        $events = $reservationModel->getEventsForCalendar();
        
        header('Content-Type: application/json');
        echo json_encode($events);
        exit;
    }
}
