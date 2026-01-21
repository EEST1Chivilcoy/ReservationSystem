<?php

namespace App\Core;

use PDO;

abstract class Model
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Métodos comunes podrían ir aquí (findAll, findById, etc.)
}
