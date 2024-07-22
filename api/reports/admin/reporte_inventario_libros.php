<?php
require_once('../../helpers/report.php');
require_once('../../models/data/libros_data.php');

$pdf = new Report;
$inventario = new LibroData;

$pdf->startReport('Reporte de inventario de libros');

$libros = $inventario->obtenerInventario();
$totales = $inventario->obtenerTotalLibros();

$pdf->setFont('Arial', 'B', 12);
$pdf->cell(0, 10, $pdf->encodeString('Resumen de inventario de libros'), 0, 1);
$pdf->setFont('Arial', '', 11);
$pdf->cell(0, 10, $pdf->encodeString('Total de libros: ') . $totales['total_libros'], 0, 1);
$pdf->cell(0, 10, $pdf->encodeString('Total de existencias: ') . $totales['total_existencias'], 0, 1);
$pdf->cell(0, 10, $pdf->encodeString('Valor del inventario: $') . number_format($totales['valor_inventario'], 2), 0, 1);
$pdf->ln(10);

$pdf->setFont('Arial', 'B', 11);
$pdf->setFillColor(225);
$pdf->cell(70, 10, $pdf->encodeString('Título'), 1, 0, 'C', 1);
$pdf->cell(42, 10, $pdf->encodeString('Autor'), 1, 0, 'C', 1);
$pdf->cell(32, 10, $pdf->encodeString('Género'), 1, 0, 'C', 1);
$pdf->cell(22, 10, $pdf->encodeString('Existencias'), 1, 0, 'C', 1);
$pdf->cell(22, 10, $pdf->encodeString('Precio'), 1, 1, 'C', 1);

$pdf->setFont('Arial', '', 10);
foreach ($libros as $libro) {
    $existenciasBajas = $libro['existencias'] < 5;
    
    $pdf->setFont($existenciasBajas ? 'Arial' : '', $existenciasBajas ? 'B' : '');
    $pdf->setTextColor($existenciasBajas ? 255 : 0, 0, 0);
    
    // Ajuste automático del texto para el título
    $pdf->MultiCell(70, 10, $pdf->encodeString($libro['titulo_libro']), 1);
    
    // Posiciona la siguiente celda en la misma línea
    $pdf->SetXY($pdf->GetX() + 70, $pdf->GetY() - 10);
    
    // Información de autor, género, existencias y precio
    $pdf->cell(42, 10, $pdf->encodeString($libro['nombre_autor']), 1, 0);
    $pdf->cell(32, 10, $pdf->encodeString($libro['nombre_genero']), 1, 0);
    $pdf->cell(22, 10, $pdf->encodeString($libro['existencias']), 1, 0, 'C');
    $pdf->cell(22, 10, '$' . number_format($libro['precio'], 2), 1, 1, 'R');
    
    $pdf->setTextColor(0);
}

$pdf->ln(10);
$pdf->setFont('Arial', 'I', 10);
$pdf->cell(0, 10, $pdf->encodeString('Nota: Los libros en rojo y negrita tienen existencias menores a 5 unidades.'), 0, 1);

$pdf->output('I', 'reporte_inventario.pdf');
