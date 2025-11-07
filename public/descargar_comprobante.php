<?php
// =============================================
// Comprobante de Inscripción Moderno con QR
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

// Guardar folio en la BD
$update = $conn->prepare("UPDATE inscripciones SET folio = :folio WHERE id = :id");
$update->bindParam(':folio', $folio, PDO::PARAM_STR);
$update->bindParam(':id', $ins['id'], PDO::PARAM_INT);
$update->execute();

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
// Generar PDF con FPDF - Diseño Moderno
// ==========================
class PDF extends FPDF {
    // Cabecera personalizada
    function Header() {
        // Fondo de cabecera con color #882035
        $this->SetFillColor(136, 32, 53); // #882035
        $this->Rect(0, 0, $this->GetPageWidth(), 35, 'F');
        
        // Logos
        $logoPath = __DIR__ . '/images/logo.png';
        if (file_exists($logoPath)) {
            $this->Image($logoPath, 15, 8, 25);
        }

        $logoIcati = __DIR__ . '/images/icati.png';
        if (file_exists($logoIcati)) {
            $this->Image($logoIcati, $this->GetPageWidth() - 40, 8, 25);
        }

        // Título principal en blanco
        $this->SetY(12);
        $this->SetFont('Arial', 'B', 16);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(0, 8, utf8_decode('COMPROBANTE DE INSCRIPCIÓN'), 0, 1, 'C');
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 6, utf8_decode('EXPO APRENDE EDAYO'), 0, 1, 'C');
        
        $this->Ln(8);
    }
    
    // Pie de página personalizado
    function Footer() {
        $this->SetY(-25);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(128, 128, 128);
        $this->Cell(0, 6, utf8_decode('EDAYO Zinacantepec - Comprobante generado automáticamente'), 0, 1, 'C');
        $this->Cell(0, 6, 'Generado: ' . date('Y-m-d H:i:s'), 0, 1, 'C');
        $this->Cell(0, 6, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
    
    // Alternativa 1: Diseño con líneas divisorias elegantes
    function InfoItemModern($label, $value, $x = 15, $y = null, $width = 80) {
        if ($y === null) {
            $y = $this->GetY();
        }
        
        $this->SetXY($x, $y);
        
        // Label con acento de color
        $this->SetFont('Arial', 'B', 9);
        $this->SetTextColor(136, 32, 53); 
        $this->Cell($width, 5, utf8_decode($label), 0, 1);
        
        // Línea decorativa
        $this->SetDrawColor(185, 143, 85); 
        $this->SetLineWidth(0.3);
        $this->Line($x, $y + 6, $x + 20, $y + 6);
        
        // Valor
        $this->SetXY($x, $y + 8);
        $this->SetFont('Arial', '', 11);
        $this->SetTextColor(0, 0, 0);
        $this->MultiCell($width, 6, utf8_decode($value));
        
        return $y + 25;
    }
    
    
    // Rectángulo redondeado
    function RoundedRect($x, $y, $w, $h, $r, $style = '') {
        $k = $this->k;
        $hp = $this->h;
        if($style=='F')
            $op='f';
        elseif($style=='FD' || $style=='DF')
            $op='B';
        else
            $op='S';
        $MyArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
        $xc = $x+$w-$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));
        $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
        $xc = $x+$w-$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
        $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
        $xc = $x+$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
        $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
        $xc = $x+$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
        $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3) {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }
}

// Crear PDF
$pdf = new PDF('P', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 25);

