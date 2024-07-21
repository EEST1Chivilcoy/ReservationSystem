<?php

// Requerimientos
require('fpdf/tfpdf.php'); // Libreria TFPDF (Soporta UTF-8)
include('include/conexion.php'); //Conexion SQL

class PDF extends tFPDF
{
    function Header()
    {
        // Logo
        $this->Image('img/logo.png', 250, 10, 30); // Ajusta la ruta y las dimensiones según tu logo
        $this->Ln(35);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('DejaVu', '', 8);
        $this->Cell(0, 10, 'Fecha de impresión: ' . date('d/m/Y H:i:s'), 0, 0, 'R');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $startDate = $data['startDate'];
    $endDate = $data['endDate'];

    // Verificar la conexión
    if (!$conexion) {
        die(json_encode(["error" => "Conexión fallida: " . mysqli_connect_error()]));
    }

    // Usar consultas preparadas para prevenir inyección SQL
    $sql = "SELECT * FROM tabla WHERE fecha BETWEEN ? AND ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        die(json_encode(["error" => "Error en la consulta: " . $conexion->error]));
    }

    $pdf = new PDF('L');
    $pdf->AddPage();
    $pdf->AddFont('DejaVu', '', 'DejaVuSans.php');
    $pdf->SetFont('DejaVu', '', 12);

    // Encabezado de las columnas centrado
    $headers = ['Nombre y Apellido', 'Curso', 'Materia', 'Materiales', 'Horario inicio', 'Horario fin', 'Fecha'];
    $widths = [60, 40, 50, 40, 30, 30, 30];

    for ($i = 0; $i < count($headers); $i++) {
        $pdf->Cell($widths[$i], 10, $headers[$i], 1, 0, 'C');
    }
    $pdf->Ln();

    // Contenido de las filas
    $pdf->SetFont('DejaVu', '', 10);
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell($widths[0], 10, $row['nombreapellido'], 1, 0, 'L');
        $pdf->Cell($widths[1], 10, $row['curso'], 1, 0, 'L');
        $pdf->Cell($widths[2], 10, $row['materia'], 1, 0, 'L');
        $pdf->Cell($widths[3], 10, $row['materiales'], 1, 0, 'L');
        $pdf->Cell($widths[4], 10, $row['horario'], 1, 0, 'C');
        $pdf->Cell($widths[5], 10, $row['horario1'], 1, 0, 'C');
        $pdf->Cell($widths[6], 10, $row['fecha'], 1, 0, 'C');
        $pdf->Ln();
    }

    $stmt->close();
    $conexion->close();

    // Generar nombre de archivo amigable
    $fileName = 'registros_' . date('Ymd_His') . '.pdf';
    $pdfFile = 'pdfs/' . $fileName;

    // Asegurarse de que el directorio exista
    if (!is_dir('pdfs')) {
        mkdir('pdfs', 0755, true);
    }

    $pdf->Output('F', $pdfFile);

    if (file_exists($pdfFile)) {
        echo json_encode(['pdf' => $pdfFile]);
    } else {
        echo json_encode(['error' => 'No se pudo generar el PDF']);
    }
} else {
    echo json_encode(['error' => 'Método no permitido']);
}
