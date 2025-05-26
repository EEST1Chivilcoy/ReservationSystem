<?php
require_once __DIR__ . '/vendor/autoload.php'; // Autoload de Composer
include('include/conexion.php'); // Conexión SQL

use TCPDF;

class PDF extends TCPDF
{
    public function Header()
    {
        if ($this->getPage() == 1) {
            // Fondo morado claro
            $this->SetFillColor(99, 89, 146);
            // Color del texto
            $this->SetTextColor(39, 23, 111);
            // Fuente
            $this->SetFont('dejavusans', '', 30);
            // Título centrado
            $this->Cell(0, 20, 'Registro de Turnos', 0, 1, 'C', 1);
            // Logo
            $this->Image('img/logo.png', 250, 10, 30);
            $this->Ln(10);
        }
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('dejavusans', '', 8);
        $this->Cell(0, 10, 'Fecha de impresión: ' . date('d/m/Y H:i:s'), 0, 0, 'R');
    }

    public function AutoAdjustColumnWidths($headers, $data)
    {
        $widths = [];

        $this->SetFont('dejavusans', '', 12);
        foreach ($headers as $header) {
            $widths[] = $this->GetStringWidth($header) + 2;
        }

        foreach ($data as $row) {
            foreach ($row as $key => $value) {
                $width = $this->GetStringWidth($value) + 2;
                if ($width > $widths[$key]) {
                    $widths[$key] = $width;
                }
            }
        }

        return $widths;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $startDate = $data['startDate'];
    $endDate = $data['endDate'];

    if (!$conexion) {
        die(json_encode(["error" => "Conexión fallida: " . mysqli_connect_error()]));
    }

    mysqli_set_charset($conexion, "utf8mb4");

    $sql = "SELECT * FROM tabla WHERE fecha BETWEEN ? AND ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        die(json_encode(["error" => "Error en la consulta: " . $conexion->error]));
    }

    // Crear PDF
    $pdf = new PDF('L', 'mm', 'A4', true, 'UTF-8');
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Sistema');
    $pdf->SetTitle('Registro de Turnos');
    $pdf->SetMargins(10, 25, 10);
    $pdf->AddPage();
    $pdf->SetFont('dejavusans', '', 12);

    $headers = ['Nombre y Apellido', 'Curso', 'Materia', 'Salón', 'Materiales', 'Horario inicio', 'Horario fin', 'Fecha'];
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = [
            $row['nombreapellido'],
            $row['curso'],
            $row['materia'],
            $row['info'],
            $row['materiales'],
            $row['horario'],
            $row['horario1'],
            $row['fecha']
        ];
    }

    $widths = $pdf->AutoAdjustColumnWidths($headers, $data);

    // Encabezados
    foreach ($headers as $i => $header) {
        $pdf->Cell($widths[$i], 10, $header, 1, 0, 'C');
    }
    $pdf->Ln();

    // Filas
    $pdf->SetFont('dejavusans', '', 10);
    foreach ($data as $row) {
        foreach ($row as $i => $cell) {
            $pdf->Cell($widths[$i], 10, $cell, 1, 0, 'L');
        }
        $pdf->Ln();
    }

    $stmt->close();
    $conexion->close();

    $fileName = 'registros_' . date('Ymd_His') . '.pdf';
    $pdfFile = 'pdfs/' . $fileName;

    if (!is_dir('pdfs')) {
        mkdir('pdfs', 0755, true);
    }

    $pdf->Output($pdfFile, 'F');

    if (file_exists($pdfFile)) {
        echo json_encode(['pdf' => $pdfFile]);
    } else {
        echo json_encode(['error' => 'No se pudo generar el PDF']);
    }
} else {
    echo json_encode(['error' => 'Método no permitido']);
}
