<?php
require_once('../public/invoice.php');
require_once('../../models/data/pedido_data.php');
require_once('../../libraries/phpmailer651/src/Exception.php');
require_once('../../libraries/phpmailer651/src/PHPMailer.php');
require_once('../../libraries/phpmailer651/src/SMTP.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$pdf = new Report;
$pdf->startReport('Comprobante de compra');
$pedidos = new PedidoData;

if ($datapedidos = $pedidos->readDetailReport()) {
    $usuario = $datapedidos[0];

    // Información del usuario
    $pdf->setFillColor(225);
    $pdf->setDrawColor(0);
    $pdf->setFont('Arial', 'B', 11);
    $pdf->cell(0, 10, $pdf->encodeString('Información del usuario'), 1, 1, 'C', 1);
    $pdf->setFont('Arial', '', 11);
    $pdf->cell(50, 10, $pdf->encodeString('Nombre:'), 1, 0);
    $pdf->cell(0, 10, $pdf->encodeString($usuario['NOMBRE_USUARIO'] . ' ' . $usuario['APELLIDO_USUARIO']), 1, 1);
    $pdf->cell(50, 10, $pdf->encodeString('Correo:'), 1, 0);
    $pdf->cell(0, 10, $pdf->encodeString($usuario['CORREO']), 1, 1);
    $pdf->cell(50, 10, $pdf->encodeString('Teléfono:'), 1, 0);
    $pdf->cell(0, 10, $pdf->encodeString($usuario['TELEFONO']), 1, 1);
    $pdf->cell(50, 10, $pdf->encodeString('Dirección:'), 1, 0);
    $pdf->cell(0, 10, $pdf->encodeString($usuario['DIRECCION']), 1, 1);
    $pdf->cell(50, 10, $pdf->encodeString('DUI:'), 1, 0);
    $pdf->cell(0, 10, $pdf->encodeString($usuario['DUI']), 1, 1);

    $pdf->ln(10);

    // Detalles del pedido
    $pdf->setFillColor(225);
    $pdf->setDrawColor(0);
    $pdf->setFont('Arial', 'B', 11);
    $pdf->cell(37, 15, $pdf->encodeString('Imagen'), 1, 0, 'C', 1);
    $pdf->cell(63, 15, $pdf->encodeString('Libro'), 1, 0, 'C', 1);
    $pdf->cell(25, 15, $pdf->encodeString('Cantidad'), 1, 0, 'C', 1);
    $pdf->cell(30, 15, $pdf->encodeString('Precio (US$)'), 1, 0, 'C', 1);
    $pdf->cell(30, 15, $pdf->encodeString('Subtotal (US$)'), 1, 1, 'C', 1);

    $pdf->setFillColor(240);
    $pdf->setFont('Arial', '', 11);
    $total = 0;

    foreach ($datapedidos as $rowpedidos) {
        if ($pdf->getY() + 15 > 279 - 30) {
            $pdf->addPage('P', [216, 279]);
            $pdf->setFillColor(225);
            $pdf->setDrawColor(0); 
            $pdf->setFont('Arial', 'B', 11);
            $pdf->cell(37, 15, $pdf->encodeString('Imagen'), 1, 0, 'C', 1);
            $pdf->cell(63, 15, $pdf->encodeString('Producto'), 1, 0, 'C', 1);
            $pdf->cell(25, 15, $pdf->encodeString('Cantidad'), 1, 0, 'C', 1);
            $pdf->cell(30, 15, $pdf->encodeString('Precio (US$)'), 1, 0, 'C', 1);
            $pdf->cell(30, 15, $pdf->encodeString('Subtotal (US$)'), 1, 1, 'C', 1);
        }
        $subtotal = $rowpedidos['PRECIO'] * $rowpedidos['CANTIDAD'];
        $total += $subtotal;
        $currentY = $pdf->getY();
        $pdf->setFillColor(255, 255, 255);
        $pdf->cell(37, 18, $pdf->image('../../images/libros/' . $rowpedidos['IMAGEN'], $pdf->getX() + 10, $currentY + 2, 10), 1, 0);
        $pdf->cell(63, 18, $pdf->encodeString($rowpedidos['NOMBRE']), 1, 0, 'C');
        $pdf->cell(25, 18, $pdf->encodeString($rowpedidos['CANTIDAD']), 1, 0, 'C');
        $pdf->cell(30, 18, $pdf->encodeString('$' . $rowpedidos['PRECIO']), 1, 0, 'C');
        $pdf->cell(30, 18, $pdf->encodeString('$' . $subtotal), 1, 1, 'C');
    }
    $pdf->setFont('Arial', 'B', 11);
    $pdf->cell(155, 10, $pdf->encodeString('Total:'), 1, 0, 'R');
    $pdf->cell(30, 10, $pdf->encodeString('$' . $total), 1, 1, 'C');

    // Generar un nombre de archivo único
    $tempFileName = uniqid('factura_', true) . '.pdf';
    
    // Obtener la ruta completa del archivo temporal
    $pdfFilePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $tempFileName;

    // Guardar el PDF en el directorio temporal del sistema
    $pdf->output('F', $pdfFilePath);

    // Obtener el correo del usuario
    $userEmail = $usuario['CORREO'];

    // Enviar el correo con el PDF adjunto
    $emailSent = sendEmailWithAttachment($userEmail, $pdfFilePath);

    // Mostrar el PDF al usuario
    $pdf->output('I', 'comprobante_compra.pdf');

    // Eliminar el archivo temporal
    unlink($pdfFilePath);

    // Devolver la respuesta JSON después de mostrar el PDF
    if ($emailSent) {
        echo json_encode(['status' => true, 'message' => 'Pedido finalizado y comprobante enviado por correo']);
    } else {
        echo json_encode(['status' => false, 'error' => 'Se generó el comprobante pero no se pudo enviar por correo']);
    }
} else {
    echo json_encode(['status' => false, 'error' => 'No hay datos para generar el comprobante']);
}

function sendEmailWithAttachment($to, $pdfFilePath) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'comodos.esa@gmail.com';
        $mail->Password   = 'pwrn arkr gict nyrc';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        //Recipients
        $mail->setFrom('comodos.esa@gmail.com', 'Power Letters');
        $mail->addAddress($to);

        //Attachments
        $mail->addAttachment($pdfFilePath, 'comprobante_compra.pdf');

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Comprobante de compra';
        $mail->Body    = 'Adjunto encontrará su comprobante de compra.';

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Puedes agregar un log del error aquí si lo deseas
        // error_log('Error al enviar el correo: ' . $mail->ErrorInfo);
        return false;
    }
}