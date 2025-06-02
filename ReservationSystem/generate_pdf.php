<?php
// Requerimientos
require_once 'vendor/autoload.php'; // Autoload de Composer
include('include/conexion.php'); // Conexion SQL

use TCPDF;

class PDF extends TCPDF
{
    public function __construct($orientation = 'L', $unit = 'mm', $size = 'A4')
    {
        parent::__construct($orientation, $unit, $size, true, 'UTF-8', false);

        // Configuración básica del documento
        $this->SetCreator('Sistema de Turnos');
        $this->SetAuthor('Sistema');
        $this->SetTitle('Registro de Turnos');
        $this->SetSubject('Reporte de Turnos');

        // Configurar márgenes
        $this->SetMargins(15, 40, 15);
        $this->SetHeaderMargin(5);
        $this->SetFooterMargin(10);

        // Auto page breaks
        $this->SetAutoPageBreak(true, 25);

        // Configurar fuente por defecto
        $this->SetFont('dejavusans', '', 10);
    }

    public function Header()
    {
        // Mostrar el encabezado solo en la primera página
        if ($this->getPage() == 1) {
            // Fondo para el título
            $this->SetFillColor(99, 89, 146);
            $this->SetTextColor(255, 255, 255); // Texto blanco para mejor contraste
            $this->SetFont('dejavusans', 'B', 24);

            // Título con fondo
            $this->Cell(0, 20, 'Registro de Turnos', 0, 1, 'C', 1);

            // Logo (verificar si existe el archivo)
            if (file_exists('img/logo.png')) {
                $this->Image('img/logo.png', 250, 10, 25, 0, 'PNG');
            }

            // Espacio después del header
            $this->Ln(10);

            // Restablecer color de texto
            $this->SetTextColor(0, 0, 0);
        }
    }

    public function Footer()
    {
        // Posición a 15 mm del final
        $this->SetY(-15);
        $this->SetFont('dejavusans', '', 8);
        $this->SetTextColor(128, 128, 128);

        // Fecha de impresión
        $fechaImpresion = 'Fecha de impresión: ' . date('d/m/Y H:i:s');
        $this->Cell(0, 10, $fechaImpresion, 0, 0, 'R');

        // Número de página
        $this->Cell(0, 10, 'Página ' . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages(), 0, 0, 'L');
    }

    public function AutoAdjustColumnWidths($headers, $data)
    {
        $widths = [];
        $this->SetFont('dejavusans', '', 10);

        // Ancho disponible (restando márgenes)
        $pageWidth = $this->getPageWidth() - $this->getMargins()['left'] - $this->getMargins()['right'];

        // Calcular el ancho mínimo para cada columna basado en headers
        foreach ($headers as $header) {
            $widths[] = $this->GetStringWidth($header) + 6; // Padding adicional
        }

        // Ajustar basado en el contenido de los datos
        foreach ($data as $row) {
            foreach ($row as $key => $value) {
                if (isset($widths[$key])) {
                    $width = $this->GetStringWidth($value) + 6;
                    if ($width > $widths[$key]) {
                        $widths[$key] = $width;
                    }
                }
            }
        }

        // Ajustar proporcionalmente si excede el ancho de página
        $totalWidth = array_sum($widths);
        if ($totalWidth > $pageWidth) {
            $ratio = $pageWidth / $totalWidth;
            foreach ($widths as &$width) {
                $width *= $ratio;
            }
        }

        return $widths;
    }

    public function DrawTableHeader($headers, $widths)
    {
        // Estilo para encabezados
        $this->SetFillColor(99, 89, 146);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('dejavusans', 'B', 10);

        foreach ($headers as $i => $header) {
            $this->Cell($widths[$i], 12, $header, 1, 0, 'C', 1);
        }
        $this->Ln();

        // Restablecer colores para el contenido
        $this->SetFillColor(245, 245, 245);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('dejavusans', '', 9);
    }

    public function DrawTableRow($row, $widths, $fill = false)
    {
        $height = 8;
        foreach ($row as $i => $cell) {
            $this->Cell($widths[$i], $height, $cell, 1, 0, 'L', $fill);
        }
        $this->Ln();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Configurar headers para JSON
    header('Content-Type: application/json; charset=utf-8');

    try {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['startDate']) || !isset($data['endDate'])) {
            throw new Exception('Datos de fecha inválidos');
        }

        $startDate = $data['startDate'];
        $endDate = $data['endDate'];

        // Verificar la conexión
        if (!$conexion) {
            throw new Exception("Conexión fallida: " . mysqli_connect_error());
        }

