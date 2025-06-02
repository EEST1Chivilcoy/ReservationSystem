<?php
// Requerimientos
require_once 'vendor/autoload.php'; // Autoload de Composer
include('include/conexion.php'); // Conexion SQL

use TCPDF;

class PDF extends TCPDF
{
    private $reportTitle = 'REGISTRO DE RESERVAS';
    private $institution = 'EEST N° 1 "Mariano Moreno"';

    public function __construct($orientation = 'P', $unit = 'mm', $size = 'A4')
    {
        parent::__construct($orientation, $unit, $size, true, 'UTF-8', false);

        // Configuración básica del documento
        $this->SetCreator('Sistema de Reservas v2.0');
        $this->SetAuthor('Sistema Académico');
        $this->SetTitle('Registro de Reservas - Reporte Académico');
        $this->SetSubject('Reporte Detallado de Reservas por Período');
        $this->SetKeywords('Reservas, reporte, académico, estudiantes');

        // Configurar márgenes optimizados para A4
        $this->SetMargins(20, 50, 20);
        $this->SetHeaderMargin(10);
        $this->SetFooterMargin(15);

        // Auto page breaks
        $this->SetAutoPageBreak(true, 30);

        // Configurar fuente por defecto
        $this->SetFont('helvetica', '', 9);
    }

    public function Header()
    {
        // Rectángulo decorativo superior
        $this->SetFillColor(41, 98, 255); // Azul corporativo
        $this->Rect(0, 0, $this->getPageWidth(), 8, 'F');

        // Rectángulo principal del header
        $this->SetFillColor(248, 249, 250); // Gris muy claro
        $this->Rect(15, 12, $this->getPageWidth() - 30, 35, 'F');

        // Borde del header
        $this->SetDrawColor(220, 220, 220);
        $this->SetLineWidth(0.5);
        $this->Rect(15, 12, $this->getPageWidth() - 30, 35, 'D');

        // Logo (si existe)
        if (file_exists('img/logo.png')) {
            $this->Image('img/logo.png', 22, 18, 20, 0, 'PNG');
        } else {
            // Icono alternativo si no hay logo
            $this->SetFillColor(41, 98, 255);
            $this->Rect(22, 18, 20, 20, 'F');
            $this->SetTextColor(255, 255, 255);
            $this->SetFont('helvetica', 'B', 16);
            $this->Text(28, 32, 'ST');
        }

        // Título principal
        $this->SetTextColor(41, 98, 255);
        $this->SetFont('helvetica', 'B', 18);
        $this->Text(50, 25, $this->reportTitle);

        // Subtítulo
        $this->SetTextColor(108, 117, 125);
        $this->SetFont('helvetica', '', 10);
        $this->Text(50, 32, $this->institution);

        // Fecha y hora de generación
        $this->SetTextColor(108, 117, 125);
        $this->SetFont('helvetica', '', 8);
        $fechaGeneracion = 'Generado el: ' . date('d/m/Y - H:i:s');
        $this->Text($this->getPageWidth() - 65, 20, $fechaGeneracion);

        // Restablecer color de texto
        $this->SetTextColor(0, 0, 0);
        $this->Ln(10);
    }

    public function Footer()
    {
        // Línea decorativa superior del footer
        $this->SetY(-20);
        $this->SetDrawColor(220, 220, 220);
        $this->Line(20, $this->GetY(), $this->getPageWidth() - 20, $this->GetY());

        // Información del pie de página
        $this->SetY(-15);
        $this->SetFont('helvetica', '', 8);
        $this->SetTextColor(108, 117, 125);

        // Información izquierda
        $this->Cell(0, 5, 'Sistema de Gestión de Reservas - Confidencial', 0, 0, 'L');

        // Número de página (derecha)
        $pageInfo = 'Página ' . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages();
        $this->Cell(0, 5, $pageInfo, 0, 0, 'R');
    }

    public function AddPeriodInfo($startDate, $endDate, $totalRecords)
    {
        // Sección de información del período
        $this->SetFillColor(41, 98, 255);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 10, '  INFORMACIÓN DEL REPORTE', 0, 1, 'L', 1);

        // Información detallada
        $this->SetFillColor(240, 242, 247);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('helvetica', '', 10);

        $fechaInicio = date('d/m/Y', strtotime($startDate));
        $fechaFin = date('d/m/Y', strtotime($endDate));

