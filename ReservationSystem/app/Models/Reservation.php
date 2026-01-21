<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Reservation extends Model
{
    protected $table = 'tabla'; // Nombre de la tabla legacy

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }

    public function getEventsForCalendar()
    {
        $stmt = $this->db->query("SELECT ID as id, nombreapellido, curso, materia, horario as start_time, horario1 as end_time, fecha, info, materiales FROM {$this->table}");
        $results = $stmt->fetchAll();

        $events = [];
        foreach ($results as $row) {
            $events[] = [
                'id' => $row['id'],
                'title' => $row['info'] . ' - ' . $row['materia'],
                'start' => $row['fecha'] . 'T' . $row['start_time'],
                'end' => $row['fecha'] . 'T' . $row['end_time'],
                'extendedProps' => [
                    'nombreapellido' => $row['nombreapellido'],
                    'curso' => $row['curso'],
                    'materia' => $row['materia'],
                    'info' => $row['info'],
                    'materiales' => $row['materiales']
                ]
            ];
        }
        return $events;
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE ID = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (nombreapellido, curso, materia, horario, horario1, fecha, info, materiales) VALUES (:nombreapellido, :curso, :materia, :horario, :horario1, :fecha, :info, :materiales)");
        return $stmt->execute($data);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET curso = :curso, materia = :materia, horario = :horario, horario1 = :horario1, fecha = :fecha, info = :info, materiales = :materiales WHERE ID = :id");
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE ID = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function isSlotAvailable($info, $fecha, $start, $end, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE info = :info AND fecha = :fecha AND ((horario < :end AND horario1 > :start) OR (horario >= :start AND horario < :end))";
        
        if ($excludeId) {
            $sql .= " AND ID != :excludeId";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':info', $info);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':start', $start);
        $stmt->bindParam(':end', $end);
        
        if ($excludeId) {
            $stmt->bindParam(':excludeId', $excludeId);
        }

        $stmt->execute();
        return $stmt->fetchColumn() == 0;
    }
}