        // Asegurarse de que la conexión use UTF-8
        mysqli_set_charset($conexion, "utf8mb4");

        // Usar consultas preparadas para prevenir inyección SQL
        $sql = "SELECT nombreapellido, curso, materia, info, materiales, horario, horario1, fecha 
                FROM tabla 
                WHERE fecha BETWEEN ? AND ? 
                ORDER BY fecha ASC, horario ASC";

        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparando consulta: " . $conexion->error);
        }

        $stmt->bind_param("ss", $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            throw new Exception("Error en la consulta: " . $conexion->error);
        }

        // Crear instancia del PDF
        $pdf = new PDF('L'); // Landscape
        $pdf->AddPage();

        // Encabezados de la tabla
        $headers = [
            'Nombre y Apellido',
            'Curso',
            'Materia',
            'Salón',
            'Materiales',
            'Horario inicio',
            'Horario fin',
            'Fecha'
        ];

        // Recopilar datos
        $tableData = [];
        while ($row = $result->fetch_assoc()) {
            $tableData[] = [
                $row['nombreapellido'] ?? '',
                $row['curso'] ?? '',
                $row['materia'] ?? '',
                $row['info'] ?? '',
                $row['materiales'] ?? '',
                $row['horario'] ?? '',
                $row['horario1'] ?? '',
                $row['fecha'] ?? ''
            ];
        }

        if (empty($tableData)) {
            throw new Exception("No se encontraron registros en el rango de fechas especificado");
        }

        // Obtener anchos ajustados automáticamente
        $widths = $pdf->AutoAdjustColumnWidths($headers, $tableData);

        // Agregar información del rango de fechas
        $pdf->SetFont('dejavusans', 'B', 12);
        $pdf->Cell(0, 10, "Período: " . date('d/m/Y', strtotime($startDate)) . " - " . date('d/m/Y', strtotime($endDate)), 0, 1, 'C');
        $pdf->Ln(5);

        // Dibujar encabezados de la tabla
        $pdf->DrawTableHeader($headers, $widths);

        // Dibujar las filas de datos con alternancia de colores
        foreach ($tableData as $index => $row) {
            $fill = $index % 2 == 0; // Alternar colores
            $pdf->DrawTableRow($row, $widths, $fill);
        }

        // Cerrar conexión
        $stmt->close();
        $conexion->close();

        // Generar nombre de archivo amigable
        $fileName = 'registros_turnos_' . date('Ymd_His') . '.pdf';
        $pdfDir = __DIR__ . '/pdfs'; // Usar ruta absoluta
        $pdfFile = $pdfDir . '/' . $fileName;

        // Asegurarse de que el directorio exista y tenga permisos correctos
        if (!is_dir($pdfDir)) {
            if (!mkdir($pdfDir, 0777, true)) {
                throw new Exception('No se pudo crear el directorio de PDFs en: ' . $pdfDir);
            }
        }

        // Verificar permisos del directorio
        if (!is_writable($pdfDir)) {
            chmod($pdfDir, 0777);
            if (!is_writable($pdfDir)) {
                throw new Exception('El directorio PDFs no tiene permisos de escritura: ' . $pdfDir);
            }
        }

        // Verificar si ya existe un archivo con el mismo nombre y eliminarlo
        if (file_exists($pdfFile)) {
            unlink($pdfFile);
        }

        try {
            // Generar el PDF
            $pdf->Output($pdfFile, 'F');
        } catch (Exception $outputError) {
            // Si falla, intentar con ruta temporal del sistema
            $tempDir = sys_get_temp_dir();
            $tempFile = $tempDir . '/' . $fileName;

            try {
                $pdf->Output($tempFile, 'F');

                // Mover el archivo temporal al directorio deseado
                if (copy($tempFile, $pdfFile)) {
                    unlink($tempFile);
                } else {
                    $pdfFile = $tempFile; // Usar el archivo temporal como fallback
                }
            } catch (Exception $tempError) {
                throw new Exception('Error al generar PDF: ' . $outputError->getMessage() . ' | Temp error: ' . $tempError->getMessage());
            }
        }

        if (file_exists($pdfFile)) {
            echo json_encode([
                'success' => true,
                'pdf' => str_replace(__DIR__ . '/', '', $pdfFile), // Ruta relativa para el cliente
                'filename' => $fileName,
                'records' => count($tableData),
                'message' => 'PDF generado exitosamente',
                'full_path' => $pdfFile // Para debugging
            ]);
        } else {
            throw new Exception('No se pudo generar el archivo PDF en: ' . $pdfFile);
        }

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'Método no permitido. Use POST.'
    ]);
}