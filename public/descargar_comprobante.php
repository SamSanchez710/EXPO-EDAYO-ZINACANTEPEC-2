<?php
// =============================================
// Comprobante de Inscripción con QR (FPDF + PHPQRCode)
// =============================================

// Librerías base
require_once __DIR__ . '/../app/libs/fpdf/fpdf.php';
require_once __DIR__ . '/../app/libs/phpqrcode/qrlib.php';
require_once __DIR__ . '/../config/database.php';


// Validar ID recibido
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die('ID de inscripción inválido');
}

// Conexión a BD
$db = new Database();
$conn = $db->getConnection();

// Obtener datos de inscripción
$query = "SELECT i.*, t.nombre AS taller_nombre 
          FROM inscripciones i
          LEFT JOIN talleres t ON i.taller_id = t.id
          WHERE i.id = :id LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$ins = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ins) {
    die('Inscripción no encontrada');
}

// Generar folio único
$folio = 'EDAYO-' . date('Y') . '-' . str_pad($ins['id'], 5, '0', STR_PAD_LEFT);

// ==========================
// Generar código QR temporal
// ==========================
$qrTemp = sys_get_temp_dir() . '/qr_' . uniqid() . '.png';
$qrData = json_encode([
    'folio' => $folio,
    'id' => $ins['id'],
    'nombre' => trim($ins['nombre'] . ' ' . $ins['apellido_paterno'] . ' ' . $ins['apellido_materno']),
    'taller' => $ins['taller_nombre'] ?? $ins['taller_seleccionado'] ?? ''
]);
QRcode::png($qrData, $qrTemp, QR_ECLEVEL_L, 3);

// ==========================
// Generar PDF con FPDF
// ==========================
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Logo institucional (ajusta ruta si cambia)
$logoPath = __DIR__ . '/images/logo.png';
if (file_exists($logoPath)) {
    $pdf->Image($logoPath, 15, 10, 30);
}

$pdf->Cell(0, 10, '', 0, 1);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 8, utf8_decode('Comprobante de Inscripción - EXPO APRENDE EDAYO'), 0, 1, 'C');
$pdf->Ln(4);

// Datos del participante
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(45, 7, 'Folio:', 0, 0);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 7, $folio, 0, 1);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(45, 7, 'Nombre:', 0, 0);
$pdf->Cell(0, 7, utf8_decode(trim($ins['nombre'].' '.$ins['apellido_paterno'].' '.$ins['apellido_materno'])), 0, 1);

$pdf->Cell(45, 7, 'Taller:', 0, 0);
$pdf->Cell(0, 7, utf8_decode($ins['taller_nombre'] ?? $ins['taller_seleccionado']), 0, 1);

$pdf->Cell(45, 7, 'Mesa:', 0, 0);
$pdf->Cell(0, 7, utf8_decode($ins['mesa_trabajo'] ?? 'No asignada'), 0, 1);

$pdf->Cell(45, 7, 'Fecha de registro:', 0, 0);
$pdf->Cell(0, 7, $ins['fecha_registro'], 0, 1);

$pdf->Ln(8);
$pdf->SetFont('Arial', 'I', 10);
$pdf->MultiCell(0, 6, utf8_decode(
    "Presenta este comprobante en la entrada del evento.\n" .
    "Puedes mostrar el código QR para una validación rápida."
), 0, 'L');

// QR en el PDF
if (file_exists($qrTemp)) {
    $pdf->Image($qrTemp, 150, 60, 40, 40);
}

// Pie de página
$pdf->SetY(-30);
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(0, 6, utf8_decode('EDAYO Zinacantepec - Comprobante de Inscripción'), 0, 1, 'C');
$pdf->Cell(0, 6, 'Generado: ' . date('Y-m-d H:i:s'), 0, 1, 'C');

// ==========================
// Salida del PDF al navegador
// ==========================
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="comprobante_'.$folio.'.pdf"');
$pdf->Output('I', 'comprobante_'.$folio.'.pdf');

// Eliminar QR temporal
if (file_exists($qrTemp)) {
    @unlink($qrTemp);
}
exit;
