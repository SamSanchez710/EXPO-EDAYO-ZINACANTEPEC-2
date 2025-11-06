<?php
require_once __DIR__ . '/../app/libs/fpdf/fpdf.php';
require_once __DIR__ . '/../app/libs/phpqrcode/qrlib.php';

echo "<h3>✅ Librerías cargadas correctamente</h3>";

$temp = sys_get_temp_dir() . '/test_qr.png';
QRcode::png('Prueba QR', $temp);
if (file_exists($temp)) {
    echo "<p>✅ QR generado correctamente: $temp</p>";
    unlink($temp);
} else {
    echo "<p>❌ Error generando QR</p>";
}

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(40, 10, 'Prueba FPDF OK');
$tempPdf = sys_get_temp_dir() . '/test.pdf';
$pdf->Output('F', $tempPdf);

if (file_exists($tempPdf)) {
    echo "<p>✅ PDF generado correctamente: $tempPdf</p>";
    unlink($tempPdf);
} else {
    echo "<p>❌ Error generando PDF</p>";
}
