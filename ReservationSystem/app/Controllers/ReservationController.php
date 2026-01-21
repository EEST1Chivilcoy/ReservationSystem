<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Reservation;

class ReservationController extends Controller
{
    public function __construct()
    {
        // Verificar autenticación para todas las acciones excepto listar (si hubiera)
        if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
            $this->redirect('/login');
        }
    }

    public function create()
    {
        $this->view('reservations/create', [
            'esAdmin' => $_SESSION['EsAdmin'] ?? false,
            'nombreyapellido' => $_SESSION['nombreyapellido'] ?? ''
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/reservations/create');
        }

        $info = $_POST['info'] ?? '';
        if ($info === 'Otro') {
            $info = $_POST['otro_salon'] ?? 'Otro';
        }

        $curso = $_POST['curso'] ?? '';
        if ($curso !== 'Reunión' && $curso !== 'Charla/Conferencia' && $curso !== 'Acto') {
            $division = $_POST['division'] ?? '';
            $curso .= ' ' . $division;
        }

        $data = [
            'nombreapellido' => (!empty($_POST['NombreYApellido']) && $_SESSION['EsAdmin']) ? $_POST['NombreYApellido'] : $_SESSION['nombreyapellido'],
            'curso' => $curso,
            'materia' => $_POST['materia'],
            'horario' => $_POST['horario'],
            'horario1' => $_POST['horario1'],
            'fecha' => $_POST['fecha'],
            'info' => $info,
            'materiales' => $_POST['materiales'] ?? ''
        ];

        $reservationModel = new Reservation();

        // Validar Disponibilidad
        if (!$reservationModel->isSlotAvailable($data['info'], $data['fecha'], $data['horario'], $data['horario1'])) {
            $this->redirect('/?error=' . urlencode('El salón ya está reservado en ese horario.'));
        }

        if ($reservationModel->create($data)) {
            $this->redirect('/?success=' . urlencode('Reserva creada correctamente.'));
        } else {
            $this->redirect('/?error=' . urlencode('Error al crear la reserva.'));
        }
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) $this->redirect('/');

        $model = new Reservation();
        $reservation = $model->findById($id);

        if (!$reservation) $this->redirect('/');

        // TODO: Verificar permisos (si solo admin o el dueño pueden editar)

        $this->view('reservations/edit', [
            'reservation' => $reservation,
            'esAdmin' => $_SESSION['EsAdmin'] ?? false
        ]);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
        }
        
        $id = $_POST['id'] ?? null;
        if (!$id) $this->redirect('/');

        // Procesar datos similar a store...
         $info = $_POST['info'] ?? '';
        if ($info === 'Otro') {
            $info = $_POST['otro_salon'] ?? 'Otro';
        }

        $curso = $_POST['curso'] ?? '';
        // Nota: Al editar, el curso podría ya venir concatenado si no lo separamos. 
        // Por simplicidad, asumiremos que se vuelve a seleccionar o que manejamos la lógica de división de nuevo.
        // En un refactor ideal, curso y división estarían en columnas separadas en la BD.
        if ($curso !== 'Reunión' && $curso !== 'Charla/Conferencia' && $curso !== 'Acto' && isset($_POST['division'])) {
            $division = $_POST['division'] ?? '';
             // Hack simple: si el curso enviado no tiene division (ej es "1º") lo concatenamos.
             // Si el usuario no cambia el select, el valor del select es "1º".
            $curso .= ' ' . $division;
        }

        $data = [
            'curso' => $curso,
            'materia' => $_POST['materia'],
            'horario' => $_POST['horario'],
            'horario1' => $_POST['horario1'],
            'fecha' => $_POST['fecha'],
            'info' => $info,
            'materiales' => $_POST['materiales'] ?? ''
        ];

        $model = new Reservation();
        
        // Excluir la reserva actual de la validación
        if (!$model->isSlotAvailable($data['info'], $data['fecha'], $data['horario'], $data['horario1'], $id)) {
             $this->redirect('/?error=' . urlencode('El salón ya está reservado en ese nuevo horario.'));
        }

        if ($model->update($id, $data)) {
            $this->redirect('/?success=' . urlencode('Reserva actualizada.'));
        } else {
            $this->redirect('/?error=' . urlencode('Error al actualizar.'));
        }
    }

    public function delete()
    {
        // Solo admin puede borrar según lógica anterior (baja_sql requeriría verificación admin)
        // O tal vez el usuario dueño. Por ahora restringimos a Admin como en baja_sql.php require VerificacionAdmin
        if (!($_SESSION['EsAdmin'] ?? false)) {
            $this->redirect('/?error=' . urlencode('No autorizado.'));
        }

        $id = $_POST['id'] ?? $_GET['id'] ?? null;
        if ($id) {
            $model = new Reservation();
            $model->delete($id);
             $this->redirect('/?success=' . urlencode('Reserva eliminada.'));
        }
        $this->redirect('/');
    }
}
