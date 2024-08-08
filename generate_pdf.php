<?php
// Requerimientos
require('fpdf/tfpdf.php'); // Libreria TFPDF (Soporta UTF-8)
include('include/conexion.php'); //Conexion SQL

class PDF extends tFPDF
{
    function __construct($orientation = 'P', $unit = 'mm', $size = 'A4')
    {
        parent::__construct($orientation, $unit, $size);
        $this->AddFont('DejaVu', '', 'DejaVuSans.php');
    }

    function Header()
    {
        // Set background color for the title
        $this->SetFillColor(99, 89, 146);
        // Set text color
        $this->SetTextColor(39, 23, 111);
        // Set font
        $this->SetFont('DejaVu', '', 30);
        // Title
        $this->Cell(0, 40, 'Registro de Turnos', 0, 1, 'C', 1);
        // Logo
        $this->Image('img/logo.png', 250, 15, 30);

        $this->Ln(10);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('DejaVu', '', 8);
        $this->Cell(0, 10, utf8_decode('Fecha de impresión: ' . date('d/m/Y H:i:s')), 0, 0, 'R');
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

    // Asegurarse de que la conexión use UTF-8
    mysqli_set_charset($conexion, "utf8mb4");

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
        $pdf->Cell($widths[$i], 10, utf8_decode($headers[$i]), 1, 0, 'C');
    }
    $pdf->Ln();

    // Contenido de las filas
    $pdf->SetFont('DejaVu', '', 10);
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell($widths[0], 10, utf8_decode($row['nombreapellido']), 1, 0, 'L');
        $pdf->Cell($widths[1], 10, utf8_decode($row['curso']), 1, 0, 'L');
        $pdf->Cell($widths[2], 10, utf8_decode($row['materia']), 1, 0, 'L');
        $pdf->Cell($widths[3], 10, utf8_decode($row['materiales']), 1, 0, 'L');
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
