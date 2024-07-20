<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;

// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../models/data/usuario_data.php');

// Se instancia la entidad correspondiente.
$usuarios = new UsuarioData;

// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Reporte de clientes frecuentes');

// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($dataUsuarios = $usuarios->clientesFrecuentes()) {
    // Se establece un color de relleno para los encabezados.
    $pdf->setFillColor(225);
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Arial', 'B', 11);
    
    // Se imprimen las celdas con los encabezados.
    $pdf->cell(60, 10, 'Nombre', 1, 0, 'C', 1);
    $pdf->cell(60, 10, 'Apellido', 1, 0, 'C', 1);
    $pdf->cell(30, 10, 'Total pedidos', 1, 0, 'C', 1);
    $pdf->cell(40, 10, 'Monto total (US$)', 1, 1, 'C', 1);
    
    // Se establece la fuente para los datos de los usuarios.
    $pdf->setFont('Arial', '', 11);
    
    // Se recorren los registros fila por fila.
    foreach ($dataUsuarios as $rowUsuario) {
        // Se imprimen las celdas con los datos de los usuarios.
        $pdf->cell(60, 10, $pdf->encodeString($rowUsuario['nombre_usuario']), 1, 0);
        $pdf->cell(60, 10, $pdf->encodeString($rowUsuario['apellido_usuario']), 1, 0);
        $pdf->cell(30, 10, $rowUsuario['total_pedidos'], 1, 0, 'C');
        $pdf->cell(40, 10, number_format($rowUsuario['monto_total'], 2), 1, 1, 'R');
    }
} else {
    $pdf->cell(0, 10, $pdf->encodeString('No hay clientes frecuentes para mostrar'), 1, 1, 'C');
}

// Se envía el documento al navegador web.
$pdf->output('I', 'clientes_frecuentes.pdf');