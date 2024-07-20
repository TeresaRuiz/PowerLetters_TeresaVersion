<?php
require_once('../../helpers/report.php');
require_once('../../models/data/pedido_data.php');

$pdf = new Report;
$pedidos = new PedidoData;

$pdf->startReport('Reporte de pedidos pendientes');

$pedidosPendientes = $pedidos->obtenerPedidosPendientes();
$totales = $pedidos->obtenerTotalPedidosPendientes();

$pdf->setFont('Arial', 'B', 12);
$pdf->cell(0, 10, 'Resumen de pedidos pendientes', 0, 1);
$pdf->setFont('Arial', '', 11);
$pdf->cell(0, 10, 'Total de pedidos pendientes: ' . $totales['total_pedidos'], 0, 1);
$pdf->cell(0, 10, 'Valor total de pedidos pendientes: $' . number_format($totales['valor_total_pendiente'], 2), 0, 1);
$pdf->ln(10);

$pdf->setFont('Arial', 'B', 11);
$pdf->setFillColor(225); // Color gris claro
$pdf->cell(25, 10, 'ID Pedido', 1, 0, 'C', 1);
$pdf->cell(35, 10, 'Fecha', 1, 0, 'C', 1);
$pdf->cell(50, 10, 'Cliente', 1, 0, 'C', 1);
$pdf->cell(50, 10,  $pdf->encodeString('Dirección'), 1, 0, 'C', 1); // Dirección con acento
$pdf->cell(30, 10, 'Total', 1, 1, 'C', 1);

$pdf->setFont('Arial', '', 10);
foreach ($pedidosPendientes as $pedido) {
    $pdf->cell(25, 10, $pedido['id_pedido'], 1, 0, 'C');
    $pdf->cell(35, 10, $pedido['fecha_pedido'], 1, 0, 'C');
    $pdf->cell(50, 10, $pdf->encodeString($pedido['nombre_usuario'] . ' ' . $pedido['apellido_usuario']), 1, 0);
    $pdf->cell(50, 10, $pdf->encodeString($pedido['direccion_pedido']), 1, 0);
    $pdf->cell(30, 10, '$' . number_format($pedido['total_pedido'], 2), 1, 1, 'R');
}

$pdf->output('I', 'reporte_pedidos_pendientes.pdf');
?>
