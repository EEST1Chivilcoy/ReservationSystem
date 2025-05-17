<?php

// Configurar cabeceras CORS y JSON
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

// Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'error' => true,
        'message' => 'Solo se permiten solicitudes POST'
    ]);
    exit();
}

// Obtener datos del body
$data = json_decode(file_get_contents('php://input'), true);

// Verificar que se proporcionó la contraseña
if (!isset($data['password'])) {
    echo json_encode([
        'error' => true,
        'message' => 'Se requiere contraseña para acceder a los datos'
    ]);
    exit();
}

// Incluir el archivo de conexión
require_once('include/conexion.php');

// Verificar la contraseña
if ($data['password'] !== $clave) {
    echo json_encode([
        'error' => true,
        'message' => 'Contraseña incorrecta'
    ]);
    mysqli_close($conexion);
    exit();
}

try {
    // Consulta para obtener los registros
    $query = "SELECT 
        id,
        nombreapellido,
        curso,
        materia,
        fecha,
        horario,
        info,
        materiales,
        DATE_FORMAT(fecha, '%d/%m/%Y') as fecha_formateada,
        DATE_FORMAT(created_at, '%d/%m/%Y %H:%i:%s') as fecha_creacion
    FROM tabla 
    ORDER BY fecha ASC, horario ASC";

    $resultado = mysqli_query($conexion, $query);

    if (!$resultado) {
        throw new Exception(mysqli_error($conexion));
    }

    $registros = [];
    while ($row = mysqli_fetch_assoc($resultado)) {
        $registros[] = [
            'id' => (int) $row['id'],
            'nombreapellido' => $row['nombreapellido'],
            'curso' => $row['curso'],
            'materia' => $row['materia'],
            'fecha' => $row['fecha'],
            'fecha_formateada' => $row['fecha_formateada'],
            'horario' => $row['horario'],
            'salon' => $row['info'],
            'materiales' => $row['materiales'],
            'fecha_creacion' => $row['fecha_creacion']
        ];
    }

    // Consulta para obtener los usuarios (incluyendo la clave hasheada)
    $queryUsuarios = "SELECT usuario, NombreYApellido, esAdmin, clave FROM usuarios ORDER BY NombreYApellido ASC";
    $resultadoUsuarios = mysqli_query($conexion, $queryUsuarios);

    if (!$resultadoUsuarios) {
        throw new Exception(mysqli_error($conexion));
    }

    $usuarios = [];
    while ($row = mysqli_fetch_assoc($resultadoUsuarios)) {
        $usuarios[] = [
            'usuario' => $row['usuario'],
            'nombreyapellido' => $row['NombreYApellido'],
            'esAdmin' => (bool) $row['esAdmin'],
            'clave' => $row['clave'] // hash de la contraseña
        ];
    }

    // Respuesta final
    echo json_encode([
        'error' => false,
        'message' => 'Datos recuperados exitosamente',
        'total_registros' => count($registros),
        'total_usuarios' => count($usuarios),
        'datos' => $registros,
        'usuarios' => $usuarios
    ]);

} catch (Exception $e) {
    echo json_encode([
        'error' => true,
        'message' => 'Error al obtener los datos: ' . $e->getMessage()
    ]);
} finally {
    mysqli_close($conexion);
}