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
        // Mostrar el encabezado solo en la primera página
        if ($this->PageNo() == 1) {
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
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('DejaVu', '', 8);
        $this->Cell(0, 10, utf8_decode('Fecha de impresión: ' . date('d/m/Y H:i:s')), 0, 0, 'R');
    }

    function AutoAdjustColumnWidths($headers, $data)
    {
        $widths = [];
        $this->SetFont('DejaVu', '', 12);

        // Calcular el ancho máximo para cada columna
        foreach ($headers as $header) {
            $widths[] = $this->GetStringWidth(utf8_decode($header)) + 10; // Ancho de los encabezados
        }

        foreach ($data as $row) {
            foreach ($row as $key => $value) {
                $width = $this->GetStringWidth(utf8_decode($value)) + 10;
                if ($width > $widths[$key]) {
                    $widths[$key] = $width; // Ancho máximo de cada columna
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

    // Encabezados y datos
    $headers = ['Nombre y Apellido', 'Curso', 'Materia', 'Materiales', 'Horario inicio', 'Horario fin', 'Fecha'];
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            utf8_decode($row['nombreapellido']),
            utf8_decode($row['curso']),
            utf8_decode($row['materia']),
            utf8_decode($row['materiales']),
            $row['horario'],
            $row['horario1'],
            $row['fecha']
        ];
    }

    // Obtener anchos ajustados automáticamente
    $widths = $pdf->AutoAdjustColumnWidths($headers, $data);

    // Dibujar los encabezados
    foreach ($headers as $i => $header) {
        $pdf->Cell($widths[$i], 10, utf8_decode($header), 1, 0, 'C');
    }
    $pdf->Ln();

    // Dibujar las filas de datos
    $pdf->SetFont('DejaVu', '', 10);
    foreach ($data as $row) {
        foreach ($row as $i => $cell) {
            $pdf->Cell($widths[$i], 10, $cell, 1, 0, 'L');
        }
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