        $info = sprintf(
            "   Período consultado: %s al %s   |   Total de registros: %d   |   Fecha de consulta: %s",
            $fechaInicio,
            $fechaFin,
            $totalRecords,
            date('d/m/Y')
        );

        $this->Cell(0, 8, $info, 0, 1, 'L', 1);
        $this->Ln(5);
    }

    public function OptimizeColumnWidths($headers, $data)
    {
        // Anchos optimizados para A4 en orientación vertical
        $pageWidth = $this->getPageWidth() - 40; // Restando márgenes

        // Definir anchos proporcionales optimizados
        $proportions = [
            'Nombre y Apellido' => 0.25,  // 25%
            'Curso' => 0.12,              // 12%
            'Materia' => 0.18,            // 18%
            'Salón' => 0.10,              // 10%
            'Materiales' => 0.15,         // 15%
            'Inicio' => 0.08,             // 8%
            'Fin' => 0.08,                // 8%
            'Fecha' => 0.12               // 12%
        ];

        $widths = [];
        foreach ($proportions as $prop) {
            $widths[] = $pageWidth * $prop;
        }

        return $widths;
    }

    public function DrawEnhancedTableHeader($headers, $widths)
    {
        // Encabezado con gradiente simulado
        $this->SetFillColor(52, 58, 64); // Gris oscuro elegante
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('helvetica', 'B', 9);
        $this->SetDrawColor(255, 255, 255);
        $this->SetLineWidth(0.3);

        $headerHeight = 12;

        foreach ($headers as $i => $header) {
            $this->Cell($widths[$i], $headerHeight, $header, 1, 0, 'C', 1);
        }
        $this->Ln();

        // Línea decorativa bajo el encabezado
        $this->SetDrawColor(41, 98, 255);
        $this->SetLineWidth(1);
        $this->Line(20, $this->GetY(), $this->getPageWidth() - 20, $this->GetY());
        $this->Ln(2);

        // Restablecer configuración para contenido
        $this->SetDrawColor(220, 220, 220);
        $this->SetLineWidth(0.2);
        $this->SetTextColor(33, 37, 41);
        $this->SetFont('helvetica', '', 8);
    }

    public function DrawEnhancedTableRow($row, $widths, $isEven = false)
    {
        $rowHeight = 8;

        // Colores alternados más sutiles
        if ($isEven) {
            $this->SetFillColor(248, 249, 250); // Gris muy claro
        } else {
            $this->SetFillColor(255, 255, 255); // Blanco
        }

        foreach ($row as $i => $cell) {
            // Ajustar alineación según el contenido
            $align = 'L';
            if ($i >= 5 && $i <= 7) { // Horarios y fecha
                $align = 'C';
            }

            // Truncar texto largo si es necesario
            $cellText = $this->truncateText($cell, $widths[$i]);

            $this->Cell($widths[$i], $rowHeight, $cellText, 1, 0, $align, 1);
        }
        $this->Ln();
    }

    private function truncateText($text, $width)
    {
        $this->SetFont('helvetica', '', 8);

        // Si el texto cabe, devolverlo tal como está
        if ($this->GetStringWidth($text) <= $width - 4) {
            return $text;
        }

        // Truncar y añadir puntos suspensivos
        while ($this->GetStringWidth($text . '...') > $width - 4 && strlen($text) > 0) {
            $text = substr($text, 0, -1);
        }

        return $text . '...';
    }

    public function AddSummarySection($totalRecords, $startDate, $endDate)
    {
        $this->Ln(10);

        // Línea separadora
        $this->SetDrawColor(220, 220, 220);
        $this->Line(20, $this->GetY(), $this->getPageWidth() - 20, $this->GetY());
        $this->Ln(5);

        // Título de resumen
        $this->SetFont('helvetica', 'B', 11);
        $this->SetTextColor(41, 98, 255);
        $this->Cell(0, 8, 'RESUMEN ESTADÍSTICO', 0, 1, 'C');
        $this->Ln(3);

        // Información estadística
        $this->SetFont('helvetica', '', 9);
        $this->SetTextColor(0, 0, 0);

        $dias = (strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24) + 1;
        $promedioDiario = round($totalRecords / $dias, 1);

        $stats = [
            "Total de Reservas registrados: {$totalRecords}",
            "Período analizado: {$dias} días",
            "Promedio diario: {$promedioDiario} Reservas/día"
        ];

        foreach ($stats as $stat) {
            $this->Cell(0, 6, "• " . $stat, 0, 1, 'L');
        }
    }
}

