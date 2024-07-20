<?php
require_once('../public/invoice.php');
require_once('../../models/data/pedido_data.php');

$pdf = new Report;
$pdf->startReport('Comprobante de compra');
$pedidos = new PedidoData;

// Se verifica si existen datos para mostrar, de lo contrario se imprime un mensaje.
if ($datapedidos = $pedidos->readDetailReport()) {
    // Obtener la información del usuario del primer elemento
    $usuario = $datapedidos[0];

    // Imprimir la información del usuario
    $pdf->setFillColor(225); // Color gris claro
    $pdf->setDrawColor(0); // Color negro para los bordes
    $pdf->setFont('Arial', 'B', 11);

    // Encabezados de la información del usuario
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

    $pdf->ln(10); // Espacio antes de los detalles del pedido

    // Encabezados de los detalles del pedido
    $pdf->setFillColor(225); // Color gris claro
    $pdf->setDrawColor(0); // Color negro para los bordes
    $pdf->setFont('Arial', 'B', 11);
    $pdf->cell(37, 15, $pdf->encodeString('Imagen'), 1, 0, 'C', 1);
    $pdf->cell(63, 15, $pdf->encodeString('Libro'), 1, 0, 'C', 1);
    $pdf->cell(25, 15, $pdf->encodeString('Cantidad'), 1, 0, 'C', 1);
    $pdf->cell(30, 15, $pdf->encodeString('Precio (US$)'), 1, 0, 'C', 1);
    $pdf->cell(30, 15, $pdf->encodeString('Subtotal (US$)'), 1, 1, 'C', 1);

    // Detalles del pedido
    $pdf->setFillColor(240); // Color gris muy claro
    $pdf->setFont('Arial', '', 11);
    $total = 0;

    foreach ($datapedidos as $rowpedidos) {
        // Verifica si se ha creado una nueva página
        if ($pdf->getY() + 15 > 279 - 30) { // Ajusta este valor según el tamaño de tus celdas y la altura de la página
            $pdf->addPage('P', [216, 279]); // Añade una nueva página
            $pdf->setFillColor(225);
            $pdf->setDrawColor(0); 
            $pdf->setFont('Arial', 'B', 11);
            // Vuelve a imprimir los encabezados en la nueva página
            $pdf->cell(37, 15, $pdf->encodeString('Imagen'), 1, 0, 'C', 1);
            $pdf->cell(63, 15, $pdf->encodeString('Producto'), 1, 0, 'C', 1);
            $pdf->cell(25, 15, $pdf->encodeString('Cantidad'), 1, 0, 'C', 1);
            $pdf->cell(30, 15, $pdf->encodeString('Precio (US$)'), 1, 0, 'C', 1);
            $pdf->cell(30, 15, $pdf->encodeString('Subtotal (US$)'), 1, 1, 'C', 1);
        }
        $subtotal = $rowpedidos['PRECIO'] * $rowpedidos['CANTIDAD'];
        $total += $subtotal;
        $currentY = $pdf->getY(); // Obtén la coordenada Y actual
        $pdf->setFillColor(225);
        $pdf->setDrawColor(0); 
        $pdf->setFont('Arial', 'B', 11);
        // Imprime las celdas con los datos y la imagen
        $pdf->setFillColor(255, 255, 255);
        $pdf->cell(37, 18, $pdf->image('../../images/libros/' . $rowpedidos['IMAGEN'], $pdf->getX() + 10, $currentY + 2, 10), 1, 0);
        $pdf->cell(63, 18, $pdf->encodeString($rowpedidos['NOMBRE']), 1, 0, 'C');
        $pdf->cell(25, 18, $pdf->encodeString($rowpedidos['CANTIDAD']), 1, 0, 'C');
        $pdf->cell(30, 18, $pdf->encodeString('$' . $rowpedidos['PRECIO']), 1, 0, 'C');
        $pdf->cell(30, 18, $pdf->encodeString('$' . $subtotal), 1, 1, 'C');
    }
    $pdf->setFont('Arial', 'B', 11);
    $pdf->setFillColor(255);
} else {
    $pdf->cell(0, 15, $pdf->encodeString('No hay libros para mostrar'), 1, 1);
}

$pdf->output('I', 'factura.pdf');
