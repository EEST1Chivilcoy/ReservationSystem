<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class User extends Model
{
    public function findByUsername($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE usuario = :usuario");
        $stmt->bindParam(':usuario', $username);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM usuarios WHERE usuario = :usuario");
        $stmt->bindParam(':usuario', $data['usuario']);
        $stmt->execute();
        
        if ($stmt->fetchColumn() > 0) {
            return false; // Usuario ya existe
        }

        $hash = password_hash($data['clave'], PASSWORD_BCRYPT);

        $stmt = $this->db->prepare("INSERT INTO usuarios (usuario, clave, NombreYApellido) VALUES (:usuario, :clave, :nomyapp)");
        $stmt->bindParam(':usuario', $data['usuario']);
        $stmt->bindParam(':clave', $hash);
        $stmt->bindParam(':nomyapp', $data['nomyapp']);

        return $stmt->execute();
    }
}