// Procesar la solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');

    try {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['startDate']) || !isset($data['endDate'])) {
            throw new Exception('Datos de fecha inválidos');
        }

        $startDate = $data['startDate'];
        $endDate = $data['endDate'];

        // Verificar conexión a la base de datos
        if (!$conexion) {
            throw new Exception("Error de conexión: " . mysqli_connect_error());
        }

        mysqli_set_charset($conexion, "utf8mb4");

        // Consulta SQL optimizada
        $sql = "SELECT 
                    COALESCE(nombreapellido, 'N/A') as nombreapellido,
                    COALESCE(curso, 'N/A') as curso,
                    COALESCE(materia, 'N/A') as materia,
                    COALESCE(info, 'N/A') as info,
                    COALESCE(materiales, 'N/A') as materiales,
                    COALESCE(horario, 'N/A') as horario,
                    COALESCE(horario1, 'N/A') as horario1,
                    DATE_FORMAT(fecha, '%d/%m/%Y') as fecha_formatted
                FROM tabla 
                WHERE fecha BETWEEN ? AND ? 
                ORDER BY fecha ASC, horario ASC, nombreapellido ASC";

        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparando consulta: " . $conexion->error);
        }

        $stmt->bind_param("ss", $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            throw new Exception("Error ejecutando consulta: " . $conexion->error);
        }

        // Crear PDF mejorado
        $pdf = new PDF('P'); // Portrait para A4
        $pdf->AddPage();

        // Encabezados optimizados
        $headers = [
            'Nombre y Apellido',
            'Curso',
            'Materia',
            'Salón',
            'Materiales',
            'Inicio',
            'Fin',
            'Fecha'
        ];

        // Recopilar datos
        $tableData = [];
        while ($row = $result->fetch_assoc()) {
            $tableData[] = [
                $row['nombreapellido'],
                $row['curso'],
                $row['materia'],
                $row['info'],
                $row['materiales'],
                $row['horario'],
                $row['horario1'],
                $row['fecha_formatted']
            ];
        }

        if (empty($tableData)) {
            throw new Exception("No se encontraron registros para el período especificado");
        }

        // Agregar información del período
        $pdf->AddPeriodInfo($startDate, $endDate, count($tableData));

        // Obtener anchos optimizados
        $widths = $pdf->OptimizeColumnWidths($headers, $tableData);

        // Dibujar tabla
        $pdf->DrawEnhancedTableHeader($headers, $widths);

        foreach ($tableData as $index => $row) {
            // Verificar si necesita nueva página
            if ($pdf->GetY() > 250) { // Límite para A4
                $pdf->AddPage();
                $pdf->DrawEnhancedTableHeader($headers, $widths);
            }

            $pdf->DrawEnhancedTableRow($row, $widths, $index % 2 == 0);
        }

        // Agregar sección de resumen
        $pdf->AddSummarySection(count($tableData), $startDate, $endDate);

        // Cerrar conexiones
        $stmt->close();
        $conexion->close();

        // Generar archivo PDF
        $fileName = 'registro_Reservas_' . date('Ymd_His') . '.pdf';
        $pdfDir = __DIR__ . '/pdfs';
        $pdfFile = $pdfDir . '/' . $fileName;

        // Crear directorio si no existe
        if (!is_dir($pdfDir)) {
            if (!mkdir($pdfDir, 0755, true)) {
                throw new Exception('No se pudo crear directorio: ' . $pdfDir);
            }
        }

        // Generar PDF
        $pdf->Output($pdfFile, 'F');

        if (!file_exists($pdfFile)) {
            throw new Exception('Error al generar el archivo PDF');
        }

        // Respuesta exitosa
        echo json_encode([
            'success' => true,
            'pdf' => str_replace(__DIR__ . '/', '', $pdfFile),
            'filename' => $fileName,
            'records' => count($tableData),
            'message' => 'Reporte generado exitosamente',
            'fileSize' => round(filesize($pdfFile) / 1024, 2) . ' KB'
        ]);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage(),
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'Método no permitido. Use POST.'
    ]);
}
?>