// --------------------------
// Marca de agua MUY opaca
// --------------------------
$watermark = __DIR__ . '/images/tuerca_icati.png';
if (file_exists($watermark)) {
    $originalImage = imagecreatefrompng($watermark);
    if ($originalImage) {
        $width = imagesx($originalImage);
        $height = imagesy($originalImage);
        
        $watermarkTemp = sys_get_temp_dir() . '/watermark_opaque_' . uniqid() . '.png';
        $opaqueImage = imagecreatetruecolor($width, $height);
        imagesavealpha($opaqueImage, true);
        $transparent = imagecolorallocatealpha($opaqueImage, 0, 0, 0, 127);
        imagefill($opaqueImage, 0, 0, $transparent);
        
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $color = imagecolorat($originalImage, $x, $y);
                $alpha = ($color >> 24) & 0x7F;
                $newAlpha = min(115, $alpha + 100);
                $newColor = $color & 0x00FFFFFF | ($newAlpha << 24);
                imagesetpixel($opaqueImage, $x, $y, $newColor);
            }
        }
        
        imagepng($opaqueImage, $watermarkTemp, 9);
        imagedestroy($originalImage);
        imagedestroy($opaqueImage);
        
        $pdfWidth = $pdf->GetPageWidth();
        $pdfHeight = $pdf->GetPageHeight();
        $wmWidth = 180;
        $wmHeight = 180;
        $wmX = ($pdfWidth - $wmWidth) / 2;
        $wmY = ($pdfHeight - $wmHeight) / 2 + 10;
        $pdf->Image($watermarkTemp, $wmX, $wmY, $wmWidth, $wmHeight);
        
        @unlink($watermarkTemp);
    }
}

// --------------------------
// Sección de información principal
// --------------------------
$pdf->SetY(45);
$pdf->SetFont('Arial', 'B', 20);
$pdf->SetTextColor(136, 32, 53);
$pdf->Cell(0, 10, utf8_decode($ins['nombre'].' '.$ins['apellido_paterno'].' '.$ins['apellido_materno']), 0, 1, 'C');

$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(185, 143, 85);
$pdf->Cell(0, 6, 'Folio: ' . $folio, 0, 1, 'C');

$pdf->Ln(15);


$y_position = $pdf->GetY();
$y_position = $pdf->InfoItemModern('TALLER ASIGNADO', $ins['taller_nombre'] ?? $ins['taller_seleccionado'] ?? 'Por asignar', 60, $y_position, 75);
$y_position = $pdf->InfoItemModern('MESA DE TRABAJO', $ins['mesa_trabajo'] ?? 'No asignada', 115, $y_position - 25, 75);
$y_position = $pdf->InfoItemModern('FECHA DE REGISTRO', $ins['fecha_registro'], 60, $y_position, 75);
$pdf->InfoItemModern('ESTADO', 'Confirmado', 115, $y_position - 25, 75);

// --------------------------
// Sección QR
// --------------------------
$pdf->SetY($pdf->GetY() + 40);

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(136, 32, 53);
$pdf->Cell(0, 6, 'CODIGO DE VERIFICACION', 0, 1, 'C');

if (file_exists($qrTemp)) {
    // QR centrado en la página
    $pdf->Image($qrTemp, 85, $pdf->GetY() + 5, 40, 40);
}

$pdf->SetY($pdf->GetY() + 48);
$pdf->SetFont('Arial', 'I', 9);
$pdf->SetTextColor(136, 32, 53);
$pdf->Cell(0, 4, utf8_decode('Presente este código para acceso rápido'), 0, 1, 'C');

// --------------------------
// Instrucciones
// --------------------------
$pdf->SetY($pdf->GetY() + 30);
$pdf->SetFillColor(255, 253, 250);
$pdf->SetDrawColor(185, 143, 85);
$pdf->SetLineWidth(0.3);
$pdf->RoundedRect(15, $pdf->GetY(), 180, 28, 3, 'DF');

$pdf->SetXY(20, $pdf->GetY() + 5);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetTextColor(136, 32, 53);
$pdf->Cell(0, 5, utf8_decode('INSTRUCCIONES IMPORTANTES:'), 0, 1);

$pdf->SetXY(20, $pdf->GetY());
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->MultiCell(0, 4, utf8_decode(
    "1. Presente este comprobante en la entrada del evento\n" .
    "2. Mantenga su código QR visible para validación rápida\n" .
    "3. Llegue 15 minutos antes de su taller asignado"
));

// ==========================
// Salida del PDF
// ==========================
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="comprobante_'.$folio.'.pdf"');
$pdf->Output('I', 'comprobante_'.$folio.'.pdf');

if (file_exists($qrTemp)) {
    @unlink($qrTemp);
}
exit;