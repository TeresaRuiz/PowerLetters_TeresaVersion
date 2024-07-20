<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');
require_once('../../models/data/pedido_data.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
$ventas = new PedidoData;

// Se verifica si existen los parámetros de fecha, de lo contrario se muestra un mensaje.
if (isset($_GET['fechaInicio']) && isset($_GET['fechaFin'])) {
    // Se establece el valor de las fechas, de lo contrario se muestra un mensaje.
    if ($ventas->setFechaInicio($_GET['fechaInicio']) && $ventas->setFechaFin($_GET['fechaFin'])) {
        // Se inicia el reporte con el encabezado del documento.
        $pdf->startReport('Reporte de ventas por período');
        
        // Obtener el total de ventas y número de pedidos
        $totales = $ventas->totalVentasYPedidos();
        
        // Mostrar información general
        $pdf->setFont('Arial', 'B', 12);
        $pdf->cell(0, 10, $pdf->encodeString('Período: ' . $_GET['fechaInicio'] . ' a ' . $_GET['fechaFin']), 0, 1);
        $pdf->cell(0, 10, $pdf->encodeString('Total de ventas: $' . number_format($totales['total_ventas'], 2)), 0, 1);
        $pdf->cell(0, 10, $pdf->encodeString('Número de pedidos: ' . $totales['numero_pedidos']), 0, 1);
        $pdf->ln(10);

        // Mostrar ventas detalladas
        $pdf->setFont('Arial', 'B', 11);
        $pdf->setFillColor(225);
        $pdf->cell(40, 10, $pdf->encodeString('Fecha'), 1, 0, 'C', 1);
        $pdf->cell(80, 10, $pdf->encodeString('Cliente'), 1, 0, 'C', 1);
        $pdf->cell(66, 10, $pdf->encodeString('Total venta'), 1, 1, 'C', 1);

        $pdf->setFont('Arial', '', 11);
        $ventas_detalle = $ventas->ventasPorPeriodo();
        foreach ($ventas_detalle as $venta) {
            $pdf->cell(40, 10, $pdf->encodeString($venta['fecha_pedido']), 1, 0, 'C');
            $pdf->cell(80, 10, $pdf->encodeString($venta['nombre_usuario'] . ' ' . $venta['apellido_usuario']), 1, 0);
            $pdf->cell(66, 10, $pdf->encodeString('$' . number_format($venta['total_venta'], 2)), 1, 1, 'R');
        }

        $pdf->ln(10);

        // Mostrar libros más vendidos
        $pdf->setFont('Arial', 'B', 12);
        $pdf->cell(0, 10, $pdf->encodeString('Libros más vendidos en el período'), 0, 1);
        
        $pdf->setFont('Arial', 'B', 11);
        $pdf->cell(146, 10, $pdf->encodeString('Título'), 1, 0, 'C', 1);
        $pdf->cell(40, 10, $pdf->encodeString('Cantidad'), 1, 1, 'C', 1);

        $pdf->setFont('Arial', '', 11);
        $libros_vendidos = $ventas->librosMasVendidos();
        foreach ($libros_vendidos as $libro) {
            $pdf->cell(146, 10, $pdf->encodeString($libro['titulo']), 1, 0);
            $pdf->cell(40, 10, $pdf->encodeString($libro['total_vendido']), 1, 1, 'C');
        }

        // Se envía el documento al navegador.
        $pdf->output('I', 'ventas_por_periodo.pdf');
    } else {
        $pdf->startReport('Error');
        $pdf->cell(0, 10, $pdf->encodeString('Fechas incorrectas'), 1, 1, 'C');
        $pdf->output('I', 'error.pdf');
    }
} else {
    $pdf->startReport('Error');
    $pdf->cell(0, 10, $pdf->encodeString('Debe seleccionar un rango de fechas'), 1, 1, 'C');
    $pdf->output('I', 'error.pdf');
}
