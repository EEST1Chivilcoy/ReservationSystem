<?php
require('fpdf/fpdf.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $startDate = $data['startDate'];
    $endDate = $data['endDate'];

    $conn = new mysqli('localhost', 'usuario', 'contraseña', 'base_de_datos');
    if ($conn->connect_error) {
        die(json_encode(["error" => "Conexión fallida: " . $conn->connect_error]));
    }

    $sql = "SELECT * FROM tabla WHERE fecha >= '$startDate' AND fecha <= '$endDate'";
    $result = $conn->query($sql);

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);

    $pdf->Cell(40, 10, 'Columna1', 1);
    $pdf->Cell(40, 10, 'Columna2', 1);
    $pdf->Ln();

    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(40, 10, $row['columna1'], 1);
        $pdf->Cell(40, 10, $row['columna2'], 1);
        $pdf->Ln();
    }

    $conn->close();

    $pdfFile = 'pdfs/' . uniqid('pdf_', true) . '.pdf';
    $pdf->Output('F', $pdfFile);

    echo json_encode(['pdf' => $pdfFile]);
}
?>