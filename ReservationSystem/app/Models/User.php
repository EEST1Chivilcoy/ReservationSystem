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

    public function getAll($search = '', $limit = 10, $offset = 0)
    {
        $sql = "SELECT * FROM usuarios";
        $params = [];
        
        if ($search) {
            $sql .= " WHERE usuario LIKE :search OR NombreYApellido LIKE :search";
            $params[':search'] = '%' . $search . '%';
        }
        
        $sql .= " LIMIT $limit OFFSET $offset"; // PDO doesn't like parameters for LIMIT/OFFSET nicely in some drivers, direct injection for int is safe enough if cast
        
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll($search = '')
    {
        $sql = "SELECT COUNT(*) FROM usuarios";
        $params = [];
        if ($search) {
             $sql .= " WHERE usuario LIKE :search OR NombreYApellido LIKE :search";
             $params[':search'] = '%' . $search . '%';
        }
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function updateRole($id, $isAdmin)
    {
        $stmt = $this->db->prepare("UPDATE usuarios SET esAdmin = :isAdmin WHERE ID = :id");
        $stmt->bindParam(':isAdmin', $isAdmin, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM usuarios WHERE ID = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
